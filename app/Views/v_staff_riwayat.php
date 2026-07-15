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
        <title>Portal Staff - Riwayat Tugas</title>
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
                box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important; 
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
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                margin-top: 10px;
            }
            .profile-dropdown .dropdown-menu > li > a {
                color: #333;
                padding: 10px 20px;
                transition: 0.2s;
                font-family: 'Poppins', sans-serif;
                font-size: 13px;
            }
            .profile-dropdown .dropdown-menu > li > a:hover {
                background-color: #f9f5f0;
                color: #e74c3c;
            }

            /* Box Filter Navigasi */
            .filter-box {
                background: #fff;
                padding: 20px;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.03);
                border: 1px solid #eaeaea;
                margin-bottom: 40px;
                display: flex;
                align-items: center;
                gap: 15px;
                flex-wrap: wrap;
            }
            .filter-box select {
                border-radius: 8px;
                border: 1px solid #d7ccc8;
                padding: 8px 15px;
                font-family: 'Poppins', sans-serif;
                color: #555;
                outline: none;
            }
            .filter-box button {
                background-color: #8d6e63;
                color: white;
                border: none;
                padding: 8px 25px;
                border-radius: 8px;
                font-family: 'Poppins', sans-serif;
                font-weight: 600;
                transition: 0.3s;
            }
            .filter-box button:hover { background-color: #3e2723; }

            /* Desain List Riwayat */
            .history-item {
                background: #fff;
                border-radius: 12px;
                padding: 25px;
                margin-bottom: 20px;
                border: 1px solid #eaeaea;
                border-left: 5px solid #2ecc71; /* Warna hijau indikator selesai */
                box-shadow: 0 4px 10px rgba(0,0,0,0.02);
                display: flex;
                align-items: flex-start;
                transition: 0.3s;
            }
            .history-item:hover {
                transform: translateX(5px);
                box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            }
            
            .history-date-time {
                min-width: 150px;
                padding-right: 20px;
                border-right: 1px dashed #d7ccc8;
                text-align: center;
            }
            .history-date-time h5 { 
                font-family: 'Righteous', cursive; 
                color: #8d6e63; 
                margin: 0 0 5px 0; 
                font-size: 20px; 
            }
            .history-date-time span { 
                font-size: 12px; 
                color: #999; 
                font-family: 'Poppins', sans-serif;
                font-weight: bold;
            }

            .history-main {
                flex-grow: 1;
                padding: 0 25px;
            }
            .history-main h4 { 
                font-family: 'Poppins', sans-serif; 
                font-weight: 700; 
                font-size: 18px; 
                color: #3e2723; 
                margin: 0 0 8px 0; 
            }
            .history-main .task-time-original {
                font-size: 12px;
                color: #888;
                margin-bottom: 12px;
                display: inline-block;
            }
            .history-note { 
                background: #fdfbf7; 
                padding: 12px 15px; 
                border-radius: 8px; 
                font-size: 13px; 
                color: #555; 
                margin-top: 10px; 
                border-left: 3px solid #8d6e63;
                font-family: 'Poppins', sans-serif;
            }
            .history-note strong { color: #3e2723; }

            .history-status {
                min-width: 120px;
                text-align: right;
            }
            .status-badge {
                font-family: 'Poppins', sans-serif;
                font-size: 12px;
                font-weight: 600;
                padding: 6px 15px;
                border-radius: 20px;
                background-color: #e8f8f5;
                color: #2ecc71;
                border: 1px solid #2ecc71;
                display: inline-block;
            }

            /* Konten utama untuk push footer ke bawah */
            .main-content {
                flex: 1;
            }

            /* CSS KHUSUS FOOTER STAFF (Lebih Terang & Elegan) */
            .staff-footer {
                background-color: #fdfbf7; 
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
                padding: 20px 0;
                margin-top: 40px;
                text-align: center;
                font-size: 13px;
                color: #3e2723;
                font-weight: 500;
            }

            @media (max-width: 768px) {
                .history-item { flex-direction: column; }
                .history-date-time { border-right: none; border-bottom: 1px dashed #d7ccc8; padding-right: 0; padding-bottom: 15px; margin-bottom: 15px; width: 100%; text-align: left; display: flex; justify-content: space-between; align-items: center; }
                .history-main { padding: 0; margin-bottom: 15px; }
                .history-status { text-align: left; }
            }
        </style>
    </head>
    <body>
        
        <header class="top">
            <script>
                // Script untuk mendeteksi scroll dan mengubah background navbar
                document.addEventListener("DOMContentLoaded", function() {
                    window.addEventListener("scroll", function() {
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
                                <nav class="navbar navbar-default myNavBar navbar-staff" style="background: transparent; border: none;">
                                    <div class="container">
                                        <div class="navbar-header">
                                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                                <span class="sr-only">Toggle navigation</span>
                                                <span class="icon-bar" style="background-color: #8d6e63;"></span>
                                                <span class="icon-bar" style="background-color: #8d6e63;"></span>
                                                <span class="icon-bar" style="background-color: #8d6e63;"></span>
                                            </button>
                                            <a style="padding-top:0px;" class="navbar-brand" href="#"><img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" alt="logo" width="47" /></a>
                                            <span style="font-family: 'Righteous', cursive; color: #fff; font-size: 20px; line-height: 50px; margin-left: 10px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">PORTAL STAFF</span>
                                        </div>
                                                 
                                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                            <ul class="nav navbar-nav navbar-right navBar">
                                                <li class="nav-item"><a href="<?= base_url('jadwal_harian') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Jadwal Harian</a></li>
                                                <li class="nav-item active"><a href="<?= base_url('riwayat_tugas') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Riwayat Tugas</a></li>

                                                <!-- Dropdown Profil & Logout Dinamis -->
                                                <?php if ($is_logged_in): ?>
                                                    <li class="nav-item dropdown profile-dropdown">
                                                        <a href="#" class="dropdown-toggle nav-link font-weight-bold" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="text-transform: none; color: #f1c40f; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">
                                                            <img src="https://cdn-icons-png.flaticon.com/128/3135/3135715.png" width="22" style="border-radius: 50%; margin-right: 5px; margin-top: -4px;">
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
            <div class="banner" style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1900&q=80'); background-position: center; background-attachment: fixed; position: relative;">
                <div style="background: rgba(62, 39, 35, 0.75); position: absolute; top:0; left:0; width:100%; height:100%;"></div>
                
                <div class="container text-center" style="position: relative; z-index: 2; padding-top: 180px; padding-bottom: 100px;">
                    <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 45px; margin-bottom: 10px;">Riwayat Pekerjaan</h2>
                    <h4 style="color: #f1c40f; font-family: 'Poppins', sans-serif;">Log histori aktivitas dan tugas harian Anda</h4>
                </div>
            </div>

            <section style="padding: 60px 0;">
                <div class="container">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="filter-box">
                                <span style="font-family: 'Poppins', sans-serif; font-weight: bold; color: #3e2723;"><i class="fa fa-filter"></i> Filter Data:</span>
                                <select>
                                    <option value="04" selected>April</option>
                                    <option value="03">Maret</option>
                                    <option value="02">Februari</option>
                                </select>
                                <select>
                                    <option value="2026" selected>2026</option>
                                    <option value="2025">2025</option>
                                </select>
                                <button type="button">Tampilkan</button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            
                            <div class="history-item">
                                <div class="history-date-time">
                                    <h5>24 Apr</h5>
                                    <span>2026</span><br>
                                    <span style="color: #2ecc71; font-weight: normal;"><i class="fa fa-check-circle"></i> 15:10 WIB</span>
                                </div>
                                <div class="history-main">
                                    <h4>Closing Bar & Laporan Kasir</h4>
                                    <span class="task-time-original"><i class="fa fa-clock-o"></i> Jadwal Asli: 14:00 - 15:00</span>
                                    <div class="history-note">
                                        <strong>Catatan Anda:</strong> Mesin espresso sudah dibersihkan secara menyeluruh (backflush). Laporan kasir shift pagi sudah diserahkan ke ruang admin. Mesin EDC sudah di-*settle*.
                                    </div>
                                </div>
                                <div class="history-status">
                                    <span class="status-badge">Tepat Waktu</span>
                                </div>
                            </div>

                            <div class="history-item">
                                <div class="history-date-time">
                                    <h5>24 Apr</h5>
                                    <span>2026</span><br>
                                    <span style="color: #2ecc71; font-weight: normal;"><i class="fa fa-check-circle"></i> 14:05 WIB</span>
                                </div>
                                <div class="history-main">
                                    <h4>Standby Bar & Melayani Pesanan</h4>
                                    <span class="task-time-original"><i class="fa fa-clock-o"></i> Jadwal Asli: 08:00 - 14:00</span>
                                    <div class="history-note">
                                        <strong>Catatan Anda:</strong> Ramai lancar. Stok sirup vanilla di bar tinggal 1 botol, tolong shift selanjutnya untuk *restock* dari gudang belakang.
                                    </div>
                                </div>
                                <div class="history-status">
                                    <span class="status-badge">Selesai</span>
                                </div>
                            </div>

                            <div class="history-item">
                                <div class="history-date-time">
                                    <h5>23 Apr</h5>
                                    <span>2026</span><br>
                                    <span style="color: #e74c3c; font-weight: normal;"><i class="fa fa-check-circle"></i> 10:15 WIB</span>
                                </div>
                                <div class="history-main">
                                    <h4>Handle Reservasi VIP AC</h4>
                                    <span class="task-time-original"><i class="fa fa-clock-o"></i> Jadwal Asli: 09:00 - 10:00</span>
                                    <div class="history-note">
                                        <strong>Catatan Anda:</strong> Tamu reservasi datang sedikit terlambat dari jadwal, persiapan meja dan *welcome drink* selesai sesuai prosedur.
                                    </div>
                                </div>
                                <div class="history-status">
                                    <span class="status-badge" style="background-color: #fdedec; color: #e74c3c; border-color: #e74c3c;">Terlambat</span>
                                </div>
                            </div>

                            <div class="history-item">
                                <div class="history-date-time">
                                    <h5>23 Apr</h5>
                                    <span>2026</span><br>
                                    <span style="color: #2ecc71; font-weight: normal;"><i class="fa fa-check-circle"></i> 07:50 WIB</span>
                                </div>
                                <div class="history-main">
                                    <h4>Opening & Preparation Bar</h4>
                                    <span class="task-time-original"><i class="fa fa-clock-o"></i> Jadwal Asli: 07:00 - 08:00</span>
                                    <div class="history-note">
                                        <strong>Catatan Anda:</strong> Aman. Semua bahan baku siap, kalibrasi *grinder* mendapatkan rasio espresso 1:2 dalam waktu 28 detik.
                                    </div>
                                </div>
                                <div class="history-status">
                                    <span class="status-badge">Tepat Waktu</span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center" style="margin-top: 20px;">
                            <ul class="pagination" style="font-family: 'Poppins', sans-serif;">
                                <li class="disabled"><a href="#">&laquo;</a></li>
                                <li class="active"><a href="#" style="background-color: #8d6e63; border-color: #8d6e63;">1</a></li>
                                <li><a href="#" style="color: #8d6e63;">2</a></li>
                                <li><a href="#" style="color: #8d6e63;">3</a></li>
                                <li><a href="#" style="color: #8d6e63;">&raquo;</a></li>
                            </ul>
                        </div>
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
                            <img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" width="35" style="margin-right: 10px; filter: grayscale(100%) opacity(0.8);">
                            <h4 style="margin: 0;">Portal Internal</h4>
                        </div>
                        <p style="font-size: 13px; line-height: 1.8;">
                            Sistem informasi internal Kedai Kopi Senja. Harap jaga kerahasiaan data operasional, resep, dan identitas pelanggan dengan baik.
                        </p>
                    </div>

                    <!-- Kolom 2: Quick Links -->
                    <div class="col-md-4 col-sm-6">
                        <h5>Tautan Penting</h5>
                        <ul>
                            <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Standar Operasional (SOP)</a></li>
                            <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Pengajuan Cuti / Izin</a></li>
                            <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Form Laporan Kerusakan Alat</a></li>
                            <li><a href="#"><i class="fa fa-angle-right" style="margin-right: 5px;"></i> Jadwal Shift Bulanan</a></li>
                        </ul>
                    </div>

                    <!-- Kolom 3: Kontak Bantuan -->
                    <div class="col-md-4 col-sm-12">
                        <h5>Bantuan & Dukungan</h5>
                        <p style="font-size: 13px; margin-bottom: 15px;">Mengalami kendala pada sistem portal atau mesin kasir?</p>
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

        <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
        <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
    </body>
</html>