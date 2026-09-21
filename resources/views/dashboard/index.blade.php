@extends('layouts.app')

@section('title', 'Dashboard Overview')
@section('page_heading', 'Overview Realisasi Retribusi')

@section('content')
{{-- WELCOME HEADER --}}
<div class="card-custom p-3 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="fw-bold mb-1" style="font-size: clamp(0.95rem, 2.5vw, 1.15rem);">
                <i class="fas fa-chart-pie me-2 text-danger"></i>Dashboard Realisasi Retribusi
            </h5>
            <small style="color:rgba(255,255,255,0.50);">
                Selamat datang, <strong class="text-white">{{ Auth::user()->name }}</strong>
                — {{ Auth::user()->opd_name }}
            </small>
        </div>
        <div class="text-end">
            <div class="badge px-3 py-2 rounded-3" style="background:rgba(220,38,38,0.18);border:1px solid rgba(220,38,38,0.35);color:#fca5a5;font-size:0.78rem;">
                <i class="fas fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </div>
</div>

<!-- Filter Tahun Bar -->
<div class="card-custom p-3 mb-4">
    <form action="{{ route('dashboard') }}" method="GET"
          class="d-flex flex-wrap gap-2 align-items-center">
        <label class="fw-semibold small mb-0" style="color:rgba(255,255,255,0.65);">
            <i class="fas fa-filter me-1 text-danger"></i> Filter:
        </label>
        <select name="bulan" class="form-select form-select-sm rounded-3 fw-semibold"
                style="width:auto; min-width:130px;" onchange="this.form.submit()">
            @foreach($bulanList as $bln)
                <option value="{{ $bln }}" {{ $selectedBulan == $bln ? 'selected' : '' }}>
                    Bulan {{ $bln }}
                </option>
            @endforeach
        </select>
        <div class="input-group input-group-sm" style="width:auto;">
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
            <input type="number" name="tahun" class="form-control fw-bold"
                   style="width:80px;" value="{{ $tahun }}" min="2000" max="2100">
            <button type="submit" class="btn btn-sm btn-danger fw-bold">Terapkan</button>
        </div>
        <span class="badge badge-red px-2 py-1 rounded-pill ms-auto d-none d-sm-inline-flex">
            <i class="fas fa-building me-1"></i> {{ strtoupper($user->opd_name) }}
        </span>
    </form>
</div>

