@extends('layouts.admin')

@section('title', 'Tambah Destinasi — Admin TAPGO TRAVEL')

@section('content')
<h1 class="h4 fw-bold mb-4">Tambah Destinasi</h1>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.destinations.store') }}" enctype="multipart/form-data">
            @include('admin.destinations._form')
        </form>
    </div>
</div>
@endsection
