<section class="tapgo-hero position-relative text-white">
    <div class="tapgo-hero__overlay"></div>
    <div class="container tapgo-hero__content position-relative">
        <div class="row"><div class="col-lg-9 col-xl-8 mx-auto text-center text-lg-start"><p class="hero-kicker">Made for meaningful journeys</p><h1>Explore the world <u>around you</u></h1><p class="hero-copy mx-auto mx-lg-0">Take a little break from the work stress of everyday. Discover, plan a trip, and explore beautiful destinations across Indonesia and beyond.</p></div></div>

        <div class="row"><div class="col-xl-11 mx-auto">
            <div class="hero-tabs mb-3" role="tablist" aria-label="Search category">
                <button type="button" class="active" data-target="trips" data-action="{{ route('trips.index') }}" data-field="Where to?" data-placeholder="Search a destination or experience">🏝️ Trips</button>
                <button type="button" data-target="destinations" data-action="{{ route('destinations.index') }}" data-field="Destination" data-placeholder="Search a destination">📍 Destinations</button>
                <button type="button" data-target="hotels" data-action="{{ route('hotels.index') }}" data-field="Going to?" data-placeholder="Search a city or hotel">🏨 Hotels</button>
                <button type="button" data-target="flights" data-action="{{ route('flights.index') }}" data-field="Going to?" data-placeholder="Search a destination">✈️ Flights</button>
            </div>
            <form id="hero-search-form" action="{{ route('trips.index') }}" method="GET" class="tapgo-hero__search row g-0 align-items-stretch">
                <div class="col-md-5 search-field"><label for="hero-destination" id="hero-destination-label">Where to?</label><input type="text" name="q" id="hero-destination" placeholder="Search a destination or experience"></div>
                <div class="col-md-3 search-field"><label for="hero-date">When</label><input type="date" name="date" id="hero-date" min="{{ now()->toDateString() }}"></div>
                <div class="col-md-2 search-field"><label for="hero-travelers">Travelers</label><input type="number" name="travelers" id="hero-travelers" min="1" value="2"></div>
                <div class="col-md-2 d-grid"><button type="submit" class="btn btn-warning search-submit">Search <span>→</span></button></div>
            </form>
        </div></div>
    </div>
</section>
@push('scripts')
<script>
(function () {
    var tabs = document.querySelectorAll('.hero-tabs button');
    var form = document.getElementById('hero-search-form');
    var label = document.getElementById('hero-destination-label');
    var input = document.getElementById('hero-destination');
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');
            if (form) { form.action = tab.dataset.action; }
            if (label) { label.textContent = tab.dataset.field; }
            if (input) { input.placeholder = tab.dataset.placeholder; }
        });
    });
})();
</script>
@endpush
