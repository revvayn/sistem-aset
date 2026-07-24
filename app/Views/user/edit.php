<?php

/**
 * @var array $user
 */
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit User</h5>
                <a href="<?= base_url('users') ?>" class="btn btn-sm btn-dark">Kembali</a>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('users/update/' . $user['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= old('nama', $user['nama'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= old('email', $user['email'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Barunya (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                        <small class="text-muted">Biarkan kosong jika password tidak diubah.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role / Hak Akses <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="staff" <?= ($user['role'] ?? '') === 'staff' ? 'selected' : '' ?>>Staff (Input & Edit Data)</option>
                            <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin (Akses Penuh)</option>
                            <option value="viewer" <?= ($user['role'] ?? '') === 'viewer' ? 'selected' : '' ?>>Viewer (Hanya Lihat Data)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 mt-3">Update Data User</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>