<!-- STAT CARDS ROW -->
<div class="row g-3 mb-4">
    <!-- Stat 1 -->
    <div class="col-md-3 col-sm-6">
        <div class="card-custom p-3 d-flex align-items-center">
            <div class="stat-icon bg-danger text-white me-3">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <p class="text-muted mb-0 small fw-semibold">Realisasi {{ $selectedBulan }} {{ $tahun }}</p>
                <h5 class="fw-bold mb-0 text-danger">Rp {{ number_format($totalRealisasiBulanIni, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>

    <!-- Stat 2 -->
    <div class="col-md-3 col-sm-6">
        <div class="card-custom p-3 d-flex align-items-center">
            <div class="stat-icon bg-dark text-white me-3" style="background: var(--deep-burgundy) !important;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <p class="text-muted mb-0 small fw-semibold">Total Realisasi {{ $tahun }}</p>
                <h5 class="fw-bold mb-0">Rp {{ number_format($totalRealisasiTahun, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>

    <!-- Stat 3 -->
    <div class="col-md-3 col-sm-6">
        <div class="card-custom p-3 d-flex align-items-center">
            <div class="stat-icon bg-danger-subtle text-danger me-3">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div>
                <p class="text-muted mb-0 small fw-semibold">Dokumen PDF Diupload</p>
                <h5 class="fw-bold mb-0">{{ $totalDokumenUploaded }} File</h5>
            </div>
        </div>
    </div>

    <!-- Stat 4 -->
    <div class="col-md-3 col-sm-6">
        <div class="card-custom p-3 d-flex align-items-center">
            <div class="stat-icon bg-warning text-dark bg-opacity-75 me-3">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <p class="text-muted mb-0 small fw-semibold">Unit OPD Terdaftar</p>
                <h5 class="fw-bold mb-0">{{ $totalOpdAktif }} Instansi</h5>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-4 mb-4">
    <!-- Bar Chart Realisasi Bulanan -->
    <div class="col-lg-8">
        <div class="card-custom p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-danger"><i class="fas fa-chart-column me-2"></i>Grafik Perkembangan Realisasi {{ $tahun }}</h6>
                    <small class="text-muted">Total penerimaan per bulan dalam Rupiah</small>
                </div>
                <span class="badge badge-red px-3 py-2 rounded-pill">Data Terverifikasi</span>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart OPD Distribution -->
    <div class="col-lg-4">
        <div class="card-custom p-4 h-100">
            <div class="mb-3">
                <h6 class="fw-bold mb-0 text-danger"><i class="fas fa-chart-pie me-2"></i>{{ $isAdmin ? 'Kontribusi per OPD' : 'Distribusi Retribusi' }}</h6>
                <small class="text-muted">Proporsi penerimaan {{ $tahun }}</small>
            </div>
            <div style="height: 260px; position: relative;" class="d-flex justify-content-center align-items-center">
                <canvas id="opdChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- RECENT PDF UPLOADS - CARD LIST -->
<div class="card-custom p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h6 class="fw-bold mb-0" style="color:#fca5a5;">
                <i class="fas fa-clock-rotate-left me-2"></i>Aktivitas Upload Dokumen Terakhir
            </h6>
            <small style="color:rgba(255,255,255,0.45);">Riwayat unggah dokumen PDF & foto bukti</small>
        </div>
        <a href="{{ route('upload.index') }}" class="btn btn-sm btn-red">
            <i class="fas fa-plus me-1"></i> Upload Baru
        </a>
    </div>

    @forelse($recentUploads as $item)
    <div class="d-flex align-items-start gap-3 mb-3 pb-3"
         style="border-bottom: 1px solid rgba(255,255,255,0.08); {{ $loop->last ? 'border-bottom:none; margin-bottom:0; padding-bottom:0;' : '' }}">

        {{-- Ikon file / status indicator --}}
        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3"
             style="width:46px; height:46px; background:
                @if($item->status === 'Success') rgba(22,163,74,0.18);
                @elseif($item->status === 'Processing') rgba(234,179,8,0.18);
                @else rgba(220,38,38,0.18); @endif
             ">
            @if($item->status === 'Success')
                <i class="fas fa-file-circle-check fa-lg" style="color:#86efac;"></i>
            @elseif($item->status === 'Processing')
                <i class="fas fa-file-circle-exclamation fa-lg fa-spin" style="color:#fde68a;"></i>
            @else
                <i class="fas fa-file-circle-xmark fa-lg" style="color:#fca5a5;"></i>
            @endif
        </div>

        {{-- Info utama --}}
        <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                <span class="fw-bold text-truncate" style="color:#fff; font-size:0.88rem; max-width:240px;">
                    {{ $item->original_filename }}
                </span>
                {{-- Badge Status --}}
                @if($item->status === 'Success')
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(22,163,74,0.20);border:1px solid rgba(22,163,74,0.40);color:#86efac;font-size:0.72rem;">
                        <i class="fas fa-check-circle me-1"></i>Terverifikasi
                    </span>
                @elseif($item->status === 'Processing')
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(234,179,8,0.20);border:1px solid rgba(234,179,8,0.40);color:#fde68a;font-size:0.72rem;">
                        <i class="fas fa-spinner fa-spin me-1"></i>Diproses
                    </span>
                @else
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(220,38,38,0.20);border:1px solid rgba(220,38,38,0.40);color:#fca5a5;font-size:0.72rem;">
                        <i class="fas fa-times-circle me-1"></i>Gagal
                    </span>
                @endif
            </div>

            {{-- Meta info baris 2 --}}
            <div class="d-flex flex-wrap gap-2 align-items-center" style="font-size:0.78rem;">
                <span style="color:rgba(255,255,255,0.50);">
                    <i class="fas fa-building me-1" style="color:#fca5a5;"></i>
                    {{ $item->opd_name }}
                </span>
                <span style="color:rgba(255,255,255,0.30);">•</span>
                <span style="color:rgba(255,255,255,0.50);">
                    <i class="fas fa-calendar-alt me-1" style="color:#fca5a5;"></i>
                    {{ $item->periode }}
                </span>
                <span style="color:rgba(255,255,255,0.30);">•</span>
                <span class="fw-bold" style="color:#86efac;">
                    Rp {{ number_format($item->total_nilai, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Waktu upload (kanan) --}}
        <div class="flex-shrink-0 text-end d-none d-sm-block">
            <small style="color:rgba(255,255,255,0.38); font-size:0.72rem; line-height:1.4;">
                {{ $item->created_at->format('d M Y') }}<br>
                <span style="color:rgba(255,255,255,0.25);">{{ $item->created_at->format('H:i') }}</span>
            </small>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <div class="mb-3">
            <i class="fas fa-inbox fa-3x" style="color:rgba(255,255,255,0.15);"></i>
        </div>
        <p class="mb-1 fw-semibold" style="color:rgba(255,255,255,0.45);">Belum ada dokumen diupload</p>
        <small style="color:rgba(255,255,255,0.25);">Mulai upload PDF atau foto bukti realisasi</small>
        <div class="mt-3">
            <a href="{{ route('upload.index') }}" class="btn btn-sm btn-red">
                <i class="fas fa-cloud-upload-alt me-1"></i> Upload Sekarang
            </a>
        </div>
    </div>
    @endforelse
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: @json($bulanList),
                datasets: [{
                    label: 'Realisasi (Rp)',
                    data: @json($chartBulanan),
                    backgroundColor: 'rgba(153, 27, 27, 0.85)',
                    borderColor: '#7f1d1d',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toLocaleString('id-ID') + ' Jt';
                            }
                        }
                    }
                }
            }
        });

        const ctxOpd = document.getElementById('opdChart').getContext('2d');
        new Chart(ctxOpd, {
            type: 'doughnut',
            data: {
                labels: @json($opdChartLabels),
                datasets: [{
                    data: @json($opdChartData),
                    backgroundColor: [
                        '#991b1b',
                        '#dc2626',
                        '#b91c1c',
                        '#7f1d1d',
                        '#f87171',
                        '#ef4444'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
@endsection
