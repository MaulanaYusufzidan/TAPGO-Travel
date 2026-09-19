@extends('layouts.app')

@section('title', $trip->title . ' — TAPGO TRAVEL')
@section('meta_description', \Illuminate\Support\Str::limit($trip->description, 150))

@section('content')
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb current="{{ $trip->title }}" />

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ $trip->title }}</h1>
            <p class="text-muted mb-0">
                {{ $trip->destination->name }} · {{ $trip->duration }}
                @if ($trip->min_group_size || $trip->max_group_size)
                    · {{ $trip->min_group_size }}–{{ $trip->max_group_size }} people
                @endif
                @if ($trip->category)
                    · <span class="badge bg-primary-subtle text-primary">{{ $trip->category->name }}</span>
                @endif
            </p>
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="navigator.clipboard && navigator.clipboard.writeText(window.location.href); this.textContent='Link copied ✓';">↗ Share</button>
    </div>

    {{-- Gallery --}}
    @php $images = $trip->images; @endphp
    @if ($images->isNotEmpty())
        <div class="gallery-grid" style="height: 420px;">
            <div class="gallery-grid__main">
                <img src="{{ asset('storage/' . $images->first()->image_path) }}" alt="{{ $trip->title }}">
            </div>
            <div class="gallery-grid__thumbs">
                @foreach ($images->skip(1)->take(4) as $i => $image)
                    <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank" rel="noopener">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->caption ?? $trip->title }}">
                        @if ($i === 3 && $images->count() > 5)
                            <span class="gallery-grid__more">+{{ $images->count() - 5 }} more photos</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div class="ratio ratio-21x9 bg-secondary-subtle rounded mb-4"></div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Tabs --}}
            <div class="tapgo-tabs" role="tablist">
                <a href="#overview" class="active" data-tab-target="overview">Overview</a>
                <a href="#itinerary" data-tab-target="itinerary">Itinerary</a>
                <a href="#inclusions" data-tab-target="inclusions">Inclusions &amp; Exclusions</a>
                <a href="#reviews" data-tab-target="reviews">Reviews</a>
            </div>

            <section class="detail-panel tab-pane" id="overview">
                <h2>Overview</h2>
                <p class="text-muted">{{ $trip->description }}</p>
                @if ($trip->meeting_point)
                    <p class="mb-0"><strong>Meeting point:</strong> {{ $trip->meeting_point }}</p>
                @endif
            </section>

            @if ($trip->itineraries->isNotEmpty())
                <section class="detail-panel tab-pane" id="itinerary">
                    <h2>Itinerary</h2>
                    <div class="vstack gap-3">
                        @foreach ($trip->itineraries as $day)
                            <div class="d-flex gap-3">
                                <div class="score-chip" style="min-width:52px;">Day<br>{{ $day->day_number }}</div>
                                <div>
                                    <strong class="d-block" style="color:#17233b;">{{ $day->title }}</strong>
                                    @if ($day->description)
                                        <p class="text-muted small mb-0">{{ $day->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($trip->inclusions->isNotEmpty() || $trip->exclusions->isNotEmpty())
                <section class="detail-panel tab-pane" id="inclusions">
                    <h2>Inclusions &amp; Exclusions</h2>
                    <div class="row">
                        @if ($trip->inclusions->isNotEmpty())
                            <div class="col-md-6">
                                <h3 class="h6 fw-semibold mb-2">Included</h3>
                                <ul class="list-unstyled">
                                    @foreach ($trip->inclusions as $inc)
                                        <li class="mb-2">✅ {{ $inc->item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($trip->exclusions->isNotEmpty())
                            <div class="col-md-6">
                                <h3 class="h6 fw-semibold mb-2">Not included</h3>
                                <ul class="list-unstyled">
                                    @foreach ($trip->exclusions as $exc)
                                        <li class="mb-2 text-muted">✕ {{ $exc->item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </section>
            @endif

            <section class="detail-panel tab-pane" id="reviews">
                <h2>Guest Reviews</h2>
                @if ($trip->reviews_count > 0)
                    <div class="review-score">
                        <div class="review-score__big">
                            <strong>{{ number_format($trip->rating_avg, 1) }}</strong>
                            <span>{{ $trip->reviews_count }} reviews</span>
                        </div>
                        <p class="text-muted small mb-0">Guests rate this trip {{ number_format($trip->rating_avg, 1) }} out of 5 based on {{ $trip->reviews_count }} completed bookings.</p>
                    </div>
                @else
                    <p class="text-muted">No reviews yet — be the first to travel and share your experience.</p>
                @endif
            </section>
        </div>

        <div class="col-lg-4">
            <div class="booking-card" id="schedule-picker">
                @if ($trip->base_price)
                    <p class="text-muted small mb-1">Starting from</p>
                    <p class="h3 fw-bold mb-3" style="color:#17233b;">Rp {{ number_format($trip->base_price, 0, ',', '.') }}<span class="fs-6 text-muted fw-normal"> / person</span></p>
                @endif

                @if ($schedules->isEmpty())
                    <p class="small text-muted mb-3">No schedules are available for this trip right now.</p>
                    <button type="button" class="btn btn-primary w-100" disabled>Book Now</button>
                @else
                    <form method="GET" action="{{ route('bookings.create') }}">
                        <label class="form-label small fw-semibold text-uppercase text-muted">Choose a date</label>
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
                                    <span class="small text-muted">{{ $schedule->available_seats }} seats</span>
                                </label>
                            @endforeach
                        </div>

                        <label for="traveler-qty" class="form-label small fw-semibold text-uppercase text-muted">Travelers</label>
                        <input type="number" name="quantity" id="traveler-qty" class="form-control mb-3" value="1" min="1">

                        <div class="d-flex justify-content-between small text-muted mb-2">
                            <span>Price / person</span>
                            <span id="schedule-price">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold mb-3">
                            <span>Total</span>
                            <span id="schedule-total">Rp 0</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Request to Book</button>
                    </form>
                @endif

                <hr>
                <p class="small fw-semibold text-uppercase text-muted mb-2">Good to know</p>
                <ul class="list-unstyled small text-muted mb-0">
                    <li class="mb-2">✅ Instant confirmation after payment</li>
                    <li class="mb-2">✅ Secure checkout via Midtrans</li>
                    <li class="mb-0">ℹ️ Please review the schedule cutoff time before booking</li>
                </ul>
            </div>
        </div>
    </div>

    @if ($related->isNotEmpty())
        <div class="mt-5">
            <div class="section-heading">
                <div><h2 class="h4">Similar experiences</h2></div>
            </div>
            <div class="row g-4">
                @foreach ($related as $item)
                    <div class="col-md-4">
                        <x-trip-card :trip="$item" />
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
</main>

@push('scripts')
<script>
(function () {
    // Tabs
    var tabs = document.querySelectorAll('.tapgo-tabs a');
    var panes = document.querySelectorAll('.tab-pane');
    function showPane(id) {
        panes.forEach(function (p) { p.style.display = (p.id === id) ? '' : 'none'; });
        tabs.forEach(function (t) { t.classList.toggle('active', t.dataset.tabTarget === id); });
    }
    if (tabs.length) {
        showPane('overview');
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function (e) {
                e.preventDefault();
                showPane(tab.dataset.tabTarget);
                window.scrollTo({ top: tab.closest('.col-lg-8').offsetTop - 90, behavior: 'smooth' });
            });
        });
    }

    @if ($schedules->isNotEmpty())
    // Schedule price calculator
    var radios = document.querySelectorAll('.schedule-radio');
    var qtyInput = document.getElementById('traveler-qty');
    var priceEl = document.getElementById('schedule-price');
    var totalEl = document.getElementById('schedule-total');

    function formatRupiah(num) {
        return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }

    function recalc() {
        var selected = document.querySelector('.schedule-radio:checked');
        if (!selected) return;

        var price = parseFloat(selected.dataset.price);
        var available = parseInt(selected.dataset.available, 10);

        qtyInput.max = available;
        var qty = parseInt(qtyInput.value, 10) || 1;
        if (qty > available) qty = available;
        if (qty < 1) qty = 1;
        qtyInput.value = qty;

        priceEl.textContent = formatRupiah(price);
        totalEl.textContent = formatRupiah(price * qty);
    }

    radios.forEach(function (r) { r.addEventListener('change', recalc); });
    qtyInput.addEventListener('input', recalc);
    recalc();
    @endif
})();
</script>
@endpush
@endsection
