<?php $__env->startSection('title', 'Admin Dashboard — BookNest'); ?>
<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="stat-grid" style="margin-top:24px;">
            <div class="card stat-card"><div class="stat-label">Total Book Copies</div><div class="stat-value"><?php echo e($stats['total_books']); ?></div></div>
            <div class="card stat-card"><div class="stat-label">Available Copies</div><div class="stat-value"><?php echo e($stats['available_books']); ?></div></div>
            <div class="card stat-card"><div class="stat-label">Registered Users</div><div class="stat-value"><?php echo e($stats['total_users']); ?></div></div>
            <div class="card stat-card"><div class="stat-label">Categories</div><div class="stat-value"><?php echo e($stats['total_categories']); ?></div></div>
            <div class="card stat-card"><div class="stat-label">Active Loans</div><div class="stat-value"><?php echo e($stats['active_borrowings']); ?></div></div>
            <div class="card stat-card"><div class="stat-label">Overdue Loans</div><div class="stat-value"><?php echo e($stats['overdue_borrowings']); ?></div></div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:32px;">
            <div class="card card-pad">
                <h3 style="margin-top:0;">Books per Category</h3>
                <table class="data-table">
                    <tbody>
                    <?php $__currentLoopData = $perCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr><td><?php echo e($c->name); ?><?php echo e($topCategory && $topCategory->id === $c->id ? ' 👑' : ''); ?></td><td><?php echo e($c->books_count); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card card-pad">
                <h3 style="margin-top:0;">Low Availability</h3>
                <table class="data-table">
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $lowAvailability; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr><td><?php echo e($b->title); ?></td><td><?php echo e($b->available_copies); ?>/<?php echo e($b->total_copies); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td class="book-author">Nothing running low.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div style="display:flex;gap:14px;margin-top:32px;">
            <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-primary">Manage Books</a>
            <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-secondary">Manage Categories</a>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">Manage Users</a>
            <a href="<?php echo e(route('admin.authors.index')); ?>" class="btn btn-secondary">Manage Authors</a>
            <a href="<?php echo e(route('admin.borrowings.index')); ?>" class="btn btn-secondary">Borrowings</a>
            <a href="<?php echo e(route('chatbot.show')); ?>" class="btn btn-outline" style="border-color:var(--color-button);color:var(--color-button);">Ask AI</a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>