<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
            <div class="card">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold"><?php echo e(__('auth.forgot_password')); ?></h4>
                        <p class="text-muted"><?php echo e(__('auth.forgot_password_description')); ?></p>
                    </div>

                    <?php if(session('status')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ph ph-check-circle me-2"></i>
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ph ph-warning-circle me-2"></i>
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('password.email')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <label for="email" class="form-label"><?php echo e(__('auth.email')); ?></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph ph-envelope"></i>
                                </span>
                                <input type="email"
                                       class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="email"
                                       name="email"
                                       value="<?php echo e(old('email')); ?>"
                                       placeholder="<?php echo e(__('auth.email_placeholder')); ?>"
                                       required
                                       autofocus>
                            </div>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block">
                                    <?php echo e($message); ?>

                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph ph-paper-plane-tilt me-2"></i>
                                <?php echo e(__('auth.send_reset_link')); ?>

                            </button>
                        </div>

                        <div class="text-center">
                            <a href="<?php echo e(route('login')); ?>" class="text-decoration-none">
                                <i class="ph ph-arrow-left me-1"></i>
                                <?php echo e(__('auth.back_to_login')); ?>

                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>