<?php $__env->startSection('title', 'Dashboard Overview'); ?>
<?php $__env->startSection('page_heading', 'Overview Realisasi Retribusi'); ?>

<?php $__env->startSection('content'); ?>

<div class="card-custom p-3 mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h5 class="fw-bold mb-1" style="font-size: clamp(0.95rem, 2.5vw, 1.15rem);">
                <i class="fas fa-chart-pie me-2 text-danger"></i>Dashboard Realisasi Retribusi
            </h5>
            <small style="color:rgba(255,255,255,0.50);">
                Selamat datang, <strong class="text-white"><?php echo e(Auth::user()->name); ?></strong>
                — <?php echo e(Auth::user()->opd_name); ?>

            </small>
        </div>
        <div class="text-end">
            <div class="badge px-3 py-2 rounded-3" style="background:rgba(220,38,38,0.18);border:1px solid rgba(220,38,38,0.35);color:#fca5a5;font-size:0.78rem;">
                <i class="fas fa-calendar-alt me-1"></i><?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

            </div>
        </div>
    </div>
</div>

<!-- Filter Tahun Bar -->
<div class="card-custom p-3 mb-4">
    <form action="<?php echo e(route('dashboard')); ?>" method="GET"
          class="d-flex flex-wrap gap-2 align-items-center">
        <label class="fw-semibold small mb-0" style="color:rgba(255,255,255,0.65);">
            <i class="fas fa-filter me-1 text-danger"></i> Filter:
        </label>
        <select name="bulan" class="form-select form-select-sm rounded-3 fw-semibold"
                style="width:auto; min-width:130px;" onchange="this.form.submit()">
            <?php $__currentLoopData = $bulanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($bln); ?>" <?php echo e($selectedBulan == $bln ? 'selected' : ''); ?>>
                    Bulan <?php echo e($bln); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <div class="input-group input-group-sm" style="width:auto;">
            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
            <input type="number" name="tahun" class="form-control fw-bold"
                   style="width:80px;" value="<?php echo e($tahun); ?>" min="2000" max="2100">
            <button type="submit" class="btn btn-sm btn-danger fw-bold">Terapkan</button>
        </div>
        <span class="badge badge-red px-2 py-1 rounded-pill ms-auto d-none d-sm-inline-flex">
            <i class="fas fa-building me-1"></i> <?php echo e(strtoupper($user->opd_name)); ?>

        </span>
    </form>
</div>

<!-- STAT CARDS ROW -->
<div class="row g-3 mb-4">
    <!-- Stat 1 -->
    <div class="col-md-3 col-sm-6">
        <div class="card-custom p-3 d-flex align-items-center">
            <div class="stat-icon bg-danger text-white me-3">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <p class="text-muted mb-0 small fw-semibold">Realisasi <?php echo e($selectedBulan); ?> <?php echo e($tahun); ?></p>
                <h5 class="fw-bold mb-0 text-danger">Rp <?php echo e(number_format($totalRealisasiBulanIni, 0, ',', '.')); ?></h5>
            </div>
        </div>
    </div>

    <!-- Stat 2 -->
    <div class="col-md-3 col-sm-6">
        <div class="card-custom p-3 d-flex align-items-center">
            <div class="stat-icon bg-dark text-white me-3" style="background: var(--deep-burgundy) !important;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div>
                <p class="text-muted mb-0 small fw-semibold">Total Realisasi <?php echo e($tahun); ?></p>
                <h5 class="fw-bold mb-0">Rp <?php echo e(number_format($totalRealisasiTahun, 0, ',', '.')); ?></h5>
            </div>
        </div>
    </div>

    <!-- Stat 3 -->
    <div class="col-md-3 col-sm-6">
        <div class="card-custom p-3 d-flex align-items-center">
            <div class="stat-icon bg-danger-subtle text-danger me-3">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div>
                <p class="text-muted mb-0 small fw-semibold">Dokumen PDF Diupload</p>
                <h5 class="fw-bold mb-0"><?php echo e($totalDokumenUploaded); ?> File</h5>
            </div>
        </div>
    </div>

    <!-- Stat 4 -->
    <div class="col-md-3 col-sm-6">
        <div class="card-custom p-3 d-flex align-items-center">
            <div class="stat-icon bg-warning text-dark bg-opacity-75 me-3">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <p class="text-muted mb-0 small fw-semibold">Unit OPD Terdaftar</p>
                <h5 class="fw-bold mb-0"><?php echo e($totalOpdAktif); ?> Instansi</h5>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-4 mb-4">
    <!-- Bar Chart Realisasi Bulanan -->
    <div class="col-lg-8">
        <div class="card-custom p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-danger"><i class="fas fa-chart-column me-2"></i>Grafik Perkembangan Realisasi <?php echo e($tahun); ?></h6>
                    <small class="text-muted">Total penerimaan per bulan dalam Rupiah</small>
                </div>
                <span class="badge badge-red px-3 py-2 rounded-pill">Data Terverifikasi</span>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Doughnut Chart OPD Distribution -->
    <div class="col-lg-4">
        <div class="card-custom p-4 h-100">
            <div class="mb-3">
                <h6 class="fw-bold mb-0 text-danger"><i class="fas fa-chart-pie me-2"></i><?php echo e($isAdmin ? 'Kontribusi per OPD' : 'Distribusi Retribusi'); ?></h6>
                <small class="text-muted">Proporsi penerimaan <?php echo e($tahun); ?></small>
            </div>
            <div style="height: 260px; position: relative;" class="d-flex justify-content-center align-items-center">
                <canvas id="opdChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- RECENT PDF UPLOADS - CARD LIST -->
