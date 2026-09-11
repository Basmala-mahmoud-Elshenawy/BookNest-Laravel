<?php

namespace App\Services;

/**
 * Built-in rule-based Library Assistant.
 *
 * This service intentionally has no network access, API client, model
 * integration, or environment-based credentials. It only formats facts
 * already gathered by ChatbotService from data the authenticated user is
 * allowed to see.
 */
class AIService
{
    /**
     * Turn an intent + authorized database context into a deterministic reply.
     * The context is an array of facts, never an Eloquent model or query.
     *
     * @param array<string,mixed> $context
     */
    public function generate(string $message, string $intent, array $context, bool $isAdmin = false): string
    {
        return match ($intent) {
            'greeting' => $this->greeting(),
            'help' => $this->help($isAdmin),
            'how_to_borrow' => $this->howToBorrow(),
            'how_to_return' => $this->howToReturn(),
            'how_to_favorite' => $this->howToFavorite(),
            'profile' => $this->profileHelp(),
            'availability' => $this->availability($context),
            'book_search' => $this->bookSearch($context),
            'author_search' => $this->authorSearch($context),
            'category_search' => $this->categorySearch($context),
            'recommendation' => $this->recommendation($context),
            'comparison' => $this->comparison($context),
            'admin_stats' => $this->adminStats($context),
            default => $this->fallback($context, $isAdmin),
        };
    }

    private function greeting(): string
    {
        return 'Hi! I’m the BookNest Library Assistant. I can help you find books, check availability, explore authors and categories, get recommendations, or explain how to use the library.';
    }

    private function help(bool $isAdmin): string
    {
        $reply = "I can help with:\n• Finding books by title, topic, ISBN, author, or category\n• Checking whether books are available\n• Recommendations based on your profile\n• Explaining books and comparing books\n• Explaining borrowing, returning, favorites, and your profile";

        if ($isAdmin) {
            $reply .= "\n• Library statistics and low-availability books";
        }

        return $reply;
    }

    private function howToBorrow(): string
    {
        return 'To borrow a book, open its details or use its Borrow button on a book card. You must be logged in, and the book needs at least one available copy. The system records the borrowing and due date automatically.';
    }

    private function howToReturn(): string
    {
        return 'To return a book, open your Borrowings page and choose Return Book for an active borrowing. The return is recorded and the available copy is added back to the library.';
    }

    private function howToFavorite(): string
    {
        return 'To save a book, use the heart button on its book card or details page. You can view all saved books from your Favorites page.';
    }

    private function profileHelp(): string
    {
        return 'Open Profile from the navigation bar to update your library profile. Your interests, topics, skills, goals, and preferred categories can improve your personalized recommendations.';
    }

    private function availability(array $context): string
    {
        $books = $context['books'] ?? [];
        if ($books === []) {
            return 'I couldn’t find a matching book. Try the title, author, category, or a shorter topic phrase.';
        }

        return collect($books)->map(function (array $book): string {
            $availability = $book['available_copies'] > 0
                ? "available ({$book['available_copies']} copies)"
                : 'currently unavailable';

            return "• {$book['title']} — {$availability}";
        })->implode("\n");
    }

    private function bookSearch(array $context): string
    {
        $books = $context['books'] ?? [];
        if ($books === []) {
            return 'I couldn’t find a matching book in the library catalog. Try another title, topic, author, ISBN, or category.';
        }

        return "Here are the matching books:\n".collect($books)->map(function (array $book): string {
            $author = $book['authors'] !== '' ? " by {$book['authors']}" : '';
            $category = $book['category'] !== '' ? " [{$book['category']}]" : '';
            return "• {$book['title']}{$author}{$category}";
        })->implode("\n");
    }

    private function authorSearch(array $context): string
    {
        $books = $context['books'] ?? [];
        if ($books === []) {
            return 'I couldn’t find books by that author. Check the spelling or try another author name.';
        }

        $author = $context['search_term'] ?? 'that author';
        return "Books matching {$author}:\n".collect($books)->map(fn (array $book) => "• {$book['title']}")->implode("\n");
    }

    private function categorySearch(array $context): string
    {
        $books = $context['books'] ?? [];
        if ($books === []) {
            return 'I couldn’t find books in that category. Try browsing the Categories page for the available categories.';
        }

        $category = $context['search_term'] ?? 'that category';
        return "Books in or matching {$category}:\n".collect($books)->map(fn (array $book) => "• {$book['title']}" . ($book['available_copies'] > 0 ? ' — available' : ' — unavailable'))->implode("\n");
    }

    private function recommendation(array $context): string
    {
        $books = $context['books'] ?? [];
        if ($books === []) {
            return $context['profile_message'] ?? 'I couldn’t find recommendation matches yet. Complete your profile and try again.';
        }

        return "Based on your library profile, I’d start with:\n".collect($books)->map(function (array $book): string {
            $match = isset($book['match_percentage']) ? " — {$book['match_percentage']}% match" : '';
            $reason = $book['match_reason'] ?? '';
            return "• {$book['title']}{$match}".($reason !== '' ? "\n  {$reason}" : '');
        })->implode("\n");
    }

    private function comparison(array $context): string
    {
        $books = $context['books'] ?? [];
        if (count($books) < 2) {
            return 'I need at least two matching books to compare them. Try giving me two book titles.';
        }

        $lines = ['Here’s a database-based comparison:'];
        foreach (array_slice($books, 0, 2) as $book) {
            $lines[] = "• {$book['title']} — Author: {$book['authors']}; Category: {$book['category']}; Availability: ".($book['available_copies'] > 0 ? 'available' : 'unavailable').'.';
        }

        return implode("\n", $lines);
    }

    private function adminStats(array $context): string
    {
        if (!isset($context['total_book_copies'])) {
            return 'I can only show library statistics to an authenticated administrator.';
        }

        $reply = "Library statistics:\n• Total book copies: {$context['total_book_copies']}\n• Currently available copies: {$context['available_copies']}\n• Registered users: {$context['total_users']}";

        if (($context['top_category'] ?? '') !== '') {
            $reply .= "\n• Largest category: {$context['top_category']}";
        }

        if (($context['low_availability'] ?? []) !== []) {
            $reply .= "\n\nLow-availability books:\n".collect($context['low_availability'])->map(fn (array $book) => "• {$book['title']} ({$book['available_copies']}/{$book['total_copies']})")->implode("\n");
        }

        return $reply;
    }

    private function fallback(array $context, bool $isAdmin): string
    {
        if (($context['books'] ?? []) !== []) {
            return $this->bookSearch($context);
        }

        return $this->help($isAdmin);
    }
}
