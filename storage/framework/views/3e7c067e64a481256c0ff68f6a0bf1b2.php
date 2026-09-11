<?php $__env->startSection('title', 'Manage Users — BookNest'); ?>
<?php $__env->startSection('content'); ?>
<section class="section"><div class="container">
    <div class="section-head"><h2>Users</h2><a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary btn-sm">+ New User</a></div>
    <div class="card card-pad admin-note" style="margin-bottom:20px;">
        <strong>Account roles</strong>
        <p class="book-author" style="margin:5px 0 0;">Public Sign Up always creates a User. Use this protected page to create an Admin or User account. Both roles can browse the catalog, view details, use their profile, favorites and the BookNest AI assistant; Admins additionally get library management and statistics.</p>
    </div>
    <div class="card">
        <table class="data-table">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr></thead>
            <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($u->name); ?></td><td><?php echo e($u->email); ?></td>
                    <td><span class="badge badge-<?php echo e($u->role === 'admin' ? 'reserved' : 'available'); ?>"><?php echo e(ucfirst($u->role)); ?></span></td>
                    <td style="display:flex;gap:8px;">
                        <a href="<?php echo e(route('admin.users.edit', $u)); ?>" class="btn btn-outline btn-sm" style="border-color:var(--color-button);color:var(--color-button);">Edit</a>
                        <form action="<?php echo e(route('admin.users.destroy', $u)); ?>" method="POST" onsubmit="return confirm('Delete this user?');">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;"><?php echo e($users->links('vendor.pagination.booknest')); ?></div>
</div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/admin/users/index.blade.php ENDPATH**/ ?>