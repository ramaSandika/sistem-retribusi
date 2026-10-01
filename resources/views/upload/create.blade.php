@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-navy text-white py-3 d-flex justify-content-between align-items-center" style="background-color: #0b3d91;">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-arrow-up me-2"></i>Upload Dokumen Realisasi Retribusi BAPENDA</h5>
                    <small class="text-light opacity-75">Ekstraksi cerdas berbasis Gemini AI Multimodal (Fokus Akun 4.1.02)</small>
                </div>
                <span class="badge bg-warning text-dark px-3 py-2 fw-semibold">AI Powered OCR</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tahun Anggaran <span class="text-danger">*</span></label>
                            <input type="number" name="tahun" class="form-control" value="{{ old('tahun', date('Y')) }}" placeholder="2025" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Periode Pelaporan <span class="text-danger">*</span></label>
                            <select name="periode" class="form-select" required>
                                <option value="">-- Pilih Periode Laporan --</option>
                                <optgroup label="Semester / Triwulan (Konsolidasi)">
                                    <option value="Semester I" {{ old('periode') == 'Semester I' ? 'selected' : '' }}>Semester I (Januari - Juni)</option>
                                    <option value="Semester II" {{ old('periode') == 'Semester II' ? 'selected' : '' }}>Semester II (Juli - Desember)</option>
                                    <option value="Triwulan I" {{ old('periode') == 'Triwulan I' ? 'selected' : '' }}>Triwulan I</option>
                                    <option value="Triwulan II" {{ old('periode') == 'Triwulan II' ? 'selected' : '' }}>Triwulan II</option>
                                    <option value="Triwulan III" {{ old('periode') == 'Triwulan III' ? 'selected' : '' }}>Triwulan III</option>
                                    <option value="Triwulan IV" {{ old('periode') == 'Triwulan IV' ? 'selected' : '' }}>Triwulan IV</option>
                                </optgroup>
                                <optgroup label="Bulanan">
                                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bln)
                                        <option value="{{ $bln }}" {{ old('periode') == $bln ? 'selected' : '' }}>Bulan {{ $bln }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Unit OPD / Instansi Pengirim</label>
                            <input type="text" name="unit_opd" class="form-control" value="{{ old('unit_opd', auth()->user()->unit_opd ?? 'BAPENDA / Konsolidasi Kota') }}" placeholder="Contoh: Dinas Perhubungan, Dinas Lingkungan Hidup">
                            <small class="text-muted">Tentukan nama instansi / OPD pemilik dokumen realisasi ini.</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Dokumen PDF Laporan Realisasi <span class="text-danger">*</span></label>
                            <div class="p-3 border rounded bg-light">
                                <input type="file" name="file_pdf" class="form-control" accept="application/pdf" required>
                                <div class="mt-2 text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>Format file <strong>.pdf</strong> (Maks. 30MB). AI akan membaca tabel APBD, mengabaikan pos pajak/belanja, dan hanya mengambil pos <strong>4.1.02 Retribusi Daerah</strong>.
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Catatan / Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan mengenai dokumen atau nomor BAP...">{{ old('keterangan') }}</textarea>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="force_replace" id="forceReplace" value="1">
                                <label class="form-check-label text-secondary small" for="forceReplace">
                                    Perbarui/timpa data jika laporan untuk periode dan tahun ini sudah pernah diunggah.
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-2 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold" style="background-color: #0b3d91; border-color: #0b3d91;">
                            <i class="bi bi-cpu me-1"></i> Ekstrak & Proses dengan Gemini AI
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-3 py-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($recentUploads) && $recentUploads->count() > 0)
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-secondary">Riwayat Upload Terakhir</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Periode</th>
                                <th>Tahun</th>
                                <th>OPD</th>
                                <th>Status</th>
                                <th>Tanggal Upload</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUploads as $upl)
                            <tr>
                                <td class="fw-semibold">{{ $upl->periode }}</td>
                                <td>{{ $upl->tahun }}</td>
                                <td>{{ $upl->unit_opd ?? '-' }}</td>
                                <td>
                                    @if($upl->status == 'Success')
                                        <span class="badge bg-success">Tervalidasi</span>
                                    @elseif($upl->status == 'Processing')
                                        <span class="badge bg-warning text-dark">Dalam Proses</span>
                                    @else
                                        <span class="badge bg-danger">Gagal</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $upl->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('upload.preview', $upl->id) }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection