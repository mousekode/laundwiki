<?php
// src/assets/components/Navbar.php
// Sidebar Navigation Component
?>
<div class="sidebar-brand">
    <div class="d-flex align-items-center mb-0 px-1">
        <div class="bg-primary text-white rounded p-2 fw-bold me-2" style="font-size: 1.1rem; line-height: 1.1;">LW</div>
        <span class="fs-5 fw-bold text-light">Laundwiki</span>
    </div>
</div>

<div class="px-3 my-3">
    <a href="<?= routeUrl('/transaksi?add=1') ?>" 
       class="btn btn-primary w-100 d-flex align-items-center justify-content-center py-2.5 px-2 gap-2" 
       style="font-weight: 500; border-radius: 8px;"
       <?= $activeRoute === '/transaksi' ? 'data-bs-toggle="modal" data-bs-target="#modalTransaksi"' : '' ?>>
        <span>🛒</span> 
        <span>Buat Pesanan Baru</span>
    </a>
</div>

<ul class="sidebar-menu">
    <li class="sidebar-menu-item">
        <a href="<?= routeUrl('/dashboard') ?>" class="<?= ($activeRoute === '/' || $activeRoute === '/dashboard') ? 'active' : '' ?>">
            <span class="me-2">📊</span> Dashboard
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?= routeUrl('/pelanggan') ?>" class="<?= $activeRoute === '/pelanggan' ? 'active' : '' ?>">
            <span class="me-2">👥</span> Pelanggan
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?= routeUrl('/layanan') ?>" class="<?= $activeRoute === '/layanan' ? 'active' : '' ?>">
            <span class="me-2">🧺</span> Layanan
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?= routeUrl('/paket') ?>" class="<?= $activeRoute === '/paket' ? 'active' : '' ?>">
            <span class="me-2">⚡</span> Paket
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a href="<?= routeUrl('/transaksi') ?>" class="<?= $activeRoute === '/transaksi' ? 'active' : '' ?>">
            <span class="me-2">🛒</span> Transaksi Laundry
        </a>
    </li>
</ul>

<div class="sidebar-footer mt-auto">
    <p class="mb-0">&copy; 2026 LaundryWiki</p>
    <p class="mb-0" style="font-size: 0.7rem; opacity: 0.5;">UAS Pemrograman Web</p>
</div>
