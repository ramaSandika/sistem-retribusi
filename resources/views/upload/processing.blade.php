@extends('layouts.app')

@section('title', 'AI Sedang Memproses Dokumen')

@section('content')
<style>
    body { background: #f0f4ff; }

    .processing-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 8px 40px rgba(11,61,145,0.12);
        overflow: hidden;
    }

    /* Header gradient */
    .card-header-gradient {
        background: linear-gradient(135deg, #0b3d91 0%, #1a73e8 60%, #4fc3f7 100%);
        padding: 48px 32px 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .card-header-gradient::before {
        content: '';
        position: absolute;
        top: -60px; left: -60px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }
    .card-header-gradient::after {
        content: '';
        position: absolute;
        bottom: -80px; right: -40px;
        width: 250px; height: 250px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    /* Pulse robot icon */
    .ai-icon-wrap {
        position: relative;
        display: inline-block;
        margin-bottom: 20px;
    }
    .pulse-ring {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 90px; height: 90px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.5);
        animation: pulse-out 2s ease-out infinite;
    }
    .pulse-ring:nth-child(2) { animation-delay: 0.6s; }
    .pulse-ring:nth-child(3) { animation-delay: 1.2s; }
    @keyframes pulse-out {
        0%   { transform: translate(-50%,-50%) scale(0.8); opacity:1; }
        100% { transform: translate(-50%,-50%) scale(2.5); opacity:0; }
    }
    .ai-icon-inner {
        width: 80px; height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(4px);
        border: 2px solid rgba(255,255,255,0.4);
        position: relative; z-index: 2;
    }

    /* Dots loading */
    .dots-wrap { display: flex; gap: 8px; justify-content: center; margin: 12px 0; }
    .dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: rgba(255,255,255,0.6);
        animation: bounce-dot 1.4s ease-in-out infinite;
    }
    .dot:nth-child(1) { animation-delay: 0s; }
    .dot:nth-child(2) { animation-delay: 0.2s; }
    .dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes bounce-dot {
        0%, 80%, 100% { transform: scale(0.7); background: rgba(255,255,255,0.4); }
        40% { transform: scale(1.2); background: white; }
    }

    /* Progress shimmer */
    .progress-shimmer {
        height: 8px;
        border-radius: 99px;
        background: linear-gradient(90deg, #e8f0fe 25%, #c5d8ff 50%, #e8f0fe 75%);
        background-size: 200% 100%;
        animation: shimmer 1.8s ease-in-out infinite;
        overflow: hidden;
    }
    @keyframes shimmer {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Step item */
    .step-item {
        display: flex; align-items: flex-start; gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s;
    }
    .step-item:last-child { border-bottom: none; }
    .step-icon {
        width: 28px; height: 28px; min-width: 28px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
    }
    .step-icon.done  { background: #d1fae5; color: #059669; }
    .step-icon.active { background: #dbeafe; color: #2563eb; }
    .step-icon.idle  { background: #f1f5f9; color: #94a3b8; }
    .step-icon.error { background: #fee2e2; color: #dc2626; }

    @keyframes spin-slow { to { transform: rotate(360deg); } }
    .spin-anim { animation: spin-slow 1.2s linear infinite; }

    /* Tip rotate */
    .tip-text { transition: opacity 0.5s; }

    /* Success / fail state */
    .success-icon { display: none; }
    .fail-icon    { display: none; }
</style>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-7 col-lg-6">

    <div class="processing-card" id="main-card">

        {{-- ===== HEADER ===== --}}
        <div class="card-header-gradient" id="header-area">
            {{-- Pulse rings --}}
            <div class="ai-icon-wrap" id="icon-processing">
                <div class="pulse-ring"></div>
                <div class="pulse-ring"></div>
                <div class="pulse-ring"></div>
                <div class="ai-icon-inner">
                    <i class="bi bi-robot text-white" style="font-size:2.2rem;"></i>
                </div>
            </div>

            {{-- Success icon --}}
            <div class="success-icon mb-3" id="icon-done">
                <div class="ai-icon-inner mx-auto" style="background:rgba(255,255,255,0.25); border-color:rgba(255,255,255,0.6);">
                    <i class="bi bi-check-lg text-white" style="font-size:2.2rem; font-weight:900;"></i>
                </div>
            </div>

            {{-- Fail icon --}}
            <div class="fail-icon mb-3" id="icon-failed">
                <div class="ai-icon-inner mx-auto" style="background:rgba(255,100,100,0.3); border-color:rgba(255,150,150,0.6);">
                    <i class="bi bi-x-lg text-white" style="font-size:2.2rem; font-weight:900;"></i>
                </div>
            </div>

            <h4 class="text-white fw-bold mb-1" id="header-title">Gemini AI Sedang Bekerja</h4>
            <p class="text-white mb-3" style="opacity:.85; font-size:.9rem;" id="header-sub">
                Menganalisis dokumen APBD dan mengekstrak data retribusi...
            </p>

            {{-- Dots loading --}}
            <div class="dots-wrap" id="dots-area">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>

        {{-- ===== BODY ===== --}}
        <div class="p-4">

            {{-- Progress bar --}}
            <div class="mb-4" id="progress-area">
                <div class="d-flex justify-content-between small text-muted mb-1">
                    <span>Progres Analisis AI</span>
                    <span id="progress-label">Memproses...</span>
                </div>
                <div class="progress-shimmer" id="progress-bar"></div>
            </div>

            {{-- Info dokumen --}}
            <div class="bg-light rounded-3 p-3 mb-4" style="border-left: 4px solid #0b3d91;">
                <div class="row g-1 small">
                    <div class="col-5 text-muted">📄 Dokumen</div>
                    <div class="col-7 fw-semibold text-truncate">{{ basename($upload->file_path) }}</div>
                    <div class="col-5 text-muted">📅 Periode</div>
                    <div class="col-7 fw-semibold">{{ $upload->periode }} {{ $upload->tahun }}</div>
                    <div class="col-5 text-muted">🏢 OPD</div>
                    <div class="col-7 fw-semibold">{{ $upload->unit_opd }}</div>
                </div>
            </div>

            {{-- Step list --}}
            <div class="mb-3" id="steps-area">
                <p class="small fw-semibold text-secondary mb-2">
                    <i class="bi bi-list-check me-1"></i> Tahapan Proses
                </p>

                <div class="step-item">
                    <div class="step-icon done"><i class="bi bi-check"></i></div>
                    <div>
                        <div class="fw-semibold small">File Diunggah</div>
                        <div class="text-muted" style="font-size:.8rem;">PDF berhasil disimpan ke server</div>
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-icon done"><i class="bi bi-check"></i></div>
                    <div>
                        <div class="fw-semibold small">Antrian Diproses</div>
                        <div class="text-muted" style="font-size:.8rem;">Job dikirim ke background worker</div>
                    </div>
                </div>

                <div class="step-item" id="step-ai">
                    <div class="step-icon active" id="step-ai-icon">
                        <i class="bi bi-arrow-repeat spin-anim"></i>
                    </div>
                    <div>
                        <div class="fw-semibold small">Gemini AI Membaca Dokumen</div>
                        <div class="text-muted small" id="step-ai-sub">Mengekstrak rekening 4.1.02 Retribusi Daerah...</div>
                    </div>
                </div>

                <div class="step-item" id="step-save" style="opacity:.4;">
                    <div class="step-icon idle"><i class="bi bi-database"></i></div>
                    <div>
                        <div class="fw-semibold small">Simpan & Verifikasi</div>
                        <div class="text-muted" style="font-size:.8rem;">Data siap untuk ditinjau dan dikonfirmasi</div>
                    </div>
                </div>
            </div>

            {{-- Tip rotating --}}
            <div class="text-center small text-muted" id="tip-area">
                <i class="bi bi-lightbulb me-1 text-warning"></i>
                <span class="tip-text" id="tip-text">Halaman ini otomatis berpindah saat AI selesai</span>
            </div>

            {{-- Fail action --}}
            <div class="text-center d-none" id="fail-area">
                <div class="alert alert-danger rounded-3 small mb-3" id="fail-message"></div>
                <a href="{{ route('upload.create') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Upload Ulang Dokumen
                </a>
            </div>

            {{-- Success countdown --}}
            <div class="text-center d-none" id="success-area">
                <div class="alert alert-success rounded-3 small mb-3" id="success-message"></div>
                <p class="text-muted small">Otomatis ke halaman review dalam <strong id="countdown">3</strong> detik...</p>
                <a href="{{ route('upload.preview', $upload->id) }}" class="btn btn-success">
                    <i class="bi bi-eye me-1"></i> Lihat Hasil Sekarang
                </a>
            </div>
        </div>
    </div>

    <p class="text-center text-muted small mt-3">
        <i class="bi bi-shield-lock me-1"></i>
        Data dokumen diproses secara aman oleh Gemini AI BAPENDA
    </p>

</div>
</div>
</div>

<script>
const statusUrl  = '{{ route("upload.ocr-status", $upload->id) }}';
const previewUrl = '{{ route("upload.preview", $upload->id) }}';

const tips = [
    'Halaman ini otomatis berpindah saat AI selesai',
    'Gemini AI dapat membaca tabel angka dalam PDF secara akurat',
    'Hasil ekstraksi bisa diedit sebelum disimpan',
    'Data rekening 4.1.02 difilter otomatis oleh AI',
    'Proses biasanya selesai dalam 10–40 detik',
];
let tipIdx = 0;
const tipEl = document.getElementById('tip-text');
setInterval(() => {
    tipEl.style.opacity = 0;
    setTimeout(() => {
        tipIdx = (tipIdx + 1) % tips.length;
        tipEl.textContent = tips[tipIdx];
        tipEl.style.opacity = 1;
    }, 400);
}, 4000);

function markDone() {
    document.getElementById('icon-processing').style.display = 'none';
    document.getElementById('icon-done').style.display = 'block';
    document.getElementById('header-area').style.background = 'linear-gradient(135deg, #059669, #10b981)';
    document.getElementById('header-title').textContent   = '✅ Analisis Selesai!';
    document.getElementById('dots-area').innerHTML = '';
    document.getElementById('progress-bar').style.background = '#d1fae5';
    document.getElementById('progress-bar').style.animation = 'none';
    document.getElementById('progress-label').textContent = 'Selesai!';

    const stepAiIcon = document.getElementById('step-ai-icon');
    stepAiIcon.className = 'step-icon done';
    stepAiIcon.innerHTML = '<i class="bi bi-check"></i>';
    document.getElementById('step-ai-sub').textContent = 'Rekening berhasil diekstrak';
    const stepSave = document.getElementById('step-save');
    stepSave.style.opacity = '1';
    stepSave.querySelector('.step-icon').className = 'step-icon done';
    stepSave.querySelector('.step-icon').innerHTML = '<i class="bi bi-check"></i>';
}

function markFailed(msg) {
    document.getElementById('icon-processing').style.display = 'none';
    document.getElementById('icon-failed').style.display = 'block';
    document.getElementById('header-area').style.background = 'linear-gradient(135deg, #dc2626, #ef4444)';
    document.getElementById('header-title').textContent = 'Proses Gagal';
    document.getElementById('header-sub').textContent   = 'Terjadi kesalahan saat memproses dokumen';
    document.getElementById('dots-area').innerHTML = '';
    document.getElementById('progress-area').classList.add('d-none');
    document.getElementById('tip-area').classList.add('d-none');
    const stepAiIcon = document.getElementById('step-ai-icon');
    stepAiIcon.className = 'step-icon error';
    stepAiIcon.innerHTML = '<i class="bi bi-x"></i>';
    document.getElementById('step-ai-sub').textContent = 'Gagal membaca dokumen';
    document.getElementById('fail-message').textContent = msg;
    document.getElementById('fail-area').classList.remove('d-none');
}

async function checkStatus() {
    try {
        const res  = await fetch(statusUrl);
        const data = await res.json();

        if (data.status === 'done') {
            clearInterval(pollInterval);
            markDone();
            document.getElementById('success-message').innerHTML =
                `<i class="bi bi-stars me-1"></i> Berhasil mengekstrak <strong>${data.count ?? ''} baris</strong> rekening retribusi dari dokumen.`;
            document.getElementById('success-area').classList.remove('d-none');
            document.getElementById('tip-area').classList.add('d-none');
            document.getElementById('header-sub').textContent = `${data.count ?? ''} rekening retribusi berhasil diekstrak`;

            let sisa = 3;
            const countEl = document.getElementById('countdown');
            const timer = setInterval(() => {
                sisa--;
                if (countEl) countEl.textContent = sisa;
                if (sisa <= 0) { clearInterval(timer); window.location.href = previewUrl; }
            }, 1000);

        } else if (data.status === 'failed') {
            clearInterval(pollInterval);
            markFailed(data.message || 'Terjadi kesalahan yang tidak diketahui.');
        }
    } catch (e) { /* tetap poll */ }
}

const pollInterval = setInterval(checkStatus, 3000);
setTimeout(checkStatus, 2000);
</script>
@endsection
