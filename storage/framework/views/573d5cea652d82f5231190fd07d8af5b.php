<?php if($errors->any()): ?>
    <div class="form-error" style="margin-bottom:16px;"><?php echo e($errors->first()); ?></div>
<?php endif; ?>
<div class="form-group">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name ?? '')); ?>" required>
</div>
<div class="form-group">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email ?? '')); ?>" required>
</div>
<div class="form-group">
    <label class="form-label">Role</label>
    <select name="role" class="form-control">
        <option value="user" <?php if(old('role', $user->role ?? 'user') === 'user'): echo 'selected'; endif; ?>>User</option>
        <option value="admin" <?php if(old('role', $user->role ?? '') === 'admin'): echo 'selected'; endif; ?>>Admin</option>
    </select>
</div>
<div class="form-group">
    <label class="form-label">Password <?php if(isset($user)): ?><span class="form-help">(leave blank to keep current)</span><?php endif; ?></label>
    <input type="password" name="password" class="form-control" <?php echo e(isset($user) ? '' : 'required'); ?>>
</div>
<?php /**PATH C:\Users\BASMALA\OneDrive\Desktop\BookNest-Final-RuleBased-AI\booknest\resources\views/admin/users/_form.blade.php ENDPATH**/ ?>