<?php
// src/assets/models/Layanan.php

class Layanan {
    private $conn;
    private $table_name = "layanan";

    public $id;
    public $nama_layanan;
    public $harga_per_kg;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nama_layanan = null, $harga_per_kg = null) {
        if ($nama_layanan !== null) {
            $this->nama_layanan = trim($nama_layanan);
        }
        if ($harga_per_kg !== null) {
            $this->harga_per_kg = $harga_per_kg;
        }

        $query = "INSERT INTO " . $this->table_name . " SET nama_layanan=:nama_layanan, harga_per_kg=:harga_per_kg";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_layanan", $this->nama_layanan);
        $stmt->bindParam(":harga_per_kg", $this->harga_per_kg);

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

    public function update($id = null, $nama_layanan = null, $harga_per_kg = null) {
        if ($id !== null) {
            $this->id = intval($id);
        }
        if ($nama_layanan !== null) {
            $this->nama_layanan = trim($nama_layanan);
        }
        if ($harga_per_kg !== null) {
            $this->harga_per_kg = $harga_per_kg;
        }

        $query = "UPDATE " . $this->table_name . " SET nama_layanan=:nama_layanan, harga_per_kg=:harga_per_kg WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama_layanan", $this->nama_layanan);
        $stmt->bindParam(":harga_per_kg", $this->harga_per_kg);
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
        $query = "SELECT * FROM " . $this->table_name . " WHERE nama_layanan LIKE :keyword OR harga_per_kg LIKE :keyword ORDER BY id DESC";
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
