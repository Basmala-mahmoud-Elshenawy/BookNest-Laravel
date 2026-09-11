<?php $__env->startSection('title', 'Edit Category — BookNest'); ?>
<?php $__env->startSection('content'); ?>
<section class="section"><div class="container" style="max-width:520px;">
    <h1>Edit Category</h1>
    <form method="POST" action="<?php echo e(route('admin.categories.update', $category)); ?>" class="card card-pad" style="margin-top:20px;">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <?php echo $__env->make('admin.categories._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button class="btn btn-primary">Save Changes</button>
    </form>
</div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/admin/categories/edit.blade.php ENDPATH**/ ?>