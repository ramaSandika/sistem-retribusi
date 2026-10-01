@extends('layouts.app')

@section('title', $product->name)

@section('meta')
    <meta property="og:title" content="{{ $product->name }} | Retro Threads" />
    <meta property="og:description" content="Harga: Rp {{ number_format($product->price, 0, ',', '.') }} | {{ Str::limit($product->description, 100) }}" />
    <meta property="og:image" content="{{ $product->primary_image_url }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="product" />
@endsection

@section('content')
    <div class="container py-5">
        
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-5">
            <ol class="breadcrumb font-monospace" style="font-size: 0.8rem; text-transform: uppercase;">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Catalog</a></li>
                <li class="breadcrumb-item"><a href="{{ route('catalog', ['kategori' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <!-- Product View -->
        <div class="row g-5">
            
            <!-- Gallery Section (Left) -->
            <div class="col-lg-6">
                <div class="overflow-hidden position-relative shadow-sm rounded-3 bg-white p-0 border border-light-subtle">
                    @if($product->images->count() > 0)
                        <!-- Bootstrap Carousel -->
                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded-3">
                                @foreach($product->images as $index => $img)
                                     <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                         <div class="ratio bg-light" style="--bs-aspect-ratio: 110%; overflow: hidden;">
                                             <img src="{{ $img->image_url }}" class="d-block w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                                         </div>
                                     </div>
                                @endforeach
                            </div>
                            
                            @if($product->images->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true" style="background-size: 50%;"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true" style="background-size: 50%;"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>

                                <!-- Thumbnail Indicators -->
                                <div class="row g-2 p-3 justify-content-center bg-white border-top border-light-subtle">
                                    @foreach($product->images as $index => $img)
                                        <div class="col-2">
                                            <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}" class="w-100 p-0 border border-light-subtle rounded-3 overflow-hidden ratio ratio-1x1 {{ $index === 0 ? 'active border-dark' : '' }}" {{ $index === 0 ? 'aria-current="true"' : '' }} aria-label="Slide {{ $index + 1 }}">
                                                <img src="{{ $img->image_url }}" class="w-100 h-100 object-fit-cover" alt="thumbnail">
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- No images fallback -->
                        <div class="ratio bg-light text-muted rounded-3" style="--bs-aspect-ratio: 110%;">
                            <div class="d-flex flex-column align-items-center justify-content-center w-100 h-100 text-center">
                                <i class="bi bi-image fs-1 d-block mb-2"></i>
                                <span class="font-monospace" style="font-size: 0.75rem;">NO PHOTOS AVAILABLE</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detail Info Section (Right) -->
            <div class="col-lg-6">
                <div class="ps-lg-4">
                    
                    <!-- Category & Status -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted font-monospace text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.1em;">
                            {{ $product->category->name }}
                        </span>
                        @if($product->status === 'available')
                            <span class="badge bg-white border border-success text-success font-monospace rounded-0 px-3 py-2" style="font-size: 0.7rem; letter-spacing: 0.05em; text-transform: uppercase;">
                                <i class="bi bi-check-circle-fill me-1"></i> Available
                            </span>
                        @else
                            <span class="badge bg-white border border-danger text-danger font-monospace rounded-0 px-3 py-2" style="font-size: 0.7rem; letter-spacing: 0.05em; text-transform: uppercase;">
                                <i class="bi bi-x-circle-fill me-1"></i> Sold Out
                            </span>
                        @endif
                    </div>

                    <!-- Product Title -->
                    <h1 class="display-6 fw-bold mb-3 text-dark" style="letter-spacing: -0.02em;">{{ $product->name }}</h1>
                    
                    <!-- Price -->
                    <div class="product-price-modern text-dark fs-2 mb-4 pb-3 border-bottom border-light fw-bold">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>

                    <!-- Specs Table -->
                    <div class="card bg-white border border-light-subtle rounded-3 p-4 mb-4 shadow-sm">
                        <h4 class="h6 text-uppercase text-secondary mb-3 pb-2 border-bottom border-light fw-bold font-monospace" style="letter-spacing: 0.05em; font-size: 0.75rem;">Specifications</h4>
                        <table class="table table-borderless mb-0" style="font-size: 0.9rem;">
                            <tbody>
                                <tr class="border-bottom border-light">
                                    <td class="text-secondary py-2 ps-0" style="width: 35%;">Brand</td>
                                    <td class="text-dark py-2 fw-semibold">{{ $product->brand ?? 'No Brand' }}</td>
                                </tr>
                                <tr class="border-bottom border-light">
                                    <td class="text-secondary py-2 ps-0">Size</td>
                                    <td class="text-dark py-2 fw-semibold"><span class="badge bg-dark rounded-pill px-3">{{ $product->size }}</span></td>
                                </tr>
                                <tr class="border-bottom border-light">
                                    <td class="text-secondary py-2 ps-0">Condition</td>
                                    <td class="text-dark py-2 fw-semibold">{{ $product->condition }}</td>
                                </tr>
                                <tr>
                                    <td class="text-secondary py-2 ps-0">Category</td>
                                    <td class="text-dark py-2 fw-semibold">{{ $product->category->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Description -->
                    <div class="mb-5">
                        <h4 class="h6 text-uppercase text-secondary mb-3 pb-2 border-bottom border-light fw-bold font-monospace" style="letter-spacing: 0.05em; font-size: 0.75rem;">Description</h4>
                        <p class="text-secondary" style="white-space: pre-line; line-height: 1.8; font-size: 0.95rem;">{{ $product->description }}</p>
                    </div>

                    <!-- Order Actions -->
                    @if($product->status === 'available')
                        <div class="d-grid mb-4">
                            <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-dark btn-lg py-3 px-4 rounded-3 d-flex align-items-center justify-content-center gap-2 fw-bold" style="background-color: #111111; border-color: #111111; font-size: 0.95rem;">
                                <i class="bi bi-whatsapp fs-5 text-success"></i> Inquire via WhatsApp
                            </a>
                            <small class="text-center text-muted font-monospace mt-2" style="font-size: 0.72rem;">Click to connect directly with the admin via WhatsApp.</small>
                        </div>
                    @else
                        <div class="d-grid mb-4">
                            <button class="btn btn-secondary btn-lg py-3 px-4 rounded-3 cursor-not-allowed fw-bold" disabled style="background-color: #767676; border-color: #767676;">
                                <i class="bi bi-x-circle"></i> Item Sold Out
                            </button>
                            <small class="text-center text-muted font-monospace mt-2" style="font-size: 0.72rem;">Sorry, this item is already sold.</small>
                        </div>
                    @endif

                </div>
            </div>

        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->count() > 0)
            <div class="mt-5 pt-5 border-top border-light-subtle">
                <div class="text-center mb-5">
                    <span class="text-uppercase font-monospace text-secondary" style="font-size: 0.75rem; letter-spacing: 0.2em;">You Might Also Like</span>
                    <h2 class="display-6 fw-bold mt-2">Recommended Pieces</h2>
                    <div class="vintage-divider"></div>
                </div>

                <div class="row g-3 g-md-4">
                    @foreach($relatedProducts as $related)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 rounded-3 overflow-hidden border border-light-subtle shadow-sm product-card-modern">
                                <div class="position-relative bg-light" style="overflow: hidden; padding-bottom: 110%;">
                                    <a href="{{ route('catalog.show', $related->slug) }}" class="d-block position-absolute top-0 start-0 w-100 h-100">
                                        <img src="{{ $related->primary_image_url }}" alt="{{ $related->name }}" class="w-100 h-100 object-fit-cover transition-transform">
                                    </a>
                                </div>
                                <div class="p-3 bg-white d-flex flex-column justify-content-between flex-grow-1">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="text-muted font-monospace text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">
                                                {{ $related->category->name ?? 'VINTAGE' }}
                                            </span>
                                            <span class="badge border border-secondary-subtle text-secondary font-monospace rounded-pill py-0 px-2" style="font-size: 0.65rem;">
                                                {{ $related->size }}
                                            </span>
                                        </div>
                                        <h3 class="product-title-modern mb-2">
                                            <a href="{{ route('catalog.show', $related->slug) }}" class="text-dark text-decoration-none">
                                                {{ $related->name }}
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="pt-2 mt-auto">
                                        <div class="product-price-modern">
                                            Rp{{ number_format($related->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <style>
        .product-card-modern {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background: #ffffff;
            border-color: #ebebeb !important;
        }
        .product-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.06) !important;
        }
        .transition-transform {
            transition: transform 0.3s ease;
        }
        .product-card-modern:hover .transition-transform {
            transform: scale(1.04);
        }
        .product-title-modern {
            font-family: 'Playfair Display', Georgia, serif !important;
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: capitalize;
            letter-spacing: 0.01em;
            line-height: 1.3;
            color: #111111;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.5rem;
        }
        .product-price-modern {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #111111;
            letter-spacing: -0.01em;
        }
    </style>
@endsection
