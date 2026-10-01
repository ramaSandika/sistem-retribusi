@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header text-white py-3 d-flex justify-content-between align-items-center" style="background-color: #0b3d91;">
        <div>
            <h5 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Manajemen Pengguna (User & Admin)</h5>
            <small class="text-light opacity-75">Kelola akun administrator BAPENDA dan operator OPD kedinasan</small>
        </div>
        <button type="button" class="btn btn-warning text-dark fw-bold btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna Baru
        </button>
    </div>

    <div class="card-body p-4">
        <!-- Pencarian Pengguna -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-4 bg-light p-3 rounded-3 border">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama pengguna, email, atau unit OPD..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold" style="background-color: #0b3d91;">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    Reset
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th style="width: 130px;">Hak Akses (Role)</th>
                        <th>Unit OPD / Dinas</th>
                        <th style="width: 150px;">Terdaftar Pada</th>
                        <th style="width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $u)
                    <tr>
                        <td class="text-center text-muted small">{{ $users->firstItem() + $index }}</td>
                        <td class="fw-semibold">
                            <i class="bi bi-person-circle me-1 text-secondary"></i>
                            {{ $u->name }}
                            @if(auth()->id() === $u->id)
                                <span class="badge bg-info text-dark ms-1">Akun Anda</span>
                            @endif
                        </td>
                        <td><code>{{ $u->email }}</code></td>
                        <td>
                            @if($u->role === 'admin')
                                <span class="badge bg-danger"><i class="bi bi-shield-lock me-1"></i>Admin</span>
                            @else
                                <span class="badge bg-primary"><i class="bi bi-person me-1"></i>User OPD</span>
                            @endif
                        </td>
                        <td>{{ $u->unit_opd ?? '-' }}</td>
                        <td class="small text-muted">{{ $u->created_at ? $u->created_at->format('d M Y H:i') : '-' }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $u->id }}" title="Edit Pengguna">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                @if(auth()->id() !== $u->id)
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus Pengguna">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Modal Edit Pengguna -->
                            <div class="modal fade" id="editModal{{ $u->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.users.update', $u->id) }}" method="POST" class="modal-content text-start">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-navy text-white">
                                            <h6 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Pengguna: {{ $u->name }}</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                                                <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-secondary">Hak Akses (Role)</label>
                                                <select name="role" class="form-select" required>
                                                    <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin (Administrator BAPENDA)</option>
                                                    <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>User (Operator OPD)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-secondary">Unit OPD / Instansi</label>
                                                <input type="text" name="unit_opd" class="form-control" value="{{ $u->unit_opd }}">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold text-secondary">Password Baru (Opsional)</label>
                                                <input type="password" name="new_password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                                                <small class="text-muted" style="font-size: 0.75rem;">Isi minimal 6 karakter hanya jika ingin mereset password pengguna ini.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm fw-bold" style="background-color: #0b3d91;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block text-secondary mb-2"></i>
                            Tidak ada data pengguna yang sesuai pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna Baru -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.users.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-navy text-white" style="background-color: #0b3d91;">
                <h6 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2 text-warning"></i>Tambah Pengguna Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Alamat Email Login</label>
                    <input type="email" name="email" class="form-control" placeholder="Contoh: budi@dishub.go.id" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Hak Akses (Role)</label>
                        <select name="role" class="form-select" required>
                            <option value="user" selected>User (Operator OPD)</option>
                            <option value="admin">Admin (Administrator)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Password Awal</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold text-secondary">Unit OPD / Instansi</label>
                    <input type="text" name="unit_opd" class="form-control" placeholder="Contoh: Dinas Perhubungan">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning btn-sm fw-bold text-dark">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>
@endsection
