<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Management User</h2>
    <a href="<?= base_url('users/create') ?>" class="btn btn-primary">+ Tambah User Baru</a>
</div>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Role / Hak Akses</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php $i = 1;
                        foreach ($users as $u) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><strong><?= esc($u['nama']) ?></strong></td>
                                <td><?= esc($u['email']) ?></td>
                                <td>
                                    <?php
                                    $badge = 'bg-secondary';
                                    if ($u['role'] === 'admin') $badge = 'bg-danger';
                                    if ($u['role'] === 'staff') $badge = 'bg-primary';
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= strtoupper(esc($u['role'])) ?></span>
                                </td>
                                <td class="text-center">
                                    <!-- Tombol Edit -->
                                    <a href="<?= base_url('users/edit/' . $u['id']) ?>" class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <?php if (session()->get('id') != $u['id']) : ?>
                                        <a href="<?= base_url('users/delete/' . $u['id']) ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Yakin ingin menghapus user ini?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark">Akun Saya</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada user terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>