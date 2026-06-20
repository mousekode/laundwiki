<?php
// src/assets/models/Paket.php

class Paket {
    private $conn;
    private $table_name = "paket";

    public $id;
    public $nama_paket;
    public $biaya_tambahan;
    public $durasi_hari;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nama_paket = null, $biaya_tambahan = null, $durasi_hari = null) {
        if ($nama_paket !== null) {
            $this->nama_paket = trim($nama_paket);
        }
        if ($biaya_tambahan !== null) {
            $this->biaya_tambahan = $biaya_tambahan;
        }
        if ($durasi_hari !== null) {
            $this->durasi_hari = intval($durasi_hari);
        }

        $query = "INSERT INTO " . $this->table_name . " SET nama_paket=:nama_paket, biaya_tambahan=:biaya_tambahan, durasi_hari=:durasi_hari";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_paket", $this->nama_paket);
        $stmt->bindParam(":biaya_tambahan", $this->biaya_tambahan);
        $stmt->bindParam(":durasi_hari", $this->durasi_hari, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne($id = null) {
        if ($id !== null) {
            $this->id = intval($id);
        }

        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id = null, $nama_paket = null, $biaya_tambahan = null, $durasi_hari = null) {
        if ($id !== null) {
            $this->id = intval($id);
        }
        if ($nama_paket !== null) {
            $this->nama_paket = trim($nama_paket);
        }
        if ($biaya_tambahan !== null) {
            $this->biaya_tambahan = $biaya_tambahan;
        }
        if ($durasi_hari !== null) {
            $this->durasi_hari = intval($durasi_hari);
        }

        $query = "UPDATE " . $this->table_name . " SET nama_paket=:nama_paket, biaya_tambahan=:biaya_tambahan, durasi_hari=:durasi_hari WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_paket", $this->nama_paket);
        $stmt->bindParam(":biaya_tambahan", $this->biaya_tambahan);
        $stmt->bindParam(":durasi_hari", $this->durasi_hari, PDO::PARAM_INT);
        $stmt->bindParam(":id", $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id = null) {
        if ($id !== null) {
            $this->id = intval($id);
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function search($keyword) {
        $search = '%' . trim($keyword) . '%';
        $query = "SELECT * FROM " . $this->table_name . " WHERE nama_paket LIKE :keyword OR biaya_tambahan LIKE :keyword OR durasi_hari LIKE :keyword ORDER BY id DESC";
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
}
?>
