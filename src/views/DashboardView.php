<?php
// src/views/DashboardView.php
// Dashboard View - Stats and Overview
$totalPelanggan = $pelangganObj->count();
$totalLayanan = $layananObj->count();
$totalPaket = $paketObj->count();
$totalTransaksi = $transaksiObj->count();
$revenue = $transaksiObj->sumRevenue();

$antreCount = $transaksiObj->countByStatus('Antre');
$cuciCount = $transaksiObj->countByStatus('Dicuci');
$selesaiCount = $transaksiObj->countByStatus('Selesai');

$recentOrders = array_slice($transaksiObj->readAll(), 0, 5); // Show latest 5 orders
?>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm bg-white">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="fs-1 p-3 bg-success bg-opacity-10 text-success rounded-circle">💰</div>
                <div>
                    <h6 class="text-muted small mb-1 fw-bold">Pendapatan (Lunas)</h6>
                    <div class="fs-5 fw-bold text-dark">Rp <?= number_format($revenue, 0, ',', '.') ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm bg-white">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="fs-1 p-3 bg-primary bg-opacity-10 text-primary rounded-circle">📦</div>
                <div>
                    <h6 class="text-muted small mb-1 fw-bold">Total Transaksi</h6>
                    <div class="fs-5 fw-bold text-dark"><?= $totalTransaksi ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm bg-white">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="fs-1 p-3 bg-info bg-opacity-10 text-info rounded-circle">👥</div>
                <div>
                    <h6 class="text-muted small mb-1 fw-bold">Total Pelanggan</h6>
                    <div class="fs-5 fw-bold text-dark"><?= $totalPelanggan ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card h-100 border-0 shadow-sm bg-white">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="fs-1 p-3 bg-warning bg-opacity-10 text-warning rounded-circle">⏳</div>
                <div>
                    <h6 class="text-muted small mb-1 fw-bold">Antrean Aktif</h6>
                    <div class="fs-5 fw-bold text-dark"><?= ($antreCount + $cuciCount) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title text-dark mb-3 fw-bold">Status Antrean Laundry</h5>
        <div class="row g-2">
            <div class="col-md-4">
                <div class="text-center p-3 bg-warning bg-opacity-10 border border-warning rounded">
                    <div class="text-warning text-uppercase small fw-bold">Antre</div>
                    <div class="h3 fw-bold text-warning mt-1"><?= $antreCount ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 bg-info bg-opacity-10 border border-info rounded">
                    <div class="text-info text-uppercase small fw-bold">Dicuci</div>
                    <div class="h3 fw-bold text-info mt-1"><?= $cuciCount ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-3 bg-success bg-opacity-10 border border-success rounded">
                    <div class="text-success text-uppercase small fw-bold">Selesai</div>
                    <div class="h3 fw-bold text-success mt-1"><?= $selesaiCount ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold text-dark">Transaksi Terbaru</h5>
        <a href="<?= routeUrl('/transaksi') ?>" class="btn btn-outline-secondary btn-sm rounded">Lihat Semua Transaksi</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-3">Nota / Pelanggan</th>
                        <th>Layanan & Paket</th>
                        <th>Berat</th>
                        <th>Total Harga</th>
                        <th>Tgl Masuk</th>
                        <th>Estimasi Selesai</th>
                        <th>Status</th>
                        <th class="px-3">Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada transaksi terekam.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentOrders as $order): 
                            // Mapping class badge berdasarkan status
                            $trxBadge = $order['status_transaksi'] === 'Antre' ? 'warning text-dark' : ($order['status_transaksi'] === 'Dicuci' ? 'info text-dark' : 'success');
                            $payBadge = $order['status_pembayaran'] === 'Lunas' ? 'success' : 'danger';
                        ?>
                            <tr>
                                <td class="px-3">
                                    <strong class="text-primary">#TRX-<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></strong><br>
                                    <small class="text-dark fw-semibold"><?= htmlspecialchars($order['nama_pelanggan']) ?></small>
                                </td>
                                <td>
                                    <span><?= htmlspecialchars($order['nama_layanan']) ?></span><br>
                                    <small class="text-muted">Paket: <?= htmlspecialchars($order['nama_paket']) ?></small>
                                </td>
                                <td class="fw-semibold"><?= number_format($order['berat'], 2) ?> kg</td>
                                <td class="text-dark fw-bold">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                                <td><?= date('d-m-Y', strtotime($order['tanggal_masuk'])) ?></td>
                                <td><?= date('d-m-Y', strtotime($order['tanggal_selesai'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $trxBadge ?>">
                                        <?= htmlspecialchars($order['status_transaksi']) ?>
                                    </span>
                                </td>
                                <td class="px-3">
                                    <span class="badge bg-<?= $payBadge ?>">
                                        <?= htmlspecialchars($order['status_pembayaran']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
