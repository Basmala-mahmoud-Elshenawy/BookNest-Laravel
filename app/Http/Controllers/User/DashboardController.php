<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, RecommendationService $recommendations): View
    {
        $user = $request->user();
        $candidateBooks = Book::with(['category', 'authors'])->latest()->limit(30)->get();
        $recommended = $recommendations->rank($user, $candidateBooks)->take(6);
        $currentBorrowings = $user->borrowings()->with('book')->currentlyActive()->latest('borrowed_at')->get();
        $recentBorrowings = $user->borrowings()->with('book')->latest('borrowed_at')->limit(6)->get();
        $favoritesCount = $user->favorites()->count();
        $overdueCount = $currentBorrowings->filter(fn ($b) => $b->isOverdue())->count();

        return view('user.dashboard', compact('recommended','currentBorrowings','recentBorrowings','favoritesCount','overdueCount'));
    }
}