<div class="card-custom p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h6 class="fw-bold mb-0" style="color:#fca5a5;">
                <i class="fas fa-clock-rotate-left me-2"></i>Aktivitas Upload Dokumen Terakhir
            </h6>
            <small style="color:rgba(255,255,255,0.45);">Riwayat unggah dokumen PDF & foto bukti</small>
        </div>
        <a href="<?php echo e(route('upload.index')); ?>" class="btn btn-sm btn-red">
            <i class="fas fa-plus me-1"></i> Upload Baru
        </a>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $recentUploads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="d-flex align-items-start gap-3 mb-3 pb-3"
         style="border-bottom: 1px solid rgba(255,255,255,0.08); <?php echo e($loop->last ? 'border-bottom:none; margin-bottom:0; padding-bottom:0;' : ''); ?>">

        
        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3"
             style="width:46px; height:46px; background:
                <?php if($item->status === 'Success'): ?> rgba(22,163,74,0.18);
                <?php elseif($item->status === 'Processing'): ?> rgba(234,179,8,0.18);
                <?php else: ?> rgba(220,38,38,0.18); <?php endif; ?>
             ">
            <?php if($item->status === 'Success'): ?>
                <i class="fas fa-file-circle-check fa-lg" style="color:#86efac;"></i>
            <?php elseif($item->status === 'Processing'): ?>
                <i class="fas fa-file-circle-exclamation fa-lg fa-spin" style="color:#fde68a;"></i>
            <?php else: ?>
                <i class="fas fa-file-circle-xmark fa-lg" style="color:#fca5a5;"></i>
            <?php endif; ?>
        </div>

        
        <div class="flex-grow-1 min-w-0">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                <span class="fw-bold text-truncate" style="color:#fff; font-size:0.88rem; max-width:240px;">
                    <?php echo e($item->original_filename); ?>

                </span>
                
                <?php if($item->status === 'Success'): ?>
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(22,163,74,0.20);border:1px solid rgba(22,163,74,0.40);color:#86efac;font-size:0.72rem;">
                        <i class="fas fa-check-circle me-1"></i>Terverifikasi
                    </span>
                <?php elseif($item->status === 'Processing'): ?>
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(234,179,8,0.20);border:1px solid rgba(234,179,8,0.40);color:#fde68a;font-size:0.72rem;">
                        <i class="fas fa-spinner fa-spin me-1"></i>Diproses
                    </span>
                <?php else: ?>
                    <span class="badge rounded-pill px-2 py-1"
                          style="background:rgba(220,38,38,0.20);border:1px solid rgba(220,38,38,0.40);color:#fca5a5;font-size:0.72rem;">
                        <i class="fas fa-times-circle me-1"></i>Gagal
                    </span>
                <?php endif; ?>
            </div>

            
            <div class="d-flex flex-wrap gap-2 align-items-center" style="font-size:0.78rem;">
                <span style="color:rgba(255,255,255,0.50);">
                    <i class="fas fa-building me-1" style="color:#fca5a5;"></i>
                    <?php echo e($item->opd_name); ?>

                </span>
                <span style="color:rgba(255,255,255,0.30);">•</span>
                <span style="color:rgba(255,255,255,0.50);">
                    <i class="fas fa-calendar-alt me-1" style="color:#fca5a5;"></i>
                    <?php echo e($item->periode); ?>

                </span>
                <span style="color:rgba(255,255,255,0.30);">•</span>
                <span class="fw-bold" style="color:#86efac;">
                    Rp <?php echo e(number_format($item->total_nilai, 0, ',', '.')); ?>

                </span>
            </div>
        </div>

        
        <div class="flex-shrink-0 text-end d-none d-sm-block">
            <small style="color:rgba(255,255,255,0.38); font-size:0.72rem; line-height:1.4;">
                <?php echo e($item->created_at->format('d M Y')); ?><br>
                <span style="color:rgba(255,255,255,0.25);"><?php echo e($item->created_at->format('H:i')); ?></span>
            </small>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center py-5">
        <div class="mb-3">
            <i class="fas fa-inbox fa-3x" style="color:rgba(255,255,255,0.15);"></i>
        </div>
        <p class="mb-1 fw-semibold" style="color:rgba(255,255,255,0.45);">Belum ada dokumen diupload</p>
        <small style="color:rgba(255,255,255,0.25);">Mulai upload PDF atau foto bukti realisasi</small>
        <div class="mt-3">
            <a href="<?php echo e(route('upload.index')); ?>" class="btn btn-sm btn-red">
                <i class="fas fa-cloud-upload-alt me-1"></i> Upload Sekarang
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($bulanList, 15, 512) ?>,
                datasets: [{
                    label: 'Realisasi (Rp)',
                    data: <?php echo json_encode($chartBulanan, 15, 512) ?>,
                    backgroundColor: 'rgba(153, 27, 27, 0.85)',
                    borderColor: '#7f1d1d',
                    borderWidth: 1,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toLocaleString('id-ID') + ' Jt';
                            }
                        }
                    }
                }
            }
        });

        const ctxOpd = document.getElementById('opdChart').getContext('2d');
        new Chart(ctxOpd, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($opdChartLabels, 15, 512) ?>,
                datasets: [{
                    data: <?php echo json_encode($opdChartData, 15, 512) ?>,
                    backgroundColor: [
                        '#991b1b',
                        '#dc2626',
                        '#b91c1c',
                        '#7f1d1d',
                        '#f87171',
                        '#ef4444'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\agung windu\.gemini\antigravity\scratch\sistem-retribusi-github\resources\views/dashboard/index.blade.php ENDPATH**/ ?>