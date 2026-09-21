<?php $__env->startSection('title', 'Master Data Kode Rekening Retribusi'); ?>
<?php $__env->startSection('page_heading', 'Master Data Kode Rekening Retribusi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h5 class="fw-bold text-white mb-1">
            <i class="fas fa-list-check me-2 text-danger"></i>Katalog Kode Rekening & Target Anggaran
        </h5>
        <small style="color:rgba(255,255,255,0.60);">
            Kelola data master acuan kode rekening untuk pencocokan otomatis hasil ekstraksi PDF
        </small>
    </div>
    <button class="btn btn-red fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahMaster">
        <i class="fas fa-plus me-1"></i> Tambah Kode Rekening
    </button>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success border-success-subtle rounded-3 small p-3 mb-3">
        <i class="fas fa-check-circle me-1"></i> <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<!-- FILTER & SEARCH BAR -->
<div class="card-custom p-3 mb-4">
    <form action="<?php echo e(route('master.index')); ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5 col-12">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari kode rekening, jenis retribusi, kategori..." value="<?php echo e($search); ?>">
            </div>
        </div>
        <div class="col-md-4 col-8">
            <select name="opd" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="Semua OPD">Semua Instansi OPD</option>
                <?php $__currentLoopData = $opdList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($o): ?>
                    <option value="<?php echo e($o); ?>" <?php echo e($opd == $o ? 'selected' : ''); ?>><?php echo e($o); ?></option>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3 col-4 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-red fw-bold w-100">Cari</button>
            <?php if($search || ($opd && $opd !== 'Semua OPD')): ?>
                <a href="<?php echo e(route('master.index')); ?>" class="btn btn-sm btn-outline-light"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- LIST MASTER DATA CARDS -->
<div class="card-custom p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between pb-3 mb-3" style="border-bottom: 1px solid rgba(255,255,255,0.12);">
        <span class="fw-bold" style="color:#fca5a5; font-size:0.88rem;">
            <i class="fas fa-database me-2"></i>Daftar Master Rekening (<?php echo e($items->total()); ?> Data)
        </span>
        <span class="small" style="color:rgba(255,255,255,0.45);">Halaman <?php echo e($items->currentPage()); ?> dari <?php echo e($items->lastPage()); ?></span>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 py-3"
         style="border-bottom: 1px solid rgba(255,255,255,0.08); <?php echo e($loop->last ? 'border-bottom:none;' : ''); ?>">

        <!-- Kolom Info Kiri -->
        <div class="d-flex align-items-center gap-3 flex-grow-1" style="min-width: 280px;">
            <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3 fw-bold"
                 style="width:44px;height:44px;background:rgba(220,38,38,0.18);border:1px solid rgba(220,38,38,0.35);color:#fca5a5;">
                <i class="fas fa-barcode"></i>
            </div>
            <div>
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <span class="badge px-2 py-1 rounded-3 fw-bold"
                          style="background:rgba(220,38,38,0.25);border:1px solid rgba(220,38,38,0.50);color:#fecaca;font-family:monospace;font-size:0.82rem;letter-spacing:0.5px;">
                        <?php echo e($item->kode_rekening); ?>

                    </span>
                    <span class="badge rounded-pill px-2"
                          style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.20);color:rgba(255,255,255,0.85);font-size:0.72rem;">
                        <?php echo e($item->kategori); ?>

                    </span>
                </div>
                <h6 class="fw-bold mb-1 text-white" style="font-size:0.95rem; letter-spacing:0.2px;">
                    <?php echo e($item->nama_retribusi); ?>

                </h6>
                <small style="color:rgba(255,255,255,0.55); font-size:0.78rem;">
                    <i class="fas fa-building me-1 text-danger"></i> <?php echo e($item->opd_name ?: 'Badan Pendapatan Daerah'); ?>

                </small>
            </div>
        </div>

        <!-- Kolom Target Anggaran & Tombol Aksi Kanan -->
        <div class="d-flex align-items-center gap-4 ms-auto">
            <div class="text-end">
                <small class="d-block" style="color:rgba(255,255,255,0.45);font-size:0.72rem;font-weight:600;text-transform:uppercase;">Target Anggaran</small>
                <span class="fw-bold fs-6" style="color:#86efac;">
                    Rp <?php echo e(number_format($item->target_anggaran, 0, ',', '.')); ?>

                </span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center"
                        style="width:34px;height:34px;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.25);color:#fff;"
                        data-bs-toggle="modal" data-bs-target="#modalEditMaster<?php echo e($item->id); ?>" title="Edit">
                    <i class="fas fa-pencil-alt" style="font-size:0.80rem;"></i>
                </button>
                <form action="<?php echo e(route('master.destroy', $item->id)); ?>" method="POST" onsubmit="return confirm('Hapus master kode rekening <?php echo e($item->kode_rekening); ?>?')" class="m-0">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center"
                            style="width:34px;height:34px;background:rgba(220,38,38,0.22);border:1px solid rgba(220,38,38,0.45);color:#fca5a5;" title="Hapus">
                        <i class="fas fa-trash-alt" style="font-size:0.80rem;"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center py-5">
        <i class="fas fa-folder-open fa-3x mb-3" style="color:rgba(255,255,255,0.15);"></i>
        <p style="color:rgba(255,255,255,0.50);">Tidak ada data kode rekening ditemukan.</p>
    </div>
    <?php endif; ?>

    <div class="mt-4 pt-2">
        <?php echo e($items->withQueryString()->links()); ?>

    </div>
