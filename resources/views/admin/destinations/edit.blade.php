@extends('layouts.admin')

@section('title', 'Edit Destinasi — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Edit Destinasi</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.destinations._form')
        </form>
    </div>
</div>
@endsection
