@extends('layouts.app')
@section('title','Contact — TAPGO Travel')
@section('meta_description', 'Hubungi tim TAPGO Travel untuk pertanyaan seputar pemesanan dan perjalanan Anda.')
@section('content')
<main class="marketplace-page">
    <section class="editorial-hero has-photo" style="--hero-photo: url('https://images.unsplash.com/photo-1530866495561-507c9faab9c9?auto=format&fit=crop&w=1600&q=80');">
        <div class="container">
            <p class="eyebrow">Get in touch</p>
            <h1>Get in touch.</h1>
            <p>Questions about a booking or your next adventure? We are here to help.</p>
        </div>
    </section>

    <div class="container section-space">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8 order-lg-1">
                <div class="detail-panel">
                    <h2>Drop Us a Line</h2>
                    <p class="text-muted">Get in touch via the form below and we'll reply as soon as we can.</p>
                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Your Name</label>
                                <input name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email ID</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone No.</label>
                                <input name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject</label>
                                <input name="subject" value="{{ old('subject', request('subject')) }}" class="form-control @error('subject') is-invalid @enderror">
                                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Your Query</label>
                                <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div><button class="btn btn-primary">Send Message ✈</button></div>
                        </div>
                    </form>
                </div>

                <div class="ratio ratio-21x9 rounded overflow-hidden mt-4">
                    <iframe title="TAPGO Travel office location" src="https://www.google.com/maps?q=Jakarta,Indonesia&output=embed" loading="lazy" style="border:0;"></iframe>
                </div>
            </div>

            <div class="col-lg-4 order-lg-2">
                <div class="contact-info-card"><div class="icon">✉️</div><h3>Drop a Mail</h3><p>hello@tapgotravel.com<br>support@tapgotravel.com</p></div>
                <div class="contact-info-card"><div class="icon">📞</div><h3>Call Us</h3><p>(021) 555 0188<br>+62 812 3456 7890</p></div>
                <div class="contact-info-card"><div class="icon">📍</div><h3>Our Office</h3><p>Jakarta, Indonesia</p></div>
            </div>
        </div>
    </div>
</main>
@endsection
