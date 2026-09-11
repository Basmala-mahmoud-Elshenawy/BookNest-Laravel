<?php $__env->startSection('title', 'Dashboard — BookNest'); ?>
<?php $__env->startSection('content'); ?>
<section class="section"><div class="container">
<h1>Welcome back, <?php echo e(auth()->user()->name); ?></h1><p class="book-author">Your personal library at a glance.</p>
<div class="stat-grid" style="margin-top:24px;">
<div class="card stat-card"><div class="stat-label">Current Loans</div><div class="stat-value"><?php echo e($currentBorrowings->count()); ?></div></div>
<div class="card stat-card"><div class="stat-label">Overdue</div><div class="stat-value"><?php echo e($overdueCount); ?></div></div>
<div class="card stat-card"><div class="stat-label">Favorites</div><div class="stat-value"><?php echo e($favoritesCount); ?></div></div>
</div>
<div class="section-head" style="margin-top:40px;"><h2>Current Borrowings</h2><a href="<?php echo e(route('borrowings.index')); ?>" class="view-all">View history <span class="link-chevron" aria-hidden="true">›</span></a></div>
<div class="card"><table class="data-table"><thead><tr><th>Book</th><th>Due</th><th>Status</th><th></th></tr></thead><tbody>
<?php $__empty_1 = true; $__currentLoopData = $currentBorrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($b->book?->title ?? 'Book removed'); ?></td><td><?php echo e($b->due_at?->format('Y-m-d') ?? '—'); ?></td><td><span class="badge badge-<?php echo e($b->status); ?>"><?php echo e(ucfirst($b->status)); ?></span></td><td><form method="POST" action="<?php echo e(route('borrowings.return',$b)); ?>"><?php echo csrf_field(); ?><button class="btn btn-secondary btn-sm">Return</button></form></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="4">No current loans. <a class="view-all" href="<?php echo e(route('books.index')); ?>">Browse books <span class="link-chevron" aria-hidden="true">›</span></a></td></tr><?php endif; ?>
</tbody></table></div>
<div class="section-head" style="margin-top:40px;"><h2>Recommended For You</h2><a href="<?php echo e(route('recommendations.index')); ?>" class="view-all">See all <span class="link-chevron" aria-hidden="true">›</span></a></div>
<div class="book-grid"><?php $__empty_1 = true; $__currentLoopData = $recommended; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if (isset($component)) { $__componentOriginalb2ad6158e46176d6a9dc77a41399ede1 = $component; } ?>
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
<?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="card card-pad">Fill in your <a class="view-all" href="<?php echo e(route('profile.edit')); ?>">profile</a> to get personalized matches.</div><?php endif; ?></div>
<div class="section-head" style="margin-top:40px;"><h2>Recent Borrowing History</h2></div>
<div class="card"><table class="data-table"><thead><tr><th>Book</th><th>Borrowed</th><th>Returned</th><th>Status</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $recentBorrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><?php echo e($b->book?->title ?? 'Book removed'); ?></td><td><?php echo e($b->borrowed_at?->format('Y-m-d')); ?></td><td><?php echo e($b->returned_at?->format('Y-m-d')??'—'); ?></td><td><span class="badge badge-<?php echo e($b->status); ?>"><?php echo e(ucfirst($b->status)); ?></span></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="4">No history yet.</td></tr><?php endif; ?></tbody></table></div>
<div class="quick-links" style="margin-top:32px;"><a class="btn btn-secondary" href="<?php echo e(route('favorites.index')); ?>">♥ My Favorites</a><a class="btn btn-secondary" href="<?php echo e(route('profile.edit')); ?>">Profile</a><a class="btn btn-primary" href="<?php echo e(route('chatbot.show')); ?>">Open Chatbot</a></div>
</div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/user/dashboard.blade.php ENDPATH**/ ?>