@props(['current', 'links' => []])
<nav aria-label="breadcrumb" class="tapgo-breadcrumb"><a href="{{ route('home') }}">Home</a>@foreach ($links as $link)<span>›</span><a href="{{ $link['url'] }}">{{ $link['label'] }}</a>@endforeach<span>›</span><span aria-current="page">{{ $current }}</span></nav>
