<?php
// src/assets/models/Transaksi.php

class Transaksi {
    private $conn;
    private $table_name = "order_laundry";

    public function __construct($db) {
        $this->conn = $db;
    }

    private function fetchLayanan($id_layanan) {
        $query = "SELECT harga_per_kg FROM layanan WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_layanan, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function fetchPaket($id_paket) {
        $query = "SELECT biaya_tambahan, durasi_hari FROM paket WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_paket, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function calculateTotalHarga($id_layanan, $id_paket, $berat) {
        $layanan = $this->fetchLayanan($id_layanan);
        $paket = $this->fetchPaket($id_paket);

        if (!$layanan || !$paket) {
            return 0;
        }

        $harga_per_kg = floatval($layanan['harga_per_kg']);
        $biaya_tambahan = floatval($paket['biaya_tambahan']);
        return ($harga_per_kg * floatval($berat)) + $biaya_tambahan;
    }

    private function calculateTanggalSelesai($tanggal_masuk, $id_paket) {
        $paket = $this->fetchPaket($id_paket);
        if (!$paket) {
            return $tanggal_masuk;
        }

        $durasi = intval($paket['durasi_hari']);
        $date = new DateTime($tanggal_masuk);
        $date->modify("+$durasi days");
        return $date->format('Y-m-d');
    }

