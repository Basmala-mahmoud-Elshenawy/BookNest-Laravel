<?php $__env->startSection('title', 'Categories — BookNest'); ?>
<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="container">
        <div class="section-head"><h2>Categories</h2></div>
        <div class="feature-grid">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('categories.show', $category)); ?>" class="card card-pad">
                    <strong><?php echo e($category->name); ?></strong>
                    <p class="book-author" style="margin:6px 0 0;"><?php echo e($category->books_count); ?> books</p>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/categories/index.blade.php ENDPATH**/ ?>