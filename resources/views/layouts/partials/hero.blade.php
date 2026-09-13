{{--
    TAPGO TRAVEL — Hero Section
    Ref: PRD section 9 (Homepage Requirements) & section 10 (Search Requirements)
    Catatan: form search di bawah ini masih UI shell (belum terhubung ke
    backend search) — logika pencarian dipasang pada fase Destination/Trip.
--}}
<section class="tapgo-hero position-relative text-white">
    <div class="tapgo-hero__overlay"></div>

    <div class="container position-relative py-5 py-lg-6">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">Discover More. Travel Better.</h1>
                <p class="lead mb-4">
                    Temukan destinasi terbaik, jelajahi paket perjalanan pilihan,
                    dan pesan liburanmu ke seluruh penjuru Indonesia — semua dalam satu platform.
                </p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form action="{{ url('/trips') }}" method="GET"
                      class="tapgo-hero__search bg-white rounded-4 shadow p-3 p-md-4 row g-3 align-items-end text-dark">
                    <div class="col-md-4">
                        <label for="hero-destination" class="form-label small fw-semibold text-uppercase text-muted">Destination</label>
                        <input type="text" name="destination" id="hero-destination"
                               class="form-control" placeholder="Mau ke mana?">
                    </div>
                    <div class="col-md-3">
                        <label for="hero-date" class="form-label small fw-semibold text-uppercase text-muted">Travel Date</label>
                        <input type="date" name="date" id="hero-date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="hero-travelers" class="form-label small fw-semibold text-uppercase text-muted">Travelers</label>
                        <input type="number" name="travelers" id="hero-travelers"
                               class="form-control" min="1" value="1">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
