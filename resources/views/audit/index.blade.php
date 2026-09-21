@extends('layouts.app')

@section('title', 'Audit Trail Log Aktivitas')
@section('page_heading', 'Audit Trail & Histori Aktivitas')

@section('content')
<div class="card-custom p-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h6 class="fw-bold mb-0" style="color:#fca5a5;">
                <i class="fas fa-history me-2"></i>Log Aktivitas Pengguna & Sistem
            </h6>
            <small style="color:rgba(255,255,255,0.45);">Jejak audit otomatis untuk transparansi dan validasi data retribusi</small>
        </div>
        <span class="badge rounded-pill px-3 py-2 fw-bold"
              style="background:rgba(220,38,38,0.18);border:1px solid rgba(220,38,38,0.35);color:#fca5a5;font-size:0.72rem;">
            <i class="fas fa-shield-halved me-1"></i>Audit Trail Active
        </span>
    </div>

    {{-- Log List --}}
    @forelse($logs as $log)
    <div class="d-flex align-items-start gap-3 mb-3 pb-3"
         style="border-bottom:1px solid rgba(255,255,255,0.07);{{ $loop->last ? 'border-bottom:none;margin-bottom:0;padding-bottom:0;' : '' }}">

        {{-- Action Icon --}}
        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3"
             style="width:44px;height:44px;
                @if(str_contains($log->action,'UPLOAD')) background:rgba(59,130,246,0.18);
                @elseif(str_contains($log->action,'SAVE') || str_contains($log->action,'VERIFY')) background:rgba(22,163,74,0.18);
                @elseif(str_contains($log->action,'DELETE')) background:rgba(220,38,38,0.18);
                @elseif(str_contains($log->action,'EXPORT')) background:rgba(168,85,247,0.18);
                @elseif(str_contains($log->action,'UPDATE')) background:rgba(234,179,8,0.18);
                @else background:rgba(255,255,255,0.08); @endif
             ">
            @if(str_contains($log->action,'UPLOAD'))
                <i class="fas fa-cloud-upload-alt" style="color:#93c5fd;font-size:1rem;"></i>
            @elseif(str_contains($log->action,'SAVE') || str_contains($log->action,'VERIFY'))
                <i class="fas fa-check-circle" style="color:#86efac;font-size:1rem;"></i>
            @elseif(str_contains($log->action,'DELETE'))
                <i class="fas fa-trash-alt" style="color:#fca5a5;font-size:1rem;"></i>
            @elseif(str_contains($log->action,'EXPORT'))
                <i class="fas fa-file-excel" style="color:#d8b4fe;font-size:1rem;"></i>
            @elseif(str_contains($log->action,'UPDATE'))
                <i class="fas fa-pen-to-square" style="color:#fde68a;font-size:1rem;"></i>
            @else
                <i class="fas fa-circle-info" style="color:rgba(255,255,255,0.55);font-size:1rem;"></i>
            @endif
        </div>

        {{-- Info utama --}}
        <div class="flex-grow-1 min-w-0">
            {{-- Baris 1: User + badge aksi --}}
            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                <span class="fw-bold" style="color:#fff;font-size:0.88rem;">
                    <i class="fas fa-user-circle me-1" style="color:#fca5a5;"></i>
                    {{ $log->user_name }}
                </span>
                {{-- Badge Aksi --}}
                @if(str_contains($log->action,'UPLOAD'))
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(59,130,246,0.20);border:1px solid rgba(59,130,246,0.35);color:#93c5fd;font-size:0.68rem;">
                        {{ $log->action }}
                    </span>
                @elseif(str_contains($log->action,'SAVE') || str_contains($log->action,'VERIFY'))
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(22,163,74,0.20);border:1px solid rgba(22,163,74,0.35);color:#86efac;font-size:0.68rem;">
                        {{ $log->action }}
                    </span>
                @elseif(str_contains($log->action,'DELETE'))
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(220,38,38,0.20);border:1px solid rgba(220,38,38,0.35);color:#fca5a5;font-size:0.68rem;">
                        {{ $log->action }}
                    </span>
                @elseif(str_contains($log->action,'EXPORT'))
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(168,85,247,0.20);border:1px solid rgba(168,85,247,0.35);color:#d8b4fe;font-size:0.68rem;">
                        {{ $log->action }}
                    </span>
                @elseif(str_contains($log->action,'UPDATE'))
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(234,179,8,0.20);border:1px solid rgba(234,179,8,0.35);color:#fde68a;font-size:0.68rem;">
                        {{ $log->action }}
                    </span>
                @else
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.60);font-size:0.68rem;">
                        {{ $log->action }}
                    </span>
                @endif
            </div>

            {{-- Baris 2: Detail aktivitas --}}
            <p class="mb-1" style="color:rgba(255,255,255,0.65);font-size:0.80rem;line-height:1.4;">
                {{ $log->details }}
            </p>

            {{-- Baris 3: IP address --}}
            <span style="color:rgba(255,255,255,0.28);font-size:0.72rem;font-family:monospace;">
                <i class="fas fa-network-wired me-1"></i>{{ $log->ip_address ?? '127.0.0.1' }}
            </span>
        </div>

        {{-- Waktu --}}
        <div class="flex-shrink-0 text-end" style="min-width:80px;">
            <small style="color:rgba(255,255,255,0.38);font-size:0.72rem;line-height:1.5;display:block;">
                {{ $log->created_at->format('d M Y') }}
            </small>
            <small style="color:rgba(255,255,255,0.22);font-size:0.68rem;font-family:monospace;">
                {{ $log->created_at->format('H:i:s') }}
            </small>
        </div>
    </div>
    @empty
    <div class="text-center py-5">
        <i class="fas fa-shield-halved fa-3x mb-3" style="color:rgba(255,255,255,0.10);display:block;"></i>
        <p class="fw-semibold mb-1" style="color:rgba(255,255,255,0.35);">Belum ada catatan log aktivitas</p>
        <small style="color:rgba(255,255,255,0.20);">Aktivitas akan tercatat secara otomatis</small>
    </div>
    @endforelse

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-4 pt-3"
         style="border-top:1px solid rgba(255,255,255,0.08);">
        {{ $logs->links() }}
    </div>
</div>
@endsection
