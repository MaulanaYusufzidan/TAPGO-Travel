@extends('layouts.admin')

@section('title', 'Tambah Trip — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Tambah Trip</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.trips.store') }}">
            @include('admin.trips._form')
        </form>
    </div>
</div>
@endsection
