<?php $__env->startSection('title', 'Data Realisasi Retribusi'); ?>
<?php $__env->startSection('page_heading', 'Data Realisasi Retribusi Daerah'); ?>

<?php $__env->startSection('content'); ?>


<div class="card-custom p-4 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h6 class="fw-bold mb-0" style="color:#fca5a5;">
                <i class="fas fa-database me-2"></i>Rekapitulasi Data Realisasi Retribusi
            </h6>
            <small style="color:rgba(255,255,255,0.45);">Total <?php echo e($totalRecord); ?> catatan ditemukan</small>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('realisasi.print', request()->all())); ?>" target="_blank"
               class="btn btn-sm fw-bold rounded-3"
               style="background:rgba(255,255,255,0.10);border:1px solid rgba(255,255,255,0.20);color:#fff;">
                <i class="fas fa-print me-1"></i> Cetak PDF
            </a>
            <a href="<?php echo e(route('realisasi.export', request()->all())); ?>"
               class="btn btn-sm btn-red fw-bold rounded-3">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>

    
    <form action="<?php echo e(route('realisasi.index')); ?>" method="GET"
          class="d-flex flex-wrap gap-2 align-items-center mb-4">
        <input type="number" name="tahun" class="form-control form-control-sm rounded-3 fw-bold"
               style="width:90px;" placeholder="Tahun" value="<?php echo e($tahun); ?>" min="2000" max="2100">

        <?php if($isAdmin): ?>
        <select name="opd" class="form-select form-select-sm rounded-3 fw-semibold"
                style="width:auto; min-width:160px;" onchange="this.form.submit()">
            <option value="Semua OPD" <?php echo e(($opd === 'Semua OPD' || !$opd) ? 'selected' : ''); ?>>Semua Instansi OPD</option>
            <?php $__currentLoopData = $opdList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($o); ?>" <?php echo e($opd === $o ? 'selected' : ''); ?>><?php echo e($o); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php endif; ?>

        <select name="periode" class="form-select form-select-sm rounded-3 fw-semibold"
                style="width:auto; min-width:130px;" onchange="this.form.submit()">
            <option value="Semua Periode" <?php echo e(($periode === 'Semua Periode' || !$periode) ? 'selected' : ''); ?>>Semua Bulan</option>
            <?php $__currentLoopData = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($bln); ?>" <?php echo e($periode === $bln ? 'selected' : ''); ?>><?php echo e($bln); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <input type="text" name="search" class="form-control form-control-sm rounded-3"
               style="min-width:180px; flex:1;" placeholder="🔍 Cari kode / nama retribusi..."
               value="<?php echo e($search); ?>">

        <button type="submit" class="btn btn-sm btn-red fw-bold rounded-3 px-3">
            <i class="fas fa-search me-1"></i> Cari
        </button>
    </form>

    
    <div class="d-flex align-items-center justify-content-between rounded-3 px-4 py-3 mb-1"
         style="background:rgba(220,38,38,0.12); border:1px solid rgba(220,38,38,0.25);">
        <div class="d-flex align-items-center gap-3">
            <div style="width:42px;height:42px;background:rgba(220,38,38,0.20);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-calculator" style="color:#fca5a5;font-size:1.1rem;"></i>
            </div>
            <div>
                <small style="color:rgba(255,255,255,0.50);font-size:0.75rem;display:block;">Total Nilai Realisasi</small>
                <h5 class="fw-bold mb-0" style="color:#fca5a5;">Rp <?php echo e(number_format($totalNilai, 0, ',', '.')); ?></h5>
            </div>
        </div>
        <span class="badge rounded-pill px-3 py-2 fw-bold"
              style="background:rgba(255,255,255,0.10);border:1px solid rgba(255,255,255,0.20);color:#fff;">
            <?php echo e($totalRecord); ?> Item
        </span>
    </div>
</div>


