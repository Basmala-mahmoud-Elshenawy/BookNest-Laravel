<?php $__env->startSection('title', 'Manage Categories — BookNest'); ?>
<?php $__env->startSection('content'); ?>
<section class="section"><div class="container">
    <div class="section-head"><h2>Categories</h2><a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary btn-sm">+ New Category</a></div>
    <div class="card">
        <table class="data-table">
            <thead><tr><th>Name</th><th>Books</th><th></th></tr></thead>
            <tbody>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($c->name); ?></td><td><?php echo e($c->books_count); ?></td>
                    <td style="display:flex;gap:8px;">
                        <a href="<?php echo e(route('admin.categories.edit', $c)); ?>" class="btn btn-outline btn-sm" style="border-color:var(--color-button);color:var(--color-button);">Edit</a>
                        <form action="<?php echo e(route('admin.categories.destroy', $c)); ?>" method="POST" onsubmit="return confirm('Delete this category? Books will become uncategorized.');">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top:20px;"><?php echo e($categories->links()); ?></div>
</div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>