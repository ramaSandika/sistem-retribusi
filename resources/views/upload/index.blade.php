@extends('layouts.app')

@section('title', 'Upload Dokumen & Foto Realisasi')
@section('page_heading', 'Upload PDF & Foto Bukti Realisasi')

@section('content')
<div class="row">
    <!-- Form Upload (Col-5) -->
    <div class="col-lg-5 col-12 mb-4">
        <div class="card-custom p-4">
            <h6 class="fw-bold text-danger mb-3">
                <i class="fas fa-cloud-upload-alt me-2"></i> Form Pengunggahan Dokumen / Foto Bukti
            </h6>
            <p class="text-white-50 small mb-4">Pilih OPD, periode, dan unggah berkas PDF resmi atau Foto Bukti (JPG/PNG/WEBP). Sistem akan membaca dan mengoperasikan ekstraksi data secara otomatis.</p>

            @if($errors->any())
                <div class="alert alert-danger border-danger-subtle rounded-3 small p-3 mb-3">
                    <div class="fw-bold mb-1"><i class="fas fa-triangle-exclamation me-1"></i> Perhatian:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('upload.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-white mb-2">Instansi / Unit OPD</label>
                    <select name="opd_name" class="form-select rounded-3 border-danger-subtle" required>
                        @foreach($opdList as $opd)
                            <option value="{{ $opd }}" {{ ($user->opd_name === $opd) ? 'selected' : '' }}>{{ $opd }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold text-white mb-2">Pilihan Bulan</label>
                        <select name="periode" class="form-select rounded-3 border-danger-subtle" required>
                            @php
                                $daftarBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                $defaultBulan = old('periode', 'Juni');
                            @endphp
                            @foreach($daftarBulan as $bln)
                                <option value="{{ $bln }}" {{ $defaultBulan == $bln ? 'selected' : '' }}>{{ $bln }}</option>
                            @endforeach
                        </select>
                        <small class="text-white-50" style="font-size: 0.72rem;">Pilih salah satu dari 12 bulan</small>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold text-white mb-2">Tahun Anggaran</label>
                        <input type="number" name="tahun" class="form-control rounded-3 border-danger-subtle" placeholder="Contoh: 2025" value="{{ old('tahun', date('Y')) }}" min="2000" max="2099" required>
                        <small class="text-white-50" style="font-size: 0.72rem;">Bisa isi tahun berapa saja bebas</small>
                    </div>
                </div>

                <!-- Drag and drop upload box for PDF / Images -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-white mb-2">Berkas PDF atau Foto Bukti (PDF / JPG / PNG / WEBP)</label>
                    <div class="border border-2 border-danger-subtle rounded-4 p-4 text-center" id="dropZone" style="border-style: dashed !important; background: rgba(0,0,0,0.30);">
                        <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                        <h6 class="fw-bold mb-1 text-white">Pilih Berkas PDF / Foto Bukti</h6>
                        <small class="text-white-50 d-block mb-3">Format PDF, JPG, JPEG, PNG, WEBP (Maksimal 20 MB)</small>
                        <input type="file" name="file_upload" id="fileUpload" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp,application/pdf" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-red w-100 py-3 fw-bold shadow-sm" style="font-size: 1.02rem;">
                    <i class="fas fa-microchip me-2"></i> Ekstraksi Data PDF & Parsing Berkas
                </button>
            </form>
        </div>
    </div>

    <!-- Informasi Alur Kerja & Panduan (Col-7) -->
    <div class="col-lg-7 col-12">
        <div class="card-custom p-4">
            <h6 class="fw-bold text-danger mb-3">
                <i class="fas fa-circle-info me-2"></i> Alur Proses Ekstraksi PDF & Foto Bukti
            </h6>
            
            <div class="timeline ps-3 border-start border-danger border-2 ms-2">
                <div class="mb-4 position-relative">
                    <span class="badge bg-danger rounded-circle position-absolute" style="left: -26px; top: 0; width: 22px; height: 22px;">1</span>
                    <h6 class="fw-bold mb-1 text-white">Unggah Berkas PDF atau Foto Kwitansi</h6>
                    <p class="text-white-50 small mb-0">Operator OPD mengunggah dokumen PDF atau foto bukti fisik kwitansi/lapangan.</p>
                </div>
                <div class="mb-4 position-relative">
                    <span class="badge bg-danger rounded-circle position-absolute" style="left: -26px; top: 0; width: 22px; height: 22px;">2</span>
                    <h6 class="fw-bold mb-1 text-white">Sistem Ekstraksi (Parser Engine)</h6>
                    <p class="text-white-50 small mb-0">Sistem mengekstrak Kode Rekening, Nama Retribusi, dan Nilai Realisasi (Rp).</p>
                </div>
                <div class="mb-4 position-relative">
                    <span class="badge bg-danger rounded-circle position-absolute" style="left: -26px; top: 0; width: 22px; height: 22px;">3</span>
                    <h6 class="fw-bold mb-1 text-white">Preview & Validasi Interaktif</h6>
                    <p class="text-white-50 small mb-0">Operator dapat melihat foto bukti pada tabel validasi dan menyesuaikan data sebelum disimpan.</p>
                </div>
                <div class="position-relative">
                    <span class="badge bg-danger rounded-circle position-absolute" style="left: -26px; top: 0; width: 22px; height: 22px;">4</span>
                    <h6 class="fw-bold mb-1 text-white">Simpan Database & Galeri Bukti</h6>
                    <p class="text-white-50 small mb-0">Data dan foto tersimpan di sistem, siap diekspor ke Excel dan dicetak dalam laporan resmi.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
