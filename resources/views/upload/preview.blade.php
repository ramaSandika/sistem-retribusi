@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header text-white py-3 d-flex justify-content-between align-items-center" style="background-color: #0b3d91;">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="bi bi-ui-checks me-2"></i>Validasi & Pratinjau Hasil Gemini AI OCR</h5>
                    <small class="text-light opacity-75">
                        Laporan: {{ $upload->periode }} {{ $upload->tahun }} &bull; Unit: {{ $upload->unit_opd ?? 'OPD' }}
                    </small>
                </div>
                <div>
                    <span class="badge bg-light text-dark px-3 py-2 fw-semibold">
                        {{ count($parsedData) }} Pos Rekening Retribusi Ditemukan
                    </span>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                    <div class="me-3 fs-3 text-primary"><i class="bi bi-info-circle-fill"></i></div>
                    <div>
                        <strong>Petunjuk Pemeriksaan (Human-in-the-Loop):</strong><br>
                        Sistem Gemini AI telah mengekstrak pos <strong>4.1.02 (Retribusi Daerah)</strong> dari file PDF Anda. Anda dapat mengoreksi angka atau nama retribusi langsung pada tabel di bawah sebelum mengklik <strong>"Simpan Permanen ke Database"</strong>.
                    </div>
                </div>

                @php
                    $totalTarget = 0;
                    $totalRealisasi = 0;
                    $totalLalu = 0;
                    foreach($parsedData as $row) {
                        // Hanya jumlahkan baris rincian/sub rincian agar tidak double count dengan baris induk
                        $isInduk = in_array($row['level_rekening'] ?? '', ['kelompok', 'jenis', 'objek']);
                        if (!$isInduk) {
                            $totalTarget += (float) ($row['anggaran'] ?? 0);
                            $totalRealisasi += (float) ($row['realisasi'] ?? ($row['nilai'] ?? 0));
                            $totalLalu += (float) ($row['realisasi_lalu'] ?? 0);
                        }
                    }
                    $persenTotal = $totalTarget > 0 ? round(($totalRealisasi / $totalTarget) * 100, 2) : 0;
                @endphp

                <!-- Kartu Ringkasan Hasil Ekstraksi -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card bg-light border-0 p-3">
                            <span class="text-secondary small fw-semibold">Target Anggaran Retribusi</span>
                            <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($totalTarget, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0 p-3">
                            <span class="text-secondary small fw-semibold">Realisasi Retribusi</span>
                            <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0 p-3">
                            <span class="text-secondary small fw-semibold">Capaian Retribusi (%)</span>
                            <h4 class="fw-bold text-success mb-0">{{ $persenTotal }}%</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0 p-3">
                            <span class="text-secondary small fw-semibold">Realisasi Tahun Lalu</span>
                            <h4 class="fw-bold text-secondary mb-0">Rp {{ number_format($totalLalu, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Form Konfirmasi & Tabel Editable -->
                <form action="{{ route('upload.confirm', $upload->id) }}" method="POST">
                    @csrf

                    <div class="table-responsive mb-4 border rounded shadow-sm">
                        <table class="table table-hover align-middle mb-0" id="previewTable">
                            <thead class="table-dark" style="background-color: #1e293b;">
                                <tr>
                                    <th style="width: 45px;">#</th>
                                    <th style="width: 170px;">Kode Rekening</th>
                                    <th>Uraian / Nama Retribusi</th>
                                    <th style="width: 160px;" class="text-end">Target Anggaran (Rp)</th>
                                    <th style="width: 160px;" class="text-end">Realisasi (Rp)</th>
                                    <th style="width: 90px;" class="text-center">%</th>
                                    <th style="width: 160px;" class="text-end">Realisasi Lalu (Rp)</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parsedData as $index => $data)
                                @php
                                    $isHeader = in_array($data['level_rekening'] ?? '', ['kelompok', 'jenis', 'objek']);
                                    $valAnggaran = (float) ($data['anggaran'] ?? 0);
                                    $valRealisasi = (float) ($data['realisasi'] ?? ($data['nilai'] ?? 0));
                                    $valPersen = (float) ($data['persentase'] ?? 0);
                                    $valLalu = (float) ($data['realisasi_lalu'] ?? 0);
                                @endphp
                                <tr class="{{ $isHeader ? 'table-warning fw-semibold' : '' }}" id="row-{{ $index }}">
                                    <td class="text-muted small text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <input type="text" name="items[{{ $index }}][kode_rekening]" 
                                               class="form-control form-control-sm font-monospace" 
                                               value="{{ $data['kode_rekening'] ?? '' }}" required>
                                        <input type="hidden" name="items[{{ $index }}][level_rekening]" 
                                               value="{{ $data['level_rekening'] ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="text" name="items[{{ $index }}][nama_retribusi]" 
                                               class="form-control form-control-sm" 
                                               value="{{ $data['nama_retribusi'] ?? '' }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[{{ $index }}][anggaran]" 
                                               class="form-control form-control-sm text-end input-anggaran" 
                                               data-index="{{ $index }}"
                                               value="{{ $valAnggaran }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[{{ $index }}][nilai]" 
                                               class="form-control form-control-sm text-end input-realisasi" 
                                               data-index="{{ $index }}"
                                               value="{{ $valRealisasi }}">
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary font-monospace persen-badge-{{ $index }}">
                                            {{ $valPersen }}%
                                        </span>
                                        <input type="hidden" name="items[{{ $index }}][persentase]" 
                                               class="input-persen-{{ $index }}" 
                                               value="{{ $valPersen }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[{{ $index }}][realisasi_lalu]" 
                                               class="form-control form-control-sm text-end text-muted" 
                                               value="{{ $valLalu }}">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1" onclick="deleteRow({{ $index }})" title="Hapus baris">
                                            <i class="bi bi-trash"></i> &times;
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-danger py-4">
                                        Tidak ada data akun retribusi yang dapat diekstrak dari dokumen.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(!empty($parsedData))
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div>
                            <span class="text-muted small">Pastikan semua data di atas telah sesuai sebelum menekan tombol simpan.</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('upload.create') }}" class="btn btn-outline-danger px-3">
                                Batal & Upload Ulang
                            </a>
                            <button type="submit" class="btn btn-success px-4 fw-semibold shadow-sm">
                                <i class="bi bi-check-circle-fill me-1"></i> Simpan Permanen ke Database
                            </button>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deleteRow(idx) {
        if (confirm('Hapus baris ini dari daftar?')) {
            const row = document.getElementById('row-' + idx);
            if (row) {
                row.remove();
            }
        }
    }

    // Auto-update persentase jika angka diubah operator
    document.querySelectorAll('.input-anggaran, .input-realisasi').forEach(input => {
        input.addEventListener('input', function() {
            const idx = this.dataset.index;
            const row = document.getElementById('row-' + idx);
            if (!row) return;

            const anggaran = parseFloat(row.querySelector('.input-anggaran').value) || 0;
            const realisasi = parseFloat(row.querySelector('.input-realisasi').value) || 0;
            let persen = 0;
            if (anggaran > 0) {
                persen = ((realisasi / anggaran) * 100).toFixed(2);
            }

            const badge = document.querySelector('.persen-badge-' + idx);
            if (badge) badge.innerText = persen + '%';
            const hiddenPersen = document.querySelector('.input-persen-' + idx);
            if (hiddenPersen) hiddenPersen.value = persen;
        });
    });
</script>
@endpush