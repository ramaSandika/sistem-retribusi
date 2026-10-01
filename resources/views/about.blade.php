@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <!-- Page Header -->
    <div class="page-header bg-white border-0 mb-0">
        <div class="container">
            <span class="text-uppercase font-monospace text-secondary fs-8" style="letter-spacing: 0.2em;">Our Heritage & Vision</span>
            <h1 class="display-5 fw-bold mt-2">About Us</h1>
            <p class="text-muted col-lg-6 mx-auto mt-2 mb-0">OG Catalogue curates quality apparel selections — bringing the finest pieces to elevate your style.</p>
        </div>
    </div>

    <!-- History / Story Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center py-4">
                        <img src="{{ asset('images/og-logo.png') }}" alt="OG Logo" style="height: 140px; width: auto; object-fit: contain; margin-bottom: 1.5rem;">
                        <h2 class="font-monospace fw-bold text-dark mb-1" style="letter-spacing: 0.15em; font-size: 1.3rem;">OG CATALOGUE</h2>
                        <div class="vintage-divider my-4"></div>
                        <p class="text-secondary small fst-italic">Curated Fashion & Lifestyle</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold my-3">Our Story & Vision</h2>
                    <p class="text-secondary mb-3" style="line-height: 1.8;">
                        Established in 2019 — bringing you carefully curated pieces from designer labels, streetwear, outdoor gear, basics, and everything in between.
                    </p>
                    <p class="text-secondary mb-3" style="line-height: 1.8;">
                        All the good stuff, handpicked for your wardrobe — without killing your wallet. 💸
                    </p>
                    <p class="text-secondary mb-3" style="line-height: 1.8;">
                        🔥 Designer<br>
                        🛹 Streetwear<br>
                        🏕️ Outdoor<br>
                        👕 Basics<br>
                        & much more.
                    </p>
                    <p class="text-secondary mb-3" style="line-height: 1.8;">
                        Curated with style. Priced to stay affordable.
                    </p>
                    <p class="text-secondary mb-0" style="line-height: 1.8;">
                        Never hesitate to look cool. You don’t have to break the bank to have great style. Stay fresh.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
