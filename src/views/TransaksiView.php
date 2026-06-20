<?php
// src/views/TransaksiView.php
// Transaksi View - Floating Modal Style with Separate Add Button
$isEdit = false;
$editData = null;

if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $editData = $transaksiObj->readOne($editId);
    if ($editData) {
        $isEdit = true;
    }
}

// Cek apakah mode modal tambah aktif
$isAdd = isset($_GET['add']);

// Fetch dependencies data master
$customers = $pelangganObj->readAll();
$services = $layananObj->readAll();
$packages = $paketObj->readAll();

// Fetch listing riwayat transaksi
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($searchKeyword !== '') {
    $ordersList = $transaksiObj->search($searchKeyword);
} else {
    $ordersList = $transaksiObj->readAll();
}
?>

<style>
/* Overlay latar belakang gelap transparan */
.modal-overlay-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.6); /* Warna backdrop gelap modern */
    backdrop-filter: blur(4px); /* Efek blur kaca halus */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.25s ease-in-out;
}

/* Tampilkan modal jika parameter &add atau &edit aktif */
.modal-overlay-custom.active-modal {
    opacity: 1;
    pointer-events: auto;
}

/* Box form utama di dalam modal */
.modal-box-custom {
    background: #ffffff;
    border-radius: 14px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.05);
    width: 92%;
    max-width: 700px;
    max-height: 85vh;
    overflow-y: auto;
    transform: translateY(-20px);
    transition: transform 0.25s ease-in-out;
}

.modal-overlay-custom.active-modal .modal-box-custom {
    transform: translateY(0);
}

/* Header di dalam Modal */
.modal-header-custom {
    padding: 1.25rem 1.5rem;
    background: #1e293b;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top-left-radius: 14px;
    border-top-right-radius: 14px;
}

.modal-header-custom h5 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 600;
}

.modal-close-custom {
    background: none;
    border: none;
    color: #94a3b8;
    font-size: 1.6rem;
    cursor: pointer;
    text-decoration: none;
    line-height: 1;
}
.modal-close-custom:hover {
    color: #ffffff;
}

/* Area Form Konten */
.modal-body-custom {
    padding: 1.5rem;
}

/* Footer Modal */
.modal-footer-custom {
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    border-bottom-left-radius: 14px;
    border-bottom-right-radius: 14px;
}

.badge {
    padding: 6px 12px;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
    display: inline-block;
    text-align: center;
}

/* Warna Status Laundry */
.badge-antre {
    background-color: #ffc107 !important; /* Kuning Amber */
    color: #1e293b !important;
}
.badge-dicuci {
    background-color: #0dcaf0 !important; /* Cyan Cerah */
    color: #ffffff !important;
}
.badge-selesai {
    background-color: #198754 !important; /* Hijau Sukses */
    color: #ffffff !important;
}

/* Warna Status Pembayaran */
.badge-lunas {
    background-color: #198754 !important; /* Hijau */
    color: #ffffff !important;
}
.badge-belum-bayar {
    background-color: #dc3545 !important; /* Merah */
    color: #ffffff !important;
}

