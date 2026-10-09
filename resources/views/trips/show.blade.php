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
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm" title="Bookmark (belum tersambung ke akun)">🔖 Bookmark</button>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="navigator.clipboard && navigator.clipboard.writeText(window.location.href); this.textContent='Link copied ✓';">↗ Share</button>
        </div>
    </div>

    {{-- Gallery --}}
    @php
        $images = $trip->images;
        $galleryFallback = 'https://images.unsplash.com/photo-1530789253388-582c481c54b0?auto=format&fit=crop&w=1400&q=85';
        $galleryUrls = $images->map(fn ($img) => asset('storage/' . $img->image_path))->values();
    @endphp
    @if ($images->isNotEmpty())
        <div class="gallery-grid" style="height: 420px;">
            <div class="gallery-grid__main">
                <a href="#" onclick="tapgoLightboxOpen(event, 0)"><img src="{{ $galleryUrls[0] }}" alt="{{ $trip->title }}" onerror="this.onerror=null;this.src='{{ $galleryFallback }}'"></a>
            </div>
            <div class="gallery-grid__thumbs">
                @foreach ($images->skip(1)->take(4) as $i => $image)
                    <a href="#" onclick="tapgoLightboxOpen(event, {{ $loop->iteration }})" style="position:relative; display:block;">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->caption ?? $trip->title }}" onerror="this.onerror=null;this.src='{{ $galleryFallback }}'">
                        @if ($loop->last && $images->count() > 5)
                            <span class="gallery-grid__more">+{{ $images->count() - 5 }} more photos</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <div id="tapgo-lightbox" style="display:none; position:fixed; inset:0; background:rgba(10,15,25,.92); z-index:1050; align-items:center; justify-content:center;">
            <button type="button" onclick="tapgoLightboxClose()" aria-label="Close" style="position:absolute; top:20px; right:24px; background:none; border:none; color:#fff; font-size:2rem; line-height:1; cursor:pointer;">&times;</button>
            <button type="button" onclick="tapgoLightboxNav(-1)" aria-label="Previous" style="position:absolute; left:16px; background:none; border:none; color:#fff; font-size:2.5rem; cursor:pointer;">&#8249;</button>
            <img id="tapgo-lightbox-img" src="" alt="{{ $trip->title }}" style="max-width:88vw; max-height:82vh; object-fit:contain; border-radius:.5rem;" onerror="this.onerror=null;this.src='{{ $galleryFallback }}'">
            <button type="button" onclick="tapgoLightboxNav(1)" aria-label="Next" style="position:absolute; right:16px; background:none; border:none; color:#fff; font-size:2.5rem; cursor:pointer;">&#8250;</button>
            <span id="tapgo-lightbox-counter" style="position:absolute; bottom:20px; color:#fff; font-size:.85rem;"></span>
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
                <a href="#hotels-transfers" data-tab-target="hotels-transfers">Hotels &amp; Transfers</a>
            </div>

            <section class="detail-panel tab-pane" id="overview">
                <h2>Overview</h2>
                <p class="text-muted">{{ $trip->description }}</p>
                @if ($trip->meeting_point)
                    <p class="mb-3"><strong>Meeting point:</strong> {{ $trip->meeting_point }}</p>
                @endif

                @if ($trip->inclusions->isNotEmpty())
                    <h3 class="h6 fw-bold mt-4 mb-2">Tour Highlights</h3>
                    <div class="row g-2">
                        @foreach ($trip->inclusions->take(6) as $inc)
                            <div class="col-md-6"><span style="color:#0f9d75;">✓</span> {{ $inc->item }}</div>
                        @endforeach
                    </div>
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

            @php
                $hotelKeywords = ['hotel', 'akomodasi', 'penginapan', 'bintang', 'kamar', 'resort'];
                $transferKeywords = ['transfer', 'transportasi', 'antar', 'jemput', 'shuttle', 'ber-ac', 'bus', 'kereta', 'pesawat', 'airfare'];
                $hotelItems = $trip->inclusions->filter(fn ($i) => collect($hotelKeywords)->contains(fn ($k) => str_contains(strtolower($i->item), $k)));
                $transferItems = $trip->inclusions->filter(fn ($i) => collect($transferKeywords)->contains(fn ($k) => str_contains(strtolower($i->item), $k)));
            @endphp
            <section class="detail-panel tab-pane" id="hotels-transfers">
                <h2>Hotels &amp; Transfers</h2>
                @if ($hotelItems->isNotEmpty())
                    <h3 class="h6 fw-semibold mb-2">Accommodation</h3>
                    <ul class="list-unstyled mb-3">
                        @foreach ($hotelItems as $item)
                            <li class="mb-2">🏨 {{ $item->item }}</li>
                        @endforeach
                    </ul>
                @endif
                @if ($transferItems->isNotEmpty())
                    <h3 class="h6 fw-semibold mb-2">Transfers</h3>
                    <ul class="list-unstyled mb-0">
                        @foreach ($transferItems as $item)
                            <li class="mb-2">🚌 {{ $item->item }}</li>
                        @endforeach
                    </ul>
                @endif
                @if ($hotelItems->isEmpty() && $transferItems->isEmpty())
                    <p class="text-muted mb-0">Detail akomodasi & transfer belum dicantumkan untuk trip ini — lihat Inclusions di bawah untuk gambaran lengkap.</p>
                @endif
            </section>

            @if ($trip->inclusions->isNotEmpty() || $trip->exclusions->isNotEmpty())
                <section class="detail-panel" id="inclusions">
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

            <section class="detail-panel" id="reviews">
                <h2>Guest Reviews</h2>
                @if ($trip->reviews->isNotEmpty())
                    <div class="review-score">
                        <div class="review-score__big">
                            <strong>{{ number_format($trip->rating_avg, 1) }}</strong>
                            <span>{{ $trip->reviews_count }} reviews</span>
                        </div>
                        <p class="text-muted small mb-0">Guests rate this trip {{ number_format($trip->rating_avg, 1) }} out of 5 based on {{ $trip->reviews_count }} reviews.</p>
                    </div>

                    @foreach ($trip->reviews->take(6) as $review)
                        <div class="d-flex gap-3 border rounded p-3 mt-3" style="border-color:#e9edf0 !important;">
                            <div style="width:48px;height:48px;min-width:48px;border-radius:.5rem;background:#e7ecff;color:#5b6cf0;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.2rem;">{{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}</div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong class="small">{{ $review->user->name ?? 'Guest' }}</strong>
                                    <span class="small text-muted">{{ $review->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                                <span class="small text-warning">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                <p class="text-muted small mb-0 mt-1">{{ $review->comment }}</p>
                            </div>
                        </div>
                    @endforeach

                    @guest
                        <div class="alert alert-info small mt-3 mb-0">Login to submit a review. <a href="{{ route('login') }}">Login</a></div>
                    @endguest
                @else
                    <p class="text-muted">No reviews yet — be the first to travel and share your experience.</p>
                @endif
            </section>
        </div>

        <div class="col-lg-4">
            <div class="booking-card" id="schedule-picker">
                @if ($trip->base_price)
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            @if ($trip->discount_percentage)
                                <span class="price-was d-block">Rp {{ number_format($trip->base_price, 0, ',', '.') }}</span>
                            @endif
                            <p class="h3 fw-bold mb-0" style="color:#17233b;">Rp {{ number_format($trip->priceAfterDiscount(), 0, ',', '.') }}<span class="fs-6 text-muted fw-normal"> / person</span></p>
                        </div>
                        @if ($trip->discount_percentage)
                            <span class="badge bg-success-subtle text-success">{{ $trip->discount_percentage }}% OFF</span>
                        @endif
                    </div>
                    <p class="small text-muted mb-3">*Excluding applicable taxes</p>
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

                        <button type="submit" class="btn btn-primary w-100">Proceed to Book Online</button>
                    </form>
                @endif

                <hr>
                <a href="{{ route('contact') }}" class="btn btn-outline-primary w-100 mb-3">Send Inquiry</a>

                <p class="small fw-semibold text-uppercase text-muted mb-2">Coupons &amp; Offers</p>
                <div class="d-flex gap-2 mb-1">
                    <input type="text" class="form-control form-control-sm" placeholder="Have a coupon code?" disabled>
                    <button type="button" class="btn btn-outline-secondary btn-sm" disabled>Apply</button>
                </div>
                <p class="small text-muted mb-3"><em>Coupon belum aktif.</em></p>

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
window.tapgoGalleryImages = @json($galleryUrls ?? []);
window.tapgoLightboxIndex = 0;
function tapgoLightboxRender() {
    var imgs = window.tapgoGalleryImages;
    if (!imgs.length) return;
    document.getElementById('tapgo-lightbox-img').src = imgs[window.tapgoLightboxIndex];
    document.getElementById('tapgo-lightbox-counter').textContent = (window.tapgoLightboxIndex + 1) + ' / ' + imgs.length;
}
function tapgoLightboxOpen(e, index) {
    e.preventDefault();
    window.tapgoLightboxIndex = index || 0;
    tapgoLightboxRender();
    document.getElementById('tapgo-lightbox').style.display = 'flex';
}
function tapgoLightboxClose() {
    document.getElementById('tapgo-lightbox').style.display = 'none';
}
function tapgoLightboxNav(dir) {
    var imgs = window.tapgoGalleryImages;
    window.tapgoLightboxIndex = (window.tapgoLightboxIndex + dir + imgs.length) % imgs.length;
    tapgoLightboxRender();
}
</script>
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
