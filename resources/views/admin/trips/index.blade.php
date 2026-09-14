@extends('layouts.admin')

@section('title', 'Trips — Admin TAPGO TRAVEL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">Trips</h1>
    <a href="{{ route('admin.trips.create') }}" class="btn btn-primary btn-sm">+ Tambah Trip</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Destination</th>
                        <th>Category</th>
                        <th>Base Price</th>
                        <th>Schedules</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trips as $trip)
                        <tr>
                            <td>{{ $trip->title }}</td>
                            <td>{{ $trip->destination->name }}</td>
                            <td>{{ $trip->category->name ?? '-' }}</td>
                            <td>Rp {{ number_format($trip->base_price, 0, ',', '.') }}</td>
                            <td>{{ $trip->schedules_count }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($trip->status) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.trips.edit', $trip) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                <form action="{{ route('admin.trips.destroy', $trip) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus trip ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted small">Belum ada trip.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $trips->links() }}</div>
    </div>
</div>
@endsection
