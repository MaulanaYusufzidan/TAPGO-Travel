@extends('layouts.admin')

@section('title', 'Schedules — Admin TAPGO TRAVEL')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 fw-bold mb-0">Schedules</h1>
    <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary btn-sm">+ Tambah Schedule</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Trip</th>
                        <th>Date</th>
                        <th>Capacity</th>
                        <th>Booked</th>
                        <th>Bookings</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->trip->title }}</td>
                            <td>{{ $schedule->date->format('d M Y') }}</td>
                            <td>{{ $schedule->capacity }}</td>
                            <td>{{ $schedule->booked_seats }}</td>
                            <td>{{ $schedule->bookings_count }}</td>
                            <td>Rp {{ number_format($schedule->price, 0, ',', '.') }}</td>
                            <td><span class="badge bg-secondary">{{ ucfirst($schedule->status) }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus schedule ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-muted small">Belum ada schedule.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $schedules->links() }}</div>
    </div>
</div>
@endsection
