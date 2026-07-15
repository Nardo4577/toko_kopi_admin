<?php
// Otomatis mengambil status login dan nama dari Session CodeIgniter
$session = session();
$is_logged_in = $session->get('isLoggedIn') ? true : false;
$nama_user = $session->get('username') ?? 'Admin';

$foto_user = $session->get('foto_profil') ?: 'default_profil.jpg';
// Data dinamis didapatkan dari Controller Admin.php
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Portal Admin - Dashboard Kopi Senja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/32/924/924514.png" sizes="32x32" />

    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/responsive.css') ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #fdfbf7;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Admin Khusus & Efek Scroll */
        .navbar-admin {
            border-bottom: 3px solid #8d6e63;
        }

        .fixedArea {
            z-index: 9999 !important;
            position: fixed;
            width: 100%;
            transition: all 0.4s ease-in-out;
            background-color: transparent;
        }

        .fixedArea.navbar-scrolled {
            background-color: #3e2723 !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
            border-bottom: 3px solid #f1c40f;
        }

        .fixedArea.navbar-scrolled .myNavBar {
            padding-bottom: 0px !important;
            padding-top: 0px !important;
            min-height: auto !important;
        }

        .fixedArea.navbar-scrolled .nav-link,
        .fixedArea.navbar-scrolled span,
        .fixedArea.navbar-scrolled i {
            color: #ffffff !important;
        }

        .fixedArea.navbar-scrolled .nav-link:hover {
            color: #f1c40f !important;
        }

        /* CSS Dropdown Profil */
        .profile-dropdown .dropdown-menu {
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        .profile-dropdown .dropdown-menu>li>a {
            color: #333;
            padding: 10px 20px;
            transition: 0.2s;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .profile-dropdown .dropdown-menu>li>a:hover {
            background-color: #f9f5f0;
            color: #e74c3c;
        }

        /* Penyesuaian Desain Dashboard Card ke Tema Kopi */
        .main-content {
            flex: 1;
        }

        .admin-section {
            padding: 60px 0;
        }

        .admin-card {
            background: #fff;
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border-top: 4px solid #8d6e63;
            overflow: hidden;
            transition: 0.3s;
        }

        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(141, 110, 99, 0.15);
        }

        .admin-card .card-body {
            padding: 25px;
        }

        .admin-card .card-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #3e2723;
            font-size: 18px;
            margin-bottom: 20px;
            border-bottom: 1px solid #f0e9e1;
            padding-bottom: 10px;
        }

        .admin-card .card-title span {
            color: #8d6e63;
            font-weight: 400;
            font-size: 14px;
        }

        /* Warna Icon Info Card */
        .icon-kopi-primary {
            background-color: #8d6e63 !important;
        }

        .icon-kopi-secondary {
            background-color: #8d6e63 !important;
        }

        .list-group-item {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            border-color: #f0e9e1;
            padding: 15px 10px;
        }

        .badge-kopi {
            background-color: #8d6e63 !important;
        }

        .badge-dark {
            background-color: #8d6e63 !important;
        }

        /* Tabel Laporan */
        .table-laporan th {
            background-color: #f9f5f0;
            color: #3e2723;
            font-family: 'Poppins', sans-serif;
            border-bottom: 2px solid #8d6e63 !important;
        }

        .table-laporan td {
            vertical-align: middle;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #555;
        }

        /* CSS KHUSUS FOOTER ADMIN */
        .admin-footer {
            background-color: #fdfbf7;
            color: #555;
            font-family: 'Poppins', sans-serif;
            padding-top: 50px;
            margin-top: auto;
            border-top: 1px solid #eaddd3;
        }

        .admin-footer h4 {
            color: #3e2723;
            font-family: 'Righteous', cursive;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .admin-footer h5 {
            color: #8d6e63;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .admin-footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .admin-footer ul li {
            margin-bottom: 10px;
        }

        .admin-footer ul li a {
            color: #666;
            text-decoration: none;
            transition: 0.3s;
            font-size: 13px;
        }

        .admin-footer ul li a:hover {
            color: #8d6e63;
            padding-left: 5px;
            font-weight: 600;
        }

        .admin-footer .contact-info p {
            margin-bottom: 8px;
            font-size: 13px;
            color: #555;
        }

        .admin-footer .contact-info i {
            margin-right: 8px;
            color: #8d6e63;
            font-size: 16px;
        }

        .admin-footer-bottom {
            background-color: #eaddd3;
            padding: 20px 0;
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #3e2723;
            font-weight: 500;
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
                            <nav class="navbar navbar-default myNavBar navbar-admin"
                                style="background: transparent; border: none;">
                                <div class="container">
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar" style="background-color: #8d6e63;"></span>
                                            <span class="icon-bar" style="background-color: #8d6e63;"></span>
                                            <span class="icon-bar" style="background-color: #8d6e63;"></span>
                                        </button>
                                        <a style="padding-top:0px;" class="navbar-brand" href="#"><img
                                                src="https://cdn-icons-png.flaticon.com/64/924/924514.png" alt="logo"
                                                width="47" /></a>
                                        <span
                                            style="font-family: 'Righteous', cursive; color: #fff; font-size: 20px; line-height: 50px; margin-left: 10px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">PORTAL
                                            ADMIN</span>
                                    </div>

                                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                        <ul class="nav navbar-nav navbar-right navBar">
                                            <li class="nav-item active"><a href="<?= base_url('admin/dashboard') ?>"
                                                    class="nav-link text-uppercase font-weight-bold"
                                                    style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Dashboard</a>
                                            </li>
                                            <li class="nav-item"><a href="<?= base_url('admin/stok') ?>"
                                                    class="nav-link text-uppercase font-weight-bold"
                                                    style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola
                                                    Menu</a></li>
                                            <li class="nav-item"><a href="<?= base_url('admin/karyawan') ?>"
                                                    class="nav-link text-uppercase font-weight-bold"
                                                    style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola
                                                    Staff</a></li>
                                            <li class="nav-item"><a href="<?= base_url('admin/meja') ?>"
                                                    class="nav-link text-uppercase font-weight-bold"
                                                    style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola
                                                    Meja</a></li>
                                            <li class="nav-item"><a href="<?= base_url('admin/booking') ?>"
                                                    class="nav-link text-uppercase font-weight-bold"
                                                    style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola
                                                    Reservasi</a></li>
                                            <li class="nav-item"><a href="<?= base_url('admin/pemesanan') ?>"
                                                    class="nav-link text-uppercase font-weight-bold"
                                                    style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola
                                                    Pemesanan</a></li>

                                            <!-- Dropdown Profil & Logout Dinamis -->
                                            <?php if ($is_logged_in): ?>
                                                <li class="nav-item dropdown profile-dropdown">
                                                    <a href="#" class="dropdown-toggle nav-link font-weight-bold"
                                                        data-toggle="dropdown" role="button" aria-haspopup="true"
                                                        aria-expanded="false"
                                                        style="text-transform: none; color: #f1c40f; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">
                                                        <img src="<?= base_url('img/profil/' . $foto_user) ?>" width="22"
                                                            height="22"
                                                            style="border-radius: 50%; margin-right: 5px; margin-top: -4px; object-fit: cover; border: 1px solid #f1c40f;">
                                                        Halo, <?= htmlspecialchars($nama_user) ?> <span
                                                            class="caret"></span>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="<?= base_url('logout') ?>"><i class="fa fa-sign-out"
                                                                    style="margin-right: 8px;"></i> Keluar Akun</a></li>
                                                    </ul>
                                                </li>
                                            <?php else: ?>
                                                <li class="nav-item">
                                                    <a href="<?= base_url('login') ?>"
                                                        class="nav-link text-uppercase font-weight-bold"
                                                        style="color: #f1c40f;">Login</a>
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
        <div class="banner"
            style="background-image: url('https://images.unsplash.com/photo-1559925393-8be0ec4767c8?auto=format&fit=crop&w=1900&q=80'); background-position: center; background-attachment: fixed; position: relative;">
            <div style="background: rgba(34, 34, 34, 0.8); position: absolute; top:0; left:0; width:100%; height:100%;">
            </div>

            <div class="container text-center"
                style="position: relative; z-index: 2; padding-top: 180px; padding-bottom: 100px;">
                <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 45px; margin-bottom: 10px;">
                    Dashboard Manajemen</h2>
                <h4 style="color: #f1c40f; font-family: 'Poppins', sans-serif;">Pantau performa dan aktivitas harian
                    Kedai Kopi Senja.</h4>
            </div>
        </div>

        <section class="admin-section">
            <div class="container">

                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="card admin-card">
                            <div class="card-body">
                                <h5 class="card-title">Reservasi Masuk <span>| Bulan ini</span></h5>
                                <div class="d-flex align-items-center" style="display: flex; align-items: center;">
                                    <div class="card-icon rounded-circle icon-kopi-primary text-white"
                                        style="width: 65px; height: 65px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px;">
                                        <i class="bi bi-journal-check"></i>
                                    </div>
                                    <div class="ps-3" style="padding-left: 20px;">
                                        <h6 class="mb-0 fw-bold"
                                            style="font-size: 32px; font-family: 'Righteous', cursive; color: #3e2723; margin: 0;">
                                            <?= $reservasiHariIni ?> <span
                                                style="font-size: 14px; font-family: 'Poppins', sans-serif; color: #888;">Booking</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <div class="card admin-card">
                            <div class="card-body">
                                <h5 class="card-title">Estimasi Total Tamu <span>| Bulan Ini</span></h5>
                                <div class="d-flex align-items-center" style="display: flex; align-items: center;">
                                    <div class="card-icon rounded-circle icon-kopi-secondary text-white"
                                        style="width: 65px; height: 65px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px;">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3" style="padding-left: 20px;">
                                        <h6 class="mb-0 fw-bold"
                                            style="font-size: 32px; font-family: 'Righteous', cursive; color: #3e2723; margin: 0;">
                                            <?= $totalTamuHariIni ?> <span
                                                style="font-size: 14px; font-family: 'Poppins', sans-serif; color: #888;">Orang</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="card admin-card">
                            <div class="card-body">
                                <h5 class="card-title">Statistik Reservasi <span>| 7 Hari Terakhir</span></h5>
                                <div style="height: 300px;">
                                    <canvas id="reservasiChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="card admin-card">
                            <div class="card-body">
                                <h5 class="card-title">Kopi Terfavorit <span>| Bulan Ini</span></h5>
                                <ul class="list-group list-group-flush"
                                    style="padding: 0; margin: 0; list-style: none;">
                                    <?php if (!empty($rankingKopi)): ?>
                                        <?php foreach ($rankingKopi as $idx => $kopi): ?>
                                            <li class="list-group-item"
                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <span class="badge badge-dark"
                                                        style="border-radius: 50%; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; margin-right: 8px;">
                                                        <?= $idx + 1 ?>
                                                    </span>
                                                    <i class="bi bi-cup-hot" style="color: #8d6e63; margin-right: 5px;"></i>
                                                    <?= htmlspecialchars($kopi['nama_kopi']) ?>
                                                </div>
                                                <span class="badge badge-kopi" style="border-radius: 12px; padding: 6px 10px;">
                                                    <?= $kopi['jumlah_pesanan'] ?> Cup
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-center text-muted">
                                            Belum ada data pesanan bulan ini
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card admin-card">
                            <div class="card-body">
                                <h5 class="card-title">Filter Laporan Reservasi</h5>
                                <form method="GET" class="row"
                                    style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 15px; margin-bottom: 20px;">

                                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                        <label
                                            style="font-family: 'Poppins', sans-serif; font-size: 13px;">Bulan</label>
                                        <select name="bulan" class="form-control" style="border-radius: 8px;">
                                            <option value="all" <?= (isset($selectedBulan) && $selectedBulan == 'all') ? 'selected' : '' ?>>Semua Bulan</option>
                                            <option value="01" <?= (isset($selectedBulan) && $selectedBulan == '01') ? 'selected' : '' ?>>Januari</option>
                                            <option value="02" <?= (isset($selectedBulan) && $selectedBulan == '02') ? 'selected' : '' ?>>Februari</option>
                                            <option value="03" <?= (isset($selectedBulan) && $selectedBulan == '03') ? 'selected' : '' ?>>Maret</option>
                                            <option value="04" <?= (isset($selectedBulan) && $selectedBulan == '04') ? 'selected' : '' ?>>April</option>
                                            <option value="05" <?= (isset($selectedBulan) && $selectedBulan == '05') ? 'selected' : '' ?>>Mei</option>
                                            <option value="06" <?= (isset($selectedBulan) && $selectedBulan == '06') ? 'selected' : '' ?>>Juni</option>
                                            <option value="07" <?= (isset($selectedBulan) && $selectedBulan == '07') ? 'selected' : '' ?>>Juli</option>
                                            <option value="08" <?= (isset($selectedBulan) && $selectedBulan == '08') ? 'selected' : '' ?>>Agustus</option>
                                            <option value="09" <?= (isset($selectedBulan) && $selectedBulan == '09') ? 'selected' : '' ?>>September</option>
                                            <option value="10" <?= (isset($selectedBulan) && $selectedBulan == '10') ? 'selected' : '' ?>>Oktober</option>
                                            <option value="11" <?= (isset($selectedBulan) && $selectedBulan == '11') ? 'selected' : '' ?>>November</option>
                                            <option value="12" <?= (isset($selectedBulan) && $selectedBulan == '12') ? 'selected' : '' ?>>Desember</option>
                                        </select>
                                    </div>

                                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                        <label
                                            style="font-family: 'Poppins', sans-serif; font-size: 13px;">Tahun</label>
                                        <select name="tahun" class="form-control" style="border-radius: 8px;">
                                            <?php $tahunSekarang = date('Y'); ?>
                                            <option value="<?= $tahunSekarang ?>" <?= (isset($selectedTahun) && $selectedTahun == $tahunSekarang) ? 'selected' : '' ?>>
                                                <?= $tahunSekarang ?>
                                            </option>
                                            <option value="<?= $tahunSekarang - 1 ?>" <?= (isset($selectedTahun) && $selectedTahun == ($tahunSekarang - 1)) ? 'selected' : '' ?>>
                                                <?= $tahunSekarang - 1 ?>
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group" style="flex: 2; margin-bottom: 0;">
                                        <label style="font-family: 'Poppins', sans-serif; font-size: 13px;">Cari Nama
                                            Pemesan</label>
                                        <input type="text" class="form-control" name="keyword"
                                            placeholder="Masukkan kata kunci..." style="border-radius: 8px;"
                                            value="<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
                                    </div>

                                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                        <button type="submit" class="btn"
                                            style="background-color: #3e2723; color: white; width: 100%; padding: 8px; border-radius: 8px; font-family: 'Poppins', sans-serif; font-weight: bold;"><i
                                                class="fa fa-search"></i> Terapkan</button>
                                    </div>

                                </form>

                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f0e9e1; padding-top: 20px; margin-top: 10px;">
                                    <h5 class="card-title" style="border: none; margin: 0; padding: 0;">Rincian Data
                                        Reservasi</h5>
                                    <button onclick="window.print()" class="btn btn-sm"
                                        style="border: 2px solid #8d6e63; color: #8d6e63; background: transparent; border-radius: 5px; font-family: 'Poppins', sans-serif; font-weight: bold;">
                                        <i class="fa fa-print"></i> Cetak PDF
                                    </button>
                                </div>

                                <div class="table-responsive" style="margin-top: 20px;">
                                    <table class="table table-striped table-hover table-laporan">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Nama</th>
                                                <th scope="col">Waktu </th>
                                                <th scope="col">No Meja </th>
                                                <th scope="col">Kapasitas</th>
                                                <th scope="col">Tanggal </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($historyData)): ?>
                                                <?php $no = 1;
                                                foreach ($historyData as $row): ?>
                                                    <tr>
                                                        <th scope="row" style="vertical-align: middle;">
                                                            <?= $no++ ?>
                                                        </th>
                                                        <td style="font-weight: 600; color: #3e2723;">
                                                            <?= htmlspecialchars($row['nama_pemesan']) ?>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-clock-o text-muted"></i>
                                                            <?= date('H:i', strtotime($row['waktu'])) ?>
                                                        </td>
                                                        <td>
                                                            <?php if (isset($row['status_reservasi']) && $row['status_reservasi'] === 'Dibatalkan'): ?>
                                                                <span class="label label-danger"
                                                                    style="background-color: #e74c3c; padding: 5px 10px; font-size: 12px; border-radius: 5px;">Dibatalkan
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="label label-default"
                                                                    style="background-color: #8d6e63; padding: 5px 10px; font-size: 12px; border-radius: 5px;">Meja
                                                                    <?= $row['no_meja'] ?: '-' ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-users text-muted"></i> <?= $row['jumlah_tamu'] ?>
                                                            Orang
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-calendar text-muted"></i>
                                                            <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="6" class="text-center">Tidak ada data reservasi ditemukan.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
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

    <!-- FOOTER ADMIN BARU -->
    <footer class="admin-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" width="35"
                            style="margin-right: 10px; filter: grayscale(100%) opacity(0.8);">
                        <h4 style="margin: 0;">Portal Manajemen</h4>
                    </div>
                    <p style="font-size: 13px; line-height: 1.8;">
                        Sistem kontrol utama Kedai Kopi Senja. Harap kelola data stok, karyawan, dan reservasi pelanggan
                        secara berkala demi kelancaran operasional.
                    </p>
                </div>
                <div class="col-md-4 col-sm-6">
                    <h5>Akses Pintas</h5>
                    <ul>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Pengaturan Akun
                                Admin</a></li>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Ekspor Laporan
                                Bulanan</a></li>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Kelola Menu &
                                Harga</a></li>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Riwayat Login
                                Sistem</a></li>
                    </ul>
                </div>
                <div class="col-md-4 col-sm-12">
                    <h5>Bantuan Teknis</h5>
                    <p style="font-size: 13px; margin-bottom: 15px;">Perlu bantuan untuk menangani *error* atau
                        *maintenance* sistem?</p>
                    <div class="contact-info">
                        <p><i class="fa fa-whatsapp"></i> Developer Support: +62 811-0000-9999</p>
                        <p><i class="fa fa-envelope"></i> dev@kopisenja.com</p>
                        <p><i class="fa fa-database"></i> Server Status: <span
                                style="color: #2ecc71; font-weight: bold;">Online</span></p>
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

    <!-- SCRIPT UNTUK BAGAN / GRAFIK -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const ctx = document.getElementById('reservasiChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // Bisa diganti 'line'
                data: {
                    labels: <?= json_encode($chartLabels) ?>,
                    datasets: [{
                        label: 'Jumlah Reservasi',
                        data: <?= json_encode($chartData) ?>,
                        backgroundColor: 'rgba(141, 110, 99, 0.8)', /* Warna Coklat Kopi Senja */
                        borderColor: '#3e2723',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>

    <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
</body>

</html>