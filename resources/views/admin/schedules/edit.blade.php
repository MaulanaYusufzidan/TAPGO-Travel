@extends('layouts.admin')

@section('title', 'Edit Schedule — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Edit Schedule</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}">
            @method('PUT')
            @include('admin.schedules._form')
        </form>
    </div>
</div>
@endsection
