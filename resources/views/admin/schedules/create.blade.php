@extends('layouts.admin')

@section('title', 'Tambah Schedule — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Tambah Schedule</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.schedules.store') }}">
            @include('admin.schedules._form')
        </form>
    </div>
</div>
@endsection