</div>

<!-- MODALS DI LUAR CONTAINER UTAMA AGAR TIDAK TERHALANG GLASS MORPHISM -->
<?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modalEditMaster<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#1a0808; border:1px solid rgba(220,38,38,0.45); color:#fff; border-radius:18px; box-shadow: 0 10px 40px rgba(0,0,0,0.7);">
            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.12);">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-edit me-2 text-danger"></i>Edit Master Rekening</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('master.update', $item->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode Rekening</label>
                        <input type="text" name="kode_rekening" class="form-control fw-bold" value="<?php echo e($item->kode_rekening); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Retribusi / Uraian</label>
                        <input type="text" name="nama_retribusi" class="form-control" value="<?php echo e($item->nama_retribusi); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Instansi OPD Pengelola</label>
                        <input type="text" name="opd_name" class="form-control" value="<?php echo e($item->opd_name); ?>">
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Kategori</label>
                            <input type="text" name="kategori" class="form-control" value="<?php echo e($item->kategori); ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Target Anggaran (Rp)</label>
                            <input type="number" name="target_anggaran" class="form-control" value="<?php echo e($item->target_anggaran); ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.12);">
                    <button type="button" class="btn btn-secondary btn-sm rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red btn-sm fw-bold rounded-3">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- MODAL TAMBAH MASTER -->
<div class="modal fade" id="modalTambahMaster" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#1a0808; border:1px solid rgba(220,38,38,0.45); color:#fff; border-radius:18px; box-shadow: 0 10px 40px rgba(0,0,0,0.7);">
            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.12);">
                <h6 class="modal-title fw-bold text-white"><i class="fas fa-plus-circle me-2 text-danger"></i>Tambah Master Kode Rekening</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('master.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode Rekening (cth: 4.1.01.09.01)</label>
                        <input type="text" name="kode_rekening" class="form-control fw-bold" placeholder="4.1.01.xx.xx" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Retribusi / Uraian</label>
                        <input type="text" name="nama_retribusi" class="form-control" placeholder="cth: Pajak Reklame Papan/Billboard" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Instansi OPD Pengelola</label>
                        <input type="text" name="opd_name" class="form-control" placeholder="cth: Badan Pendapatan Daerah" value="Badan Pendapatan Daerah">
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Kategori</label>
                            <input type="text" name="kategori" class="form-control" placeholder="Pajak Daerah" value="Pajak Daerah">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Target Anggaran (Rp)</label>
                            <input type="number" name="target_anggaran" class="form-control" placeholder="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.12);">
                    <button type="button" class="btn btn-secondary btn-sm rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-red btn-sm fw-bold rounded-3"><i class="fas fa-save me-1"></i> Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\agung windu\.gemini\antigravity\scratch\sistem-retribusi-github\resources\views/master/index.blade.php ENDPATH**/ ?>