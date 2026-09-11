<?php $__env->startSection('title', 'Your Profile — BookNest'); ?>
<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="container" style="max-width:720px;">
        <h1>Your Profile</h1>
        <p class="book-author">Tell us what you like — this powers your match percentages and AI recommendations.</p>

        <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="card card-pad" style="margin-top:24px;">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Interests <span class="form-help">(comma-separated)</span></label>
                <input type="text" name="interests" class="form-control" value="<?php echo e(old('interests', implode(', ', $user->profile->interests ?? []))); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Favorite Topics</label>
                <input type="text" name="favorite_topics" class="form-control" value="<?php echo e(old('favorite_topics', implode(', ', $user->profile->favorite_topics ?? []))); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Skills</label>
                <input type="text" name="skills" class="form-control" value="<?php echo e(old('skills', implode(', ', $user->profile->skills ?? []))); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Learning Goals</label>
                <input type="text" name="learning_goals" class="form-control" value="<?php echo e(old('learning_goals', implode(', ', $user->profile->learning_goals ?? []))); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Preferred Categories</label>
                <div style="display:flex;flex-wrap:wrap;gap:10px;">
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $preferred = $user->profile->preferred_category_ids ?? []; ?>
                        <label style="display:flex;align-items:center;gap:6px;font-size:0.85rem;background:var(--color-bg);padding:8px 12px;border-radius:999px;">
                            <input type="checkbox" name="preferred_category_ids[]" value="<?php echo e($category->id); ?>" <?php if(in_array($category->id, $preferred)): echo 'checked'; endif; ?>>
                            <?php echo e($category->name); ?>

                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Profile</button>
        </form>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/user/profile.blade.php ENDPATH**/ ?>