@extends('layouts.app')

@section('title', 'OCR Dokumen & PDF Gemini AI')
@section('page_heading', 'Ekstraksi Cerdas Gemini AI')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <!-- Kolom Kiri: Form Upload Berkas (PDF / Gambar) -->
        <div class="col-lg-5 col-12">
            <div class="card-custom p-4 border-0 shadow-sm rounded-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon bg-danger-subtle text-danger rounded-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">Ekstraksi Dokumen & PDF</h5>
                        <small class="text-white-50">Didukung oleh Google Gemini AI</small>
                    </div>
                </div>

                <p class="text-white-50 small mb-4">
                    Unggah berkas <strong>PDF LRA/Kuitansi</strong> atau <strong>foto dokumen</strong>. Kecerdasan buatan Gemini akan membaca seluruh teks, angka, dan tabel lalu mengelompokkannya secara otomatis ke format terstruktur.
                </p>

                <form action="{{ route('ocr.process') }}" method="POST" enctype="multipart/form-data" id="ocrUploadForm">
                    @csrf

                    <!-- Area Unggah Berkas -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-white mb-2">Pilih Dokumen PDF atau Gambar</label>
                        <div class="rounded-4 p-4 text-center position-relative" id="dropArea"
                             style="border: 2px dashed rgba(255,255,255,0.35); background: rgba(0,0,0,0.25); cursor: pointer; transition: all 0.2s ease;">
                            
                            <!-- Tampilan Awal Sebelum Berkas Dipilih -->
                            <div id="uploadPrompt">
                                <i class="fas fa-cloud-arrow-up fa-3x text-danger mb-3 d-block"></i>
                                <h6 class="fw-bold mb-1 text-white">Klik atau Tarik Berkas ke Sini</h6>
                                <small class="text-white-50 d-block mb-1">Mendukung format <strong>PDF, JPG, JPEG, PNG, WEBP</strong></small>
                                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill" style="font-size: 0.72rem;">Maksimal 20 MB</span>
                            </div>

                            <!-- Preview Setelah Berkas Dipilih -->
                            <div id="fileSelectedPreview" class="d-none">
                                <div id="pdfIconBox" class="d-none mb-2 text-danger">
                                    <i class="fas fa-file-pdf fa-4x"></i>
                                </div>
                                <img src="" id="previewImg" class="img-fluid rounded-3 mb-2 shadow-sm d-none" style="max-height: 180px; object-fit: contain;">
                                <h6 class="text-white fw-bold mb-1 text-truncate px-2" id="filenameText"></h6>
                                <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill small" id="filesizeText"></span>
                                <p class="text-white-50 small mt-2 mb-0"><i class="fas fa-rotate me-1"></i> Klik di sini jika ingin mengganti berkas</p>
                            </div>

                            <input type="file" name="image" id="fileInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept=".pdf,image/jpeg,image/png,image/jpg,image/webp,application/pdf" style="cursor: pointer;" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-red w-100 py-3 fw-bold shadow" id="btnSubmit" style="font-size: 1.05rem;">
                        <i class="fas fa-bolt me-2"></i> Jalankan Ekstraksi AI
                    </button>
                </form>

                <!-- Loading State Indicator -->
                <div id="loadingIndicator" class="text-center py-4 d-none">
                    <div class="spinner-border text-danger mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Memproses...</span>
                    </div>
                    <h6 class="text-white fw-bold mb-1">Sedang Memproses dengan Gemini AI...</h6>
                    <p class="text-white-50 small mb-0">Model sedang membaca dan menyusun seluruh teks dokumen Anda.</p>
                </div>
            </div>

            <!-- Panduan Penggunaan Singkat -->
            <div class="card-custom p-4 border-0 shadow-sm rounded-4 mt-4">
                <h6 class="fw-bold text-white small mb-2"><i class="fas fa-circle-info text-danger me-2"></i>Tips Hasil Maksimal</h6>
                <ul class="text-white-50 small ps-3 mb-0">
                    <li class="mb-1">Dokumen <strong>PDF Laporan Realisasi (LRA)</strong> akan dibaca halaman per halaman oleh AI.</li>
                    <li class="mb-1">Jika mengunggah foto, pastikan teks tegak lurus dan pencahayaan terang.</li>
                    <li>Semua hasil otomatis disajikan dalam bentuk tabel rapi dan kode JSON murni.</li>
                </ul>
            </div>
        </div>

        <!-- Kolom Kanan: Tampilan Hasil Ekstraksi -->
        <div class="col-lg-7 col-12">
            <div class="card-custom p-4 border-0 shadow-sm rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom border-white-10">
                    <div>
                        <h5 class="fw-bold mb-0 text-white"><i class="fas fa-file-invoice me-2 text-danger"></i>Hasil Ekstraksi Dokumen</h5>
                        <small class="text-white-50">Data hasil pemindaian dan ekstraksi Google Gemini</small>
                    </div>
                    @if(isset($rawResult))
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
                            <i class="fas fa-check-circle me-1"></i> Berhasil Diproses
                        </span>
                    @endif
                </div>

                @if(!isset($rawResult))
                    <!-- Placeholder saat belum ada data -->
                    <div class="text-center py-5 my-auto">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white-50 mb-3" style="width: 90px; height: 90px; font-size: 36px; background: rgba(255,255,255,0.08);">
                            <i class="fas fa-file-circle-check"></i>
                        </div>
                        <h5 class="fw-bold text-white mb-2">Belum Ada Dokumen Diproses</h5>
                        <p class="text-white-50 small mx-auto" style="max-width: 380px; line-height: 1.6;">
                            Silakan pilih dokumen PDF laporan atau foto kuitansi di sebelah kiri, lalu tekan tombol <strong>Jalankan Ekstraksi AI</strong> untuk melihat hasilnya di sini.
                        </p>
                    </div>
                @else
                    <!-- Info Sumber Berkas -->
                    <div class="row g-3 mb-4">
                        @if(isset($isPdf) && $isPdf)
                            <div class="col-md-4 col-12">
                                <div class="border border-white-10 rounded-3 p-3 text-center" style="background: rgba(0,0,0,0.25);">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                    <small class="text-white-50 fw-semibold d-block">Dokumen PDF Sumber</small>
                                    <small class="text-white d-block text-truncate fw-bold mt-1" title="{{ $originalFilename ?? '' }}">{{ $originalFilename ?? 'dokumen.pdf' }}</small>
                                </div>
                            </div>
                        @elseif(isset($imagePreview) && $imagePreview)
                            <div class="col-md-4 col-12">
                                <div class="border border-white-10 rounded-3 p-2 text-center" style="background: rgba(0,0,0,0.25);">
                                    <small class="text-white-50 fw-semibold d-block mb-1">Foto Sumber</small>
                                    <img src="{{ $imagePreview }}" class="img-fluid rounded-2 shadow-sm" style="max-height: 140px; object-fit: contain;">
                                    <small class="text-white d-block text-truncate mt-1">{{ $originalFilename ?? 'gambar' }}</small>
                                </div>
                            </div>
                        @endif
                        <div class="{{ (isset($isPdf) && $isPdf) || (isset($imagePreview) && $imagePreview) ? 'col-md-8' : 'col-12' }}">
                            <div class="border border-info-subtle rounded-3 p-3 mb-0" style="background: rgba(59,130,246,0.15);">
                                <h6 class="fw-bold text-white small mb-1"><i class="fas fa-circle-check text-info me-1"></i> Dokumen Berhasil Dianalisis</h6>
                                <p class="small text-white-50 mb-0">
                                    {{ $isJson ? 'AI berhasil menstrukturkan data ke dalam format JSON objek/tabel.' : 'Data berhasil diekstraksi dalam format teks dokumen.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Navigasi Hasil -->
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4 py-2 small fw-bold text-white" id="pills-parsed-tab" data-bs-toggle="pill" data-bs-target="#pills-parsed" type="button" role="tab">
                                <i class="fas fa-table-cells me-1"></i> Tampilan Tabel Data
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 py-2 small fw-bold text-white" id="pills-raw-tab" data-bs-toggle="pill" data-bs-target="#pills-raw" type="button" role="tab">
                                <i class="fas fa-code me-1"></i> Raw JSON
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- Tab 1: Tabel / Data Terstruktur -->
                        <div class="tab-pane fade show active" id="pills-parsed" role="tabpanel">
                            @if($isJson && is_array($jsonResult))
                                <div class="table-responsive rounded-3" style="background: rgba(15, 5, 5, 0.70); border: 1.5px solid rgba(255,255,255,0.20);">
                                    <table class="table align-middle mb-0" style="background: transparent;">
                                        <thead>
                                            <tr style="background: rgba(0,0,0,0.60); border-bottom: 2px solid rgba(220,38,38,0.50);">
                                                <th style="width: 35%; color: #fca5a5 !important;" class="py-3 px-3 fw-bold">Bidang / Kolom</th>
                                                <th style="color: #ffffff !important;" class="py-3 px-3 fw-bold">Nilai Ekstraksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jsonResult as $key => $val)
                                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.12); background: rgba(0,0,0,0.25);">
                                                    <td class="fw-bold text-white small px-3 py-3" style="vertical-align: top; color: #ffffff !important;">
                                                        <i class="fas fa-caret-right text-danger me-1"></i> {{ ucwords(str_replace('_', ' ', $key)) }}
                                                    </td>
                                                    <td class="small px-3 py-3" style="color: #ffffff !important;">
                                                        @if(is_array($val))
                                                            <pre class="mb-0 p-2 rounded small text-white" style="background: rgba(0,0,0,0.50); font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.15); max-height: 250px; overflow-y: auto; color: #ffffff !important;">{{ json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        @else
                                                            <span class="fw-semibold text-white" style="color: #ffffff !important; font-size: 0.95rem;">{{ $val }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-3 rounded-3" style="background: rgba(0,0,0,0.30); border: 1px solid rgba(255,255,255,0.15);">
                                    <pre class="small text-white mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $rawResult }}</pre>
                                </div>
                            @endif
                        </div>

                        <!-- Tab 2: Raw JSON Text -->
                        <div class="tab-pane fade" id="pills-raw" role="tabpanel">
                            <div class="position-relative">
                                <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-2 shadow" onclick="copyRawText()">
                                    <i class="fas fa-copy me-1"></i> Salin JSON
                                </button>
                                <pre id="rawContentArea" class="text-white p-3 rounded-3 small mb-0" style="max-height: 450px; overflow-y: auto; font-family: monospace; background: #0f172a; border: 1px solid rgba(255,255,255,0.15);">{{ is_array($jsonResult) ? json_encode($jsonResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $rawResult }}</pre>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const fileInput = document.getElementById('fileInput');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const fileSelectedPreview = document.getElementById('fileSelectedPreview');
    const previewImg = document.getElementById('previewImg');
    const pdfIconBox = document.getElementById('pdfIconBox');
    const filenameText = document.getElementById('filenameText');
    const filesizeText = document.getElementById('filesizeText');
    const ocrUploadForm = document.getElementById('ocrUploadForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const loadingIndicator = document.getElementById('loadingIndicator');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                filenameText.innerText = file.name;
                const sizeKb = (file.size / 1024);
                filesizeText.innerText = sizeKb > 1024 ? (sizeKb / 1024).toFixed(2) + ' MB' : sizeKb.toFixed(1) + ' KB';

                uploadPrompt.classList.add('d-none');
                fileSelectedPreview.classList.remove('d-none');

                if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
                    pdfIconBox.classList.remove('d-none');
                    previewImg.classList.add('d-none');
                } else {
                    pdfIconBox.classList.add('d-none');
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        previewImg.src = evt.target.result;
                        previewImg.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    }

    if (ocrUploadForm) {
        ocrUploadForm.addEventListener('submit', function() {
            btnSubmit.classList.add('d-none');
            loadingIndicator.classList.remove('d-none');
        });
    }

    function copyRawText() {
        const text = document.getElementById('rawContentArea').innerText;
        navigator.clipboard.writeText(text).then(function() {
            alert('Teks JSON berhasil disalin ke clipboard!');
        });
    }
</script>
@endsection
