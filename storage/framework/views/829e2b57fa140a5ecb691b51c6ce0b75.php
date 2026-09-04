<?php $__env->startSection('title', 'Dashboard Overview'); ?>
<?php $__env->startSection('page_heading', 'Overview Realisasi Retribusi'); ?>

<?php $__env->startSection('content'); ?>
<!-- Filter Tahun Bar -->
<div class="card-custom p-3 mb-4">
    <form action="<?php echo e(route('dashboard')); ?>" method="GET" class="row g-3 align-items-center">
        <div class="col-auto">
            <label class="fw-bold text-muted small"><i class="fas fa-filter me-1 text-danger"></i> Filter Periode:</label>
        </div>
        <div class="col-auto">
            <select name="bulan" class="form-select form-select-sm rounded-3 border-danger-subtle fw-semibold" onchange="this.form.submit()">
                <?php $__currentLoopData = $bulanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($bln); ?>" <?php echo e($selectedBulan == $bln ? 'selected' : ''); ?>>Bulan <?php echo e($bln); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-danger-subtle"><i class="fas fa-calendar-alt"></i></span>
                <input type="number" name="tahun" class="form-control form-control-sm border-danger-subtle fw-bold" style="width: 100px;" value="<?php echo e($tahun); ?>" placeholder="Tahun" min="2000" max="2100">
                <button type="submit" class="btn btn-sm btn-danger fw-bold">Terapkan</button>
            </div>
        </div>
        <div class="col-auto ms-auto">
            <span class="badge bg-danger text-white px-3 py-2 rounded-pill fw-bold">
                <i class="fas fa-building me-1"></i> Unit OPD: <?php echo e(strtoupper($user->opd_name)); ?>

            </span>
        </div>
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

<!-- RECENT PDF UPLOADS TABLE -->
<div class="card-custom p-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0 text-danger"><i class="fas fa-clock-rotate-left me-2"></i>Aktivitas Upload Dokumen Terakhir</h6>
        <a href="<?php echo e(route('upload.index')); ?>" class="btn btn-sm btn-red">
            <i class="fas fa-plus me-1"></i> Upload PDF Baru
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="small text-muted">File PDF</th>
                    <th class="small text-muted">Periode</th>
                    <th class="small text-muted">OPD / Instansi</th>
                    <th class="small text-muted">Total Nilai</th>
                    <th class="small text-muted">Status Parsing</th>
                    <th class="small text-muted">Tanggal Upload</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentUploads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="fw-bold text-danger">
                            <i class="fas fa-file-pdf me-2"></i><?php echo e($item->original_filename); ?>

                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo e($item->periode); ?></span></td>
                        <td class="small fw-semibold"><?php echo e($item->opd_name); ?></td>
                        <td class="fw-bold text-success">Rp <?php echo e(number_format($item->total_nilai, 0, ',', '.')); ?></td>
                        <td>
                            <?php if($item->status === 'Success'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="fas fa-check-circle me-1"></i> Success</span>
                            <?php elseif($item->status === 'Processing'): ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1"><i class="fas fa-spinner fa-spin me-1"></i> Processing</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="fas fa-times-circle me-1"></i> Failed</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted"><?php echo e($item->created_at->format('d M Y, H:i')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada dokumen PDF diupload.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project\Projects\Ba\resources\views/dashboard/index.blade.php ENDPATH**/ ?>