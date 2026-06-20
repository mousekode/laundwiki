<?php
// src/views/404.php
// 404 Not Found View
?>
<div class="text-center py-5">
    <div class="display-1 text-muted fw-bold mb-3">404</div>
    <h3 class="fw-bold text-dark">Halaman Tidak Ditemukan</h3>
    <p class="text-muted mb-4">Maaf, halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan.</p>
    <a href="<?= routeUrl('/dashboard') ?>" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
        Kembali ke Dashboard
    </a>
</div>
