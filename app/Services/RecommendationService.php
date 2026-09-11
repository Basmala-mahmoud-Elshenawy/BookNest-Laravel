<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Deterministic profile -> book matching engine.
 *
 * This is intentionally NOT dependent on an external AI/embeddings API: it
 * must keep working even with zero AI configuration (see spec section 20).
 * It tokenizes the user's stated interests/topics/skills/goals and the
 * book's title/description/category/author, then scores overlap with a
 * weighted Jaccard-style similarity, converted to a 0-100 percentage.
 *
 * The assistant can use this deterministic score and explanation directly;
 * no external AI provider is required.
 */
class RecommendationService
{
    /** Category match is a strong signal, so it gets extra weight. */
    private const CATEGORY_BONUS = 0.25;

    public function matchPercentage(User $user, Book $book): int
    {
        $profile = $user->profile;

        if (! $profile) {
            return 0;
        }

        $userTokens = collect($profile->tokenBag());
        $bookTokens = collect($book->tokenBag());

        if ($userTokens->isEmpty() || $bookTokens->isEmpty()) {
            return 0;
        }

        $overlap = $userTokens->intersect($bookTokens)->count();
        $union = $userTokens->merge($bookTokens)->unique()->count();

        $similarity = $union > 0 ? $overlap / $union : 0;

        // Category bonus: if the book's category id is among the user's
        // explicitly preferred categories, boost the score.
        $preferredCategoryIds = collect($profile->preferred_category_ids ?? []);
        if ($book->category_id && $preferredCategoryIds->contains($book->category_id)) {
            $similarity = min(1, $similarity + self::CATEGORY_BONUS);
        }

        // Compress into a friendlier, less "everything is near 0%" curve
        // while staying strictly monotonic with raw similarity.
        $percentage = 100 * (1 - (1 - $similarity) ** 0.6);

        return (int) round(max(0, min(100, $percentage)));
    }

    /**
     * Score and sort an authorized collection of books for this user,
     * highest match first. Callers must pass in books they have already
     * decided the user may see (e.g. via Book::query() with normal scopes) --
     * this service does not perform authorization.
     */
    public function rank(User $user, Collection $books): Collection
    {
        return $books
            ->map(function (Book $book) use ($user) {
                $book->setAttribute('match_percentage', $this->matchPercentage($user, $book));

                return $book;
            })
            ->sortByDesc('match_percentage')
            ->values();
    }

    public function explainMatch(User $user, Book $book): string
    {
        $profile = $user->profile;
        if (! $profile) {
            return 'Complete your profile (interests, topics, skills, goals) to get a personalized match explanation.';
        }

        $userTokens = collect($profile->tokenBag());
        $bookTokens = collect($book->tokenBag());
        $shared = $userTokens->intersect($bookTokens)->take(5);

        if ($shared->isEmpty()) {
            return "This book doesn't strongly overlap with your stated interests yet, but it may still be worth exploring.";
        }

        return 'Matched on: '.$shared->implode(', ').'.';
    }
}
