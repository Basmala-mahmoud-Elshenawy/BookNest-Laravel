<?php $__env->startSection('title', 'Sign Up — BookNest'); ?>
<?php $__env->startSection('content'); ?>
<div class="auth-wrap">
    <div class="auth-card card">
        <div class="auth-header">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="BookNest">
            <strong>BookNest Library</strong>
        </div>
        <div class="auth-body">
            <h2 style="margin-top:0;">Create your account</h2>
            <p class="book-author" style="margin-bottom:14px;">Join the BookNest community</p>
            <div class="card card-pad auth-role-note" style="margin-bottom:20px;">
                <strong>New accounts are User accounts.</strong>
                <p class="book-author" style="margin:5px 0 0;">For security, an Admin account cannot be created from public registration. An existing Admin can create or promote accounts from Admin → Users.</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="form-error" style="margin-bottom:16px;">
                    <ul style="margin:0;padding-left:18px;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('register')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Sign Up</button>
            </form>
            <p style="margin-top:18px;font-size:0.88rem;">Already have an account? <a href="<?php echo e(route('login')); ?>" style="color:var(--color-button);font-weight:600;">Login</a></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/auth/register.blade.php ENDPATH**/ ?>