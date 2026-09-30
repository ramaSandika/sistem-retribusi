@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header text-white py-3 d-flex justify-content-between align-items-center" style="background-color: #0b3d91;">
        <div>
            <h5 class="mb-0 fw-bold"><i class="bi bi-table me-2"></i>Data Realisasi Retribusi Daerah (Akun 4.1.02)</h5>
            <small class="text-light opacity-75">Daftar rekonsiliasi data hasil ekstraksi OCR dan verifikasi dokumen APBD</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-info text-dark fw-semibold" id="toggleEditBtn" onclick="toggleEditMode()">
                <i class="bi bi-pencil-square me-1"></i> Mode Edit Keseluruhan
            </button>
            <a href="{{ route('realisasi.export', request()->query()) }}" class="btn btn-sm btn-success fw-semibold">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
            <a href="{{ route('upload.create') }}" class="btn btn-sm btn-warning text-dark fw-semibold">
                <i class="bi bi-upload me-1"></i> Upload PDF Baru
            </a>
        </div>
    </div>
    <div class="card-body p-4">
        <!-- Form Filter -->
        <form method="GET" action="{{ route('realisasi.index') }}" class="row g-2 mb-4 bg-light p-3 rounded-3 border">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary">Tahun Anggaran</label>
                <input type="number" name="tahun" class="form-control form-control-sm" placeholder="Contoh: 2025" value="{{ request('tahun') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-secondary">Periode Pelaporan</label>
                <select name="periode" class="form-select form-select-sm">
                    <option value="">-- Semua Periode --</option>
                    <option value="Semester I" {{ request('periode') == 'Semester I' ? 'selected' : '' }}>Semester I</option>
                    <option value="Semester II" {{ request('periode') == 'Semester II' ? 'selected' : '' }}>Semester II</option>
                    <option value="Triwulan I" {{ request('periode') == 'Triwulan I' ? 'selected' : '' }}>Triwulan I</option>
                    <option value="Triwulan II" {{ request('periode') == 'Triwulan II' ? 'selected' : '' }}>Triwulan II</option>
                    <option value="Triwulan III" {{ request('periode') == 'Triwulan III' ? 'selected' : '' }}>Triwulan III</option>
                    <option value="Triwulan IV" {{ request('periode') == 'Triwulan IV' ? 'selected' : '' }}>Triwulan IV</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bln)
                        <option value="{{ $bln }}" {{ request('periode') == $bln ? 'selected' : '' }}>Bulan {{ $bln }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-secondary">Cari Kode / Nama Retribusi</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Contoh: Parkir, Pasar, 4.1.02.01" value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold" style="background-color: #0b3d91;">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="{{ route('realisasi.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    Reset
                </a>
            </div>
        </form>

        <!-- Form Batch Update (Edit Keseluruhan) -->
        <form action="{{ route('realisasi.batchUpdate') }}" method="POST" id="batchForm">
            @csrf
            @method('PUT')

            <div class="alert alert-warning d-none py-2 px-3 align-items-center justify-content-between mb-3 shadow-sm border-0" id="editAlertBar">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-warning"></i>
                    <span><strong>Mode Edit Aktif:</strong> Anda dapat mengedit teks dan angka langsung pada tabel di bawah. Klik tombol <strong>Simpan Perubahan</strong> setelah selesai.</span>
                </div>
                <button type="submit" class="btn btn-sm btn-success fw-bold px-3">
                    <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 45px;">No</th>
                            <th style="width: 170px;">Kode Rekening</th>
                            <th>Uraian / Nama Retribusi</th>
                            <th class="text-end" style="width: 160px;">Target Anggaran (Rp)</th>
                            <th class="text-end" style="width: 160px;">Realisasi (Rp)</th>
                            <th class="text-center" style="width: 80px;">%</th>
                            <th class="text-end" style="width: 160px;">Realisasi Lalu (Rp)</th>
                            <th style="width: 110px;">Periode</th>
                            <th style="width: 50px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                        @php
                            $isInduk = in_array($item->level_rekening, ['kelompok', 'jenis', 'objek']);
                        @endphp
                        <tr class="{{ $isInduk ? 'table-warning fw-semibold' : '' }}" id="row-{{ $item->id }}">
                            <td class="text-muted small text-center">{{ $data->firstItem() + $index }}</td>
                            
                            <!-- Kode Rekening -->
                            <td>
                                <span class="view-mode font-monospace">{{ $item->kode_rekening }}</span>
                                <input type="text" name="items[{{ $item->id }}][kode_rekening]" 
                                       class="form-control form-control-sm font-monospace edit-mode d-none" 
                                       value="{{ $item->kode_rekening }}">
                            </td>

                            <!-- Nama Retribusi -->
                            <td>
                                <span class="view-mode">{{ $item->nama_retribusi }}</span>
                                <input type="text" name="items[{{ $item->id }}][nama_retribusi]" 
                                       class="form-control form-control-sm edit-mode d-none" 
                                       value="{{ $item->nama_retribusi }}">
                            </td>

                            <!-- Target Anggaran -->
                            <td class="text-end">
                                <span class="view-mode font-monospace">Rp {{ number_format($item->anggaran, 0, ',', '.') }}</span>
                                <input type="number" step="0.01" name="items[{{ $item->id }}][anggaran]" 
                                       class="form-control form-control-sm text-end font-monospace edit-mode d-none input-anggaran" 
                                       data-id="{{ $item->id }}"
                                       value="{{ $item->anggaran }}">
                            </td>

                            <!-- Realisasi -->
                            <td class="text-end">
                                <span class="view-mode font-monospace fw-semibold text-primary">Rp {{ number_format($item->nilai, 0, ',', '.') }}</span>
                                <input type="number" step="0.01" name="items[{{ $item->id }}][nilai]" 
                                       class="form-control form-control-sm text-end font-monospace edit-mode d-none input-realisasi" 
                                       data-id="{{ $item->id }}"
                                       value="{{ $item->nilai }}">
                            </td>

                            <!-- Persentase -->
                            <td class="text-center font-monospace">
                                <span class="badge {{ $item->persentase >= 50 ? 'bg-success' : 'bg-secondary' }} persen-badge-{{ $item->id }}">
                                    {{ $item->persentase }}%
                                </span>
                            </td>

                            <!-- Realisasi Lalu -->
                            <td class="text-end">
                                <span class="view-mode font-monospace text-muted">Rp {{ number_format($item->realisasi_lalu, 0, ',', '.') }}</span>
                                <input type="number" step="0.01" name="items[{{ $item->id }}][realisasi_lalu]" 
                                       class="form-control form-control-sm text-end font-monospace edit-mode d-none" 
                                       value="{{ $item->realisasi_lalu }}">
                            </td>

                            <!-- Periode -->
                            <td>
                                <span class="badge bg-light text-dark border">{{ $item->periode }} {{ $item->tahun }}</span>
                            </td>

                            <!-- Aksi Hapus -->
                            <td class="text-center">
                                <form action="{{ route('realisasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus baris ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 p-1" title="Hapus baris">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block text-secondary mb-2"></i>
                                Tidak ada data realisasi retribusi yang cocok dengan filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-none justify-content-end mt-3" id="saveBottomBar">
                <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm">
                    <i class="bi bi-check-circle-fill me-1"></i> Simpan Semua Perubahan
                </button>
            </div>
        </form>

        <div class="mt-4">
            {{ $data->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let isEdit = false;

    function toggleEditMode() {
        isEdit = !isEdit;
        const viewElements = document.querySelectorAll('.view-mode');
        const editElements = document.querySelectorAll('.edit-mode');
        const editAlertBar = document.getElementById('editAlertBar');
        const saveBottomBar = document.getElementById('saveBottomBar');
        const toggleBtn = document.getElementById('toggleEditBtn');

        if (isEdit) {
            viewElements.forEach(el => el.classList.add('d-none'));
            editElements.forEach(el => el.classList.remove('d-none'));
            editAlertBar.classList.remove('d-none');
            editAlertBar.classList.add('d-flex');
            saveBottomBar.classList.remove('d-none');
            saveBottomBar.classList.add('d-flex');
            toggleBtn.innerHTML = '<i class="bi bi-x-circle me-1"></i> Batal Edit';
            toggleBtn.className = 'btn btn-sm btn-secondary fw-semibold';
        } else {
            viewElements.forEach(el => el.classList.remove('d-none'));
            editElements.forEach(el => el.classList.add('d-none'));
            editAlertBar.classList.add('d-none');
            editAlertBar.classList.remove('d-flex');
            saveBottomBar.classList.add('d-none');
            saveBottomBar.classList.remove('d-flex');
            toggleBtn.innerHTML = '<i class="bi bi-pencil-square me-1"></i> Mode Edit Keseluruhan';
            toggleBtn.className = 'btn btn-sm btn-info text-dark fw-semibold';
        }
    }

    // Auto kalkulasi persentase langsung di browser saat input angka diubah
    document.querySelectorAll('.input-anggaran, .input-realisasi').forEach(input => {
        input.addEventListener('input', function() {
            const id = this.dataset.id;
            const row = document.getElementById('row-' + id);
            if (!row) return;

            const anggaran = parseFloat(row.querySelector('.input-anggaran').value) || 0;
            const realisasi = parseFloat(row.querySelector('.input-realisasi').value) || 0;
            let persen = 0;
            if (anggaran > 0) {
                persen = ((realisasi / anggaran) * 100).toFixed(2);
            }

            const badge = document.querySelector('.persen-badge-' + id);
            if (badge) {
                badge.innerText = persen + '%';
                if (persen >= 50) {
                    badge.className = 'badge bg-success persen-badge-' + id;
                } else {
                    badge.className = 'badge bg-secondary persen-badge-' + id;
                }
            }
        });
    });
</script>
@endpush