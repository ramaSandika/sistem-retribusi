@extends('layouts.app')

@section('content')
<!-- Header Dashboard & Filter -->
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h4 class="fw-bold mb-1 text-dark">
            <i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard Realisasi Retribusi Daerah
        </h4>
        <p class="text-muted small mb-0">
            Monitoring capaian penerimaan Pendapatan Asli Daerah (PAD) khusus pos Retribusi Daerah (Akun 4.1.02)
        </p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <form method="GET" action="{{ route('dashboard') }}" class="d-inline-flex gap-2">
            <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                @for($y = date('Y') + 1; $y >= 2023; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endfor
            </select>
            <select name="periode" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Periode</option>
                <option value="Semester I" {{ $periode == 'Semester I' ? 'selected' : '' }}>Semester I</option>
                <option value="Semester II" {{ $periode == 'Semester II' ? 'selected' : '' }}>Semester II</option>
                <option value="Triwulan I" {{ $periode == 'Triwulan I' ? 'selected' : '' }}>Triwulan I</option>
                <option value="Triwulan II" {{ $periode == 'Triwulan II' ? 'selected' : '' }}>Triwulan II</option>
                <option value="Triwulan III" {{ $periode == 'Triwulan III' ? 'selected' : '' }}>Triwulan III</option>
                <option value="Triwulan IV" {{ $periode == 'Triwulan IV' ? 'selected' : '' }}>Triwulan IV</option>
            </select>
            <a href="{{ route('upload.create') }}" class="btn btn-sm btn-primary text-nowrap" style="background-color: #0b3d91; border-color: #0b3d91;">
                <i class="bi bi-upload me-1"></i> Upload PDF Baru
            </a>
        </form>
    </div>
</div>

<!-- 4 Kartu KPI Utama -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-4 border-primary">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Target Anggaran APBD</span>
                    <span class="badge bg-primary-subtle text-primary p-2 rounded-circle"><i class="bi bi-bullseye fs-5"></i></span>
                </div>
                <h4 class="fw-bold mb-1 text-dark">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</h4>
                <div class="text-muted small">Target penerimaan tahun {{ $tahun }}</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-4 border-success">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Realisasi Penerimaan</span>
                    <span class="badge bg-success-subtle text-success p-2 rounded-circle"><i class="bi bi-cash-stack fs-5"></i></span>
                </div>
                <h4 class="fw-bold mb-1 text-success">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</h4>
                <div class="text-muted small">Total dana retribusi terhimpun</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-4 border-warning">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Persentase Capaian</span>
                    <span class="badge bg-warning-subtle text-warning p-2 rounded-circle"><i class="bi bi-pie-chart-fill fs-5"></i></span>
                </div>
                <h4 class="fw-bold mb-1 text-warning">{{ $persenCapaian }}%</h4>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min($persenCapaian, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-4 border-info">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-secondary small fw-semibold">Dokumen Terverifikasi</span>
                    <span class="badge bg-info-subtle text-info p-2 rounded-circle"><i class="bi bi-file-earmark-check fs-5"></i></span>
                </div>
                <h4 class="fw-bold mb-1 text-info">{{ $totalFile }} Dokumen</h4>
                <div class="text-muted small">File PDF terunggah & tervalidasi</div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik dan Breakdown Jenis Retribusi -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Tren Realisasi vs Target per Periode</h6>
            </div>
            <div class="card-body">
                <canvas id="realisasiChart" style="max-height: 320px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-pie-chart me-2 text-primary"></i>Komposisi Retribusi Daerah</h6>
            </div>
            <div class="card-body">
                <div class="mb-3 p-3 bg-light rounded-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-semibold">4.1.02.01 Retribusi Jasa Umum</span>
                        <span class="small text-primary fw-bold">Rp {{ number_format($kategoriStat['jasa_umum'], 0, ',', '.') }}</span>
                    </div>
                    <small class="text-muted d-block mb-1">Pelayanan kesehatan, persampahan, parkir jalan, pasar, dll.</small>
                </div>

                <div class="mb-3 p-3 bg-light rounded-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-semibold">4.1.02.02 Retribusi Jasa Usaha</span>
                        <span class="small text-success fw-bold">Rp {{ number_format($kategoriStat['jasa_usaha'], 0, ',', '.') }}</span>
                    </div>
                    <small class="text-muted d-block mb-1">Sewa kekayaan daerah, parkir khusus, RPH, rekreasi, dll.</small>
                </div>

                <div class="p-3 bg-light rounded-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-semibold">4.1.02.03 Retribusi Perizinan Tertentu</span>
                        <span class="small text-warning fw-bold">Rp {{ number_format($kategoriStat['perizinan'], 0, ',', '.') }}</span>
                    </div>
                    <small class="text-muted d-block mb-1">Persetujuan Bangunan Gedung (PBG), dll.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dokumen Upload Terbaru -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Dokumen Realisasi Terbaru</h6>
        <a href="{{ route('retribusi.index') }}" class="btn btn-sm btn-outline-primary">Lihat Seluruh Data</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tahun</th>
                        <th>Periode</th>
                        <th>OPD / Unit Kerja</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Waktu Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestUploads as $doc)
                    <tr>
                        <td class="fw-semibold">{{ $doc->tahun }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $doc->periode }}</span></td>
                        <td>{{ $doc->unit_opd ?? '-' }}</td>
                        <td class="small text-muted">{{ Str::limit($doc->keterangan ?? '-', 40) }}</td>
                        <td>
                            @if($doc->status == 'Success')
                                <span class="badge bg-success">Tervalidasi</span>
                            @elseif($doc->status == 'Processing')
                                <span class="badge bg-warning text-dark">Processing</span>
                            @else
                                <span class="badge bg-danger">Gagal</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('upload.preview', $doc->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Belum ada dokumen yang diunggah untuk tahun {{ $tahun }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('realisasiChart').getContext('2d');
    const dataGrafik = JSON.parse('{!! json_encode($grafikData) !!}');

    const labels = dataGrafik.map(item => item.periode);
    const anggaran = dataGrafik.map(item => item.total_anggaran);
    const realisasi = dataGrafik.map(item => item.total_realisasi);

    new window.Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels.length ? labels : ['Belum Ada Data'],
            datasets: [
                {
                    label: 'Target Anggaran (Rp)',
                    data: anggaran.length ? anggaran : [0],
                    backgroundColor: 'rgba(148, 163, 184, 0.6)',
                    borderColor: 'rgba(148, 163, 184, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Realisasi (Rp)',
                    data: realisasi.length ? realisasi : [0],
                    backgroundColor: 'rgba(11, 61, 145, 0.8)',
                    borderColor: 'rgba(11, 61, 145, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
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
</script>
@endpush