@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="h2 text-dark fw-bold mb-1">Ringkasan Katalog</h1>
            <p class="text-secondary font-monospace" style="font-size: 0.8rem;">Statistik kinerja dan pengelolaan OG Catalogue.</p>
        </div>
        <div>
            <a href="{{ route('products.create') }}" class="btn btn-black d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    <!-- Stat Grid -->
    <div class="row g-4 mb-5">
        <!-- Stat item 1 -->
        <div class="col-md-4">
            <div class="card-stat bg-white shadow-sm border-start border-dark border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary font-monospace text-uppercase" style="font-size: 0.75rem;">Total Produk</span>
                    <i class="bi bi-box-seam fs-4 text-secondary"></i>
                </div>
                <div class="stat-val text-dark">{{ $totalProducts }}</div>
            </div>
        </div>
        <!-- Stat item 2 -->
        <div class="col-md-4">
            <div class="card-stat bg-white shadow-sm border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary font-monospace text-uppercase" style="font-size: 0.75rem;">Produk Tersedia</span>
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <div class="stat-val text-success">{{ $availableProducts }}</div>
            </div>
        </div>
        <!-- Stat item 3 -->
        <div class="col-md-4">
            <div class="card-stat bg-white shadow-sm border-start border-danger border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary font-monospace text-uppercase" style="font-size: 0.75rem;">Produk Sold Out</span>
                    <i class="bi bi-x-circle fs-4 text-danger"></i>
                </div>
                <div class="stat-val text-danger">{{ $soldOutProducts }}</div>
            </div>
        </div>
    </div>

    <!-- Recent Uploads Table -->
    <div class="card-admin shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Terakhir Ditambahkan</span>
            <a href="{{ route('products.index') }}" class="btn btn-outline-black btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.9rem;">Kelola Semua Produk</a>
        </div>
        <div class="card-body p-0">
            <!-- Desktop Table View -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="table-light font-monospace text-secondary" style="font-size: 0.75rem;">
                        <tr>
                            <th class="ps-4">PRODUK</th>
                            <th>KATEGORI</th>
                            <th>HARGA</th>
                            <th>UKURAN</th>
                            <th>BRAND</th>
                            <th>STATUS</th>
                            <th class="pe-4 text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentProducts as $product)
                            <tr>
                                <td class="ps-4 py-3 fw-medium text-dark">{{ $product->name }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td class="font-monospace fw-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td><span class="badge border border-secondary-subtle text-secondary font-monospace rounded-pill py-1 px-2" style="font-size: 0.7rem;">{{ $product->size }}</span></td>
                                <td>{{ $product->brand ?? '-' }}</td>
                                <td>
                                    @if($product->status === 'available')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 font-monospace" style="font-size: 0.65rem;">TERSEDIA</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 font-monospace" style="font-size: 0.65rem;">SOLD OUT</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-black px-2 py-1 rounded-3" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada produk yang diunggah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="d-md-none p-3">
                <div class="d-flex flex-column gap-3">
                    @forelse($recentProducts as $product)
                        <div class="border border-light-subtle rounded-3 p-3 bg-white shadow-sm">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="text-muted font-monospace text-uppercase" style="font-size: 0.68rem;">{{ $product->category->name }}</span>
                                <span class="badge border border-secondary-subtle text-secondary font-monospace rounded-pill py-0 px-2" style="font-size: 0.65rem;">SZ {{ $product->size }}</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">{{ $product->name }}</h6>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div class="fw-bold text-dark font-monospace" style="font-size: 0.9rem;">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @if($product->status === 'available')
                                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 font-monospace" style="font-size: 0.65rem;">TERSEDIA</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 font-monospace" style="font-size: 0.65rem;">SOLD OUT</span>
                                    @endif
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-black px-2 py-1 rounded-3" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Belum ada produk yang diunggah.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
