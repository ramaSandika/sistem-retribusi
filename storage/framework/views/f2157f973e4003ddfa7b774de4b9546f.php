<?php $__env->startSection('title', 'Audit Trail Log Aktivitas'); ?>
<?php $__env->startSection('page_heading', 'Audit Trail & Histori Aktivitas Sistem'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-custom p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h6 class="fw-bold text-danger m-0">
                <i class="fas fa-history me-2"></i> Log Aktivitas Pengguna & PDF Parsing
            </h6>
            <small class="text-muted">Jejak audit otomatis untuk transparansi dan validasi data retribusi</small>
        </div>
        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold">
            Audit Trail Active
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-light">
                <tr>
                    <th class="small text-muted" style="width: 170px;">Waktu (WIB)</th>
                    <th class="small text-muted" style="width: 180px;">Pengguna</th>
                    <th class="small text-muted" style="width: 140px;">Aksi</th>
                    <th class="small text-muted">Detail Rincian Aktivitas</th>
                    <th class="small text-muted" style="width: 120px;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="small text-muted font-monospace"><?php echo e($log->created_at->format('Y-m-d H:i:s')); ?></td>
                        <td class="fw-semibold small">
                            <i class="fas fa-user-circle me-1 text-danger"></i> <?php echo e($log->user_name); ?>

                        </td>
                        <td>
                            <?php if(str_contains($log->action, 'UPLOAD')): ?>
                                <span class="badge bg-primary-subtle text-primary border px-2 py-1"><i class="fas fa-upload me-1"></i> <?php echo e($log->action); ?></span>
                            <?php elseif(str_contains($log->action, 'SAVE') || str_contains($log->action, 'VERIFY')): ?>
                                <span class="badge bg-success-subtle text-success border px-2 py-1"><i class="fas fa-save me-1"></i> <?php echo e($log->action); ?></span>
                            <?php elseif(str_contains($log->action, 'DELETE')): ?>
                                <span class="badge bg-danger-subtle text-danger border px-2 py-1"><i class="fas fa-trash me-1"></i> <?php echo e($log->action); ?></span>
                            <?php else: ?>
                                <span class="badge bg-light text-dark border px-2 py-1"><?php echo e($log->action); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-dark"><?php echo e($log->details); ?></td>
                        <td class="small text-muted font-monospace"><?php echo e($log->ip_address ?? '127.0.0.1'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada catatan log aktivitas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <?php echo e($logs->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project\Projects\Ba\resources\views/audit/index.blade.php ENDPATH**/ ?>