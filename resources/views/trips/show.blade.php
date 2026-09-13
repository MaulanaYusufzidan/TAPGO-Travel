@extends('layouts.app')

@section('title', $trip->title . ' — TAPGO TRAVEL')
@section('meta_description', \Illuminate\Support\Str::limit($trip->description, 150))

@section('content')
    <div class="ratio ratio-21x9 bg-secondary-subtle">
        @if ($trip->images->first())
            <img src="{{ asset('storage/' . $trip->images->first()->image_path) }}"
                 alt="{{ $trip->title }}" class="object-fit-cover">
        @endif
    </div>

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                @if ($trip->category)
                    <span class="badge bg-primary-subtle text-primary mb-2">{{ $trip->category->name }}</span>
                @endif
                <h1 class="fw-bold mb-1">{{ $trip->title }}</h1>
                <p class="text-muted mb-2">
                    <a href="{{ route('destinations.show', $trip->destination) }}" class="text-decoration-none">{{ $trip->destination->name }}</a>
                </p>
                <p class="mb-4">
                    &#9733; {{ number_format($trip->rating_avg, 1) }} ({{ $trip->reviews_count }} reviews)
                    &middot; {{ $trip->duration }}
                    @if ($trip->min_group_size || $trip->max_group_size)
                        &middot; {{ $trip->min_group_size }}-{{ $trip->max_group_size }} orang
                    @endif
                </p>

                <p class="mb-4">{{ $trip->description }}</p>

                @if ($trip->meeting_point)
                    <p class="mb-4"><strong>Meeting Point:</strong> {{ $trip->meeting_point }}</p>
                @endif

                @if ($trip->images->count() > 1)
                    <h2 class="h4 fw-semibold mb-3">Gallery</h2>
                    <div class="row g-2 mb-4">
                        @foreach ($trip->images->skip(1) as $image)
                            <div class="col-4">
                                <div class="ratio ratio-1x1 bg-secondary-subtle rounded overflow-hidden">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         alt="{{ $image->caption ?? $trip->title }}" class="object-fit-cover">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($trip->itineraries->isNotEmpty())
                    <h2 class="h4 fw-semibold mb-3">Itinerary</h2>
                    <ul class="list-unstyled mb-4">
                        @foreach ($trip->itineraries as $day)
                            <li class="mb-2">
                                <strong>Day {{ $day->day_number }}: {{ $day->title }}</strong>
                                @if ($day->description)
                                    <p class="small text-muted mb-0">{{ $day->description }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="row">
                    @if ($trip->inclusions->isNotEmpty())
                        <div class="col-md-6">
                            <h2 class="h5 fw-semibold mb-3">Included</h2>
                            <ul>
                                @foreach ($trip->inclusions as $inc)
                                    <li>{{ $inc->item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($trip->exclusions->isNotEmpty())
                        <div class="col-md-6">
                            <h2 class="h5 fw-semibold mb-3">Excluded</h2>
                            <ul>
                                @foreach ($trip->exclusions as $exc)
                                    <li>{{ $exc->item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" id="schedule-picker" style="top: 1rem;">
                    <div class="card-body">
                        <p class="text-muted small mb-1">Mulai dari</p>
                        <p class="h3 fw-bold text-primary mb-3">Rp {{ number_format($trip->base_price, 0, ',', '.') }}</p>

                        @if ($schedules->isEmpty())
                            <p class="small text-muted mb-3">
                                Belum ada jadwal tersedia untuk trip ini saat ini.
                            </p>
                            <button type="button" class="btn btn-primary w-100" disabled>Book Now</button>
                        @else
                            <label class="form-label small fw-semibold text-uppercase text-muted">Pilih Jadwal</label>
                            <div class="list-group mb-3">
                                @foreach ($schedules as $schedule)
                                    <label class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>
                                            <input class="form-check-input me-2 schedule-radio"
                                                   type="radio" name="schedule_id" value="{{ $schedule->id }}"
                                                   data-price="{{ $schedule->price }}"
                                                   data-available="{{ $schedule->available_seats }}"
                                                   {{ $loop->first ? 'checked' : '' }}>
                                            {{ $schedule->date->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="small text-muted">{{ $schedule->available_seats }} kursi</span>
                                    </label>
                                @endforeach
                            </div>

                            <label for="traveler-qty" class="form-label small fw-semibold text-uppercase text-muted">Jumlah Traveler</label>
                            <input type="number" id="traveler-qty" class="form-control mb-3" value="1" min="1">

                            <div class="d-flex justify-content-between small text-muted mb-2">
                                <span>Harga / orang</span>
                                <span id="schedule-price">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between fw-semibold mb-3">
                                <span>Total</span>
                                <span id="schedule-total">Rp 0</span>
                            </div>

                            <button type="button" class="btn btn-primary w-100" disabled>
                                Book Now (input traveler menyusul)
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($schedules->isNotEmpty())
        @push('scripts')
        <script>
            (function () {
                const radios = document.querySelectorAll('.schedule-radio');
                const qtyInput = document.getElementById('traveler-qty');
                const priceEl = document.getElementById('schedule-price');
                const totalEl = document.getElementById('schedule-total');

                function formatRupiah(num) {
                    return 'Rp ' + Math.round(num).toLocaleString('id-ID');
                }

                function recalc() {
                    const selected = document.querySelector('.schedule-radio:checked');
                    if (!selected) return;

                    const price = parseFloat(selected.dataset.price);
                    const available = parseInt(selected.dataset.available, 10);

                    qtyInput.max = available;
                    let qty = parseInt(qtyInput.value, 10) || 1;
                    if (qty > available) qty = available;
                    if (qty < 1) qty = 1;
                    qtyInput.value = qty;

                    priceEl.textContent = formatRupiah(price);
                    totalEl.textContent = formatRupiah(price * qty);
                }

                radios.forEach((r) => r.addEventListener('change', recalc));
                qtyInput.addEventListener('input', recalc);
                recalc();
            })();
        </script>
        @endpush
    @endif
@endsection
