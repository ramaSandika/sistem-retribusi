@extends('layouts.app')

@section('title', 'Memproses Dokumen')

@section('content')
<style>
    .spinner-ring {
        width: 56px;
        height: 56px;
        border: 5px solid #e2e8f0;
        border-top-color: #0b3d91;
        border-radius: 50%;
        animation: spin 0.9s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-6 col-lg-5">

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 text-center">

            {{-- Spinner (saat proses) --}}
            <div id="area-loading">
                <div class="spinner-ring mx-auto mb-3"></div>
                <h5 class="fw-bold text-dark mb-1">AI Sedang Memproses</h5>
                <p class="text-muted small mb-0">Menganalisis dokumen dan mengekstrak data retribusi...</p>
            </div>

            {{-- Sukses --}}
            <div id="area-done" class="d-none">
                <div class="rounded-circle bg-success bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                    <i class="bi bi-check-lg text-success fs-3"></i>
                </div>
                <h5 class="fw-bold text-success mb-1">Berhasil!</h5>
                <p class="text-muted small mb-0" id="done-msg">Data berhasil diekstrak.</p>
            </div>

            {{-- Gagal --}}
            <div id="area-failed" class="d-none">
                <div class="rounded-circle bg-danger bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                    <i class="bi bi-x-lg text-danger fs-3"></i>
                </div>
                <h5 class="fw-bold text-danger mb-1">Proses Gagal</h5>
                <p class="text-muted small mb-2">Terjadi kesalahan saat memproses.</p>
                <div class="alert alert-danger small text-start mb-3" id="failed-msg"></div>
                <div class="alert alert-warning small text-start mb-3">
                    <strong>Kemungkinan penyebab:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        <li>GEMINI_API_KEY di <code>.env</code> tidak valid</li>
                        <li>Queue worker belum jalan: <code>php artisan queue:work</code></li>
                    </ul>
                </div>
                <a href="{{ route('upload.create') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Upload Ulang
                </a>
            </div>

            <hr class="my-3">

            {{-- Info dokumen --}}
            <div class="text-start small">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Dokumen</span>
                    <span class="fw-semibold text-truncate ms-2" style="max-width:180px;">{{ basename($upload->file_path) }}</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Periode</span>
                    <span class="fw-semibold">{{ $upload->periode }} {{ $upload->tahun }}</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">OPD</span>
                    <span class="fw-semibold text-truncate ms-2" style="max-width:180px;">{{ $upload->unit_opd }}</span>
                </div>
            </div>

            {{-- Tombol sukses --}}
            <div id="area-success-btn" class="d-none mt-3">
                <p class="text-muted small mb-2">Otomatis pindah dalam <strong id="countdown">3</strong> detik...</p>
                <a href="{{ route('upload.preview', $upload->id) }}" class="btn btn-success btn-sm">
                    <i class="bi bi-eye me-1"></i> Lihat Hasil
                </a>
            </div>

        </div>
    </div>

    <p class="text-center text-muted small mt-3">
        <i class="bi bi-shield-lock me-1"></i> Dokumen diproses aman oleh Gemini AI BAPENDA
    </p>

</div>
</div>
</div>

<script>
const statusUrl  = '{{ route("upload.ocr-status", $upload->id) }}';
const previewUrl = '{{ route("upload.preview", $upload->id) }}';

function markDone(data) {
    document.getElementById('area-loading').classList.add('d-none');
    document.getElementById('area-done').classList.remove('d-none');
    document.getElementById('done-msg').textContent =
        'Berhasil mengekstrak ' + (data.count ?? '') + ' baris rekening retribusi.';
    document.getElementById('area-success-btn').classList.remove('d-none');
    let sisa = 3;
    const countEl = document.getElementById('countdown');
    const timer = setInterval(() => {
        sisa--;
        if (countEl) countEl.textContent = sisa;
        if (sisa <= 0) { clearInterval(timer); window.location.href = previewUrl; }
    }, 1000);
}

function markFailed(msg) {
    document.getElementById('area-loading').classList.add('d-none');
    document.getElementById('area-failed').classList.remove('d-none');
    document.getElementById('failed-msg').textContent = msg || 'Kesalahan tidak diketahui.';
}

async function checkStatus() {
    try {
        const res  = await fetch(statusUrl);
        const data = await res.json();
        if (data.status === 'done') {
            clearInterval(pollInterval);
            markDone(data);
        } else if (data.status === 'failed') {
            clearInterval(pollInterval);
            markFailed(data.message);
        }
    } catch (e) {}
}

const pollInterval = setInterval(checkStatus, 3000);
setTimeout(checkStatus, 1500);
</script>
@endsection