<div class="card-custom p-4">

    
    <form action="<?php echo e(route('realisasi.bulkDelete')); ?>" method="POST" id="bulkForm"
          onsubmit="return confirm('Hapus ' + getSelectedCount() + ' data terpilih?')">
        <?php echo csrf_field(); ?>
        <div id="bulkActionBar"
             class="d-flex align-items-center justify-content-between rounded-3 px-3 py-2 mb-3 d-none"
             style="background:rgba(220,38,38,0.18);border:1px solid rgba(220,38,38,0.35);">
            <span style="color:#fca5a5;font-size:0.85rem;font-weight:600;">
                <i class="fas fa-check-double me-2"></i>
                <span id="selectedCountText">0</span> data terpilih
            </span>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm fw-semibold rounded-3"
                        style="background:rgba(255,255,255,0.10);color:#fff;border:1px solid rgba(255,255,255,0.20);"
                        onclick="clearAllSelections()">Batal</button>
                <button type="submit" class="btn btn-sm btn-red fw-bold rounded-3">
                    <i class="fas fa-trash-alt me-1"></i> Hapus
                </button>
            </div>
        </div>

        
        <div class="d-flex align-items-center gap-2 mb-3 pb-2"
             style="border-bottom:1px solid rgba(255,255,255,0.10);">
            <input type="checkbox" id="selectAllCheckbox" class="form-check-input"
                   style="width:16px;height:16px;" onchange="toggleSelectAll(this)">
            <label for="selectAllCheckbox" style="color:rgba(255,255,255,0.50);font-size:0.80rem;cursor:pointer;">
                Pilih Semua di Halaman Ini
            </label>
        </div>

        
        <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex align-items-start gap-3 mb-3 pb-3 row-item"
             style="border-bottom:1px solid rgba(255,255,255,0.07);<?php echo e($loop->last ? 'border-bottom:none;margin-bottom:0;padding-bottom:0;' : ''); ?>">

            
            <div class="flex-shrink-0 pt-1">
                <input type="checkbox" name="selected_ids[]" value="<?php echo e($row->id); ?>"
                       class="form-check-input row-checkbox"
                       style="width:16px;height:16px;" onchange="updateSelectedCount()">
            </div>

            
            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3"
                 style="width:46px;height:46px;background:rgba(220,38,38,0.15);border:1px solid rgba(220,38,38,0.25);">
                <i class="fas fa-file-invoice-dollar" style="color:#fca5a5;font-size:1rem;"></i>
            </div>

            
            <div class="flex-grow-1 min-w-0">
                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                    <span class="fw-bold" style="color:#fff;font-size:0.90rem;"><?php echo e($row->nama_retribusi); ?></span>
                    <span class="badge rounded-pill px-2"
                          style="background:rgba(220,38,38,0.18);border:1px solid rgba(220,38,38,0.30);color:#fca5a5;font-size:0.70rem;">
                        <?php echo e($row->kode_rekening); ?>

                    </span>
                    <span class="badge rounded-pill px-2"
                          style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.14);color:rgba(255,255,255,0.60);font-size:0.70rem;">
                        <?php echo e($row->periode); ?>

                    </span>
                </div>
                <div class="d-flex flex-wrap gap-3 align-items-center" style="font-size:0.78rem;">
                    <span style="color:rgba(255,255,255,0.50);">
                        <i class="fas fa-building me-1" style="color:#fca5a5;"></i><?php echo e($row->opd_name); ?>

                    </span>
                    <span class="fw-bold" style="color:#86efac;font-size:0.90rem;">
                        Rp <?php echo e(number_format($row->nilai, 0, ',', '.')); ?>

                    </span>
                </div>
            </div>

            
            <div class="flex-shrink-0 d-flex gap-1">
                <button type="button"
                        class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center"
                        style="width:34px;height:34px;background:rgba(59,130,246,0.18);border:1px solid rgba(59,130,246,0.30);color:#93c5fd;"
                        data-bs-toggle="modal" data-bs-target="#detailModal<?php echo e($row->id); ?>" title="Detail">
                    <i class="fas fa-eye" style="font-size:0.80rem;"></i>
                </button>
                <button type="button"
                        class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center"
                        style="width:34px;height:34px;background:rgba(234,179,8,0.18);border:1px solid rgba(234,179,8,0.30);color:#fde68a;"
                        data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($row->id); ?>" title="Edit">
                    <i class="fas fa-edit" style="font-size:0.80rem;"></i>
                </button>
                <button type="button"
                        class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center"
                        style="width:34px;height:34px;background:rgba(220,38,38,0.18);border:1px solid rgba(220,38,38,0.30);color:#fca5a5;"
                        onclick="deleteSingle(<?php echo e($row->id); ?>)" title="Hapus">
                    <i class="fas fa-trash-alt" style="font-size:0.80rem;"></i>
                </button>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x mb-3" style="color:rgba(255,255,255,0.12);display:block;"></i>
            <p class="fw-semibold mb-1" style="color:rgba(255,255,255,0.40);">Tidak ada data realisasi</p>
            <small style="color:rgba(255,255,255,0.25);">Coba ubah filter atau upload dokumen baru</small>
        </div>
        <?php endif; ?>
    </form>

    
    <form id="singleDeleteForm" method="POST" style="display:none;">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
    </form>

    
    <div class="d-flex justify-content-end mt-4 pt-3"
         style="border-top:1px solid rgba(255,255,255,0.08);">
        <?php echo e($data->links()); ?>

    </div>
