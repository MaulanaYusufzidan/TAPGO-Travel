{{--
    TAPGO TRAVEL — Footer Component
    Ref: PRD section 7 (Information Architecture) & section 9 (Homepage Requirements)
--}}
<footer class="bg-dark text-light pt-5 pb-4 mt-5">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6">
                <a href="{{ url('/') }}" class="navbar-brand fw-bold text-white fs-4 d-inline-block mb-2">
                    TAPGO TRAVEL
                </a>
                <p class="text-secondary mb-1">Discover More. Travel Better.</p>
                <p class="small text-secondary">
                    Platform digital travel &amp; wisata Indonesia — temukan destinasi,
                    pilih paket perjalanan, dan pesan perjalananmu dengan mudah dan aman.
                </p>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-uppercase fw-semibold mb-3">Explore</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ url('/destinations') }}" class="link-light link-underline-opacity-0 text-decoration-none">Destinations</a></li>
                    <li class="mb-2"><a href="{{ url('/trips') }}" class="link-light link-underline-opacity-0 text-decoration-none">Trips</a></li>
                    <li class="mb-2"><a href="{{ url('/experiences') }}" class="link-light link-underline-opacity-0 text-decoration-none">Experiences</a></li>
                    <li class="mb-2"><a href="{{ url('/travel-guide') }}" class="link-light link-underline-opacity-0 text-decoration-none">Travel Guide</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-uppercase fw-semibold mb-3">Company</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ url('/about') }}" class="link-light link-underline-opacity-0 text-decoration-none">About</a></li>
                    <li class="mb-2"><a href="{{ url('/faq') }}" class="link-light link-underline-opacity-0 text-decoration-none">FAQ</a></li>
                    <li class="mb-2"><a href="{{ url('/help-center') }}" class="link-light link-underline-opacity-0 text-decoration-none">Help Center</a></li>
                    <li class="mb-2"><a href="{{ url('/contact') }}" class="link-light link-underline-opacity-0 text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="text-uppercase fw-semibold mb-3">Stay in the loop</h6>
                <p class="small text-secondary">Dapatkan info promo dan destinasi baru langsung ke email kamu.</p>
                <form class="d-flex" action="#" method="POST" onsubmit="return false;">
                    <input type="email" class="form-control me-2" placeholder="Email kamu" aria-label="Email untuk newsletter">
                    <button class="btn btn-primary flex-shrink-0" type="submit">Subscribe</button>
                </form>
            </div>
        </div>

        <hr class="border-secondary mt-5 mb-3">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="small text-secondary mb-0">
                &copy; {{ date('Y') }} TAPGO TRAVEL. All rights reserved.
            </p>
            <div class="small text-secondary">
                <a href="{{ url('/faq') }}" class="link-light link-underline-opacity-0 text-decoration-none me-3">FAQ</a>
                <a href="{{ url('/help-center') }}" class="link-light link-underline-opacity-0 text-decoration-none">Help Center</a>
            </div>
        </div>
    </div>
</footer>
