<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * Rule-based Library Assistant orchestrator.
 *
 * Authentication and authorization are handled by the HTTP middleware and
 * the authenticated User model. This service only queries data that the
 * resolved role is allowed to see, then passes plain facts to AIService for
 * deterministic response formatting. There is no external AI/API call.
 */
class ChatbotService
{
    private const ADMIN_ONLY_SIGNALS = [
        'all users', 'registered users', 'how many users', 'user list',
        'admin statistics', 'admin-only', 'admin only', 'every user',
        'list users', 'user accounts', 'total users', 'إحصائيات الادمن',
        'عدد المستخدمين', 'المستخدمين المسجلين', 'قائمة المستخدمين',
    ];

    private const STOP_WORDS = [
        'recommend', 'recommendation', 'recommendations', 'find', 'show', 'search',
        'tell', 'give', 'me', 'books', 'book', 'about', 'please', 'the', 'a', 'an',
        'good', 'best', 'available', 'availability', 'is', 'are', 'there', 'any',
        'which', 'what', 'can', 'you', 'compare', 'comparison', 'between', 'and',
        'for', 'in', 'on', 'of', 'with', 'from', 'by', 'do', 'i', 'have', 'how',
        'many', 'category', 'categories', 'author', 'authors', 'كتاب', 'كتب',
        'عن', 'في', 'من', 'هل', 'يوجد', 'متاح', 'متاحة', 'ابحث', 'رشح', 'رشحلي',
        'اقتراح', 'اقتراحات', 'مقارنة', 'بين', 'و', 'لي', 'ليّ', 'عايز', 'عايزة',
    ];

    public function __construct(
        private readonly AIService $ai,
        private readonly RecommendationService $recommendations,
    ) {
    }

    /**
     * @return array{response:string,rejected:bool,reason:?string}
     */
    public function handle(User $user, string $message): array
    {
        $normalized = $this->normalize($message);
        $isAdmin = $user->isAdmin();

        if (! $isAdmin && $this->looksLikeAdminRequest($normalized)) {
            return [
                'response' => "I can't share administrative or other users' account information. I can help with books, recommendations, availability, authors, categories, and how to use the library.",
                'rejected' => true,
                'reason' => 'user_requested_admin_scope',
            ];
        }

        $intent = $this->detectIntent($normalized, $isAdmin);
        $context = $this->buildContext($user, $normalized, $intent, $isAdmin);
        $response = $this->ai->generate($message, $intent, $context, $isAdmin);

        return ['response' => $response, 'rejected' => false, 'reason' => null];
    }

    private function detectIntent(string $message, bool $isAdmin): string
    {
        if ($this->containsAny($message, ['hello', 'hi', 'hey', 'مرحبا', 'اهلا', 'أهلا', 'السلام عليكم'])) {
            return 'greeting';
        }

        if ($isAdmin && $this->containsAny($message, ['statistics', 'stats', 'how many users', 'how many books', 'registered users', 'إحصائيات', 'عدد المستخدمين', 'عدد الكتب'])) {
            return 'admin_stats';
        }

        if ($this->containsAny($message, ['how do i borrow', 'how to borrow', 'borrow a book', 'borrow book', 'ازاي استعير', 'كيف استعار', 'استعير'])) {
            return 'how_to_borrow';
        }

        if ($this->containsAny($message, ['how do i return', 'how to return', 'return a book', 'return book', 'ازاي ارجع', 'إرجاع الكتاب', 'ارجع الكتاب'])) {
            return 'how_to_return';
        }

        if ($this->containsAny($message, ['favorite', 'favourites', 'favorites', 'heart', 'المفضلة', 'المفضلات'])) {
            return 'how_to_favorite';
        }

        if ($this->containsAny($message, ['profile', 'my profile', 'البروفايل', 'الملف الشخصي'])) {
            return 'profile';
        }

        if ($this->containsAny($message, ['help', 'what can you do', 'how does this work', 'مساعدة', 'تقدر تعمل ايه', 'ازاي استخدم'])) {
            return 'help';
        }

        if ($this->containsAny($message, ['compare', 'comparison', 'compare books', 'قارن', 'مقارنة', 'الفرق بين'])) {
            return 'comparison';
        }

        if ($this->containsAny($message, ['recommend', 'recommendation', 'suggest', 'match my interests', 'رشح', 'اقتراح', 'مناسب لاهتماماتي', 'مناسبة لاهتماماتي'])) {
            return 'recommendation';
        }

        if ($this->containsAny($message, ['available', 'availability', 'in stock', 'متاح', 'متاحة', 'نسخة', 'نسخ'])) {
            return 'availability';
        }

        if ($this->containsAny($message, ['author', 'written by', 'books by', 'مؤلف', 'كاتب', 'كتب الكاتب'])) {
            return 'author_search';
        }

        if ($this->containsAny($message, ['category', 'genre', 'تصنيف', 'فئة', 'قسم'])) {
            return 'category_search';
        }

        return 'book_search';
    }

