<?php $__env->startSection('title', 'Login — BookNest'); ?>
<?php $__env->startSection('content'); ?>
<div class="auth-wrap">
    <div class="auth-card card">
        <div class="auth-header">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="BookNest">
            <strong>BookNest Library</strong>
        </div>
        <div class="auth-body">
            <h2 style="margin-top:0;">Welcome Back!</h2>
            <p class="book-author" style="margin-bottom:24px;">Login to your account</p>

            <?php if($errors->any()): ?>
                <div class="form-error" style="margin-bottom:16px;"><?php echo e($errors->first()); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Login</button>
            </form>
            <p style="margin-top:18px;font-size:0.88rem;">Don't have an account? <a href="<?php echo e(route('register')); ?>" style="color:var(--color-button);font-weight:600;">Sign Up</a></p>
            <div class="card card-pad auth-demo-note" style="margin-top:16px;">
                <strong>Demo accounts</strong>
                <p class="form-help" style="margin:6px 0 0;">Admin: <code>admin@booknest.test</code> / <code>password</code></p>
                <p class="form-help" style="margin:4px 0 0;">User: <code>demo@booknest.test</code> / <code>password</code></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/auth/login.blade.php ENDPATH**/ ?>