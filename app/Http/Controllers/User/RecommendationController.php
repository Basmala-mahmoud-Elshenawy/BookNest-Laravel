<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    public function index(Request $request, RecommendationService $recommendations): View
    {
        $user = $request->user();
        $books = Book::with(['category', 'authors'])->get();
        $ranked = $recommendations->rank($user, $books);

        $explanations = $ranked->take(20)->mapWithKeys(
            fn (Book $book) => [$book->id => $recommendations->explainMatch($user, $book)]
        );

        return view('user.recommendations', [
            'books' => $ranked->take(20),
            'explanations' => $explanations,
        ]);
    }
}
