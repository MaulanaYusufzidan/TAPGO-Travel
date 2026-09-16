@props(['current'])
<nav aria-label="breadcrumb" class="tapgo-breadcrumb"><a href="{{ route('home') }}">Home</a><span>›</span><span aria-current="page">{{ $current }}</span></nav>
