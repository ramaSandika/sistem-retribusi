@extends('layouts.app')

@section('title', 'Product Catalog')

@section('content')
    <!-- Catalog Section -->
    <section class="py-4 bg-white min-vh-100">
        <div class="container py-2">
            
            <!-- Breadcrumbs -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-secondary text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">Product</li>
                </ol>
            </nav>

            <!-- Control Bar: Filter, Sort, View Toggle -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-3 pb-2 flex-nowrap">
                <!-- Filter & Sort Group -->
                <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden">
                    <!-- Filter Trigger Button -->
                    <button class="btn btn-outline-dark rounded-3 px-3 py-2 d-inline-flex align-items-center gap-2 font-monospace flex-shrink-0" 
                            type="button" 
                            data-bs-toggle="offcanvas" 
                            data-bs-target="#filterOffcanvas" 
                            aria-controls="filterOffcanvas"
                            style="font-size: 0.85rem; border-color: #111111; color: #111111; background: #ffffff; height: 38px;">
                        <i class="bi bi-sliders fs-6"></i>
                        <span class="fw-semibold">Filter</span>
                        @if(request()->anyFilled(['q', 'kategori', 'ukuran', 'kondisi', 'status', 'min_price', 'max_price']))
                            <span class="badge bg-dark text-white rounded-pill px-2" style="font-size: 0.65rem;">
                                {{ collect([request('q'), request('kategori'), request('ukuran'), request('kondisi'), request('status'), request('min_price'), request('max_price')])->filter()->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Sort Dropdown -->
                    <form action="{{ route('catalog') }}" method="GET" id="sortForm" class="flex-grow-1" style="min-width: 0;">
                        @foreach(request()->except(['sort', 'page']) as $key => $val)
                            @if(is_array($val))
                                @foreach($val as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endif
                        @endforeach
                        
                        <div class="w-100">
                            <select name="sort" class="form-select rounded-3 py-2 text-dark fw-medium sort-select-custom" style="font-size: 0.82rem; border-color: #dee2e6; height: 38px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; padding-right: 2rem;" onchange="document.getElementById('sortForm').submit();">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Sort: Latest</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Grid View Toggles -->
                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                    <button class="btn btn-dark rounded-3 d-flex align-items-center justify-content-center" 
                            id="viewGridBtn"
                            style="background-color: #111111; border-color: #111111; width: 38px; height: 38px;" 
                            title="Grid View">
                        <i class="bi bi-grid-fill text-white fs-6"></i>
                    </button>
                    <button class="btn btn-outline-secondary rounded-3 d-flex align-items-center justify-content-center" 
                            id="viewListBtn"
                            style="border-color: #dee2e6; width: 38px; height: 38px;" 
                            title="List View">
                        <i class="bi bi-list-ul fs-5 text-dark"></i>
                    </button>
                </div>
            </div>

            <!-- Active Filter Badges Bar -->
            @if(request()->anyFilled(['q', 'kategori', 'ukuran', 'kondisi', 'status', 'min_price', 'max_price']))
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4 p-2 bg-light rounded-3">
                    @if(request('q'))
                        <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="badge-filter-chip">
                            <span>Keyword: "{{ request('q') }}"</span>
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                    @if(request('kategori'))
                        @php $catObj = $categories->where('slug', request('kategori'))->first(); @endphp
                        <a href="{{ request()->fullUrlWithQuery(['kategori' => null]) }}" class="badge-filter-chip">
                            <span>{{ $catObj ? $catObj->name : request('kategori') }}</span>
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                    @if(request('ukuran'))
                        <a href="{{ request()->fullUrlWithQuery(['ukuran' => null]) }}" class="badge-filter-chip">
                            <span>Size: {{ request('ukuran') }}</span>
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                    @if(request('kondisi'))
                        <a href="{{ request()->fullUrlWithQuery(['kondisi' => null]) }}" class="badge-filter-chip">
                            <span>{{ request('kondisi') }}</span>
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                    @if(request('status'))
                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="badge-filter-chip">
                            <span>{{ request('status') == 'available' ? 'Available' : 'Sold Out' }}</span>
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                    @if(request('min_price') || request('max_price'))
                        <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="badge-filter-chip">
                            <span>Rp {{ number_format(request('min_price', 0), 0, ',', '.') }} - {{ request('max_price') ? number_format(request('max_price'), 0, ',', '.') : 'Max' }}</span>
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif

                    <a href="{{ route('catalog') }}" class="text-dark fw-bold ms-2 text-decoration-none font-monospace" style="font-size: 0.8rem;">
                        Reset
                    </a>
                </div>
            @endif

            <!-- Product Grid -->
            <div class="row g-3 g-md-4" id="productsContainer">
                @forelse($products as $product)
                    <div class="col-6 col-md-4 col-lg-3 product-col">
                        <div class="card h-100 rounded-3 overflow-hidden border border-light-subtle shadow-sm product-card-modern">
                            
                            <!-- Image Container -->
                            <div class="product-img-box position-relative bg-light overflow-hidden">
                                <a href="{{ route('catalog.show', $product->slug) }}" class="d-block w-100 h-100">
                                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover transition-transform">
                                </a>

                                <!-- Status Badge -->
                                @if($product->status === 'sold_out')
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-dark text-white rounded-pill px-2 py-1 font-monospace" style="font-size: 0.65rem;">
                                        SOLD OUT
                                    </span>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-3 bg-white d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-muted font-monospace text-uppercase" style="font-size: 0.68rem; letter-spacing: 0.05em;">
                                            {{ $product->category->name ?? 'VINTAGE' }}
                                        </span>
                                        <span class="badge border border-secondary-subtle text-secondary font-monospace rounded-pill py-0 px-2" style="font-size: 0.65rem;">
                                            {{ $product->size }}
                                        </span>
                                    </div>
                                    <h3 class="product-title-modern mb-2">
                                        <a href="{{ route('catalog.show', $product->slug) }}" class="text-dark text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                </div>
                                <div class="pt-2 mt-auto">
                                    <div class="product-price-modern">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="border border-dashed rounded-4 p-5 bg-light text-center">
                            <i class="bi bi-bag-x fs-1 text-secondary mb-3 d-block"></i>
                            <h4 class="fw-bold mb-2">No Products Found</h4>
                            <p class="text-muted mb-4" style="font-size: 0.9rem;">We couldn't find any products matching your filters or search keywords.</p>
                            <a href="{{ route('catalog') }}" class="btn btn-dark rounded-3 px-4 py-2 font-monospace" style="font-size: 0.85rem;">Reset Filters</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>
    </section>

    <!-- Offcanvas Filter Drawer (Screenshot 1 Exact Replication) -->
    <div class="offcanvas offcanvas-start border-0 shadow-lg" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel" style="max-width: 380px;">
        <div class="offcanvas-header border-bottom py-3 px-4">
            <h5 class="offcanvas-title fw-bold text-dark fs-5" id="filterOffcanvasLabel">Filter</h5>
            <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-4 d-flex flex-column justify-content-between" style="overflow-y: auto;">
            <form action="{{ route('catalog') }}" method="GET" id="drawerFilterForm">
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <!-- Section: Search -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">Search</label>
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute text-muted" style="top: 12px; left: 14px; font-size: 0.9rem;"></i>
                        <input type="text" 
                               name="q" 
                               id="drawerSearch" 
                               class="form-control rounded-3 ps-5 py-2" 
                               placeholder="Search product name / SKU" 
                               value="{{ request('q') }}"
                               style="border-color: #dee2e6; font-size: 0.9rem;">
                    </div>
                </div>

                <hr class="text-muted my-4" style="opacity: 0.15;">

                <!-- Section: Size Filter -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer" 
                         data-bs-toggle="collapse" 
                         data-bs-target="#collapseSize" 
                         aria-expanded="true">
                        <span class="fw-bold text-dark" style="font-size: 0.95rem;">Size</span>
                        <i class="bi bi-chevron-up text-dark fs-6 transition-transform" id="iconSize"></i>
                    </div>

                    <div class="collapse show" id="collapseSize">
                        <input type="hidden" name="ukuran" id="selectedSizeInput" value="{{ request('ukuran') }}">
                        <div class="row g-2">
                            @php
                                 $standardSizes = ['S', 'M', 'L', 'XL', 'XXL', '28', '29', '30', '31', '32', '33', '34', '36', '38', '40'];
                                 $allSizes = collect($standardSizes)->merge($sizes)->unique()->filter()->values();
                            @endphp
                            @foreach($allSizes as $sz)
                                <div class="col-3">
                                    <button type="button" 
                                            class="size-pill-btn w-100 text-center py-2 rounded-3 {{ request('ukuran') == $sz ? 'active' : '' }}" 
                                            onclick="selectSizePill('{{ $sz }}', this)">
                                        {{ $sz }}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <hr class="text-muted my-4" style="opacity: 0.15;">

                <!-- Section: Category Filter -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer" 
                         data-bs-toggle="collapse" 
                         data-bs-target="#collapseCategory" 
                         aria-expanded="true">
                        <span class="fw-bold text-dark" style="font-size: 0.95rem;">Category</span>
                        <i class="bi bi-chevron-up text-dark fs-6 transition-transform"></i>
                    </div>

                    <div class="collapse show" id="collapseCategory">
                        <input type="hidden" name="kategori" id="selectedCategoryInput" value="{{ request('kategori') }}">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" 
                                    class="category-pill-btn px-3 py-2 rounded-3 {{ !request('kategori') ? 'active' : '' }}" 
                                    onclick="selectCategoryPill('', this)">
                                All
                            </button>
                            @foreach($categories as $cat)
                                <button type="button" 
                                        class="category-pill-btn px-3 py-2 rounded-3 {{ request('kategori') == $cat->slug ? 'active' : '' }}" 
                                        onclick="selectCategoryPill('{{ $cat->slug }}', this)">
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <hr class="text-muted my-4" style="opacity: 0.15;">

                <!-- Section: Price Range -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer" 
                         data-bs-toggle="collapse" 
                         data-bs-target="#collapsePrice" 
                         aria-expanded="true">
                        <span class="fw-bold text-dark" style="font-size: 0.95rem;">Price Range</span>
                        <i class="bi bi-chevron-up text-dark fs-6 transition-transform"></i>
                    </div>

                    <div class="collapse show" id="collapsePrice">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label text-muted small mb-1">Min (Rp)</label>
                                <input type="number" name="min_price" class="form-control rounded-3 py-2" placeholder="0" value="{{ request('min_price') }}" style="font-size: 0.85rem; border-color: #dee2e6;">
                            </div>
                            <div class="col-6">
                                <label class="form-label text-muted small mb-1">Max (Rp)</label>
                                <input type="number" name="max_price" class="form-control rounded-3 py-2" placeholder="1000000" value="{{ request('max_price') }}" style="font-size: 0.85rem; border-color: #dee2e6;">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="text-muted my-4" style="opacity: 0.15;">

                <!-- Section: Condition -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer" 
                         data-bs-toggle="collapse" 
                         data-bs-target="#collapseCondition" 
                         aria-expanded="false">
                        <span class="fw-bold text-dark" style="font-size: 0.95rem;">Condition</span>
                        <i class="bi bi-chevron-down text-dark fs-6 transition-transform"></i>
                    </div>

                    <div class="collapse" id="collapseCondition">
                        <input type="hidden" name="kondisi" id="selectedConditionInput" value="{{ request('kondisi') }}">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['Like New', 'Very Good', 'Good', 'Fair'] as $cond)
                                <button type="button" 
                                        class="condition-pill-btn px-3 py-2 rounded-3 {{ request('kondisi') == $cond ? 'active' : '' }}" 
                                        onclick="selectConditionPill('{{ $cond }}', this)">
                                    {{ $cond }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Section: Status -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer" 
                         data-bs-toggle="collapse" 
                         data-bs-target="#collapseStatus" 
                         aria-expanded="false">
                        <span class="fw-bold text-dark" style="font-size: 0.95rem;">Status</span>
                        <i class="bi bi-chevron-down text-dark fs-6 transition-transform"></i>
                    </div>

                    <div class="collapse" id="collapseStatus">
                        <input type="hidden" name="status" id="selectedStatusInput" value="{{ request('status') }}">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" 
                                    class="status-pill-btn px-3 py-2 rounded-3 {{ request('status') == 'available' ? 'active' : '' }}" 
                                    onclick="selectStatusPill('available', this)">
                                Available
                            </button>
                            <button type="button" 
                                    class="status-pill-btn px-3 py-2 rounded-3 {{ request('status') == 'sold_out' ? 'active' : '' }}" 
                                    onclick="selectStatusPill('sold_out', this)">
                                Sold Out
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Bottom Sticky Action Buttons -->
            <div class="pt-3 border-top mt-auto bg-white">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('catalog') }}" class="btn btn-outline-dark w-100 py-2 fw-semibold rounded-3" style="font-size: 0.9rem; border-color: #111111;">
                            Reset Filter
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-dark w-100 py-2 fw-semibold rounded-3 text-white" style="background-color: #111111; border-color: #111111; font-size: 0.9rem;" onclick="document.getElementById('drawerFilterForm').submit();">
                            Apply Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/6285954054217?text={{ urlencode('Hello Admin OG Catalogue, I would like to inquire about products in the catalog.') }}" 
       target="_blank" 
       class="floating-wa-btn shadow-lg" 
       title="Chat via WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>

    <style>
        /* Modern E-Commerce Card Style */
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

        .product-img-box {
            height: 280px;
            width: 100%;
        }
        @media (max-width: 768px) {
            .product-img-box {
                height: 220px;
            }
        }
        @media (max-width: 576px) {
            .product-img-box {
                height: 180px;
            }
        }

        /* List View Horizontal Card Layout */
        .list-view-card {
            flex-direction: row !important;
            align-items: center;
        }
        .list-view-card .product-img-box {
            width: 160px !important;
            height: 160px !important;
            flex-shrink: 0;
        }
        @media (max-width: 576px) {
            .list-view-card .product-img-box {
                width: 110px !important;
                height: 110px !important;
            }
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
        @media (max-width: 576px) {
            .product-title-modern {
                font-size: 0.75rem;
                height: 2.05rem;
            }
            .product-price-modern {
                font-size: 0.85rem !important;
            }
        }

        .product-price-modern {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #111111;
            letter-spacing: -0.01em;
        }

        /* Filter Chips */
        .badge-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            font-size: 0.78rem;
            color: #111111;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .badge-filter-chip:hover {
            background: #f1f3f5;
            border-color: #111111;
            color: #000;
        }
        .badge-filter-chip i {
            color: #111111;
            font-size: 0.85rem;
        }

        /* Pill Buttons in Filter Drawer */
        .size-pill-btn, .category-pill-btn, .condition-pill-btn, .status-pill-btn {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            color: #212529;
            font-size: 0.82rem;
            font-weight: 500;
            transition: all 0.15s ease;
            cursor: pointer;
        }
        .size-pill-btn:hover, .category-pill-btn:hover, .condition-pill-btn:hover, .status-pill-btn:hover {
            border-color: #111111;
            color: #111111;
        }
        .size-pill-btn.active, .category-pill-btn.active, .condition-pill-btn.active, .status-pill-btn.active {
            background: #111111 !important;
            border-color: #111111 !important;
            color: #ffffff !important;
            font-weight: 600;
        }

        /* Floating WhatsApp Button */
        .floating-wa-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 58px;
            height: 58px;
            background-color: #25d366;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            z-index: 1050;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
        }
        .floating-wa-btn:hover {
            transform: scale(1.1);
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.45) !important;
        }
    </style>

    <script>
        // Pill Selection Helpers
        function selectSizePill(size, btn) {
            const input = document.getElementById('selectedSizeInput');
            if (input.value === size) {
                input.value = '';
                btn.classList.remove('active');
            } else {
                document.querySelectorAll('.size-pill-btn').forEach(b => b.classList.remove('active'));
                input.value = size;
                btn.classList.add('active');
            }
        }

        function selectCategoryPill(categorySlug, btn) {
            const input = document.getElementById('selectedCategoryInput');
            document.querySelectorAll('.category-pill-btn').forEach(b => b.classList.remove('active'));
            input.value = categorySlug;
            btn.classList.add('active');
        }

        function selectConditionPill(condition, btn) {
            const input = document.getElementById('selectedConditionInput');
            if (input.value === condition) {
                input.value = '';
                btn.classList.remove('active');
            } else {
                document.querySelectorAll('.condition-pill-btn').forEach(b => b.classList.remove('active'));
                input.value = condition;
                btn.classList.add('active');
            }
        }

        function selectStatusPill(status, btn) {
            const input = document.getElementById('selectedStatusInput');
            if (input.value === status) {
                input.value = '';
                btn.classList.remove('active');
            } else {
                document.querySelectorAll('.status-pill-btn').forEach(b => b.classList.remove('active'));
                input.value = status;
                btn.classList.add('active');
            }
        }

        // List vs Grid View Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const gridBtn = document.getElementById('viewGridBtn');
            const listBtn = document.getElementById('viewListBtn');
            const container = document.getElementById('productsContainer');
            const cols = document.querySelectorAll('.product-col');
            const cards = document.querySelectorAll('.product-card-modern');

            listBtn.addEventListener('click', function() {
                listBtn.classList.remove('btn-outline-secondary');
                listBtn.classList.add('btn-dark');
                listBtn.style.backgroundColor = '#111111';
                listBtn.style.borderColor = '#111111';
                listBtn.querySelector('i').classList.replace('text-dark', 'text-white');

                gridBtn.classList.remove('btn-dark');
                gridBtn.classList.add('btn-outline-secondary');
                gridBtn.style.backgroundColor = 'transparent';
                gridBtn.style.borderColor = '#dee2e6';
                gridBtn.querySelector('i').classList.replace('text-white', 'text-dark');

                cols.forEach(col => {
                    col.className = 'col-12 product-col';
                });
                cards.forEach(card => {
                    card.classList.add('list-view-card');
                });
            });

            gridBtn.addEventListener('click', function() {
                gridBtn.classList.remove('btn-outline-secondary');
                gridBtn.classList.add('btn-dark');
                gridBtn.style.backgroundColor = '#111111';
                gridBtn.style.borderColor = '#111111';
                gridBtn.querySelector('i').classList.replace('text-dark', 'text-white');

                listBtn.classList.remove('btn-dark');
                listBtn.classList.add('btn-outline-secondary');
                listBtn.style.backgroundColor = 'transparent';
                listBtn.style.borderColor = '#dee2e6';
                listBtn.querySelector('i').classList.replace('text-white', 'text-dark');

                cols.forEach(col => {
                    col.className = 'col-6 col-md-4 col-lg-3 product-col';
                });
                cards.forEach(card => {
                    card.classList.remove('list-view-card');
                });
            });
        });
    </script>
@endsection
