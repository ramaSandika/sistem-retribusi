@extends('layouts.admin')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h1 class="h2 text-dark fw-bold mb-1">Pengelolaan Produk</h1>
            <p class="text-secondary font-monospace" style="font-size: 0.8rem;">Daftar lengkap kurasi produk Anda OG Catalogue.</p>
        </div>
        <div>
            <a href="{{ route('products.create') }}" class="btn btn-black d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card-admin mb-4 shadow-sm">
        <div class="card-body py-3">
            <form action="{{ route('products.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label font-monospace text-secondary text-uppercase mb-1" style="font-size: 0.7rem;">Cari Produk</label>
                    <input type="text" name="q" id="search" class="form-control py-1 font-monospace" placeholder="Nama, Brand..." value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label font-monospace text-secondary text-uppercase mb-1" style="font-size: 0.7rem;">Kategori</label>
                    <select name="category_id" id="category" class="form-select py-1 font-monospace" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label font-monospace text-secondary text-uppercase mb-1" style="font-size: 0.7rem;">Status</label>
                    <select name="status" id="status" class="form-select py-1 font-monospace" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="sold_out" {{ request('status') == 'sold_out' ? 'selected' : '' }}>Sold Out</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid gap-2">
                    <button type="submit" class="btn btn-black py-1">Cari</button>
                    @if(request()->anyFilled(['q', 'category_id', 'status']))
                        <a href="{{ route('products.index') }}" class="btn btn-outline-black py-1 font-monospace text-center" style="font-size: 0.75rem;">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Products List -->
    <div class="card-admin shadow-sm">
        <div class="card-body p-0">
            <!-- Desktop Table View (Layar Lebar / Laptop) -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="table-light font-monospace text-secondary" style="font-size: 0.75rem;">
                        <tr>
                            <th class="ps-4" style="width: 80px;">FOTO</th>
                            <th>NAMA PRODUK</th>
                            <th>KATEGORI</th>
                            <th>HARGA</th>
                            <th>UKURAN</th>
                            <th>BRAND</th>
                            <th>STATUS</th>
                            <th class="pe-4 text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-4">
                                    <div class="border border-light-subtle rounded-3 overflow-hidden ratio ratio-1x1 bg-light shadow-sm" style="width: 50px; height: 50px;">
                                        <img src="{{ $product->primary_image_url }}" class="object-fit-cover w-100 h-100" alt="thumb">
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $product->name }}</div>
                                    <div class="text-muted font-monospace" style="font-size: 0.75rem;">{{ $product->slug }}</div>
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td class="font-monospace fw-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td><span class="badge border border-secondary-subtle text-secondary font-monospace rounded-pill py-1 px-2" style="font-size: 0.7rem;">{{ $product->size }}</span></td>
                                <td>{{ $product->brand ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($product->status === 'available')
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 font-monospace" style="font-size: 0.65rem;">TERSEDIA</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 font-monospace" style="font-size: 0.65rem;">SOLD OUT</span>
                                        @endif
                                        
                                        <!-- Toggle Status Action -->
                                        <form action="{{ route('products.status', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-black p-1 border-0 rounded-circle" title="Ubah Status">
                                                <i class="bi bi-arrow-repeat text-secondary"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Edit button -->
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-black px-2 py-1 rounded-3" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                        
                                        <!-- Delete action -->
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Semua foto produk juga akan terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-black px-2 py-1 text-danger rounded-3" title="Hapus"><i class="bi bi-trash-fill"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted border-0">
                                    <i class="bi bi-box-seam fs-1 d-block mb-2"></i> Belum ada data produk kurasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View (Khusus Layar HP - Pas Layar Tanpa Geser Samping) -->
            <div class="d-md-none p-3">
                <div class="d-flex flex-column gap-3">
                    @forelse($products as $product)
                        <div class="border border-light-subtle rounded-3 p-3 bg-white shadow-sm">
                            <div class="d-flex gap-3 align-items-center mb-2">
                                <!-- Thumbnail -->
                                <div class="border border-light-subtle rounded-3 overflow-hidden ratio ratio-1x1 bg-light flex-shrink-0" style="width: 65px; height: 65px;">
                                    <img src="{{ $product->primary_image_url }}" class="object-fit-cover w-100 h-100" alt="thumb">
                                </div>
                                <!-- Title & Category -->
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="text-muted font-monospace text-uppercase" style="font-size: 0.68rem;">{{ $product->category->name }}</span>
                                        <span class="badge border border-secondary-subtle text-secondary font-monospace rounded-pill py-0 px-2" style="font-size: 0.65rem;">SZ {{ $product->size }}</span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $product->name }}</h6>
                                    <div class="fw-bold text-dark font-monospace" style="font-size: 0.9rem;">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <hr class="my-2 text-light-subtle">

                            <!-- Status & Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center pt-1">
                                <div class="d-flex align-items-center gap-1">
                                    @if($product->status === 'available')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 font-monospace" style="font-size: 0.65rem;">TERSEDIA</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 font-monospace" style="font-size: 0.65rem;">SOLD OUT</span>
                                    @endif

                                    <!-- Quick Toggle Status -->
                                    <form action="{{ route('products.status', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary p-1 border-0 rounded-circle" title="Ubah Status">
                                            <i class="bi bi-arrow-repeat fs-6 text-secondary"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="d-flex gap-2">
                                    <!-- Edit button -->
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-black px-3 py-1 rounded-3 font-monospace d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    
                                    <!-- Delete button -->
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Semua foto produk juga akan terhapus.');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1 rounded-3" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-2"></i> Belum ada data produk kurasi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Paginasi -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

    <style>
        .object-fit-cover {
            object-fit: cover;
        }
        .pagination .page-link {
            color: #000000;
            border-color: #dee2e6;
            border-radius: 0.375rem !important;
            margin: 0 2px;
        }
        .pagination .page-item.active .page-link {
            background-color: #111111;
            border-color: #111111;
            color: #ffffff;
        }
        .pagination .page-link:focus {
            box-shadow: none;
        }
    </style>
@endsection
