<?php $__env->startSection('title', 'Upload Dokumen & Foto Realisasi'); ?>
<?php $__env->startSection('page_heading', 'Upload PDF & Foto Bukti Realisasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Form Upload (Col-5) -->
    <div class="col-lg-5 col-12 mb-4">
        <div class="card-custom p-4">
            <h6 class="fw-bold text-danger mb-3">
                <i class="fas fa-cloud-upload-alt me-2"></i> Form Pengunggahan Dokumen / Foto Bukti
            </h6>
            <p class="text-muted small mb-4">Pilih OPD, periode, dan unggah berkas PDF resmi atau Foto Bukti (JPG/PNG/WEBP). Sistem akan membaca dan mengoperasikan ekstraksi data secara otomatis.</p>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger border-danger-subtle rounded-3 small p-3 mb-3">
                    <div class="fw-bold mb-1"><i class="fas fa-triangle-exclamation me-1"></i> Perhatian:</div>
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($err); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('upload.process')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Instansi / Unit OPD</label>
                    <select name="opd_name" class="form-select rounded-3 border-danger-subtle" required>
                        <?php $__currentLoopData = $opdList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($opd); ?>" <?php echo e(($user->opd_name === $opd) ? 'selected' : ''); ?>><?php echo e($opd); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold small text-muted">Periode Bulan</label>
                        <select name="periode" class="form-select rounded-3 border-danger-subtle" required>
                            <option value="Agustus 2026" selected>Agustus 2026</option>
                            <option value="Juli 2026">Juli 2026</option>
                            <option value="Juni 2026">Juni 2026</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold small text-muted">Tahun Anggaran</label>
                        <select name="tahun" class="form-select rounded-3 border-danger-subtle" required>
                            <option value="2026" selected>2026</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                </div>

                <!-- Drag and drop upload box for PDF / Images -->
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Berkas PDF atau Foto Bukti (PDF / JPG / PNG / WEBP)</label>
                    <div class="border border-2 border-danger-subtle rounded-4 p-4 text-center bg-light" id="dropZone" style="border-style: dashed !important;">
                        <i class="fas fa-file-image fa-3x text-danger opacity-75 mb-2"></i>
                        <h6 class="fw-bold mb-1">Pilih Berkas PDF / Foto Bukti</h6>
                        <small class="text-muted d-block mb-3">Format PDF, JPG, JPEG, PNG, WEBP (Maksimal 10 MB)</small>
                        <input type="file" name="file_upload" id="fileUpload" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-red w-100 py-2 fw-bold shadow-sm">
                    <i class="fas fa-microchip me-2"></i> Ekstraksi Data PDF & Parsing Berkas
                </button>
            </form>
        </div>
    </div>

    <!-- Informasi Alur Kerja & Panduan (Col-7) -->
    <div class="col-lg-7 col-12">
        <div class="card-custom p-4">
            <h6 class="fw-bold text-danger mb-3">
                <i class="fas fa-circle-info me-2"></i> Alur Proses Ekstraksi PDF & Foto Bukti
            </h6>
            
            <div class="timeline ps-3 border-start border-danger border-2 ms-2">
                <div class="mb-4 position-relative">
                    <span class="badge bg-danger rounded-circle position-absolute" style="left: -26px; top: 0; width: 22px; height: 22px;">1</span>
                    <h6 class="fw-bold mb-1 text-dark">Unggah Berkas PDF atau Foto Kwitansi</h6>
                    <p class="text-muted small mb-0">Operator OPD mengunggah dokumen PDF atau foto bukti fisik kwitansi/lapangan.</p>
                </div>
                <div class="mb-4 position-relative">
                    <span class="badge bg-danger rounded-circle position-absolute" style="left: -26px; top: 0; width: 22px; height: 22px;">2</span>
                    <h6 class="fw-bold mb-1 text-dark">Sistem Ekstraksi (Parser Engine)</h6>
                    <p class="text-muted small mb-0">Sistem mengekstrak Kode Rekening, Nama Retribusi, dan Nilai Realisasi (Rp).</p>
                </div>
                <div class="mb-4 position-relative">
                    <span class="badge bg-danger rounded-circle position-absolute" style="left: -26px; top: 0; width: 22px; height: 22px;">3</span>
                    <h6 class="fw-bold mb-1 text-dark">Preview & Validasi Interaktif</h6>
                    <p class="text-muted small mb-0">Operator dapat melihat foto bukti pada tabel validasi dan menyesuaikan data sebelum disimpan.</p>
                </div>
                <div class="position-relative">
                    <span class="badge bg-danger rounded-circle position-absolute" style="left: -26px; top: 0; width: 22px; height: 22px;">4</span>
                    <h6 class="fw-bold mb-1 text-dark">Simpan Database & Galeri Bukti</h6>
                    <p class="text-muted small mb-0">Data dan foto tersimpan di MySQL, siap diekspor ke Excel dan dicetak dalam laporan resmi.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\agung windu\.gemini\antigravity\scratch\sistem-retribusi-github\resources\views/upload/index.blade.php ENDPATH**/ ?>