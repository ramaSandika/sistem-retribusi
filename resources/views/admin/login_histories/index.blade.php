@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header text-white py-3 d-flex justify-content-between align-items-center" style="background-color: #0b3d91;">
        <div>
            <h5 class="mb-0 fw-bold"><i class="bi bi-shield-check me-2"></i>Riwayat & Audit Log Aktivitas Login</h5>
            <small class="text-light opacity-75">Pemantauan aktivitas autentikasi pengguna, IP address, waktu login, dan status akses</small>
        </div>
        @if($histories->total() > 0)
            <form action="{{ route('admin.login_histories.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan seluruh riwayat login?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-trash me-1"></i> Bersihkan Log
                </button>
            </form>
        @endif
    </div>

    <div class="card-body p-4">
        <!-- Form Filter & Pencarian Log -->
        <form method="GET" action="{{ route('admin.login_histories.index') }}" class="row g-2 mb-4 bg-light p-3 rounded-3 border">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status Login --</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Sukses (Berhasil)</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal (Percobaan Tidak Sah)</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari berdasarkan email, nama, atau alamat IP..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold" style="background-color: #0b3d91;">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.login_histories.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    Reset
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 170px;">Waktu & Tanggal</th>
                        <th>Pengguna / Email Percobaan</th>
                        <th style="width: 120px;">Role</th>
                        <th style="width: 140px;">Alamat IP</th>
                        <th>Peramban / Perangkat</th>
                        <th style="width: 120px;" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $index => $log)
                    <tr class="{{ $log->status === 'failed' ? 'table-danger' : '' }}">
                        <td class="text-center text-muted small">{{ $histories->firstItem() + $index }}</td>
                        <td class="font-monospace small">
                            <i class="bi bi-calendar-event me-1 text-secondary"></i>
                            {{ $log->login_at ? $log->login_at->format('d/m/Y H:i:s') : '-' }}
                        </td>
                        <td>
                            @if($log->user)
                                <div class="fw-semibold">{{ $log->user->name }}</div>
                                <code class="small text-muted">{{ $log->user->email }}</code>
                            @else
                                <div class="fw-semibold text-danger"><i class="bi bi-question-circle me-1"></i>Percobaan Login</div>
                                <code class="small text-danger">{{ $log->email_attempted }}</code>
                            @endif
                        </td>
                        <td>
                            @if($log->user)
                                <span class="badge {{ $log->user->isAdmin() ? 'bg-danger' : 'bg-primary' }} text-uppercase">
                                    {{ $log->user->role }}
                                </span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            <code class="fw-bold">{{ $log->ip_address ?? '-' }}</code>
                        </td>
                        <td class="small text-muted text-break" style="max-width: 250px;">
                            {{ $log->user_agent ?? '-' }}
                        </td>
                        <td class="text-center">
                            @if($log->status === 'success')
                                <span class="badge bg-success px-2 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i> Sukses
                                </span>
                            @else
                                <span class="badge bg-danger px-2 py-1">
                                    <i class="bi bi-x-circle-fill me-1"></i> Gagal
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-shield-slash fs-3 d-block text-secondary mb-2"></i>
                            Belum ada riwayat aktivitas login yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $histories->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
