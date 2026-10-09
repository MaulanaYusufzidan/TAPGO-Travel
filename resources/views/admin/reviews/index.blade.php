@extends('layouts.admin')
@section('title', 'Reviews — Admin TAPGO TRAVEL')
@section('content')
    <h1 class="h4 fw-bold mb-1">Reviews</h1>
    <p class="text-muted small mb-4">Moderate guest reviews for hotels and trips.</p>

    <ul class="nav nav-tabs mb-3" id="reviewTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="hotel-tab" data-bs-toggle="tab" data-bs-target="#hotel-pane" type="button" role="tab">Hotel Reviews ({{ $hotelReviews->total() }})</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="trip-tab" data-bs-toggle="tab" data-bs-target="#trip-pane" type="button" role="tab">Trip Reviews ({{ $tripReviews->total() }})</button>
        </li>
    </ul>

    <div class="tab-content">
        {{-- Hotel reviews --}}
        <div class="tab-pane fade show active" id="hotel-pane" role="tabpanel">
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="row g-2 align-items-end">
                        <div class="col-auto">
                            <label for="hotel_status" class="form-label small text-muted mb-1">Status</label>
                            <select name="hotel_status" id="hotel_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All statuses</option>
                                @foreach(['pending', 'published', 'rejected'] as $s)
                                    <option value="{{ $s }}" @selected(($filters['hotel_status'] ?? '') === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($hotelReviews->isEmpty())
                <div class="card"><div class="card-body text-center py-5">
                    <p class="fw-semibold mb-1">No hotel reviews found.</p>
                    <p class="text-muted small mb-0">There are currently no hotel reviews matching your criteria.</p>
                </div></div>
            @else
                <div class="card">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr>
                                <th>Customer</th><th>Hotel</th><th>Rating</th><th>Review</th><th>Status</th><th>Date</th><th></th>
                            </tr></thead>
                            <tbody>
                                @foreach($hotelReviews as $review)
                                    <tr>
                                        <td>{{ $review->user->name ?? 'Guest' }}</td>
                                        <td>{{ $review->hotel->name ?? '—' }}</td>
                                        <td>★ {{ $review->rating }}/10</td>
                                        <td style="max-width:280px;">
                                            @if($review->title)<strong class="d-block small">{{ $review->title }}</strong>@endif
                                            <span class="small text-muted">{{ \Illuminate\Support\Str::limit($review->comment, 80) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ match($review->status) { 'published' => 'text-bg-success', 'rejected' => 'text-bg-danger', default => 'text-bg-secondary' } }}">{{ ucfirst($review->status) }}</span>
                                        </td>
                                        <td class="small text-muted">{{ $review->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-label="Actions for this review">Actions</button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @foreach(['published' => 'Publish', 'pending' => 'Mark Pending', 'rejected' => 'Reject'] as $status => $label)
                                                        @if($review->status !== $status)
                                                            <li>
                                                                <form method="POST" action="{{ route('admin.reviews.hotel.status', $review) }}">
                                                                    @csrf @method('PATCH')
                                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                                    <button type="submit" class="dropdown-item">{{ $label }}</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.reviews.hotel.destroy', $review) }}" onsubmit="return confirm('Delete this review?\n\nThis action cannot be undone.');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-3">{{ $hotelReviews->onEachSide(1)->links() }}</div>
            @endif
        </div>

        {{-- Trip reviews --}}
        <div class="tab-pane fade" id="trip-pane" role="tabpanel">
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" class="row g-2 align-items-end">
                        <div class="col-auto">
                            <label for="trip_status" class="form-label small text-muted mb-1">Status</label>
                            <select name="trip_status" id="trip_status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All statuses</option>
                                @foreach(['pending', 'published', 'rejected'] as $s)
                                    <option value="{{ $s }}" @selected(($filters['trip_status'] ?? '') === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($tripReviews->isEmpty())
                <div class="card"><div class="card-body text-center py-5">
                    <p class="fw-semibold mb-1">No trip reviews found.</p>
                    <p class="text-muted small mb-0">There are currently no trip reviews matching your criteria.</p>
                </div></div>
            @else
                <div class="card">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead><tr>
                                <th>Customer</th><th>Trip</th><th>Rating</th><th>Review</th><th>Status</th><th>Date</th><th></th>
                            </tr></thead>
                            <tbody>
                                @foreach($tripReviews as $review)
                                    <tr>
                                        <td>{{ $review->user->name ?? 'Guest' }}</td>
                                        <td>{{ $review->trip->title ?? '—' }}</td>
                                        <td>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                                        <td style="max-width:280px;"><span class="small text-muted">{{ \Illuminate\Support\Str::limit($review->comment, 80) }}</span></td>
                                        <td>
                                            <span class="badge {{ match($review->status) { 'published' => 'text-bg-success', 'rejected' => 'text-bg-danger', default => 'text-bg-secondary' } }}">{{ ucfirst($review->status) }}</span>
                                        </td>
                                        <td class="small text-muted">{{ $review->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-label="Actions for this review">Actions</button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @foreach(['published' => 'Publish', 'pending' => 'Mark Pending', 'rejected' => 'Reject'] as $status => $label)
                                                        @if($review->status !== $status)
                                                            <li>
                                                                <form method="POST" action="{{ route('admin.reviews.trip.status', $review) }}">
                                                                    @csrf @method('PATCH')
                                                                    <input type="hidden" name="status" value="{{ $status }}">
                                                                    <button type="submit" class="dropdown-item">{{ $label }}</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.reviews.trip.destroy', $review) }}" onsubmit="return confirm('Delete this review?\n\nThis action cannot be undone.');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-3">{{ $tripReviews->onEachSide(1)->links() }}</div>
            @endif
        </div>
    </div>
@endsection
