<?php $__env->startSection('title', 'Data Realisasi Retribusi'); ?>
<?php $__env->startSection('page_heading', 'Data Realisasi Retribusi Daerah'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-custom p-4 mb-4">
    <!-- Header Controls -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h6 class="fw-bold text-danger m-0">
                <i class="fas fa-database me-2"></i> Rekapitulasi Data Realisasi Retribusi
            </h6>
            <small class="text-muted">Total <?php echo e($totalRecord); ?> catatan data ditemukan</small>
        </div>

        <div class="d-flex gap-2">
            <a href="<?php echo e(route('realisasi.print', request()->all())); ?>" target="_blank" class="btn btn-outline-danger fw-bold rounded-3 shadow-sm">
                <i class="fas fa-print me-2"></i> Cetak Laporan PDF
            </a>
            <a href="<?php echo e(route('realisasi.export', request()->all())); ?>" class="btn btn-success fw-bold rounded-3 shadow-sm">
                <i class="fas fa-file-excel me-2"></i> Export Ke Excel (.xlsx/.csv)
            </a>
        </div>
    </div>

    <!-- FILTER & SEARCH FORM -->
    <form action="<?php echo e(route('realisasi.index')); ?>" method="GET" class="row g-2 mb-4">
        <div class="col-md-2 col-6">
            <input type="number" name="tahun" class="form-control form-control-sm rounded-3 bg-light border-danger-subtle fw-bold" placeholder="Tahun (cth: 2026)" value="<?php echo e($tahun); ?>" min="2000" max="2100">
        </div>

        <?php if($isAdmin): ?>
        <div class="col-md-3 col-6">
            <select name="opd" class="form-select form-select-sm rounded-3 bg-light border-danger-subtle fw-semibold" onchange="this.form.submit()">
                <option value="Semua OPD" <?php echo e(($opd === 'Semua OPD' || !$opd) ? 'selected' : ''); ?>>Semua Instansi OPD</option>
                <?php $__currentLoopData = $opdList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($o); ?>" <?php echo e(($opd === $o) ? 'selected' : ''); ?>><?php echo e($o); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <?php endif; ?>

        <div class="col-md-2 col-6">
            <select name="periode" class="form-select form-select-sm rounded-3 bg-light border-danger-subtle fw-semibold" onchange="this.form.submit()">
                <option value="Semua Periode" <?php echo e(($periode === 'Semua Periode' || !$periode) ? 'selected' : ''); ?>>Semua Bulan</option>
                <?php $__currentLoopData = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($bln); ?>" <?php echo e(($periode === $bln) ? 'selected' : ''); ?>><?php echo e($bln); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="col-md-3 col-6">
            <input type="text" name="search" class="form-control form-control-sm rounded-3 bg-light border-danger-subtle" placeholder="Cari Kode atau Nama Retribusi..." value="<?php echo e($search); ?>">
        </div>

        <div class="col-md-2 col-12">
            <button type="submit" class="btn btn-sm btn-red w-100 rounded-3 fw-bold">
                <i class="fas fa-search me-1"></i> Cari Data
            </button>
        </div>
    </form>

    <!-- KPI FILTER SUMMARY -->
    <div class="bg-danger-subtle p-3 rounded-4 mb-4 border border-danger-subtle d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <i class="fas fa-calculator fa-2x text-danger opacity-75"></i>
            <div>
                <span class="small text-muted fw-bold d-block">Total Nilai Realisasi (Filter Terpilih):</span>
                <h4 class="fw-bold text-danger mb-0">Rp <?php echo e(number_format($totalNilai, 0, ',', '.')); ?></h4>
            </div>
        </div>
        <span class="badge bg-white text-danger border border-danger px-3 py-2 rounded-pill fw-bold">
            <?php echo e($totalRecord); ?> Data Item
        </span>
    </div>

    <!-- BULK ACTION FORM & DATA TABLE -->
    <form action="<?php echo e(route('realisasi.bulkDelete')); ?>" method="POST" id="bulkForm" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ' + getSelectedCount() + ' data yang dipilih?')">
        <?php echo csrf_field(); ?>

        <!-- BULK ACTION BAR (Tampil saat ada checkbox dipilih) -->
        <div id="bulkActionBar" class="alert alert-danger-subtle border border-danger-subtle d-flex align-items-center justify-content-between p-2 px-3 rounded-3 mb-3 d-none">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-check-double text-danger"></i>
                <span class="small fw-bold text-danger">
                    <span id="selectedCountText">0</span> data terpilih
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-light border py-1 px-2 small fw-semibold" onclick="clearAllSelections()">
                    Batal Pilih
                </button>
                <button type="submit" class="btn btn-sm btn-danger py-1 px-3 fw-bold shadow-sm">
                    <i class="fas fa-trash-alt me-1"></i> Hapus Data Terpilih
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40px;" class="text-center">
                            <input type="checkbox" id="selectAllCheckbox" class="form-check-input border-danger" title="Pilih Semua Baris di Halaman Ini" onchange="toggleSelectAll(this)">
                        </th>
                        <th class="small text-muted">Periode</th>
                        <th class="small text-muted">Kode Rekening</th>
                        <th class="small text-muted">Jenis Retribusi</th>
                        <th class="small text-muted">Instansi / OPD</th>
                        <th class="small text-muted">Nilai Realisasi</th>
                        <th class="small text-muted text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="selected_ids[]" value="<?php echo e($row->id); ?>" class="form-check-input row-checkbox border-secondary" onchange="updateSelectedCount()">
                            </td>
                            <td><span class="badge bg-light text-dark border"><?php echo e($row->periode); ?></span></td>
                            <td class="fw-bold text-danger"><?php echo e($row->kode_rekening); ?></td>
                            <td class="fw-semibold"><?php echo e($row->nama_retribusi); ?></td>
                            <td class="small text-muted"><?php echo e($row->opd_name); ?></td>
                            <td class="fw-bold text-success">Rp <?php echo e(number_format($row->nilai, 0, ',', '.')); ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo e($row->id); ?>" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($row->id); ?>" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" onclick="deleteSingle(<?php echo e($row->id); ?>)" title="Hapus Data">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                                Tidak ada data realisasi yang cocok dengan kriteria filter.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </form>

    <!-- Hidden Single Delete Form -->
    <form id="singleDeleteForm" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>

    <!-- MODALS CONTAINER (Diletakkan di luar tabel agar HTML valid dan tabel tampil rapi) -->
    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <!-- Detail Modal -->
        <div class="modal fade" id="detailModal<?php echo e($row->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-danger text-white rounded-top-4">
                        <h6 class="modal-title fw-bold"><i class="fas fa-file-invoice me-2"></i> Detail Catatan Realisasi</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted small" style="width: 140px;">Kode Rekening:</td>
                                <td class="fw-bold text-danger"><?php echo e($row->kode_rekening); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Jenis Retribusi:</td>
                                <td class="fw-semibold"><?php echo e($row->nama_retribusi); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Instansi OPD:</td>
                                <td><?php echo e($row->opd_name); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Periode / Tahun:</td>
                                <td><?php echo e(str_contains($row->periode, (string)$row->tahun) ? $row->periode : $row->periode . ' ' . $row->tahun); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Nilai Realisasi:</td>
                                <td class="fw-bold text-success fs-5">Rp <?php echo e(number_format($row->nilai, 0, ',', '.')); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted small">Diinput Pada:</td>
                                <td class="small text-muted"><?php echo e($row->created_at ? $row->created_at->format('d F Y, H:i') : '-'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-sm btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal<?php echo e($row->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <form action="<?php echo e(route('realisasi.update', $row->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="modal-header bg-warning text-dark rounded-top-4">
                            <h6 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Edit Data Realisasi</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Kode Rekening</label>
                                <input type="text" name="kode_rekening" class="form-control rounded-3" value="<?php echo e($row->kode_rekening); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Nama Retribusi</label>
                                <input type="text" name="nama_retribusi" class="form-control rounded-3" value="<?php echo e($row->nama_retribusi); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Nilai Realisasi (Rp)</label>
                                <input type="number" name="nilai" class="form-control rounded-3 fw-bold text-success" value="<?php echo e($row->nilai); ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-sm btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-danger fw-bold rounded-3">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-end mt-3">
        <?php echo e($data->links()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = master.checked;
        });
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.row-checkbox:checked');
        const count = checkboxes.length;
        const total = document.querySelectorAll('.row-checkbox').length;
        const master = document.getElementById('selectAllCheckbox');
        const actionBar = document.getElementById('bulkActionBar');
        const countText = document.getElementById('selectedCountText');

        countText.innerText = count;

        if (count > 0) {
            actionBar.classList.remove('d-none');
        } else {
            actionBar.classList.add('d-none');
        }

        if (master) {
            master.checked = (total > 0 && count === total);
            master.indeterminate = (count > 0 && count < total);
        }
    }

    function getSelectedCount() {
        return document.querySelectorAll('.row-checkbox:checked').length;
    }

    function clearAllSelections() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = false;
        });
        const master = document.getElementById('selectAllCheckbox');
        if (master) {
            master.checked = false;
            master.indeterminate = false;
        }
        updateSelectedCount();
    }

    function deleteSingle(id) {
        if (confirm('Yakin ingin menghapus catatan realisasi ini?')) {
            const form = document.getElementById('singleDeleteForm');
            form.action = `/realisasi/${id}`;
            form.submit();
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project\Projects\Ba\resources\views/realisasi/index.blade.php ENDPATH**/ ?>