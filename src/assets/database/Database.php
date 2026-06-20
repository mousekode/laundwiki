<?php
// src/assets/database/Database.php
// Safe and secure PDO database connection manager.

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        // Load secure configurations
        $config = require __DIR__ . '/../../configs/db.php';
        $this->host = $config['host'] ?? 'localhost';
        $this->db_name = $config['db_name'] ?? 'db_laundwiki';
        $this->username = $config['username'] ?? 'root';
        $this->password = $config['password'] ?? '';
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            // For development we print the message nicely. In production, we'd log it.
            echo "<div style='background: rgba(239, 68, 68, 0.1); border: 1px solid rgb(239, 68, 68); color: rgb(239, 68, 68); padding: 1rem; margin: 1rem; border-radius: 8px; font-family: sans-serif;'>";
            echo "<strong>Database Connection Failed:</strong> " . htmlspecialchars($exception->getMessage()) . "<br>";
            echo "Please make sure your MySQL server is running and the database <code>" . htmlspecialchars($this->db_name) . "</code> is created by importing <code>src/assets/database/uas_laundry.sql</code>.";
            echo "</div>";
            exit();
        }
        return $this->conn;
    }
}
?>
