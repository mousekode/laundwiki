<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($pageDesc ?? '') ?>">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> | CleanFlow Laundry Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= routeUrl('/src/assets/css/style.css') ?>"> 
</head>
<body>
    <div class="page">
        <aside class="sidebar-column">
            <?= renderComponent('Navbar', ['activeRoute' => $route]); ?>
        </aside>

        <div class="main-column">
            <?= renderComponent('Header', ['activeRoute' => $route]); ?>

            <div class="content-wrapper">
                <?php if (isset($_SESSION['flash_msg'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash_type'] ?> alert-dismissible fade show d-flex align-items-center shadow-sm mb-4" role="alert" style="border-radius: 8px;">
                        <div class="me-2">
                            <?= $_SESSION['flash_type'] === 'success' ? '✅' : '❌' ?>
                        </div>
                        <div>
                            <?= htmlspecialchars($_SESSION['flash_msg']) ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php 
                    unset($_SESSION['flash_msg']);
                    unset($_SESSION['flash_type']);
                    ?>
                <?php endif; ?>

                <main class="app-content">
                    <?php include $page; ?> 
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Script -->
    <script src="<?= routeUrl('/src/assets/js/app.js') ?>"></script>
</body>
</html>