    private function buildContext(User $user, string $message, string $intent, bool $isAdmin): array
    {
        if ($intent === 'admin_stats' && $isAdmin) {
            return $this->buildAdminContext($message);
        }

        if ($intent === 'recommendation') {
            return $this->buildRecommendationContext($user);
        }

        $searchTerm = $this->extractSearchTerm($message);
        $books = $this->searchBooks($searchTerm, $intent === 'availability');

        return [
            'search_term' => $searchTerm,
            'books' => $this->serializeBooks($books),
        ];
    }

    private function buildRecommendationContext(User $user): array
    {
        if (! $user->profile) {
            return [
                'profile_message' => 'Complete your profile first. Your interests, topics, skills, goals, and preferred categories are used by the built-in recommendation engine.',
                'books' => [],
            ];
        }

        $books = Book::query()->with(['category', 'authors'])->limit(50)->get();
        $ranked = $this->recommendations->rank($user, $books)->take(8);

        return [
            'books' => $this->serializeBooks($ranked),
        ];
    }

    private function buildAdminContext(string $message): array
    {
        $perCategory = Category::withCount('books')->orderByDesc('books_count')->get();
        $topCategory = $perCategory->first();
        $lowAvailability = Book::where('available_copies', '<=', 1)
            ->orderBy('available_copies')
            ->limit(10)
            ->get(['title', 'available_copies', 'total_copies']);

        $searchTerm = $this->extractSearchTerm($message);
        $searchBooks = $searchTerm !== '' ? $this->searchBooks($searchTerm) : collect();

        return [
            'total_book_copies' => Book::sum('total_copies'),
            'available_copies' => Book::sum('available_copies'),
            'total_users' => User::count(),
            'top_category' => $topCategory ? "{$topCategory->name} ({$topCategory->books_count} books)" : '',
            'low_availability' => $lowAvailability->map(fn (Book $book) => [
                'title' => $book->title,
                'available_copies' => $book->available_copies,
                'total_copies' => $book->total_copies,
            ])->all(),
            'books' => $this->serializeBooks($searchBooks),
        ];
    }

    private function searchBooks(string $term, bool $availableOnly = false)
    {
        if ($term === '') {
            return collect();
        }

        $query = Book::query()->with(['category', 'authors']);

        if ($availableOnly) {
            $query->where('available_copies', '>', 0);
        }

        $query->search($term);
        $books = $query->limit(12)->get();

        if ($books->isNotEmpty()) {
            return $books;
        }

        // A natural-language question often leaves several useful keywords.
        // Try the meaningful words individually without changing Book::search.
        $tokens = $this->meaningfulTokens($term);
        if ($tokens === []) {
            return collect();
        }

        $query = Book::query()->with(['category', 'authors']);
        if ($availableOnly) {
            $query->where('available_copies', '>', 0);
        }
        $query->where(function ($q) use ($tokens) {
            foreach ($tokens as $token) {
                $like = '%'.$token.'%';
                $q->orWhere('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('isbn', 'like', $like)
                    ->orWhereHas('authors', fn ($a) => $a->where('name', 'like', $like))
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $like));
            }
        });

        return $query->limit(12)->get();
    }

    private function serializeBooks($books): array
    {
        return $books->map(function (Book $book): array {
            $authors = $book->authors->pluck('name')->implode(', ');
            $reason = '';
            if (isset($book->match_percentage)) {
                $reason = app(RecommendationService::class)->explainMatch(request()->user(), $book);
            }

            return [
                'title' => $book->title,
                'authors' => $authors,
                'category' => $book->category?->name ?? '',
                'available_copies' => (int) $book->available_copies,
                'total_copies' => (int) $book->total_copies,
                'match_percentage' => $book->match_percentage ?? null,
                'match_reason' => $reason,
            ];
        })->all();
    }

    private function extractSearchTerm(string $message): string
    {
        $term = preg_replace('/[^\p{L}\p{N}\s+#.-]/u', ' ', $message) ?? $message;
        $tokens = preg_split('/\s+/u', Str::lower(trim($term)), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $tokens = array_values(array_filter($tokens, function (string $token): bool {
            return ! in_array($token, self::STOP_WORDS, true) && mb_strlen($token) > 1;
        }));

        return implode(' ', $tokens);
    }

    private function meaningfulTokens(string $term): array
    {
        return array_values(array_filter(
            preg_split('/\s+/u', $term, -1, PREG_SPLIT_NO_EMPTY) ?: [],
            fn (string $token): bool => mb_strlen($token) > 1
        ));
    }

    private function looksLikeAdminRequest(string $message): bool
    {
        return $this->containsAny($message, self::ADMIN_ONLY_SIGNALS);
    }

    private function containsAny(string $message, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($message, Str::lower($needle))) {
                return true;
            }
        }

        return false;
    }

    private function normalize(string $message): string
    {
        return Str::lower(trim(preg_replace('/\s+/u', ' ', $message) ?? $message));
    }
}
