@extends('layouts.app')

@section('title', 'AI Sedang Memproses Dokumen')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            {{-- Card utama --}}
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5 text-center">

                    {{-- Ikon AI berputar --}}
                    <div class="mb-4" id="icon-processing">
                        <div class="ai-spinner mx-auto">
                            <i class="bi bi-robot" style="font-size: 3rem; color: #0b3d91;"></i>
                        </div>
                    </div>
                    <div class="mb-4 d-none" id="icon-done">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <div class="mb-4 d-none" id="icon-failed">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                    </div>

                    {{-- Judul --}}
                    <h4 class="fw-bold mb-2" id="status-title" style="color:#0b3d91;">
                        AI Sedang Membaca Dokumen...
                    </h4>
                    <p class="text-muted mb-4" id="status-message">
                        Gemini AI sedang menganalisis tabel retribusi pada PDF Anda.<br>
                        Proses ini biasanya membutuhkan <strong>10–40 detik</strong>.
                    </p>

                    {{-- Progress bar animasi --}}
                    <div class="progress mb-4" style="height: 12px; border-radius: 999px;" id="progress-bar-wrap">
                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                             role="progressbar"
                             id="progress-bar"
                             style="width: 100%; background: linear-gradient(90deg, #0b3d91, #1a73e8, #0b3d91);">
                        </div>
                    </div>

                    {{-- Info dokumen --}}
                    <div class="bg-light rounded-3 p-3 text-start mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Dokumen:</span>
                            <span class="fw-semibold small">{{ basename($upload->file_path) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Periode:</span>
                            <span class="fw-semibold small">{{ $upload->periode }} {{ $upload->tahun }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">OPD:</span>
                            <span class="fw-semibold small">{{ $upload->unit_opd }}</span>
                        </div>
                    </div>

                    {{-- Pesan tips --}}
                    <div id="tips-area">
                        <p class="text-muted small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Halaman ini akan otomatis berpindah ke halaman review setelah AI selesai.
                        </p>
                    </div>

                    {{-- Tombol muncul jika gagal --}}
                    <div id="retry-area" class="d-none mt-3">
                        <a href="{{ route('upload.create') }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-left me-1"></i> Kembali & Upload Ulang
                        </a>
                    </div>

                </div>
            </div>

            {{-- Log/step indicator --}}
            <div class="card mt-3 border-0 shadow-sm rounded-4">
                <div class="card-body py-3 px-4">
                    <p class="small fw-semibold mb-2 text-secondary">
                        <i class="bi bi-activity me-1"></i> Log Proses AI
                    </p>
                    <ul class="list-unstyled mb-0 small" id="log-list">
                        <li class="mb-1 text-success"><i class="bi bi-check2 me-1"></i> File PDF berhasil diunggah ke server</li>
                        <li class="mb-1 text-success"><i class="bi bi-check2 me-1"></i> Background job dikirim ke antrian proses</li>
                        <li class="mb-1 text-primary" id="log-ai"><i class="bi bi-hourglass-split me-1 spin-icon"></i> Gemini AI menganalisis tabel retribusi...</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .ai-spinner {
        width: 80px;
        height: 80px;
        border: 4px solid #e8f0fe;
        border-top-color: #0b3d91;
        border-radius: 50%;
        animation: spin 1.2s linear infinite;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .spin-icon {
        display: inline-block;
        animation: spin 1.5s linear infinite;
    }
</style>

<script>
    const uploadId = {{ $upload->id }};
    const statusUrl = '{{ route("upload.ocr-status", $upload->id) }}';
    const previewUrl = '{{ route("upload.preview", $upload->id) }}';

    let pollCount = 0;
    let dotCount = 0;

    function addLog(text, type = 'success') {
        const icons = {success: 'check2', info: 'info-circle', danger: 'x-circle'};
        const colors = {success: 'text-success', info: 'text-primary', danger: 'text-danger'};
        const li = document.createElement('li');
        li.className = `mb-1 ${colors[type]}`;
        li.innerHTML = `<i class="bi bi-${icons[type]} me-1"></i> ${text}`;
        document.getElementById('log-list').appendChild(li);
    }

    function animateDots() {
        dotCount = (dotCount + 1) % 4;
        const dots = '.'.repeat(dotCount);
        const el = document.getElementById('log-ai');
        if (el) el.innerHTML = `<i class="bi bi-hourglass-split me-1 spin-icon"></i> Gemini AI menganalisis tabel retribusi${dots}`;
    }
    const dotsInterval = setInterval(animateDots, 600);

    async function checkStatus() {
        try {
            const res = await fetch(statusUrl);
            const data = await res.json();
            pollCount++;

            if (data.status === 'done') {
                clearInterval(dotsInterval);
                clearInterval(pollInterval);

                // Update UI selesai
                document.getElementById('icon-processing').classList.add('d-none');
                document.getElementById('icon-done').classList.remove('d-none');
                document.getElementById('status-title').textContent = 'Analisis Selesai!';
                document.getElementById('status-message').innerHTML =
                    `<span class="text-success fw-semibold">Berhasil mengekstrak <strong>${data.count ?? '?'} baris</strong> rekening retribusi.</span><br>
                     Mengalihkan ke halaman review dalam <span id="countdown">3</span> detik...`;
                document.getElementById('progress-bar').style.width = '100%';
                document.getElementById('progress-bar').classList.remove('progress-bar-animated', 'progress-bar-striped');
                document.getElementById('progress-bar').style.background = '#198754';
                document.getElementById('tips-area').classList.add('d-none');

                const logAi = document.getElementById('log-ai');
                if (logAi) {
                    logAi.className = 'mb-1 text-success';
                    logAi.innerHTML = `<i class="bi bi-check2 me-1"></i> AI selesai mengekstrak ${data.count ?? ''} rekening retribusi`;
                }
                addLog('Mengalihkan ke halaman review data...', 'info');

                // Countdown redirect
                let sisa = 3;
                const countdownEl = document.getElementById('countdown');
                const timer = setInterval(() => {
                    sisa--;
                    if (countdownEl) countdownEl.textContent = sisa;
                    if (sisa <= 0) {
                        clearInterval(timer);
                        window.location.href = previewUrl;
                    }
                }, 1000);

            } else if (data.status === 'failed') {
                clearInterval(dotsInterval);
                clearInterval(pollInterval);

                document.getElementById('icon-processing').classList.add('d-none');
                document.getElementById('icon-failed').classList.remove('d-none');
                document.getElementById('status-title').textContent = 'Proses Gagal';
                document.getElementById('status-title').style.color = '#dc3545';
                document.getElementById('status-message').innerHTML =
                    `<span class="text-danger">${data.message}</span>`;
                document.getElementById('progress-bar-wrap').classList.add('d-none');
                document.getElementById('retry-area').classList.remove('d-none');
                document.getElementById('tips-area').classList.add('d-none');

                const logAi = document.getElementById('log-ai');
                if (logAi) {
                    logAi.className = 'mb-1 text-danger';
                    logAi.innerHTML = `<i class="bi bi-x-circle me-1"></i> ${data.message}`;
                }
            }
            // else masih processing, lanjut poll
        } catch (e) {
            // Jika network error, tetap lanjut poll
        }
    }

    // Poll setiap 3 detik
    const pollInterval = setInterval(checkStatus, 3000);
    // Cek pertama kali setelah 2 detik
    setTimeout(checkStatus, 2000);
</script>
@endsection
