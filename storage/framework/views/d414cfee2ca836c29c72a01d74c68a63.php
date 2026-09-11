<?php $__env->startSection('title', 'BookNest — Find Your Next Great Book'); ?>

<?php $__env->startSection('content'); ?>
<section class="hero">
    <div class="container hero-grid">
        <div>
            <div class="hero-eyebrow">Welcome to our library</div>
            <h1 class="hero-heading">Find Your Next<br>Great Book</h1>
            <p class="hero-desc">Explore thousands of books, discover new interests, and build your own reading journey.</p>
            <div class="hero-actions">
                <a href="<?php echo e(route('books.index')); ?>" class="btn btn-primary">Browse Books</a>
                <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-secondary">Learn More</a>
            </div>
        </div>
        <div class="hero-image-wrap">
            <img src="<?php echo e(asset('images/hero.jpg')); ?>" alt="Library shelves">
        </div>
    </div>
</section>

<section class="section" id="about">
    <div class="container">
        <div class="feature-grid">
            <div class="feature-item">
                <div class="feature-icon">📚</div>
                <h3>Wide Selection</h3>
                <p>Access a vast collection of books in various genres.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">⏱️</div>
                <h3>Easy Borrowing</h3>
                <p>Simple and fast book borrowing process.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🔒</div>
                <h3>Secure & Reliable</h3>
                <p>Your information is always safe with us.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🤝</div>
                <h3>A Better Community</h3>
                <p>Join a community of book lovers.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>Latest Books</h2>
            <a href="<?php echo e(route('books.index')); ?>" class="view-all">View All <span class="link-chevron" aria-hidden="true">›</span></a>
        </div>
        <div class="book-grid">
            <?php $__empty_1 = true; $__currentLoopData = $latestBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if (isset($component)) { $__componentOriginalb2ad6158e46176d6a9dc77a41399ede1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb2ad6158e46176d6a9dc77a41399ede1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.book-card','data' => ['book' => $book]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('book-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['book' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($book)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb2ad6158e46176d6a9dc77a41399ede1)): ?>
<?php $attributes = $__attributesOriginalb2ad6158e46176d6a9dc77a41399ede1; ?>
<?php unset($__attributesOriginalb2ad6158e46176d6a9dc77a41399ede1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb2ad6158e46176d6a9dc77a41399ede1)): ?>
<?php $component = $__componentOriginalb2ad6158e46176d6a9dc77a41399ede1; ?>
<?php unset($__componentOriginalb2ad6158e46176d6a9dc77a41399ede1); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p style="color:var(--color-text-muted);">No books yet — run the database seeder to populate the catalog.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head"><h2>Browse Categories</h2></div>
        <div class="feature-grid">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('categories.show', $category)); ?>" class="card card-pad">
                    <strong><?php echo e($category->name); ?></strong>
                    <p class="book-author" style="margin-top:6px;"><?php echo e($category->books_count); ?> books</p>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/home.blade.php ENDPATH**/ ?>