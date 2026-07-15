<?php
// Otomatis mengambil status login dan nama dari Session CodeIgniter
$session = session();
$is_logged_in = $session->get('isLoggedIn') ? true : false;
$nama_user = $session->get('username') ?? 'Admin';
$foto_user = $session->get('foto_profil') ?: 'default_profil.jpg';
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Portal Admin - Kelola Pemesanan Kopi Senja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/32/924/924514.png" sizes="32x32" />

    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/responsive.css') ?>">

    <style>
        body { background-color: #fdfbf7; display: flex; flex-direction: column; min-height: 100vh; }
        
        .navbar-admin { border-bottom: 3px solid #8d6e63; }
        .fixedArea { z-index: 9999 !important; position: fixed; width: 100%; transition: all 0.4s ease-in-out; background-color: transparent; }
        .fixedArea.navbar-scrolled { background-color: #3e2723 !important; box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important; border-bottom: 3px solid #f1c40f; }
        .fixedArea.navbar-scrolled .myNavBar { padding-bottom: 0px !important; padding-top: 0px !important; min-height: auto !important; }
        .fixedArea.navbar-scrolled .nav-link, .fixedArea.navbar-scrolled span, .fixedArea.navbar-scrolled i { color: #ffffff !important; }
        .fixedArea.navbar-scrolled .nav-link:hover { color: #f1c40f !important; }

        .profile-dropdown .dropdown-menu { background-color: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-top: 10px; }
        .profile-dropdown .dropdown-menu > li > a { color: #333; padding: 10px 20px; transition: 0.2s; font-family: 'Poppins', sans-serif; font-size: 13px; }
        .profile-dropdown .dropdown-menu > li > a:hover { background-color: #f9f5f0; color: #e74c3c; }

        .main-content { flex: 1; }
        .admin-section { padding: 60px 0; }
        .admin-card { background: #fff; border-radius: 12px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; border-top: 4px solid #8d6e63; transition: 0.3s; }
        .admin-card .card-body { padding: 30px; }
        
        .table-laporan th { background-color: #f9f5f0; color: #3e2723; font-family: 'Poppins', sans-serif; border-bottom: 2px solid #8d6e63 !important; }
        .table-laporan td { vertical-align: middle !important; font-family: 'Poppins', sans-serif; font-size: 14px; color: #555; }
        
        .btn-action-kopi { background-color: #8d6e63; color: white; border: none; border-radius: 5px; transition: 0.3s; padding: 8px 15px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: bold; }
        .btn-action-kopi:hover { background-color: #3e2723; color: white; }
        
        .custom-input { border-radius: 8px; border: 1px solid #d7ccc8; padding: 10px 15px; font-family: 'Poppins', sans-serif; background-color: #fff; box-shadow: none; height: auto; width: 100%; }
        .custom-input:focus { border: 2px solid #8d6e63 !important; box-shadow: 0 0 8px rgba(141, 110, 99, 0.3) !important; outline: none !important; }

        .admin-footer { background-color: #fdfbf7; color: #555; font-family: 'Poppins', sans-serif; padding-top: 50px; margin-top: auto; border-top: 1px solid #eaddd3; }
        .admin-footer h4 { color: #3e2723; font-family: 'Righteous', cursive; margin-bottom: 20px; letter-spacing: 1px; }
        .admin-footer h5 { color: #8d6e63; font-weight: 700; margin-bottom: 20px; font-size: 16px; }
        .admin-footer ul { list-style: none; padding: 0; margin: 0; }
        .admin-footer ul li { margin-bottom: 10px; }
        .admin-footer ul li a { color: #666; text-decoration: none; transition: 0.3s; font-size: 13px; }
        .admin-footer ul li a:hover { color: #8d6e63; padding-left: 5px; font-weight: 600; }
        .admin-footer .contact-info p { margin-bottom: 8px; font-size: 13px; color: #555; }
        .admin-footer .contact-info i { margin-right: 8px; color: #8d6e63; font-size: 16px; }
        .admin-footer-bottom { background-color: #eaddd3; padding: 20px 0; margin-top: 40px; text-align: center; font-size: 13px; color: #3e2723; font-weight: 500; }
        
        .custom-modal-width { max-width: 450px; width: 100%; margin: 60px auto; }
        .custom-modal-title { font-family: 'Poppins', sans-serif; font-weight: bold; color: #fff; margin: 0; font-size: 18px; }
        .custom-modal-body { background-color: #fdfbf7; padding: 30px 25px; }

        /* ========================================== */
        /* CSS STRUK KASIR (MIRIP DENGAN HALAMAN USER) */
        /* ========================================== */
        .modal-struk-body { background: #fff; padding: 0; }
        .struk-kertas { background: #fff; width: 100%; max-width: 320px; margin: 0 auto; padding: 20px; font-family: 'Courier New', Courier, monospace; color: #000; border: 1px dashed #ccc; position: relative; }
        
        .struk-header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 15px; margin-bottom: 15px; }
        .struk-header img { width: 40px; margin-bottom: 5px; filter: grayscale(100%); }
        .struk-header h2 { font-weight: bold; font-size: 18px; margin: 0 0 5px 0; }
        .struk-header p { font-size: 12px; margin: 0; line-height: 1.2; }
        
        .struk-info { font-size: 12px; margin-bottom: 15px; line-height: 1.5; }
        .struk-info span { display: inline-block; width: 80px; }
        
        .struk-items { width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 15px; }
        .struk-items th { border-bottom: 1px dashed #000; padding-bottom: 5px; text-align: left; }
        .struk-items td { padding: 5px 0; vertical-align: top; }
        .struk-items .td-qty { width: 15%; text-align: left; }
        .struk-items .td-harga { width: 35%; text-align: right; }
        .struk-item-note { font-size: 10px; color: #555; display: block; font-style: italic; }
        
        .struk-total { border-top: 2px dashed #000; padding-top: 10px; margin-top: 5px; font-size: 12px; }
        .struk-total .row-total { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .struk-total .grand-total { font-size: 16px; font-weight: bold; margin-top: 10px; border-top: 1px dashed #000; padding-top: 10px; }
        .struk-footer { text-align: center; font-size: 11px; margin-top: 25px; border-top: 2px dashed #000; padding-top: 15px; }

        /* ========================================== */
        /* CSS DUA WAJAH (SCREEN VS PRINT)            */
        /* ========================================== */
        .area-struk { display: none; } 

        @media print {
            header, .main-content, footer, .modal-backdrop { display: none !important; }
            body, html { margin: 0; padding: 0; background-color: #fff; }
            .modal, .modal.fade { display: none !important; }
            .modal.in { display: block !important; position: static !important; }
            .modal-dialog, .modal-content, .modal-body { margin: 0 !important; padding: 0 !important; border: none !important; box-shadow: none !important; width: 100% !important; background-color: #fff !important; }
            .area-detail, .payment-proof-zone, .modal-header, .modal-footer, .no-print, .close { display: none !important; }
            
            /* Tampilkan penuh wajah kedua (Kertas Struk Kasir) */
            .modal.in .area-struk { display: block !important; visibility: visible !important; width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 10px !important; }
            .struk-kertas { border: none !important; margin: 0 !important; padding: 0 !important; max-width: 100% !important; }
        }
    </style>
</head>

<body>

    <header class="top">
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                window.addEventListener("scroll", function () {
                    var navArea = document.querySelector(".fixedArea");
                    if (window.scrollY > 50) {
                        navArea.classList.add("navbar-scrolled");
                    } else {
                        navArea.classList.remove("navbar-scrolled");
                    }
                });
            });
        </script>
        <div class="fixedArea">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12 noPadding">
                    <div class="content-wrapper one">
                        <header class="header">
                            <nav class="navbar navbar-default myNavBar navbar-admin" style="background: transparent; border: none;">
                                <div class="container">
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar" style="background-color: #8d6e63;"></span>
                                            <span class="icon-bar" style="background-color: #8d6e63;"></span>
                                            <span class="icon-bar" style="background-color: #8d6e63;"></span>
                                        </button>
                                        <a style="padding-top:0px;" class="navbar-brand" href="#"><img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" alt="logo" width="47" /></a>
                                        <span style="font-family: 'Righteous', cursive; color: #fff; font-size: 20px; line-height: 50px; margin-left: 10px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">PORTAL ADMIN</span>
                                    </div>

                                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                        <ul class="nav navbar-nav navbar-right navBar">
                                            <li class="nav-item"><a href="<?= base_url('admin/dashboard') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Dashboard</a></li>
                                            <li class="nav-item"><a href="<?= base_url('admin/stok') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Menu</a></li>
                                            <li class="nav-item"><a href="<?= base_url('admin/karyawan') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Staff</a></li>
                                            <li class="nav-item"><a href="<?= base_url('admin/meja') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Meja</a></li>
                                            <li class="nav-item"><a href="<?= base_url('admin/booking') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Reservasi</a></li>
                                            <li class="nav-item active"><a href="<?= base_url('admin/pemesanan') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Pemesanan</a></li>

                                            <?php if ($is_logged_in): ?>
                                                <li class="nav-item dropdown profile-dropdown">
                                                    <a href="#" class="dropdown-toggle nav-link font-weight-bold" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="text-transform: none; color: #f1c40f; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">
                                                        <img src="<?= base_url('img/profil/' . $foto_user) ?>" width="22" height="22" style="border-radius: 50%; margin-right: 5px; margin-top: -4px; object-fit: cover; border: 1px solid #f1c40f;">
                                                        Halo, <?= htmlspecialchars($nama_user) ?> <span class="caret"></span>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="<?= base_url('logout') ?>"><i class="fa fa-sign-out" style="margin-right: 8px;"></i> Keluar Akun</a></li>
                                                    </ul>
                                                </li>
                                            <?php else: ?>
                                                <li class="nav-item">
                                                    <a href="<?= base_url('login') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #f1c40f;">Login</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                        </header>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="banner" style="background-image: url('https://images.unsplash.com/photo-1559925393-8be0ec4767c8?auto=format&fit=crop&w=1900&q=80'); background-position: center; background-attachment: fixed; position: relative;">
            <div style="background: rgba(34, 34, 34, 0.8); position: absolute; top:0; left:0; width:100%; height:100%;"></div>

            <div class="container text-center" style="position: relative; z-index: 2; padding-top: 180px; padding-bottom: 100px;">
                <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 45px; margin-bottom: 10px;">Kelola Pemesanan</h2>
                <h4 style="color: #f1c40f; font-family: 'Poppins', sans-serif;">Pantau menu pesanan dan cetak struk pelanggan Kedai Kopi Senja.</h4>
            </div>
        </div>

        <section class="admin-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        
                        <?php if (session()->getFlashdata('pesan')): ?>
                            <div class="alert alert-success text-center" style="border-radius: 8px; font-family: 'Poppins', sans-serif;">
                                <i class="fa fa-check-circle" style="margin-right: 5px;"></i> <?= session()->getFlashdata('pesan') ?>
                            </div>
                        <?php endif; ?>

                        <div class="card admin-card">
                            <div class="card-body">
                                
                                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0e9e1; padding-bottom: 15px; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
                                    <h4 style="font-family: 'Righteous', cursive; color: #3e2723; margin: 0; font-size: 22px;">Daftar Pesanan Menu Pelanggan</h4>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="text" id="searchPemesanan" class="custom-input" placeholder="Cari nama pelanggan..." autocomplete="off" style="margin-bottom: 0; padding: 7px 15px; width: 250px;">
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-laporan">
                                        <thead>
                                            <tr>
                                                <th scope="col">Code Booking</th>
                                                <th scope="col">Nama Pelanggan</th>
                                                <th scope="col">No Meja</th>
                                                <th scope="col">Total Belanja</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($pemesananData as $row): ?>
                                                <?php if(!empty($row['items'])): ?>
                                                <tr>
                                                    <td><strong>#RSV-<?= $row['id_reservasi'] ?></strong></td>
                                                    <td class="target-nama" style="font-weight: bold; color: #3e2723;"><?= htmlspecialchars($row['nama_pemesan'] ?? $row['nama_pelanggan'] ?? 'Pelanggan') ?></td>
                                                    <td><?= htmlspecialchars($row['no_meja'] ?? 'Belum Diatur') ?></td>
                                                    <td style="color:#e67e22; font-weight:bold;">Rp <?= number_format($row['total_tagihan'], 0, ',', '.') ?></td>
                                                    <td>
                                                        <span class="label <?= ($row['status_reservasi'] == 'Dikonfirmasi' || $row['status_reservasi'] == 'Selesai') ? 'label-success' : 'label-warning' ?>" style="font-family: 'Poppins', sans-serif; padding: 5px 10px; border-radius: 5px;">
                                                            <?= $row['status_reservasi'] ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm btn-action-kopi" data-toggle="modal" data-target="#modalDetail<?= $row['id_reservasi'] ?>" style="margin-bottom: 5px;">
                                                            <i class="fa fa-search-plus"></i> Detail & Print
                                                        </button>
                                                        <?php if ($row['status_reservasi'] == 'Dikonfirmasi'): ?>
                                                            <br>
                                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalSelesai<?= $row['id_reservasi'] ?>" style="margin-bottom: 5px;">
                                                                <i class="fa fa-check"></i> Selesai
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalBatal<?= $row['id_reservasi'] ?>" style="margin-bottom: 5px;">
                                                                <i class="fa fa-times"></i> Batal
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php foreach ($pemesananData as $row): ?>
        <?php 
            if(!empty($row['items'])): 

            // 1. Membersihkan Format Metode Pembayaran
            $metode_raw = $row['metode_pembayaran'] ?? 'Online Payment';
            $metode_bersih = ucwords(str_replace('_', ' ', $metode_raw));
            if (strtolower($metode_raw) == 'qris') {
                $metode_bersih = 'QRIS';
            }

            // 2. Kalkulasi Subtotal & Pajak Ulang untuk Kebutuhan Struk
            $subtotal_produk = 0;
            foreach ($row['items'] as $item) {
                $subtotal_produk += $item['subtotal'];
            }
            $pajak = $subtotal_produk * 0.10;
            $total_akhir = $subtotal_produk + $pajak; // Harusnya sama dengan $row['total_tagihan']
        ?>
            <div class="modal fade" id="modalDetail<?= $row['id_reservasi'] ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document" style="margin-top: 80px;">
                    <div class="modal-content" style="border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); background-color: #fdfbf7;">
                        
                        <div class="modal-header no-print" style="background-color: #8d6e63; color:#fff; border-bottom: none; padding: 15px 25px; border-radius: 12px 12px 0 0;">
                            <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:0.8;"><span>&times;</span></button>
                            <h4 class="modal-title" style="font-family:'Poppins', sans-serif; font-weight: bold; font-size: 16px;"><i class="fa fa-file-text-o"></i> Detail Pemesanan #RSV-<?= $row['id_reservasi'] ?></h4>
                        </div>
                        
                        <div class="modal-body" style="padding: 25px;">
                            
                            <div class="area-detail no-print" style="font-family: 'Poppins', sans-serif;">
                                <div class="row" style="margin-bottom: 15px;">
                                    <div class="col-xs-6">
                                        <p style="font-size:13px; margin-bottom:5px; color: #555;"><strong>Pelanggan:</strong><br><span style="color: #3e2723; font-weight: 600; font-size: 15px;"><?= htmlspecialchars($row['nama_pemesan'] ?? $row['nama_pelanggan'] ?? 'Pelanggan') ?></span></p>
                                        <p style="font-size:13px; margin-bottom:5px; color: #555;"><strong>No Meja:</strong> <span style="color: #3e2723; font-weight: 600;"><?= htmlspecialchars($row['no_meja'] ?? 'Belum Diatur') ?></span></p>
                                    </div>
                                    <div class="col-xs-6 text-right">
                                        <p style="font-size:13px; margin-bottom:5px; color: #555;"><strong>Tgl Order:</strong> <?= date('d M Y') ?></p>
                                        <p style="font-size:13px; margin-bottom:5px; color: #555;"><strong>Petugas:</strong> <?= htmlspecialchars($nama_user) ?></p>
                                    </div>
                                </div>

                                <table class="table table-bordered" style="font-size: 13px; background: #fff; border-radius: 8px; overflow: hidden;">
                                    <thead style="background: #f9f5f0; color: #3e2723;">
                                        <tr>
                                            <th>Item Menu</th>
                                            <th class="text-center" width="15%">Qty</th>
                                            <th class="text-right" width="30%">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($row['items'] as $item): ?>
                                            <tr>
                                                <td style="vertical-align: middle;">
                                                    <?= htmlspecialchars($item['nama_menu']) ?>
                                                    <?php if(!empty($item['catatan_menu'])): ?>
                                                        <br><span style="font-size: 10px; color: #888; font-style: italic;">(<?= htmlspecialchars($item['catatan_menu']) ?>)</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="vertical-align: middle;">x<?= $item['jumlah_pesanan'] ?></td>
                                                <td class="text-right" style="vertical-align: middle;">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr style="font-weight:bold; font-size:15px; background: #fdfbf7;">
                                            <td colspan="2" class="text-right" style="vertical-align: middle; color: #3e2723;">TOTAL PEMBAYARAN :</td>
                                            <td class="text-right" style="color: #d35400;">Rp <?= number_format($row['total_tagihan'], 0, ',', '.') ?></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="payment-proof-zone" style="margin-top: 20px; background: #f9f5f0; padding: 15px; border-radius: 8px; border: 1px dashed #d7ccc8; text-align: center;">
                                    <label style="display:block; margin-bottom: 5px; color: #3e2723; font-weight: 600; font-size: 13px;">
                                        <i class="fa fa-credit-card" style="color: #8d6e63; margin-right: 5px;"></i> Metode Pembayaran Pelanggan:
                                    </label>
                                    <div style="font-size: 20px; font-family: 'Righteous', cursive; color: #27ae60; margin-top: 5px;">
                                        <?= htmlspecialchars($metode_bersih) ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="area-struk modal-struk-body">
                                <div class="struk-kertas">
                                    <div class="struk-header">
                                        <img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" alt="Logo">
                                        <h2>KEDAI KOPI SENJA</h2>
                                        <p>Jl. Kopi Raya No. 42 Semarang<br>IG: @kopisenja</p>
                                    </div>
                                    
                                    <div class="struk-info">
                                        <span>Order ID</span> : <strong>KDS-<?= $row['id_reservasi'] ?></strong><br>
                                        <span>Tanggal</span> : <strong><?= date('d/m/Y', strtotime($row['tanggal_jadwal'])) ?></strong><br>
                                        <span>Kasir</span> : <strong><?= strtoupper(htmlspecialchars($nama_user)) ?></strong><br>
                                        <span>Pelanggan</span> : <strong><?= strtoupper(htmlspecialchars($row['nama_pemesan'] ?? $row['nama_pelanggan'] ?? 'UMUM')) ?></strong><br>
                                        <span>Meja</span> : <strong><?= htmlspecialchars($row['no_meja'] ?? 'NON') ?></strong><br>
                                        <span>Metode</span> : <strong><?= htmlspecialchars($metode_bersih) ?></strong>
                                    </div>
                                    
                                    <table class="struk-items">
                                        <?php foreach ($row['items'] as $item): ?>
                                            <tr>
                                                <td class="td-qty"><?= $item['jumlah_pesanan'] ?>x</td>
                                                <td>
                                                    <?= strtoupper(htmlspecialchars($item['nama_menu'])) ?>
                                                    <?php if(!empty($item['catatan_menu'])): ?>
                                                        <span class="struk-item-note">(<?= htmlspecialchars($item['catatan_menu']) ?>)</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="td-harga"><?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                    
                                    <div class="struk-total">
                                        <div class="row-total"><span>Subtotal</span><span>Rp <?= number_format($subtotal_produk, 0, ',', '.') ?></span></div>
                                        <div class="row-total"><span>Pajak (10%)</span><span>Rp <?= number_format($pajak, 0, ',', '.') ?></span></div>
                                        <div class="row-total grand-total"><span>TOTAL DIBAYAR</span><span>Rp <?= number_format($total_akhir, 0, ',', '.') ?></span></div>
                                    </div>
                                    
                                    <div class="struk-footer">
                                        <p>LUNAS / PAID<br>Terima kasih atas kunjungannya!<br>Silakan datang kembali.</p>
                                    </div>
                                </div>
                            </div>
                            </div>
                        <div class="modal-footer no-print" style="background:#eaddd3; border-top: none; padding: 15px 25px; border-radius: 0 0 12px 12px;">
                            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-family: 'Poppins', sans-serif; font-weight: bold;">Tutup</button>
                            <button type="button" class="btn" onclick="window.print();" style="background-color: #3e2723; color: white; border-radius: 8px; font-family: 'Poppins', sans-serif; font-weight: bold;">
                                <i class="fa fa-print" style="margin-right: 5px;"></i> Cetak Struk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($row['status_reservasi'] == 'Dikonfirmasi'): ?>
            <!-- Modal Konfirmasi Selesai -->
            <div class="modal fade" id="modalSelesai<?= $row['id_reservasi'] ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog custom-modal-width" role="document">
                    <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                        <div class="modal-header" style="background-color: #27ae60; padding: 20px 25px; border-bottom: none;">
                            <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                            <h4 class="custom-modal-title" style="color: #fff;"><i class="fa fa-check-circle"></i> Selesai Pesanan</h4>
                        </div>
                        <form action="<?= base_url('admin/pemesanan/selesai_pemesanan') ?>" method="POST">
                            <div class="custom-modal-body text-center">
                                <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi'] ?>">
                                
                                <p style="font-family: 'Poppins', sans-serif; color: #555; margin-bottom: 20px;">Tandai pesanan milik <strong><?= htmlspecialchars($row['nama_pemesan'] ?? $row['nama_pelanggan'] ?? 'Pelanggan') ?></strong> (RSV-<?= $row['id_reservasi'] ?>) selesai dan kosongkan meja?</p>
                                
                                <div style="margin-top: 25px; display: flex; gap: 10px;">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" style="flex: 1; border-radius: 8px;">Tutup</button>
                                    <button type="submit" class="btn btn-success" style="flex: 1; border-radius: 8px; font-weight: bold;">Konfirmasi Selesai</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Konfirmasi Batal -->
            <div class="modal fade" id="modalBatal<?= $row['id_reservasi'] ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog custom-modal-width" role="document">
                    <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                        <div class="modal-header" style="background-color: #e74c3c; padding: 20px 25px; border-bottom: none;">
                            <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                            <h4 class="custom-modal-title" style="color: #fff;"><i class="fa fa-warning"></i> Batalkan Pesanan</h4>
                        </div>
                        <form action="<?= base_url('admin/pemesanan/batal_pemesanan') ?>" method="POST">
                            <div class="custom-modal-body text-center">
                                <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi'] ?>">
                                
                                <p style="font-family: 'Poppins', sans-serif; color: #555; margin-bottom: 20px;">Apakah Anda yakin ingin membatalkan pesanan milik <strong><?= htmlspecialchars($row['nama_pemesan'] ?? $row['nama_pelanggan'] ?? 'Pelanggan') ?></strong> dan kosongkan meja?</p>
                                
                                <div style="margin-top: 25px; display: flex; gap: 10px;">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" style="flex: 1; border-radius: 8px;">Tutup</button>
                                    <button type="submit" class="btn btn-danger" style="flex: 1; border-radius: 8px; font-weight: bold;">Ya, Batalkan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <footer class="admin-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" width="35" style="margin-right: 10px; filter: grayscale(100%) opacity(0.8);">
                        <h4 style="margin: 0;">Portal Manajemen</h4>
                    </div>
                    <p style="font-size: 13px; line-height: 1.8;">
                        Sistem kontrol utama Kedai Kopi Senja. Harap kelola data stok, karyawan, dan reservasi pelanggan secara berkala demi kelancaran operasional.
                    </p>
                </div>
                <div class="col-md-4 col-sm-6">
                    <h5>Akses Pintas</h5>
                    <ul>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Pengaturan Akun Admin</a></li>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Ekspor Laporan Bulanan</a></li>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Kelola Menu & Harga</a></li>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Riwayat Login Sistem</a></li>
                    </ul>
                </div>
                <div class="col-md-4 col-sm-12">
                    <h5>Bantuan Teknis</h5>
                    <p style="font-size: 13px; margin-bottom: 15px;">Perlu bantuan untuk menangani kendala sistem?</p>
                    <div class="contact-info">
                        <p><i class="fa fa-whatsapp"></i> Developer Support: +62 811-0000-9999</p>
                        <p><i class="fa fa-envelope"></i> dev@kopisenja.com</p>
                        <p><i class="fa fa-database"></i> Server Status: <span style="color: #2ecc71; font-weight: bold;">Online</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="admin-footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        Copyright &copy; -Kedai Kopi Senja Internal Admin Portal- 2026. All Rights Reserved.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            $('#searchPemesanan').on('input', function() {
                var keyword = $(this).val().toLowerCase();
                
                if (keyword.length >= 1) {
                    $('.table-laporan tbody tr').each(function() {
                        var namaPelanggan = $(this).find('.target-nama').text().toLowerCase();
                        if (namaPelanggan.indexOf(keyword) > -1) {
                            $(this).show(); 
                        } else {
                            $(this).hide(); 
                        }
                    });
                } else {
                    $('.table-laporan tbody tr').show(); 
                }
            });

            $('#searchPemesanan').on('keydown', function(e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>
</html>