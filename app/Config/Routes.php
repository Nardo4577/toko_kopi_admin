<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->post('/loginAction', 'AuthController::loginAction');
$routes->get('/logout', 'AuthController::logout');

// Routes BARU LUPA PASSWORD
$routes->post('/validasi-lupa-password', 'AuthController::validasiLupaPassword');
$routes->post('/reset-password-action', 'AuthController::resetPasswordAction');

$routes->group('', ['filter' => 'auth'], function ($routes) {


    $routes->get('admin/dashboard', 'Admin::dashboard');
    $routes->get('admin/karyawan', 'Karyawan::karyawan');
    $routes->get('admin/meja', 'MejaController::meja');
    $routes->get('admin/stok', 'StockController::stok');
    $routes->get('admin/booking', 'ReservasiController::booking');

    // =========================================================================
    // ROUTES UNTUK KELOLA MENU (STOK)
    // =========================================================================
    $routes->get('/admin/stok', 'StockController::stok'); // Read (Menampilkan halaman)
    $routes->post('/admin/simpan_stok', 'StockController::simpan_stok'); // Create (Menyimpan data)
    $routes->post('/admin/update_stok', 'StockController::update_stok'); // Update (Mengubah data)
    $routes->post('/admin/hapus_stok', 'StockController::hapus_stok'); // Delete (Menghapus data)

    // =========================================================================
    // ROUTES UNTUK KELOLA STAFF (KARYAWAN)
    // =========================================================================
    $routes->get('/admin/karyawan', 'Karyawan::karyawan'); // Read (Menampilkan Halaman)
    $routes->post('/admin/simpan_karyawan', 'Karyawan::simpan_karyawan'); // Create
    $routes->post('/admin/update_karyawan', 'Karyawan::update_karyawan'); // Update
    $routes->post('/admin/hapus_karyawan', 'Karyawan::hapus_karyawan'); // Delete

    // =========================================================================
    // ROUTES UNTUK KELOLA MEJA
    // =========================================================================
    $routes->get('/admin/meja', 'MejaController::meja');
    $routes->post('/admin/simpan_meja', 'MejaController::simpan_meja');
    $routes->post('/admin/update_meja', 'MejaController::update_meja');
    $routes->post('/admin/hapus_meja', 'MejaController::hapus_meja');

    // =========================================================================
    // ROUTES UNTUK KELOLA RESERVASI (BOOKING)
    // =========================================================================
    $routes->get('/admin/booking', 'ReservasiController::booking');
    $routes->post('/admin/konfirmasi_booking', 'ReservasiController::konfirmasi_booking');
    $routes->post('/admin/batal_booking', 'ReservasiController::batal_booking');

    $routes->get('jadwal_harian', 'Home::jadwal_harian');
    $routes->get('riwayat_tugas', 'Home::riwayat_tugas');

    // =========================================================================
    // ROUTES UNTUK KELOLA PEMESANAN
    // =========================================================================
    $routes->get('/admin/pemesanan', 'PemesananController::index');
    $routes->post('/admin/pemesanan/konfirmasi_pembayaran', 'PemesananController::konfirmasiPembayaran');
    $routes->post('/admin/pemesanan/selesai_pemesanan', 'PemesananController::selesaiPemesanan');
    $routes->post('/admin/pemesanan/batal_pemesanan', 'PemesananController::batalPemesanan');
});