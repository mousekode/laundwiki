<?php
// src/views/LayananView.php
// Layanan View - Handles List, Add, and Edit Form (Integrated Modal Version)
$isEdit = false;
$editData = null;

if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $editData = $layananObj->readOne($editId);
    if ($editData) {
        $isEdit = true;
    }
}

// Fetch list of layanan
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($searchKeyword !== '') {
    $layananList = $layananObj->search($searchKeyword);
} else {
    $layananList = $layananObj->readAll();
}
?>

<div class="row g-4">
    <div class="col-12"> 
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <h5 class="mb-0 fw-bold text-dark">Daftar Layanan</h5>
                    
                    <div class="d-flex gap-2 align-items-center">
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text bg-light text-muted">🔍</span>
                            <input type="text" id="searchBar" class="form-control border-start-0 bg-light" placeholder="Cari layanan..." value="<?= htmlspecialchars($searchKeyword) ?>">
                        </div>
                        
                        <button type="button" class="btn btn-primary d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalLayanan">
                            <span>➕</span> Tambah Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 custom-table">
                        <thead class="table-light">
                            <tr>
                                <th class="px-3">ID</th>
                                <th>Nama Layanan</th>
                                <th>Harga Per Kg</th>
                                <th style="width: 160px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($layananList)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Tidak ada data layanan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($layananList as $l): ?>
                                    <tr>
                                        <td class="px-3 text-muted fw-semibold">#LYN-<?= str_pad($l['id'], 2, '0', STR_PAD_LEFT) ?></td>
                                        <td><strong><?= htmlspecialchars($l['nama_layanan']) ?></strong></td>
                                        <td class="fw-semibold text-dark">Rp <?= number_format($l['harga_per_kg'], 0, ',', '.') ?></td>
                                        <td style="text-align: center;">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="<?= routeUrl('/layanan?edit=' . $l['id']) ?>" class="btn btn-warning btn-sm text-dark px-2" title="Edit">
                                                    ✏️ Edit
                                                </a>
                                                <a href="<?= routeUrl('/layanan?action=delete_layanan&id=' . $l['id']) ?>" class="btn btn-danger btn-sm px-2" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini? Semua transaksi terkait akan terhapus.')" title="Hapus">
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
</div>

<div class="modal fade" id="modalLayanan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLayananLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="modalLayananLabel">
                    <?= $isEdit ? '✏️ Edit Jenis Layanan' : '➕ Tambah Layanan Baru' ?>
                </h5>
                <a href="<?= routeUrl('/layanan') ?>" class="btn-close btn-close-white" aria-label="Close" <?= !$isEdit ? 'data-bs-dismiss="modal"' : '' ?>></a>
            </div>
            
            <form action="<?= routeUrl('/layanan') ?>" method="POST" class="validated-form">
                <div class="modal-body">
                    <input type="hidden" name="action" value="<?= $isEdit ? 'update_layanan' : 'add_layanan' ?>">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" for="nama_layanan">Nama Jenis Layanan</label>
                        <input type="text" id="nama_layanan" name="nama_layanan" class="form-control" placeholder="Contoh: Cuci Setrika, Cuci Kering..." value="<?= $isEdit ? htmlspecialchars($editData['nama_layanan']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" for="harga_per_kg">Harga Tarif Per Kg (Rp)</label>
                        <input type="number" id="harga_per_kg" name="harga_per_kg" min="0" class="form-control" placeholder="Contoh: 6000" value="<?= $isEdit ? htmlspecialchars($editData['harga_per_kg']) : '' ?>" required>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <?php if ($isEdit): ?>
                        <a href="<?= routeUrl('/layanan') ?>" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Layanan' ?>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php if ($isEdit): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var elemenModal = document.getElementById('modalLayanan');
            var instanceModal = new bootstrap.Modal(elemenModal);
            instanceModal.show();
        });
    </script>
<?php endif; ?>
