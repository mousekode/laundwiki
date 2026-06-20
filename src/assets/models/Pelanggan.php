<?php
// src/assets/models/Pelanggan.php

class Pelanggan {
    private $conn;
    private $table_name = "pelanggan";

    public $id;
    public $nama;
    public $telepon;
    public $alamat;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nama = null, $telepon = null, $alamat = null) {
        if ($nama !== null) {
            $this->nama = trim($nama);
        }
        if ($telepon !== null) {
            $this->telepon = trim($telepon);
        }
        if ($alamat !== null) {
            $this->alamat = trim($alamat);
        }

        $query = "INSERT INTO " . $this->table_name . " SET nama=:nama, telepon=:telepon, alamat=:alamat";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":telepon", $this->telepon);
        $stmt->bindParam(":alamat", $this->alamat);

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

    public function update($id = null, $nama = null, $telepon = null, $alamat = null) {
        if ($id !== null) {
            $this->id = intval($id);
        }
        if ($nama !== null) {
            $this->nama = trim($nama);
        }
        if ($telepon !== null) {
            $this->telepon = trim($telepon);
        }
        if ($alamat !== null) {
            $this->alamat = trim($alamat);
        }

        $query = "UPDATE " . $this->table_name . " SET nama=:nama, telepon=:telepon, alamat=:alamat WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nama", $this->nama);
        $stmt->bindParam(":telepon", $this->telepon);
        $stmt->bindParam(":alamat", $this->alamat);
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
        $query = "SELECT * FROM " . $this->table_name . " WHERE nama LIKE :keyword OR telepon LIKE :keyword OR alamat LIKE :keyword ORDER BY id DESC";
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