    public function create($id_pelanggan, $id_layanan, $id_paket, $berat, $tanggal_masuk, $status_transaksi = 'Antre', $status_pembayaran = 'Belum Bayar') {
        $id_pelanggan = intval($id_pelanggan);
        $id_layanan = intval($id_layanan);
        $id_paket = intval($id_paket);
        $berat = floatval($berat);
        $tanggal_masuk = trim($tanggal_masuk);
        $status_transaksi = trim($status_transaksi) ?: 'Antre';
        $status_pembayaran = trim($status_pembayaran) ?: 'Belum Bayar';

        $total_harga = $this->calculateTotalHarga($id_layanan, $id_paket, $berat);
        $tanggal_selesai = $this->calculateTanggalSelesai($tanggal_masuk, $id_paket);

        $query = "INSERT INTO " . $this->table_name . " SET id_pelanggan=:id_pelanggan, id_layanan=:id_layanan, id_paket=:id_paket, berat=:berat, total_harga=:total_harga, tanggal_masuk=:tanggal_masuk, tanggal_selesai=:tanggal_selesai, status_transaksi=:status_transaksi, status_pembayaran=:status_pembayaran";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_pelanggan', $id_pelanggan, PDO::PARAM_INT);
        $stmt->bindParam(':id_layanan', $id_layanan, PDO::PARAM_INT);
        $stmt->bindParam(':id_paket', $id_paket, PDO::PARAM_INT);
        $stmt->bindParam(':berat', $berat);
        $stmt->bindParam(':total_harga', $total_harga);
        $stmt->bindParam(':tanggal_masuk', $tanggal_masuk);
        $stmt->bindParam(':tanggal_selesai', $tanggal_selesai);
        $stmt->bindParam(':status_transaksi', $status_transaksi);
        $stmt->bindParam(':status_pembayaran', $status_pembayaran);

        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT o.*, p.nama AS nama_pelanggan, p.telepon AS telepon_pelanggan, l.nama_layanan, l.harga_per_kg, pk.nama_paket, pk.biaya_tambahan, pk.durasi_hari FROM " . $this->table_name . " o LEFT JOIN pelanggan p ON o.id_pelanggan = p.id LEFT JOIN layanan l ON o.id_layanan = l.id LEFT JOIN paket pk ON o.id_paket = pk.id ORDER BY o.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne($id) {
        $id = intval($id);
        $query = "SELECT o.*, p.nama AS nama_pelanggan, p.telepon AS telepon_pelanggan, l.nama_layanan, l.harga_per_kg, pk.nama_paket, pk.biaya_tambahan, pk.durasi_hari FROM " . $this->table_name . " o LEFT JOIN pelanggan p ON o.id_pelanggan = p.id LEFT JOIN layanan l ON o.id_layanan = l.id LEFT JOIN paket pk ON o.id_paket = pk.id WHERE o.id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $id_pelanggan, $id_layanan, $id_paket, $berat, $tanggal_masuk, $status_transaksi, $status_pembayaran) {
        $id = intval($id);
        $id_pelanggan = intval($id_pelanggan);
        $id_layanan = intval($id_layanan);
        $id_paket = intval($id_paket);
        $berat = floatval($berat);
        $tanggal_masuk = trim($tanggal_masuk);
        $status_transaksi = trim($status_transaksi) ?: 'Antre';
        $status_pembayaran = trim($status_pembayaran) ?: 'Belum Bayar';

        $total_harga = $this->calculateTotalHarga($id_layanan, $id_paket, $berat);
        $tanggal_selesai = $this->calculateTanggalSelesai($tanggal_masuk, $id_paket);

        $query = "UPDATE " . $this->table_name . " SET id_pelanggan=:id_pelanggan, id_layanan=:id_layanan, id_paket=:id_paket, berat=:berat, total_harga=:total_harga, tanggal_masuk=:tanggal_masuk, tanggal_selesai=:tanggal_selesai, status_transaksi=:status_transaksi, status_pembayaran=:status_pembayaran WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_pelanggan', $id_pelanggan, PDO::PARAM_INT);
        $stmt->bindParam(':id_layanan', $id_layanan, PDO::PARAM_INT);
        $stmt->bindParam(':id_paket', $id_paket, PDO::PARAM_INT);
        $stmt->bindParam(':berat', $berat);
        $stmt->bindParam(':total_harga', $total_harga);
        $stmt->bindParam(':tanggal_masuk', $tanggal_masuk);
        $stmt->bindParam(':tanggal_selesai', $tanggal_selesai);
        $stmt->bindParam(':status_transaksi', $status_transaksi);
        $stmt->bindParam(':status_pembayaran', $status_pembayaran);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id) {
        $id = intval($id);
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateStatus($id, $status_transaksi) {
        $id = intval($id);
        $status_transaksi = trim($status_transaksi);

        $query = "UPDATE " . $this->table_name . " SET status_transaksi=:status_transaksi WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status_transaksi', $status_transaksi);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updatePembayaran($id, $status_pembayaran) {
        $id = intval($id);
        $status_pembayaran = trim($status_pembayaran);

        $query = "UPDATE " . $this->table_name . " SET status_pembayaran=:status_pembayaran WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status_pembayaran', $status_pembayaran);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function search($keyword) {
        $search = '%' . trim($keyword) . '%';
        $query = "SELECT o.*, p.nama AS nama_pelanggan, p.telepon AS telepon_pelanggan, l.nama_layanan, l.harga_per_kg, pk.nama_paket, pk.biaya_tambahan, pk.durasi_hari FROM " . $this->table_name . " o LEFT JOIN pelanggan p ON o.id_pelanggan = p.id LEFT JOIN layanan l ON o.id_layanan = l.id LEFT JOIN paket pk ON o.id_paket = pk.id WHERE p.nama LIKE :keyword OR p.telepon LIKE :keyword OR l.nama_layanan LIKE :keyword OR pk.nama_paket LIKE :keyword OR o.status_transaksi LIKE :keyword OR o.status_pembayaran LIKE :keyword ORDER BY o.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':keyword', $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count() {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? intval($row['total']) : 0;
    }

    public function sumRevenue() {
        $query = "SELECT SUM(total_harga) AS revenue FROM " . $this->table_name . " WHERE status_pembayaran = 'Lunas'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? floatval($row['revenue']) : 0;
    }

    public function countByStatus($status) {
        $query = "SELECT COUNT(*) AS total FROM " . $this->table_name . " WHERE status_transaksi = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? intval($row['total']) : 0;
    }
}
?>
