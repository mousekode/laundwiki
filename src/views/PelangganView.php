<?php
// src/views/PelangganView.php
// Pelanggan View - Handles List, Add, and Edit Form (Integrated Modal Version)
$isEdit = false;
$editData = null;

if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $editData = $pelangganObj->readOne($editId);
    if ($editData) {
        $isEdit = true;
    }
}

// Fetch list of pelanggan
$searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($searchKeyword !== '') {
    $pelangganList = $pelangganObj->search($searchKeyword);
} else {
    $pelangganList = $pelangganObj->readAll();
}
?>

<div class="row g-4">
    <div class="col-12"> 
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                    <h5 class="mb-0 fw-bold text-dark">Daftar Pelanggan</h5>
                    
                    <div class="d-flex gap-2 align-items-center">
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text bg-light text-muted">🔍</span>
                            <input type="text" id="searchBar" class="form-control border-start-0 bg-light" placeholder="Cari pelanggan..." value="<?= htmlspecialchars($searchKeyword) ?>">
                        </div>
                        
                        <button type="button" class="btn btn-primary d-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPelanggan">
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
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th style="width: 160px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pelangganList)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data pelanggan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pelangganList as $p): ?>
                                    <tr>
                                        <td class="px-3 text-muted fw-semibold">#PLG-<?= str_pad($p['id'], 3, '0', STR_PAD_LEFT) ?></td>
                                        <td><strong><?= htmlspecialchars($p['nama']) ?></strong></td>
                                        <td><?= htmlspecialchars($p['telepon']) ?></td>
                                        <td><?= htmlspecialchars($p['alamat']) ?></td>
                                        <td style="text-align: center;">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="<?= routeUrl('/pelanggan?edit=' . $p['id']) ?>" class="btn btn-warning btn-sm text-dark px-2" title="Edit">
                                                    ✏️ Edit
                                                </a>
                                                <a href="<?= routeUrl('/pelanggan?action=delete_pelanggan&id=' . $p['id']) ?>" class="btn btn-danger btn-sm px-2" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini? Semua transaksi terkait akan terhapus.')" title="Hapus">
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

<div class="modal fade" id="modalPelanggan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalPelangganLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="modalPelangganLabel">
                    <?= $isEdit ? '✏️ Edit Data Pelanggan' : '➕ Tambah Pelanggan Baru' ?>
                </h5>
                <a href="<?= routeUrl('/pelanggan') ?>" class="btn-close btn-close-white" aria-label="Close" <?= !$isEdit ? 'data-bs-dismiss="modal"' : '' ?>></a>
            </div>
            
            <form action="<?= routeUrl('/pelanggan') ?>" method="POST" class="validated-form">
                <div class="modal-body">
                    <input type="hidden" name="action" value="<?= $isEdit ? 'update_pelanggan' : 'add_pelanggan' ?>">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama pelanggan..." value="<?= $isEdit ? htmlspecialchars($editData['nama']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" for="telepon">Nomor Telepon</label>
                        <input type="tel" id="telepon" name="telepon" class="form-control" placeholder="Contoh: 081234567890" value="<?= $isEdit ? htmlspecialchars($editData['telepon']) : '' ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" for="alamat">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap..." required><?= $isEdit ? htmlspecialchars($editData['alamat']) : '' ?></textarea>
                    </div>
                </div>
                
                <div class="modal-footer bg-light">
                    <?php if ($isEdit): ?>
                        <a href="<?= routeUrl('/pelanggan') ?>" class="btn btn-secondary">Batal</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <?= $isEdit ? 'Simpan Perubahan' : 'Daftarkan Pelanggan' ?>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php if ($isEdit): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var elemenModal = document.getElementById('modalPelanggan');
            var instanceModal = new bootstrap.Modal(elemenModal);
            instanceModal.show();
        });
    </script>
<?php endif; ?>
