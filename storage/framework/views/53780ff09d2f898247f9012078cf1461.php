<?php $__env->startSection('title', 'Manajemen Pengguna OPD'); ?>
<?php $__env->startSection('page_heading', 'Manajemen Akun Pengguna OPD'); ?>

<?php $__env->startSection('content'); ?>
<div class="card-custom p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h6 class="fw-bold mb-0" style="color:#fca5a5;">
                <i class="fas fa-users-gear me-2"></i>Daftar Pengguna Sistem Retribusi
            </h6>
            <small style="color:rgba(255,255,255,0.45);">Kelola akun pengguna BAPENDA dan operator OPD</small>
        </div>
        <button type="button" class="btn btn-sm btn-red fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-user-plus me-1"></i> Tambah Akun Pengguna
        </button>
    </div>

    
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="d-flex align-items-center justify-content-between gap-3 mb-3 pb-3"
         style="border-bottom:1px solid rgba(255,255,255,0.07); <?php echo e($loop->last ? 'border-bottom:none;margin-bottom:0;padding-bottom:0;' : ''); ?>">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white rounded-circle text-danger d-flex align-items-center justify-content-center fw-bold shadow-sm"
                 style="width:42px;height:42px;font-size:16px;flex-shrink:0;">
                <?php echo e(strtoupper(substr($u->name, 0, 1))); ?>

            </div>
            <div>
                <span class="fw-bold d-block" style="color:#fff;font-size:0.90rem;"><?php echo e($u->name); ?></span>
                <small style="color:rgba(255,255,255,0.45);font-size:0.75rem;">
                    <?php echo e($u->email); ?> • <strong style="color:#fca5a5;"><?php echo e($u->opd_name); ?></strong>
                </small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill px-3 py-1"
                  style="background:<?php echo e($u->isAdmin() ? 'rgba(220,38,38,0.20)' : 'rgba(59,130,246,0.20)'); ?>;
                         border:1px solid <?php echo e($u->isAdmin() ? 'rgba(220,38,38,0.40)' : 'rgba(59,130,246,0.40)'); ?>;
                         color:<?php echo e($u->isAdmin() ? '#fca5a5' : '#93c5fd'); ?>;font-size:0.72rem;">
                <?php echo e($u->isAdmin() ? 'ADMIN BAPENDA' : 'OPERATOR OPD'); ?>

            </span>

            
            <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;background:rgba(234,179,8,0.22);border:1px solid rgba(234,179,8,0.45);color:#fde68a;cursor:pointer;"
                    data-bs-toggle="modal" data-bs-target="#resetPassModal<?php echo e($u->id); ?>" title="Reset Password">
                <i class="fas fa-key" style="font-size:0.85rem;"></i>
            </button>

            <?php if($u->id !== Auth::id()): ?>
            <form action="<?php echo e(route('users.destroy', $u->id)); ?>" method="POST"
                  onsubmit="return confirm('Hapus akun pengguna <?php echo e($u->name); ?>?')" style="margin:0;">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center"
                        style="width:34px;height:34px;background:rgba(220,38,38,0.22);border:1px solid rgba(220,38,38,0.45);color:#fca5a5;cursor:pointer;" title="Hapus Akun">
                    <i class="fas fa-trash-alt" style="font-size:0.85rem;"></i>
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>




<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="resetPassModal<?php echo e($u->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?php echo e(route('users.resetPassword', $u->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header" style="border-color:rgba(255,255,255,0.10);">
                    <h6 class="modal-title fw-bold" style="color:#fde68a;">
                        <i class="fas fa-key me-2"></i>Reset Kata Sandi — <?php echo e($u->name); ?>

                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small mb-3" style="color:rgba(255,255,255,0.60);">
                        Masukkan kata sandi baru untuk akun <strong style="color:#fff;"><?php echo e($u->email); ?></strong> (<?php echo e($u->opd_name); ?>):
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Kata Sandi Baru</label>
                        <input type="password" name="new_password" class="form-control rounded-3"
                               placeholder="Masukkan kata sandi baru (min. 6 karakter)" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer" style="border-color:rgba(255,255,255,0.10);">
                    <button type="button" class="btn btn-sm fw-semibold rounded-3"
                            style="background:rgba(255,255,255,0.10);color:#fff;border:1px solid rgba(255,255,255,0.20);"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-red fw-bold rounded-3">
                        <i class="fas fa-check me-1"></i> Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?php echo e(route('users.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header" style="border-color:rgba(255,255,255,0.10);">
                    <h6 class="modal-title fw-bold" style="color:#fca5a5;">
                        <i class="fas fa-user-plus me-2"></i>Tambah Akun Pengguna Baru
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap / Jabatan</label>
                        <input type="text" name="name" class="form-control" placeholder="cth: Operator Dishub" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Resmi</label>
                        <input type="email" name="email" class="form-control" placeholder="cth: dishub@retribusi.go.id" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Peran (Role)</label>
                        <select name="role" class="form-select" required>
                            <option value="user_opd">Operator OPD</option>
                            <option value="admin">Administrator BAPENDA</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instansi / Unit OPD</label>
                        <select name="opd_name" class="form-select" required>
                            <?php $__currentLoopData = $opdList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($opd); ?>"><?php echo e($opd); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border-color:rgba(255,255,255,0.10);">
                    <button type="button" class="btn btn-sm fw-semibold rounded-3"
                            style="background:rgba(255,255,255,0.10);color:#fff;border:1px solid rgba(255,255,255,0.20);"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-red fw-bold rounded-3">
                        <i class="fas fa-save me-1"></i> Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\agung windu\.gemini\antigravity\scratch\sistem-retribusi-github\resources\views/users/index.blade.php ENDPATH**/ ?>