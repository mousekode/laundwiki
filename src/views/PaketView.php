<?php
// src/views/PaketView.php
// Paket View - Handles List, Add, and Edit Form (Integrated Modal Version)
$isEdit = false;
$editData = null;

if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $editData = $paketObj->readOne($editId);
    if ($editData) {
        $isEdit = true;
    }
}

$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($searchKeyword !== '') {
    $paketList = $paketObj->search($searchKeyword);
} else {
    $paketList = $paketObj->readAll();
}
?>

<div class="row g-4">
    <div class="col-12"> 
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <h5 class="mb-0 fw-bold text-dark">Jenis Paket Kecepatan</h5>
                    
                    <div class="d-flex gap-2 align-items-center">
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text bg-light text-muted">🔍</span>
                            <input type="text" id="searchBar" class="form-control border-start-0 bg-light" placeholder="Cari paket..." value="<?= htmlspecialchars($searchKeyword) ?>">
                        </div>
                        
                        <button type="button" class="btn btn-primary d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPaket">
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
                                <th>Nama Paket</th>
                                <th>Biaya Tambahan</th>
                                <th>Durasi Kerja</th>
                                <th style="width: 160px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($paketList)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data paket.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($paketList as $pk): ?>
                                    <tr>
                                        <td class="px-3 text-muted fw-semibold">#PKT-<?= str_pad($pk['id'], 2, '0', STR_PAD_LEFT) ?></td>
                                        <td><strong><?= htmlspecialchars($pk['nama_paket']) ?></strong></td>
                                        <td class="fw-semibold text-dark">Rp <?= number_format($pk['biaya_tambahan'], 0, ',', '.') ?></td>
                                        <td><?= $pk['durasi_hari'] ?> Hari<?= $pk['durasi_hari'] === 0 ? ' (Hari yang sama)' : '' ?></td>
                                        <td style="text-align: center;">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="<?= routeUrl('/paket?edit=' . $pk['id']) ?>" class="btn btn-warning btn-sm text-dark px-2" title="Edit">
                                                    ✏️ Edit
                                                </a>
                                                <a href="<?= routeUrl('/paket?action=delete_paket&id=' . $pk['id']) ?>" class="btn btn-danger btn-sm px-2" onclick="return confirm('Apakah Anda yakin ingin menghapus paket ini? Semua transaksi terkait akan terhapus.')" title="Hapus">
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

<div class="modal fade" id="modalPaket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalPaketLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="modalPaketLabel">
                    <?= $isEdit ? '✏️ Edit Paket Laundry' : '➕ Tambah Paket Baru' ?>
                </h5>
                <a href="<?= routeUrl('/paket') ?>" class="btn-close btn-close-white" aria-label="Close" <?= !$isEdit ? 'data-bs-dismiss="modal"' : '' ?>></a>
            </div>
            
            <form action="<?= routeUrl('/paket') ?>" method="POST" class="validated-form">
                <div class="modal-body">
                    <input type="hidden" name="action" value="<?= $isEdit ? 'update_paket' : 'add_paket' ?>">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" for="nama_paket">Nama Paket</label>
                        <input type="text" id="nama_paket" name="nama_paket" class="form-control" placeholder="Contoh: Ekspres, Reguler, Kilat..." value="<?= $isEdit ? htmlspecialchars($editData['nama_paket']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" for="biaya_tambahan">Biaya Tambahan (Rupiah)</label>
                        <input type="number" id="biaya_tambahan" name="biaya_tambahan" min="0" step="50" class="form-control" placeholder="Contoh: 3000" value="<?= $isEdit ? htmlspecialchars($editData['biaya_tambahan']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" for="durasi_hari">Durasi Selesai (Hari)</label>
                        <input type="number" id="durasi_hari" name="durasi_hari" min="0" max="30" class="form-control" placeholder="Contoh: 3 (Reguler), 1 (Ekspres), 0 (Kilat/6 Jam)" value="<?= $isEdit ? htmlspecialchars($editData['durasi_hari']) : '' ?>" required>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <?php if ($isEdit): ?>
                        <a href="<?= routeUrl('/paket') ?>" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <?= $isEdit ? 'Simpan Paket' : 'Tambahkan Paket' ?>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php if ($isEdit): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var elemenModal = document.getElementById('modalPaket');
            var instanceModal = new bootstrap.Modal(elemenModal);
            instanceModal.show();
        });
    </script>
<?php endif; ?>
