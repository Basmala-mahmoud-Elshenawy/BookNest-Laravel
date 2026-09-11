<?php if($errors->any()): ?>
    <div class="form-error" style="margin-bottom:16px;"><?php echo e($errors->first()); ?></div>
<?php endif; ?>
<div class="form-group">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $category->name ?? '')); ?>" required>
</div>
<div class="form-group">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $category->description ?? '')); ?></textarea>
</div>
<?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/admin/categories/_form.blade.php ENDPATH**/ ?>