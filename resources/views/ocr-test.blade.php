@extends('layouts.app')

@section('title', 'Uji Coba OCR Gemini AI')
@section('page_heading', 'Uji Coba OCR Google Gemini AI')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <!-- Kolom Kiri: Form Upload Gambar -->
        <div class="col-lg-5 col-12">
            <div class="card-custom p-4 border-0 shadow-sm rounded-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon bg-danger-subtle text-danger rounded-3" style="width: 46px; height: 46px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-wand-magic-sparkles"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Ekstraksi Dokumen / Gambar</h6>
                        <small class="text-muted">Didukung oleh Google Gemini 1.5 Flash</small>
                    </div>
                </div>

                <p class="text-muted small mb-4">
                    Unggah foto dokumen, formulir, kuitansi, atau catatan. AI akan membaca teks dan menyusunnya otomatis dalam format JSON terstruktur.
                </p>

                <form action="{{ route('ocr.process') }}" method="POST" enctype="multipart/form-data" id="ocrUploadForm">
                    @csrf

                    <!-- Area Drag & Drop Gambar -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted">Pilih Berkas Gambar</label>
                        <div class="border border-2 border-danger-subtle rounded-4 p-4 text-center bg-light position-relative" id="dropArea" style="border-style: dashed !important; cursor: pointer; transition: all 0.2s ease;">
                            <div id="uploadPrompt">
                                <i class="fas fa-file-image fa-3x text-danger opacity-75 mb-3 d-block"></i>
                                <h6 class="fw-bold mb-1 text-dark">Klik atau Tarik Gambar ke Sini</h6>
                                <small class="text-muted d-block mb-2">Mendukung format JPG, PNG, WEBP (Maksimal 10 MB)</small>
                            </div>
                            <div id="imageSelectedPreview" class="d-none">
                                <img src="" id="previewImg" class="img-fluid rounded-3 mb-2 shadow-sm" style="max-height: 200px; object-fit: contain;">
                                <p class="small text-muted mb-0 fw-semibold" id="filenameText"></p>
                            </div>
                            <input type="file" name="image" id="imageInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/jpeg,image/png,image/jpg,image/webp" style="cursor: pointer;" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-red w-100 py-2 fw-bold shadow-sm" id="btnSubmit">
                        <i class="fas fa-bolt me-2"></i> Jalankan Ekstraksi AI
                    </button>
                </form>

                <!-- Loading State Indicator (Hidden by default) -->
                <div id="loadingIndicator" class="text-center py-4 d-none">
                    <div class="spinner-border text-danger mb-2" role="status">
                        <span class="visually-hidden">Memproses...</span>
                    </div>
                    <p class="small fw-semibold text-muted mb-0">Sedang memproses OCR dengan Gemini AI...</p>
                </div>
            </div>

            <!-- Petunjuk Penggunaan Singkat -->
            <div class="card-custom p-4 border-0 shadow-sm rounded-4 mt-4 bg-light">
                <h6 class="fw-bold text-dark small mb-2"><i class="fas fa-circle-info text-danger me-2"></i>Tips Hasil Optimal</h6>
                <ul class="text-muted small ps-3 mb-0">
                    <li class="mb-1">Pastikan pencahayaan cukup dan teks tidak buram/kabur.</li>
                    <li class="mb-1">Teks lurus memudahkan model mendeteksi baris dan kolom.</li>
                    <li>Hasil output otomatis disaring menjadi struktur data JSON.</li>
                </ul>
            </div>
        </div>

        <!-- Kolom Kanan: Tampilan Hasil Ekstraksi -->
        <div class="col-lg-7 col-12">
            <div class="card-custom p-4 border-0 shadow-sm rounded-4 h-100">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-file-lines me-2 text-danger"></i>Hasil Ekstraksi OCR</h6>
                        <small class="text-muted">Data terstruktur dari pembacaan AI</small>
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
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center text-muted mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Gambar Diproses</h6>
                        <p class="text-muted small mx-auto" style="max-width: 360px;">
                            Silakan unggah foto dokumen atau kuitansi di sebelah kiri untuk melihat hasil ekstraksi data secara langsung di sini.
                        </p>
                    </div>
                @else
                    <!-- Hasil Ekstraksi Selesai -->
                    <div class="row g-3 mb-4">
                        @if(isset($imagePreview))
                            <div class="col-md-4 col-12">
                                <div class="border rounded-3 p-2 bg-light text-center">
                                    <small class="text-muted fw-semibold d-block mb-1">Gambar Sumber</small>
                                    <img src="{{ $imagePreview }}" class="img-fluid rounded-2 shadow-sm" style="max-height: 180px; object-fit: contain;">
                                    <small class="text-muted d-block text-truncate mt-1" style="font-size: 0.75rem;">{{ $originalFilename ?? 'gambar' }}</small>
                                </div>
                            </div>
                        @endif
                        <div class="{{ isset($imagePreview) ? 'col-md-8' : 'col-12' }}">
                            <div class="alert alert-info-subtle border border-info-subtle rounded-3 p-3 mb-0">
                                <h6 class="fw-bold text-dark small mb-1"><i class="fas fa-code me-1"></i> Status Format Respons</h6>
                                <p class="small text-muted mb-0">
                                    {{ $isJson ? 'Format terdeteksi sebagai JSON terstruktur valid.' : 'Format teks mentah (non-JSON) dikembalikan oleh AI.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Navigasi Hasil: Tampilan Rapi vs Raw JSON -->
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-3 py-1 small fw-bold" id="pills-parsed-tab" data-bs-toggle="pill" data-bs-target="#pills-parsed" type="button" role="tab">
                                <i class="fas fa-table-list me-1"></i> Tampilan Terstruktur
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-3 py-1 small fw-bold" id="pills-raw-tab" data-bs-toggle="pill" data-bs-target="#pills-raw" type="button" role="tab">
                                <i class="fas fa-code me-1"></i> Raw JSON / Teks
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- Tab 1: Tabel / Card Terstruktur -->
                        <div class="tab-pane fade show active" id="pills-parsed" role="tabpanel">
                            @if($isJson && is_array($jsonResult))
                                <div class="table-responsive border rounded-3 bg-white">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="small text-muted fw-bold" style="width: 35%;">Kunci (Field)</th>
                                                <th class="small text-muted fw-bold">Nilai Ekstraksi (Value)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jsonResult as $key => $val)
                                                <tr>
                                                    <td class="fw-semibold text-danger small">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                                                    <td class="small text-dark">
                                                        @if(is_array($val))
                                                            <pre class="mb-0 bg-light p-2 rounded small text-dark" style="font-size: 0.8rem;">{{ json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        @else
                                                            {{ $val }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="p-3 bg-light rounded-3">
                                    <p class="small text-muted mb-0" style="white-space: pre-wrap;">{{ $rawResult }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tab 2: Raw JSON Text -->
                        <div class="tab-pane fade" id="pills-raw" role="tabpanel">
                            <div class="position-relative">
                                <button class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2 rounded-2" onclick="copyRawText()">
                                    <i class="fas fa-copy me-1"></i> Salin
                                </button>
                                <pre id="rawContentArea" class="bg-dark text-light p-3 rounded-3 small mb-0" style="max-height: 400px; overflow-y: auto; font-family: monospace;">{{ is_array($jsonResult) ? json_encode($jsonResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $rawResult }}</pre>
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
    const imageInput = document.getElementById('imageInput');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const imageSelectedPreview = document.getElementById('imageSelectedPreview');
    const previewImg = document.getElementById('previewImg');
    const filenameText = document.getElementById('filenameText');
    const ocrUploadForm = document.getElementById('ocrUploadForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const loadingIndicator = document.getElementById('loadingIndicator');

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    previewImg.src = evt.target.result;
                    filenameText.innerText = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                    uploadPrompt.classList.add('d-none');
                    imageSelectedPreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
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