</div>


<?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="modal fade" id="detailModal<?php echo e($row->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="border-color:rgba(220,38,38,0.30);">
                <h6 class="modal-title fw-bold" style="color:#fca5a5;">
                    <i class="fas fa-file-invoice me-2"></i>Detail Catatan Realisasi
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex flex-column gap-3">
                    <?php $__currentLoopData = [
                        ['Kode Rekening', $row->kode_rekening, 'fca5a5'],
                        ['Jenis Retribusi', $row->nama_retribusi, 'fff'],
                        ['Instansi OPD', $row->opd_name, 'fff'],
                        ['Periode / Tahun', (str_contains($row->periode, (string)$row->tahun) ? $row->periode : $row->periode.' '.$row->tahun), 'fff'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $clr]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-between align-items-start py-2"
                         style="border-bottom:1px solid rgba(255,255,255,0.08);">
                        <span style="color:rgba(255,255,255,0.45);font-size:0.80rem;"><?php echo e($label); ?></span>
                        <span class="fw-semibold text-end" style="color:#<?php echo e($clr); ?>;font-size:0.88rem;"><?php echo e($val); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-between align-items-center pt-1">
                        <span style="color:rgba(255,255,255,0.45);font-size:0.80rem;">Nilai Realisasi</span>
                        <span class="fw-bold" style="color:#86efac;font-size:1.15rem;">
                            Rp <?php echo e(number_format($row->nilai, 0, ',', '.')); ?>

                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-color:rgba(255,255,255,0.10);">
                <button type="button" class="btn btn-sm fw-semibold rounded-3"
                        style="background:rgba(255,255,255,0.10);color:#fff;border:1px solid rgba(255,255,255,0.20);"
                        data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editModal<?php echo e($row->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?php echo e(route('realisasi.update', $row->id)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="modal-header" style="border-color:rgba(255,255,255,0.10);">
                    <h6 class="modal-title fw-bold" style="color:#fde68a;">
                        <i class="fas fa-edit me-2"></i>Edit Data Realisasi
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Kode Rekening</label>
                        <input type="text" name="kode_rekening" class="form-control rounded-3"
                               value="<?php echo e($row->kode_rekening); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Retribusi</label>
                        <input type="text" name="nama_retribusi" class="form-control rounded-3"
                               value="<?php echo e($row->nama_retribusi); ?>" required>
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Nilai Realisasi (Rp)</label>
                        <input type="number" name="nilai" class="form-control rounded-3 fw-bold"
                               value="<?php echo e($row->nilai); ?>" required>
                    </div>
                </div>
                <div class="modal-footer" style="border-color:rgba(255,255,255,0.10);">
                    <button type="button" class="btn btn-sm fw-semibold rounded-3"
                            style="background:rgba(255,255,255,0.10);color:#fff;border:1px solid rgba(255,255,255,0.20);"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-red fw-bold rounded-3">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    function toggleSelectAll(master) {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = master.checked);
        updateSelectedCount();
    }
    function updateSelectedCount() {
        const checked = document.querySelectorAll('.row-checkbox:checked').length;
        const total   = document.querySelectorAll('.row-checkbox').length;
        const master  = document.getElementById('selectAllCheckbox');
        const bar     = document.getElementById('bulkActionBar');
        document.getElementById('selectedCountText').innerText = checked;
        bar.classList.toggle('d-none', checked === 0);
        if (master) {
            master.checked       = total > 0 && checked === total;
            master.indeterminate = checked > 0 && checked < total;
        }
    }
    function getSelectedCount() {
        return document.querySelectorAll('.row-checkbox:checked').length;
    }
    function clearAllSelections() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        const master = document.getElementById('selectAllCheckbox');
        if (master) { master.checked = false; master.indeterminate = false; }
        updateSelectedCount();
    }
    function deleteSingle(id) {
        if (confirm('Yakin ingin menghapus catatan ini?')) {
            const form = document.getElementById('singleDeleteForm');
            form.action = `/realisasi/${id}`;
            form.submit();
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\agung windu\.gemini\antigravity\scratch\sistem-retribusi-github\resources\views/realisasi/index.blade.php ENDPATH**/ ?>