/* Custom display area details */
.total-display-card {
    background-color: rgba(13, 110, 253, 0.08);
    border: 1px solid rgba(13, 110, 253, 0.15);
    border-radius: 0.75rem;
    padding: 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.total-display-label {
    font-weight: 700;
    color: #1e293b;
    font-size: 0.95rem;
}
.total-display-detail {
    font-size: 0.8rem;
    color: #64748b;
}
.total-display-value {
    font-size: 1.75rem;
    font-weight: 800;
    color: #0d6efd;
}
</style>

<div class="d-flex flex-column gap-3">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
        <div>
            <a href="<?= routeUrl('/transaksi?add=true') ?>" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <span>➕</span> Tambah Transaksi Baru
            </a>
        </div>
    </div>

    <div class="modal-overlay-custom <?= ($isAdd || $isEdit) ? 'active-modal' : '' ?>">
        <div class="modal-box-custom">
            
            <div class="modal-header-custom">
                <h5><?= $isEdit ? '✏️ Edit Transaksi Laundry' : '🛒 Buat Transaksi Baru' ?></h5>
                <a href="<?= routeUrl('/transaksi') ?>" class="modal-close-custom" title="Tutup">&times;</a>
            </div>

            <form action="<?= routeUrl('/transaksi') ?>" method="POST" id="orderForm" class="validated-form">
                <div class="modal-body-custom">
                    <input type="hidden" name="action" value="<?= $isEdit ? 'update_transaksi' : 'add_transaksi' ?>">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="id_pelanggan">Pelanggan</label>
                            <select id="id_pelanggan" name="id_pelanggan" class="form-select" required>
                                <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>Pilih Pelanggan...</option>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?= $c['id'] ?>" <?= ($isEdit && $editData['id_pelanggan'] == $c['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nama']) ?> (<?= htmlspecialchars($c['telepon']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="id_layanan">Jenis Layanan</label>
                            <select id="id_layanan" name="id_layanan" class="form-select" required>
                                <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>Pilih Layanan...</option>
                                <?php foreach ($services as $s): ?>
                                    <option value="<?= $s['id'] ?>" data-harga="<?= $s['harga_per_kg'] ?>" <?= ($isEdit && $editData['id_layanan'] == $s['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['nama_layanan']) ?> - Rp <?= number_format($s['harga_per_kg'], 0, ',', '.') ?>/kg
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="id_paket">Pilihan Paket Kecepatan</label>
                            <select id="id_paket" name="id_paket" class="form-select" required>
                                <option value="" disabled <?= !$isEdit ? 'selected' : '' ?>>Pilih Paket...</option>
                                <?php foreach ($packages as $pk): ?>
                                    <option value="<?= $pk['id'] ?>" data-tambahan="<?= $pk['biaya_tambahan'] ?>" data-durasi="<?= $pk['durasi_hari'] ?>" <?= ($isEdit && $editData['id_paket'] == $pk['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pk['nama_paket']) ?> (+Rp <?= number_format($pk['biaya_tambahan'], 0, ',', '.') ?>) [<?= $pk['durasi_hari'] ?> hari]
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="berat">Berat Pakaian (Kg)</label>
                            <input type="number" id="berat" name="berat" min="0.1" step="0.01" class="form-control" placeholder="Masukkan berat..." value="<?= $isEdit ? htmlspecialchars($editData['berat']) : '' ?>" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="tanggal_masuk">Tanggal Masuk</label>
                            <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control" value="<?= $isEdit ? htmlspecialchars($editData['tanggal_masuk']) : date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="status_transaksi">Status Transaksi</label>
                            <select id="status_transaksi" name="status_transaksi" class="form-select" required>
                                <option value="Antre" <?= ($isEdit && $editData['status_transaksi'] === 'Antre') ? 'selected' : '' ?>>Antre</option>
                                <option value="Dicuci" <?= ($isEdit && $editData['status_transaksi'] === 'Dicuci') ? 'selected' : '' ?>>Dicuci</option>
                                <option value="Selesai" <?= ($isEdit && $editData['status_transaksi'] === 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="status_pembayaran">Status Pembayaran</label>
                            <select id="status_pembayaran" name="status_pembayaran" class="form-select" required>
                                <option value="Belum Bayar" <?= ($isEdit && $editData['status_pembayaran'] === 'Belum Bayar') ? 'selected' : '' ?>>Belum Bayar</option>
                                <option value="Lunas" <?= ($isEdit && $editData['status_pembayaran'] === 'Lunas') ? 'selected' : '' ?>>Lunas</option>
                            </select>
                        </div>
                    </div>

                    <div class="total-display-card my-3">
                        <div>
                            <div class="total-display-label">Estimasi Total Biaya</div>
                            <div id="calcDetails" class="total-display-detail">Masukkan detail order untuk menghitung otomatis...</div>
                            <div style="font-size: 0.8rem; margin-top: 0.4rem; color: #6c757d;">
                                Estimasi Selesai: <strong id="dateDisplay" style="color: #0d6efd;">-</strong>
                            </div>
                        </div>
                        <div id="priceDisplay" class="total-display-value">Rp 0</div>
                    </div>

                </div>
                <div class="modal-footer-custom">
                    <a href="<?= routeUrl('/transaksi') ?>" class="btn btn-secondary" style="text-decoration: none;">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <?= $isEdit ? 'Simpan Perubahan' : 'Proses Order' ?>
                    </button>
                </div>
            </form>

        </div>
    </div>


    <div class="card">
        <div class="card-header bg-white py-3 border-bottom border-light">
            <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                <h5 class="mb-0 fw-bold text-dark">Riwayat Transaksi</h5>
                
                <div class="d-flex gap-2 align-items-center">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text bg-light text-muted">🔍</span>
                        <input type="text" id="searchBar" class="form-control border-start-0 bg-light" placeholder="Cari transaksi..." value="<?= htmlspecialchars($searchKeyword) ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="table-light">
                        <tr>
                            <th class="px-3">Nota / Pelanggan</th>
                            <th>Layanan & Paket</th>
                            <th>Berat</th>
                            <th>Total Harga</th>
                            <th>Tgl Masuk</th>
                            <th>Estimasi Selesai</th>
                            <th style="width: 130px;">Status Laundry</th>
                            <th style="width: 130px;">Status Bayar</th>
                            <th style="width: 150px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($ordersList)): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Tidak ada riwayat transaksi.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ordersList as $o): ?>
                                <tr>
                                    <td class="px-3">
                                        <strong>#TRX-<?= str_pad($o['id'], 4, '0', STR_PAD_LEFT) ?></strong><br>
                                        <span class="small text-dark fw-semibold"><?= htmlspecialchars($o['nama_pelanggan']) ?></span>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($o['nama_layanan']) ?><br>
                                        <small class="text-muted">Paket: <?= htmlspecialchars($o['nama_paket']) ?></small>
                                    </td>
                                    <td class="fw-semibold text-dark"><?= number_format($o['berat'], 2) ?> kg</td>
                                    <td class="fw-bold text-dark">Rp <?= number_format($o['total_harga'], 0, ',', '.') ?></td>
                                    <td><?= date('d-m-Y', strtotime($o['tanggal_masuk'])) ?></td>
                                    <td><?= date('d-m-Y', strtotime($o['tanggal_selesai'])) ?></td>
                                    <td>
                                        <span class="badge badge-<?= strtolower($o['status_transaksi']) ?>">
                                            <?= htmlspecialchars($o['status_transaksi']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?= $o['status_pembayaran'] === 'Lunas' ? 'lunas' : 'belum-bayar' ?>">
                                            <?= htmlspecialchars($o['status_pembayaran']) ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <a href="<?= routeUrl('/transaksi?edit=' . $o['id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                ✏️ Edit
                                            </a>
                                            <a href="<?= routeUrl('/transaksi?action=delete_transaksi&id=' . $o['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')" title="Hapus">
                                                🗑️ Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
