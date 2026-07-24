<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Dokumentasi Aset' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
        }

        .sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background-color: #212529;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            padding: 12px 20px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
    </style>
</head>

<body>

    <?php $role = session()->get('role'); ?>

    <div class="d-flex">
        <!-- Sidebar Navigasi Samping -->
        <div class="sidebar d-flex flex-column p-3 text-white">
            <a href="<?= base_url('asset') ?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <i class="bi bi-box-seam fs-4 me-2"></i>
                <span class="fs-5 fw-bold">Sistem Asset</span>
            </a>
            <hr>
            
            <ul class="nav nav-pills flex-column mb-auto">
                <!-- SEMUA ROLE (Admin, Staff, Viewer) BISA AKSES -->
                <li class="nav-item">
                    <a href="<?= base_url('asset') ?>" class="nav-link <?= (uri_string() == 'asset' || uri_string() == '') ? 'active' : '' ?>">
                        <i class="bi bi-table me-2"></i> Daftar Aset
                    </a>
                </li>

                <!-- KHUSUS ROLE ADMIN -->
                <?php if ($role === 'admin') : ?>
                    <li class="nav-header text-uppercase text-muted px-3 mt-3 mb-1 style-small" style="font-size: 0.75rem;">Master Data</li>
                    <li>
                        <a href="<?= base_url('master/categories') ?>" class="nav-link <?= strpos(uri_string(), 'master/categories') !== false ? 'active' : '' ?>">
                            <i class="bi bi-tags me-2"></i> Master Kategori
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('master/components') ?>" class="nav-link <?= strpos(uri_string(), 'master/components') !== false ? 'active' : '' ?>">
                            <i class="bi bi-cpu me-2"></i> Master Komponen
                        </a>
                    </li>

                    <li class="nav-header text-uppercase text-muted px-3 mt-3 mb-1 style-small" style="font-size: 0.75rem;">Pengaturan</li>
                    <li>
                        <a href="<?= base_url('users') ?>" class="nav-link <?= strpos(uri_string(), 'users') !== false ? 'active' : '' ?>">
                            <i class="bi bi-people me-2"></i> Management User
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <hr>
            
            <!-- User Info & Dropdown Logout -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <div class="d-flex flex-column">
                        <strong><?= session()->get('nama') ?? session()->get('username') ?? 'User' ?></strong>
                        <small class="text-capitalize text-muted" style="font-size: 0.75rem;">
                            <span class="badge bg-secondary"><?= esc($role ?? 'Guest') ?></span>
                        </small>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                    <li>
                        <form action="<?= base_url('logout') ?>" method="POST" id="logout-form">
                            <?= csrf_field() ?>
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Sign out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="flex-grow-1 p-4 bg-light">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>