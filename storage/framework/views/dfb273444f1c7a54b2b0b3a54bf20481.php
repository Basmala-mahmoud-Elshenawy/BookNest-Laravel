<?php $__env->startSection('title', 'Edit User — BookNest'); ?>
<?php $__env->startSection('content'); ?>
<section class="section"><div class="container" style="max-width:520px;">
    <h1>Edit User</h1>
    <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>" class="card card-pad" style="margin-top:20px;">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <?php echo $__env->make('admin.users._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button class="btn btn-primary">Save Changes</button>
    </form>
</div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>