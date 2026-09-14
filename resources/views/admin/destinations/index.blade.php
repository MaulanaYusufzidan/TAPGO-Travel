@extends('layouts.admin')

@section('title', 'Destinations — Admin TAPGO TRAVEL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">Destinations</h1>
    <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary btn-sm">+ Tambah Destinasi</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Trips</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($destinations as $destination)
                        <tr>
                            <td>{{ $destination->name }}</td>
                            <td>{{ $destination->location }}</td>
                            <td>{{ $destination->trips_count }}</td>
                            <td>{{ $destination->is_featured ? 'Yes' : 'No' }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($destination->status) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus destinasi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted small">Belum ada destinasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $destinations->links() }}</div>
    </div>
</div>
@endsection
