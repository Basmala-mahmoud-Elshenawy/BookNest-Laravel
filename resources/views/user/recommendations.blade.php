@extends('layouts.app')
@section('title', 'Recommended For You — BookNest')
@section('content')
<section class="section">
    <div class="container">
        <h1>Recommended For You</h1>
        <p class="book-author">Sorted by highest match, based on your profile.</p>

        <div class="book-grid" style="margin-top:24px;">
            @forelse($books as $book)
                <div class="card book-card">
                    <a href="{{ route('books.show', $book) }}"><img src="{{ $book->coverUrl() }}" alt="{{ $book->title }}" class="book-cover"></a>
                    <div class="book-card-body">
                        <p class="book-title">{{ $book->title }}</p>
                        <p class="book-author">{{ $book->authors->pluck('name')->implode(', ') ?: ($book->category->name ?? '') }}</p>
                        <span class="match-pill">{{ $book->match_percentage }}% match</span>
                        <p class="book-author" style="margin-top:10px;">{{ $explanations[$book->id] ?? '' }}</p>
                    </div>
                </div>
            @empty
                <div class="card card-pad">No books available yet.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
