@extends('layouts.admin')

@section('title', 'Edit Trip — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Edit Trip</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.trips.update', $trip) }}">
            @method('PUT')
            @include('admin.trips._form')
        </form>
    </div>
</div>
@endsection
