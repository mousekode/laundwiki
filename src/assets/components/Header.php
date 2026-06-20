<?php
// src/assets/components/Header.php
// Header component with page title and description
global $pageTitle, $pageDesc;
?>
<header class="header-wrapper">
    <div>
        <h1 class="h2 text-dark mb-1" id="mainHeading"><?= htmlspecialchars($pageTitle) ?></h1>
        <p class="text-muted small mb-0"><?= htmlspecialchars($pageDesc) ?></p>
    </div>
    <div class="badge bg-white text-dark border p-2 fs-6 shadow-sm">
        📅 <?= date('d M Y') ?>
    </div>
</header>
