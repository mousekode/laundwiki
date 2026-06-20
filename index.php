<?php
// index.php
// Main Entry Point & Router using clean OOP PHP and MVC structure.
session_start();

require_once 'helper/routing.php';
require_once 'helper/renderComponent.php';

// Load Database & Models
require_once 'src/assets/database/Database.php';
require_once 'src/assets/models/Pelanggan.php';
require_once 'src/assets/models/Layanan.php';
require_once 'src/assets/models/Paket.php';
require_once 'src/assets/models/Transaksi.php';

// Initialize Database & OOP Objects
$database = new Database();
$db = $database->getConnection();

$pelangganObj = new Pelanggan($db);
$layananObj = new Layanan($db);
$paketObj = new Paket($db);
$transaksiObj = new Transaksi($db);

// Define Routes mapping path to views
$routes = [
    '/'             => 'src/views/DashboardView.php',
    '/dashboard'    => 'src/views/DashboardView.php',
    '/pelanggan'    => 'src/views/PelangganView.php',
    '/layanan'      => 'src/views/LayananView.php',
    '/paket'        => 'src/views/PaketView.php',
    '/transaksi'    => 'src/views/TransaksiView.php',
];

// Resolve route
$result = resolveRoute($routes, 'src/views/404.php');
$page = $result['page'];
$route = $result['route'];

// Handle Actions (POST / GET triggers)
$action = $_POST['action'] ?? $_GET['action'] ?? '';

if (!empty($action)) {
    $success = false;
    $msg = '';

    switch ($action) {
        // Pelanggan Actions
        case 'add_pelanggan':
            $success = $pelangganObj->create($_POST['nama'], $_POST['telepon'], $_POST['alamat']);
            $msg = $success ? 'Pelanggan baru berhasil didaftarkan!' : 'Gagal mendaftarkan pelanggan. Cek kembali isian formulir.';
            break;
        case 'update_pelanggan':
            $success = $pelangganObj->update($_POST['id'], $_POST['nama'], $_POST['telepon'], $_POST['alamat']);
            $msg = $success ? 'Data pelanggan berhasil diperbarui!' : 'Gagal memperbarui data pelanggan.';
            break;
        case 'delete_pelanggan':
            $success = $pelangganObj->delete($_GET['id']);
            $msg = $success ? 'Pelanggan berhasil dihapus.' : 'Gagal menghapus pelanggan.';
            break;

        // Layanan Actions
        case 'add_layanan':
            $success = $layananObj->create($_POST['nama_layanan'], $_POST['harga_per_kg']);
            $msg = $success ? 'Jenis layanan berhasil ditambahkan!' : 'Gagal menambahkan layanan.';
            break;
        case 'update_layanan':
            $success = $layananObj->update($_POST['id'], $_POST['nama_layanan'], $_POST['harga_per_kg']);
            $msg = $success ? 'Data layanan berhasil diperbarui!' : 'Gagal memperbarui layanan.';
            break;
        case 'delete_layanan':
            $success = $layananObj->delete($_GET['id']);
            $msg = $success ? 'Layanan berhasil dihapus.' : 'Gagal menghapus layanan.';
            break;

        // Paket Actions
        case 'add_paket':
            $success = $paketObj->create($_POST['nama_paket'], $_POST['biaya_tambahan'], $_POST['durasi_hari']);
            $msg = $success ? 'Paket laundry berhasil ditambahkan!' : 'Gagal menambahkan paket laundry.';
            break;
        case 'update_paket':
            $success = $paketObj->update($_POST['id'], $_POST['nama_paket'], $_POST['biaya_tambahan'], $_POST['durasi_hari']);
            $msg = $success ? 'Data paket berhasil diperbarui!' : 'Gagal memperbarui paket.';
            break;
        case 'delete_paket':
            $success = $paketObj->delete($_GET['id']);
            $msg = $success ? 'Paket berhasil dihapus.' : 'Gagal menghapus paket.';
            break;

        // Transaksi Actions
        case 'add_transaksi':
            $success = $transaksiObj->create(
                $_POST['id_pelanggan'],
                $_POST['id_layanan'],
                $_POST['id_paket'],
                $_POST['berat'],
                $_POST['tanggal_masuk'],
                $_POST['status_transaksi'],
                $_POST['status_pembayaran']
            );
            $msg = $success ? 'Order laundry berhasil dibuat!' : 'Gagal membuat transaksi baru. Periksa kelengkapan input.';
            break;
        case 'update_transaksi':
            $success = $transaksiObj->update(
                $_POST['id'],
                $_POST['id_pelanggan'],
                $_POST['id_layanan'],
                $_POST['id_paket'],
                $_POST['berat'],
                $_POST['tanggal_masuk'],
                $_POST['status_transaksi'],
                $_POST['status_pembayaran']
            );
            $msg = $success ? 'Transaksi berhasil diperbarui!' : 'Gagal memperbarui transaksi.';
            break;
        case 'delete_transaksi':
            $success = $transaksiObj->delete($_GET['id']);
            $msg = $success ? 'Transaksi laundry berhasil dihapus.' : 'Gagal menghapus transaksi.';
            break;
        case 'quick_status':
            $success = $transaksiObj->updateStatus($_POST['id'], $_POST['status_transaksi']);
            $msg = $success ? 'Status pengerjaan berhasil diperbarui!' : 'Gagal mengubah status pengerjaan.';
            break;
        case 'quick_payment':
            $success = $transaksiObj->updatePembayaran($_POST['id'], $_POST['status_pembayaran']);
            $msg = $success ? 'Status pembayaran berhasil diperbarui!' : 'Gagal mengubah status pembayaran.';
            break;
    }

    $_SESSION['flash_msg'] = $msg;
    $_SESSION['flash_type'] = $success ? 'success' : 'danger';

    // Redirect to the current clean route URL to prevent form resubmission
    header("Location: " . routeUrl($route));
    exit();
}

// Set view metadata based on route
$pageTitle = "Dashboard";
$pageDesc = "Pantau statistik pendapatan, antrean laundry, dan manajemen transaksi secara real-time.";

switch ($route) {
    case '/pelanggan':
        $pageTitle = "Data Pelanggan";
        $pageDesc = "Registrasi dan pengelolaan data master pelanggan laundry.";
        break;
    case '/layanan':
        $pageTitle = "Layanan Laundry";
        $pageDesc = "Atur variasi jenis jasa cuci/setrika serta tarif per kilogram.";
        break;
    case '/paket':
        $pageTitle = "Paket Kecepatan";
        $pageDesc = "Kelola durasi pengerjaan dan biaya tambahan paket express/regular.";
        break;
    case '/transaksi':
        $pageTitle = "Transaksi Order";
        $pageDesc = "Catat order masuk, lacak proses laundry, dan konfirmasi pembayaran.";
        break;
}

// Laman utama / Shell layout
include 'src/Layout.php';
?>