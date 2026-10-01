@extends('layouts.app')
@section('title', $post['title'].' — TAPGO Travel')
@section('meta_description', $post['excerpt'])
@section('content')
<main class="marketplace-page">
<div class="container" style="max-width: 860px;">
    <x-breadcrumb :links="[['label' => 'Blog', 'url' => route('blog')]]" current="{{ $post['title'] }}" />

    <p class="eyebrow">{{ $post['category'] }} · {{ $post['date'] }}</p>
    <h1 class="mb-4">{{ $post['title'] }}</h1>

    <div style="height:360px; border-radius:.6rem; overflow:hidden; margin-bottom:1.5rem;">
        <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" style="width:100%; height:100%; object-fit:cover; display:block;">
    </div>

    <div class="detail-panel">
        @foreach(explode("\n\n", $post['content']) as $paragraph)
            <p class="text-muted">{{ $paragraph }}</p>
        @endforeach
    </div>

    @if($related->isNotEmpty())
        <section class="mt-4">
            <h2 class="h5 fw-bold mb-3">More from the journal</h2>
            <div class="row g-4">
                @foreach($related as $item)
                    <div class="col-md-6">
                        <article class="journal-card">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                            <div>
                                <p class="eyebrow">{{ $item['category'] }}</p>
                                <h3>{{ $item['title'] }}</h3>
                                <a href="{{ route('blog.show', $item['slug']) }}" class="text-link">Read more →</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
</main>
@endsection
