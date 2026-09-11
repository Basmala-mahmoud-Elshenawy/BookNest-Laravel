@extends('layouts.app')
@section('title', $book->title.' — BookNest')
@section('content')
<section class="section">
    <div class="container" style="display:grid;grid-template-columns:280px 1fr;gap:40px;">
        <div><img src="{{ $book->coverUrl() }}" alt="{{ $book->title }}" style="width:100%;border-radius:var(--radius-md);box-shadow:var(--shadow-soft);"></div>
        <div>
            <h1 style="margin-bottom:6px;">{{ $book->title }}</h1>
            <p class="book-author" style="font-size:1rem;">{{ $book->authors->pluck('name')->implode(', ') ?: 'Author not specified' }}</p>
            <div class="book-meta-row" style="margin:16px 0;">
                <span class="badge badge-{{ $book->status() }}">{{ ucfirst($book->status()) }}</span>
                @if($book->category)<span class="badge badge-reserved">{{ $book->category->name }}</span>@endif
                @if(!is_null($matchPercentage))<span class="match-pill">{{ $matchPercentage }}% match for you</span>@endif
            </div>
            <p>{{ $book->description ?? 'No description available for this title yet.' }}</p>
            <div class="card card-pad" style="margin-top:20px;">
                <table style="width:100%;font-size:0.9rem;">
                    <tr><td class="book-author">ISBN</td><td>{{ $book->isbn ?? '—' }}</td></tr>
                    <tr><td class="book-author">Published</td><td>{{ optional($book->published_at)->format('Y-m-d') ?? '—' }}</td></tr>
                    <tr><td class="book-author">Available copies</td><td>{{ $book->available_copies }} / {{ $book->total_copies }}</td></tr>
                    <tr><td class="book-author">Language</td><td>{{ strtoupper($book->language) }}</td></tr>
                </table>
            </div>
            @auth
                @php
                    $activeBorrowing = auth()->user()->borrowings()->where('book_id', $book->id)->currentlyActive()->first();
                    $isFavorite = auth()->user()->favoriteBooks()->whereKey($book->id)->exists();
                @endphp
                <div class="book-actions" style="margin-top:20px;">
                    @if($activeBorrowing)
                        <form method="POST" action="{{ route('borrowings.return', $activeBorrowing) }}">@csrf<button class="btn btn-secondary" type="submit">Return Book</button></form>
                    @elseif($book->isAvailable())
                        <form method="POST" action="{{ route('borrowings.borrow', $book) }}">@csrf<button class="btn btn-primary" type="submit">Borrow Book</button></form>
                    @else
                        <span class="book-author">Currently unavailable</span>
                    @endif
                    <form method="POST" action="{{ route('favorites.toggle', $book) }}">@csrf<button class="btn btn-outline" style="border-color:var(--color-button);color:var(--color-button);" type="submit">{{ $isFavorite ? '♥ Unfavorite' : '♡ Favorite' }}</button></form>
                </div>
            @endauth
        </div>
    </div>
    @if($related->isNotEmpty())
    <div class="container" style="margin-top:48px;"><div class="section-head"><h2>Related Books</h2></div><div class="book-grid">@foreach($related as $r)<x-book-card :book="$r" />@endforeach</div></div>
    @endif
</section>
@endsection
