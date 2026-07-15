<?php
// Otomatis mengambil status login dan nama dari Session CodeIgniter
$session = session();
$is_logged_in = $session->get('isLoggedIn') ? true : false;
$nama_user = $session->get('username') ?? 'Staff';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Portal Staff - Jadwal Tugas Kopi Senja</title>
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
        body {
            background-color: #fdfbf7;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* PERBAIKAN NAVBAR SCROLL */
        .navbar-staff {
            border-bottom: 3px solid #8d6e63;
        }

        .fixedArea {
            z-index: 9999 !important;
            position: fixed;
            width: 100%;
            transition: all 0.4s ease-in-out;
            background-color: transparent;
        }

        /* Saat discroll, background jadi coklat tua dan teks jadi putih */
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

        /* Desain Card Jadwal Tugas */
        .task-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #eaeaea;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(141, 110, 99, 0.15);
            border-color: #d7ccc8;
        }

        /* Garis warna penanda di kiri card */
        .task-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background-color: #8d6e63;
        }

        .task-time {
            font-family: 'Righteous', cursive;
            color: #8d6e63;
            font-size: 22px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .task-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #3e2723;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .task-desc {
            font-family: 'Poppins', sans-serif;
            color: #666;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Status Badges */
        .status-badge {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 15px;
            border-radius: 20px;
            display: inline-block;
        }

        .status-pending {
            background-color: #f1c40f;
            color: #3e2723;
        }

        .status-process {
            background-color: #3498db;
            color: #fff;
        }

        .status-done {
            background-color: #2ecc71;
            color: #fff;
        }

        /* Tombol Update */
        .btn-update-task {
            background-color: #fdfbf7;
            color: #8d6e63;
            border: 1px solid #8d6e63;
            padding: 8px 20px;
            border-radius: 20px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 12px;
            transition: 0.3s;
            float: right;
            text-decoration: none;
            outline: none;
        }

        .btn-update-task:hover {
            background-color: #8d6e63;
            color: #fff;
            text-decoration: none;
        }

        /* Konten utama untuk push footer ke bawah */
        .main-content {
            flex: 1;
        }

        /* PERBAIKAN: CSS FOOTER STAFF (Lebih Terang & Elegan) */
        .staff-footer {
            background-color: #fdfbf7;
            /* Putih tulang / Krem terang */
            color: #555;
            font-family: 'Poppins', sans-serif;
            padding-top: 50px;
            margin-top: auto;
            border-top: 1px solid #eaddd3;
        }

        .staff-footer h4 {
            color: #3e2723;
            font-family: 'Righteous', cursive;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .staff-footer h5 {
            color: #8d6e63;
            font-weight: 700;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .staff-footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .staff-footer ul li {
            margin-bottom: 10px;
        }

        .staff-footer ul li a {
            color: #666;
            text-decoration: none;
            transition: 0.3s;
            font-size: 13px;
        }

        .staff-footer ul li a:hover {
            color: #8d6e63;
            padding-left: 5px;
            font-weight: 600;
        }

        .staff-footer .contact-info p {
            margin-bottom: 8px;
            font-size: 13px;
            color: #555;
        }

        .staff-footer .contact-info i {
            margin-right: 8px;
            color: #8d6e63;
            font-size: 16px;
        }

        .staff-footer-bottom {
            background-color: #eaddd3;
            /* Coklat susu terang */
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
            // Script untuk mendeteksi scroll dan mengubah background navbar
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
                            <nav class="navbar navbar-default myNavBar navbar-staff"
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
                                            STAFF</span>
                                    </div>

                                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                        <ul class="nav navbar-nav navbar-right navBar">
                                            <li class="nav-item active"><a href="<?= base_url('jadwal_harian') ?>"
                                                    class="nav-link text-uppercase font-weight-bold"
                                                    style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Jadwal
                                                    Harian</a></li>
                                            <li class="nav-item"><a href="<?= base_url('riwayat_tugas') ?>"
                                                    class="nav-link text-uppercase font-weight-bold"
                                                    style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Riwayat
                                                    Tugas</a></li>

                                            <!-- Dropdown Profil & Logout Dinamis -->
                                            <?php if ($is_logged_in): ?>
                                                <li class="nav-item dropdown profile-dropdown">
                                                    <a href="#" class="dropdown-toggle nav-link font-weight-bold"
                                                        data-toggle="dropdown" role="button" aria-haspopup="true"
                                                        aria-expanded="false"
                                                        style="text-transform: none; color: #f1c40f; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">
                                                        <img src="https://cdn-icons-png.flaticon.com/128/3135/3135715.png"
                                                            width="22"
                                                            style="border-radius: 50%; margin-right: 5px; margin-top: -4px;">
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
            <div style="background: rgba(62, 39, 35, 0.7); position: absolute; top:0; left:0; width:100%; height:100%;">
            </div>

            <div class="container text-center"
                style="position: relative; z-index: 2; padding-top: 180px; padding-bottom: 100px;">
                <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 45px; margin-bottom: 10px;">
                    Jadwal Tugas Harian</h2>
                <h4 style="color: #f1c40f; font-family: 'Poppins', sans-serif;">Sabtu, 25 April 2026 | Shift Pagi</h4>
            </div>
        </div>

        <section style="padding: 60px 0;">
            <div class="container">

                <div class="row" style="margin-bottom: 30px;">
                    <div class="col-md-12">
                        <h3
                            style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #3e2723; border-left: 4px solid #8d6e63; padding-left: 15px;">
                            Tugas Anda Hari Ini</h3>
                        <p style="color: #666; font-family: 'Poppins', sans-serif; padding-left: 15px;">Pastikan untuk
                            memperbarui status setiap kali Anda memulai atau menyelesaikan tugas.</p>
                        <?php if (session()->getFlashdata('pesan')): ?>
                            <div class="alert alert-success mt-3"><?= session()->getFlashdata('pesan') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <?php
                    function getStatusBadge($status, $tipe_tugas)
                    {
                        if ($tipe_tugas == 2) {
                            if ($status == 'done')
                                return '<span class="status-badge status-done"><i class="fa fa-check"></i> Pesanan Telah Siap</span>';
                            if ($status == 'process')
                                return '<span class="status-badge status-process"><i class="fa fa-spinner fa-spin"></i> Pesanan Disiapkan</span>';
                            return '<span class="status-badge status-pending"><i class="fa fa-exclamation-circle"></i> Customer Belum Datang</span>';
                        } else {
                            if ($status == 'done')
                                return '<span class="status-badge status-done"><i class="fa fa-check"></i> Selesai</span>';
                            if ($status == 'process')
                                return '<span class="status-badge status-process"><i class="fa fa-spinner fa-spin"></i> Sedang Diproses</span>';
                            return '<span class="status-badge status-pending"><i class="fa fa-exclamation-circle"></i> Belum Dikerjakan</span>';
                        }
                    }
                    function getBorderColor($status)
                    {
                        if ($status == 'done')
                            return '#2ecc71';
                        if ($status == 'process')
                            return '#3498db';
                        return '#f1c40f';
                    }
                    ?>

                    <?php if (empty($reservasiData)): ?>
                        <div class="col-md-12 text-center">
                            <p style="font-family: 'Poppins', sans-serif; color: #666;">Tidak ada jadwal reservasi untuk
                                hari ini.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($reservasiData as $res):
                            $waktu_jadwal = $res['waktu_jadwal'];
                            $waktu_persiapan_mulai = date('H:i', strtotime('-2 hour', strtotime($waktu_jadwal)));
                            $waktu_persiapan_selesai = date('H:i', strtotime('-1 hour', strtotime($waktu_jadwal)));
                            $waktu_pembersihan = date('H:i', strtotime('+2 hour', strtotime($waktu_jadwal)));
                            ?>
                            <div class="col-md-12">
                                <h4
                                    style="margin-top:20px; margin-bottom:15px; color:#3e2723; font-family: 'Poppins', sans-serif; font-weight:bold;">
                                    <i class="fa fa-bookmark" style="color:#8d6e63;"></i> Reservasi:
                                    <?= htmlspecialchars($res['nama_pemesan']) ?> (Meja <?= $res['no_meja'] ?? '-' ?>) - Jam
                                    <?= date('H:i', strtotime($waktu_jadwal)) ?>
                                </h4>
                            </div>

                            <!-- Tugas 1: Persiapan Meja -->
                            <div class="col-md-4 col-sm-12">
                                <div class="task-card" id="task-card-<?= $res['id_reservasi'] ?>-1"
                                    style="border-left-color: <?= getBorderColor('pending') ?>;">
                                    <div class="task-time">
                                        <i class="fa fa-clock-o"></i> <?= $waktu_persiapan_mulai ?> -
                                        <?= $waktu_persiapan_selesai ?>
                                    </div>
                                    <h4 class="task-title">Persiapan Meja</h4>
                                    <p class="task-desc">Membersihkan meja <?= $res['no_meja'] ?? '-' ?> dan memberi tanda
                                        reserved 1-2 jam sebelum customer datang.</p>

                                    <div>
                                        <span id="badge-container-<?= $res['id_reservasi'] ?>-1">
                                            <?= getStatusBadge('pending', 1) ?>
                                        </span>
                                        <button type="button" class="btn-update-task" data-toggle="modal"
                                            data-target="#modalUpdateStatus" data-id="<?= $res['id_reservasi'] ?>"
                                            data-tugas="1"
                                            data-nama="Persiapan Meja <?= $res['no_meja'] ?? '-' ?> (<?= htmlspecialchars($res['nama_pemesan']) ?>)"
                                            data-status="pending">Update Status</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tugas 2: Mempersiapkan Menu -->
                            <div class="col-md-4 col-sm-12">
                                <div class="task-card" id="task-card-<?= $res['id_reservasi'] ?>-2"
                                    style="border-left-color: <?= getBorderColor('pending') ?>;">
                                    <div class="task-time">
                                        <i class="fa fa-clock-o"></i> <?= date('H:i', strtotime($waktu_jadwal)) ?>
                                    </div>
                                    <h4 class="task-title">Mempersiapkan Menu</h4>
                                    <p class="task-desc">Mempersiapkan menu yang dipesan oleh customer saat customer tiba.</p>

                                    <div>
                                        <span id="badge-container-<?= $res['id_reservasi'] ?>-2">
                                            <?= getStatusBadge('pending', 2) ?>
                                        </span>
                                        <button type="button" class="btn-update-task" data-toggle="modal"
                                            data-target="#modalUpdateStatus" data-id="<?= $res['id_reservasi'] ?>"
                                            data-tugas="2"
                                            data-nama="Mempersiapkan Menu (<?= htmlspecialchars($res['nama_pemesan']) ?>)"
                                            data-status="pending">Update Status</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tugas 3: Membersihkan Meja -->
                            <div class="col-md-4 col-sm-12">
                                <div class="task-card" id="task-card-<?= $res['id_reservasi'] ?>-3"
                                    style="border-left-color: <?= getBorderColor('pending') ?>;">
                                    <div class="task-time">
                                        <i class="fa fa-clock-o"></i> Setelah Selesai
                                    </div>
                                    <h4 class="task-title">Membersihkan Meja</h4>
                                    <p class="task-desc">Membersihkan meja <?= $res['no_meja'] ?? '-' ?> setelah customer
                                        selesai dan pulang.</p>

                                    <div>
                                        <span id="badge-container-<?= $res['id_reservasi'] ?>-3">
                                            <?= getStatusBadge('pending', 3) ?>
                                        </span>
                                        <button type="button" class="btn-update-task" data-toggle="modal"
                                            data-target="#modalUpdateStatus" data-id="<?= $res['id_reservasi'] ?>"
                                            data-tugas="3"
                                            data-nama="Membersihkan Meja <?= $res['no_meja'] ?? '-' ?> (<?= htmlspecialchars($res['nama_pemesan']) ?>)"
                                            data-status="pending">Update Status</button>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>

    <!-- FOOTER STAFF BARU (WARNA TERANG) -->
    <footer class="staff-footer">
        <div class="container">
            <div class="row">

                <!-- Kolom 1: Info Portal -->
                <div class="col-md-4 col-sm-6">
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" width="35"
                            style="margin-right: 10px; filter: grayscale(100%) opacity(0.8);">
                        <h4 style="margin: 0;">Portal Internal</h4>
                    </div>
                    <p style="font-size: 13px; line-height: 1.8;">
                        Sistem informasi internal Kedai Kopi Senja. Harap jaga kerahasiaan data operasional, resep, dan
                        identitas pelanggan dengan baik.
                    </p>
                </div>

                <!-- Kolom 2: Quick Links -->
                <div class="col-md-4 col-sm-6">
                    <h5>Tautan Penting</h5>
                    <ul>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Standar Operasional
                                (SOP)</a></li>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Pengajuan Cuti /
                                Izin</a></li>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Form Laporan
                                Kerusakan Alat</a></li>
                        <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Jadwal Shift
                                Bulanan</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Kontak Bantuan -->
                <div class="col-md-4 col-sm-12">
                    <h5>Bantuan & Dukungan</h5>
                    <p style="font-size: 13px; margin-bottom: 15px;">Mengalami kendala pada sistem portal atau mesin
                        kasir?</p>
                    <div class="contact-info">
                        <p><i class="fa fa-whatsapp"></i> IT Support: +62 811-2233-4455</p>
                        <p><i class="fa fa-envelope"></i> hr@kopisenja.com</p>
                        <p><i class="fa fa-building"></i> Office: Ruang Admin Lt. 2</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="staff-footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        Copyright &copy; -Kedai Kopi Senja Internal Staff Portal- 2026. All Rights Reserved.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- MODAL UPDATE STATUS (TAMPILAN SAJA) -->
    <div class="modal fade" id="modalUpdateStatus" tabindex="-1" role="dialog" aria-labelledby="modalUpdateLabel">
        <div class="modal-dialog" role="document" style="margin-top: 120px;">
            <div class="modal-content"
                style="border-radius: 15px; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.2); overflow: hidden;">

                <div class="modal-header" style="background-color: #8d6e63; padding: 20px 25px; border-bottom: none;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="color: #fff; opacity: 0.8; text-shadow: none;"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="modalUpdateLabel"
                        style="font-family: 'Poppins', sans-serif; font-weight: bold; color: #fff;">
                        <i class="fa fa-refresh" style="margin-right: 8px;"></i> Update Status Pengerjaan (Tampilan)
                    </h4>
                </div>

                <div class="modal-body" style="background-color: #fdfbf7; padding: 30px 25px;">
                    <input type="hidden" id="ui_id_reservasi" value="">
                    <input type="hidden" id="ui_tugas_ke" value="">

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label
                            style="font-family: 'Poppins', sans-serif; font-size: 13px; color: #3e2723; font-weight: 600;">Tugas
                            yang Dikerjakan</label>
                        <input type="text" class="form-control" id="ui_tugas_dikerjakan" value="" disabled
                            style="background-color: #eee; cursor: not-allowed; border-radius: 8px; font-family: 'Poppins', sans-serif; color: #555;">
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label
                            style="font-family: 'Poppins', sans-serif; font-size: 13px; color: #3e2723; font-weight: 600;">Ubah
                            Status Menjadi</label>
                        <select id="ui_status_baru" class="form-control"
                            style="border-radius: 8px; border: 1px solid #d7ccc8; font-family: 'Poppins', sans-serif; color: #555; height: 45px;"
                            required>
                            <!-- Diisi lewat JS -->
                        </select>
                    </div>

                    <button type="button" id="btn-save-ui-status" class="btn"
                        style="background-color: #3e2723; color: #fff; width: 100%; border-radius: 8px; padding: 12px; font-family: 'Poppins', sans-serif; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; border: none;">
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
    <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
    <script>
        $('#modalUpdateStatus').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var tugas = button.data('tugas');
            var nama = button.data('nama');
            var status = button.data('status');

            var modal = $(this);
            $('#ui_id_reservasi').val(id);
            $('#ui_tugas_ke').val(tugas);
            $('#ui_tugas_dikerjakan').val(nama);

            var select = $('#ui_status_baru');
            select.empty();

            if (tugas == 2) {
                select.append('<option value="pending"' + (status == 'pending' ? ' selected' : '') + '>🟡 Customer Belum Datang</option>');
                select.append('<option value="process"' + (status == 'process' ? ' selected' : '') + '>🔵 Pesanan Sedang Disiapkan</option>');
                select.append('<option value="done"' + (status == 'done' ? ' selected' : '') + '>🟢 Pesanan Telah Siap</option>');
            } else {
                select.append('<option value="pending"' + (status == 'pending' ? ' selected' : '') + '>🟡 Belum Dikerjakan</option>');
                select.append('<option value="process"' + (status == 'process' ? ' selected' : '') + '>🔵 Sedang Diproses</option>');
                select.append('<option value="done"' + (status == 'done' ? ' selected' : '') + '>🟢 Selesai</option>');
            }
        });

        $('#btn-save-ui-status').on('click', function() {
            var id = $('#ui_id_reservasi').val();
            var tugas = $('#ui_tugas_ke').val();
            var status = $('#ui_status_baru').val();

            var card = $('#task-card-' + id + '-' + tugas);
            var badgeContainer = $('#badge-container-' + id + '-' + tugas);
            var button = card.find('.btn-update-task');
            
            // Update border color
            var borderColor = '#f1c40f'; // pending
            if (status == 'process') borderColor = '#3498db';
            if (status == 'done') borderColor = '#2ecc71';
            card.css('border-left-color', borderColor);
            
            // Update badge HTML
            var badgeHtml = '';
            if (tugas == 2) {
                if (status == 'done') badgeHtml = '<span class="status-badge status-done"><i class="fa fa-check"></i> Pesanan Telah Siap</span>';
                else if (status == 'process') badgeHtml = '<span class="status-badge status-process"><i class="fa fa-spinner fa-spin"></i> Pesanan Disiapkan</span>';
                else badgeHtml = '<span class="status-badge status-pending"><i class="fa fa-exclamation-circle"></i> Customer Belum Datang</span>';
            } else {
                if (status == 'done') badgeHtml = '<span class="status-badge status-done"><i class="fa fa-check"></i> Selesai</span>';
                else if (status == 'process') badgeHtml = '<span class="status-badge status-process"><i class="fa fa-spinner fa-spin"></i> Sedang Diproses</span>';
                else badgeHtml = '<span class="status-badge status-pending"><i class="fa fa-exclamation-circle"></i> Belum Dikerjakan</span>';
            }
            
            badgeContainer.html(badgeHtml);
            button.data('status', status); // update data-status untuk pop-up modal berikutnya
            
            // Tutup modal
            $('#modalUpdateStatus').modal('hide');
            
            // Menampilkan flash alert statis sederhana
            var alertHtml = '<div class="alert alert-success text-center mt-3 ui-alert" style="border-radius: 8px; font-family: \'Poppins\', sans-serif;"><i class="fa fa-check-circle" style="margin-right: 5px;"></i> Status tugas berhasil diperbarui (Tampilan)!</div>';
            
            if ($('.ui-alert').length == 0) {
                $('.row').first().find('.col-md-12').append(alertHtml);
            } else {
                $('.ui-alert').html('<i class="fa fa-check-circle" style="margin-right: 5px;"></i> Status tugas berhasil diperbarui (Tampilan)!').show();
            }
            setTimeout(function(){ $('.ui-alert').fadeOut(); }, 3000);
        });
    </script>
</body>

</html>