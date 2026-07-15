<?php
// Otomatis mengambil status login dan nama dari Session CodeIgniter
$session = session();
$is_logged_in = $session->get('isLoggedIn') ? true : false; 
$nama_user = $session->get('username') ?? 'Admin'; 
$foto_user = $session->get('foto_profil') ?: 'default_profil.jpg';
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Portal Admin - Kelola Reservasi Kopi Senja</title>
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
            .admin-card { background: #fff; border-radius: 12px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px; border-top: 4px solid #8d6e63; overflow: hidden; }
            .admin-card .card-body { padding: 30px; }
            
            .table-laporan th { background-color: #f9f5f0; color: #3e2723; font-family: 'Poppins', sans-serif; border-bottom: 2px solid #8d6e63 !important; }
            .table-laporan td { vertical-align: middle !important; font-family: 'Poppins', sans-serif; font-size: 14px; color: #555; }
            
            .badge-status { padding: 6px 12px; border-radius: 6px; font-weight: 600; color: white; display: inline-block; min-width: 95px; text-align: center; }
            .status-pending { background-color: #f39c12; color: #fff; }
            .status-dikonfirmasi { background-color: #27ae60; color: #fff; }
            .status-dibatalkan { background-color: #e74c3c; color: #fff; }
            
            .custom-modal-width { max-width: 450px; width: 100%; margin: 60px auto; }
            .custom-modal-header { background-color: #8d6e63; padding: 20px 25px; border-bottom: none; }
            .custom-modal-title { font-family: 'Poppins', sans-serif; font-weight: bold; color: #fff; margin: 0; font-size: 18px; }
            .custom-modal-body { background-color: #fdfbf7; padding: 30px 25px; }
            .custom-input { border-radius: 8px; border: 1px solid #d7ccc8; padding: 10px 15px; font-family: 'Poppins', sans-serif; background-color: #fff; box-shadow: none; height: auto; width: 100%; margin-bottom: 15px; }

            .admin-footer { background-color: #fdfbf7; color: #555; font-family: 'Poppins', sans-serif; padding-top: 50px; margin-top: auto; border-top: 1px solid #eaddd3; }
            .admin-footer h4 { color: #3e2723; font-family: 'Righteous', cursive; margin-bottom: 20px; letter-spacing: 1px; }
            .admin-footer h5 { color: #8d6e63; font-weight: 700; margin-bottom: 20px; font-size: 16px; }
            .admin-footer-bottom { background-color: #eaddd3; padding: 20px 0; margin-top: 40px; text-align: center; font-size: 13px; color: #3e2723; font-weight: 500; }
        </style>
    </head>
    <body>
        
        <header class="top">
            <script>
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
                                                <li class="nav-item"><a href="<?= base_url('admin/dashboard') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff;">Dashboard</a></li>
                                                <li class="nav-item"><a href="<?= base_url('admin/stok') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff;">Kelola Menu</a></li>
                                                <li class="nav-item"><a href="<?= base_url('admin/karyawan') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff;">Kelola Staff</a></li>
                                                <li class="nav-item"><a href="<?= base_url('admin/meja') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff;">Kelola Meja</a></li>
                                                <li class="nav-item active"><a href="<?= base_url('admin/booking') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff;">Kelola Reservasi</a></li>
                                                <li class="nav-item"><a href="<?= base_url('admin/pemesanan') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Pemesanan</a></li>
                                                
                                                <?php if ($is_logged_in): ?>
                                                    <li class="nav-item dropdown profile-dropdown">
                                                        <a href="#" class="dropdown-toggle nav-link font-weight-bold" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="text-transform: none; color: #f1c40f;">
                                                            <img src="<?= base_url('img/profil/' . $foto_user) ?>" width="22" height="22" style="border-radius: 50%; margin-right: 5px; margin-top: -4px; object-fit: cover; border: 1px solid #f1c40f;">
                                                            Halo, <?= htmlspecialchars($nama_user) ?> <span class="caret"></span>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <li><a href="<?= base_url('logout') ?>"><i class="fa fa-sign-out"></i> Keluar Akun</a></li>
                                                        </ul>
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
                    <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 45px;">Kelola Pemesanan Meja</h2>
                    <h4 style="color: #f1c40f; font-family: 'Poppins', sans-serif;">Verifikasi dan atur antrean reservasi pelanggan.</h4>
                </div>
            </div>

            <section class="admin-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">

                            <?php if (session()->getFlashdata('pesan')): ?>
                                <div class="alert alert-success text-center" style="border-radius: 8px; font-family: 'Poppins', sans-serif;">
                                    <i class="fa fa-check-circle"></i> <?= session()->getFlashdata('pesan') ?>
                                </div>
                            <?php endif; ?>

                            <div class="card admin-card">
                                <div class="card-body">
                                    <div style="border-bottom: 1px solid #f0e9e1; padding-bottom: 15px; margin-bottom: 20px;">
                                        <h4 style="font-family: 'Righteous', cursive; color: #3e2723; margin: 0; font-size: 22px;">Daftar Semua Booking</h4>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-laporan">
                                            <thead>
                                                <tr>
                                                    <th scope="col" width="5%">#</th>
                                                    <th scope="col" width="15%">Nama Pemesan</th>
                                                    <th scope="col" width="12%">Waktu</th>
                                                    <th scope="col" width="15%">Kapasitas</th>
                                                    <th scope="col" width="15%">Status & Meja</th>
                                                    <th scope="col" width="18%" class="text-center">Aksi</th>
                                                    <th scope="col" width="20%">Petugas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; foreach ($bookingData as $row): 
                                                    // Format Kapasitas agar rapi sesuai UI
                                                    $tamu = $row['jumlah_tamu'];
                                                    if($tamu == '1-2' || $tamu == '2') $tamuTeks = '1 - 2 Orang';
                                                    elseif($tamu == '3-4' || $tamu == '4') $tamuTeks = '3 - 4 Orang';
                                                    elseif($tamu == '5-8' || $tamu == '8') $tamuTeks = '5 - 8 Orang';
                                                    elseif($tamu == '8+') $tamuTeks = 'Lebih dari 8 Orang';
                                                    else $tamuTeks = $tamu;
                                                ?>
                                                    <tr>
                                                        <th scope="row"><?= $no++ ?></th>
                                                        <td style="font-weight: bold; color: #3e2723;">
                                                            <?= htmlspecialchars($row['nama_pemesan']) ?>
                                                            <br><small style="color: #27ae60; font-weight: 600;"><i class="fa fa-whatsapp"></i> <?= htmlspecialchars($row['whatsapp'] ?? '-') ?></small>
                                                            <br><small style="color: #888; font-weight: normal;"><i class="fa fa-commenting-o"></i> <?= htmlspecialchars($row['catatan'] ?? '-') ?></small>
                                                        </td>
                                                        <td>
                                                            <small><i class="fa fa-calendar"></i> <?= date('d M Y', strtotime($row['tanggal_jadwal'])) ?></small><br>
                                                            <small><i class="fa fa-clock-o"></i> <?= date('H:i', strtotime($row['waktu_jadwal'])) ?> WIB</small>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-users" style="color: #8d6e63;"></i> <?= htmlspecialchars($tamuTeks) ?>
                                                            <br><small style="color: #e67e22; font-weight: 600;"><i class="fa fa-star"></i> <?= htmlspecialchars(ucfirst($row['kelas_meja'] ?? '-')) ?></small>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $badgeClass = '';
                                                                if($row['status_reservasi'] == 'Pending') $badgeClass = 'status-pending';
                                                                if($row['status_reservasi'] == 'Dikonfirmasi') $badgeClass = 'status-dikonfirmasi';
                                                                if($row['status_reservasi'] == 'Dibatalkan') $badgeClass = 'status-dibatalkan';
                                                            ?>
                                                            <span class="badge-status <?= $badgeClass ?>"><?= $row['status_reservasi'] ?></span>
                                                            <br><small style="color: #888; margin-top:5px; display:inline-block;">Meja: <strong style="color: #3e2723;"><?= !empty($row['no_meja']) ? htmlspecialchars($row['no_meja']) : 'Belum Diatur' ?></strong></small>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if($row['status_reservasi'] == 'Pending'): ?>
                                                                <button type="button" class="btn btn-sm btn-success" style="font-weight: bold; margin-bottom: 5px; width: 100px;" data-toggle="modal" data-target="#modalKonfirmasi<?= $row['id_reservasi'] ?>">
                                                                    <i class="fa fa-check"></i> Terima
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-danger" style="font-weight: bold; width: 100px;" data-toggle="modal" data-target="#modalBatalkan<?= $row['id_reservasi'] ?>">
                                                                    <i class="fa fa-times"></i> Batal
                                                                </button>
                                                            <?php else: ?>
                                                                <span style="font-style: italic; color: #aaa; font-size: 12px;">Telah Diproses</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if($row['status_reservasi'] != 'Pending' && !empty($row['nama_admin'])): ?>
                                                                <div style="background: #f9f5f0; padding: 8px; border-radius: 6px; border-left: 3px solid #27ae60;">
                                                                    <small style="display: block; font-weight: 600; color: #3e2723;">ID: <?= $row['id_karyawan'] ?></small>
                                                                    <small style="display: block; color: #555;"><i class="fa fa-user-circle-o"></i> <?= htmlspecialchars($row['nama_admin']) ?></small>
                                                                </div>
                                                            <?php else: ?>
                                                                <span class="text-muted" style="font-size: 11px;">Menunggu konfirmasi...</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>

                                                    <div class="modal fade" id="modalKonfirmasi<?= $row['id_reservasi'] ?>" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog custom-modal-width" role="document">
                                                            <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                                                                <div class="modal-header" style="background-color: #27ae60; padding: 20px 25px; border-bottom: none;">
                                                                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="custom-modal-title" style="color: #fff;"><i class="fa fa-check-circle"></i> Alokasi Meja</h4>
                                                                </div>
                                                                <form action="<?= base_url('admin/konfirmasi_booking') ?>" method="POST">
                                                                    <div class="custom-modal-body">
                                                                        <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi'] ?>">
                                                                        
                                                                        <p style="font-family: 'Poppins', sans-serif; color: #555; margin-bottom: 20px;">Pilih meja kosong untuk kapasitas <strong><?= htmlspecialchars($tamuTeks) ?></strong>.</p>
                                                                        
                                                                        <div class="form-group">
                                                                            <label class="custom-form-label">Meja yang Tersedia:</label>
                                                                            <select name="no_meja" class="custom-input" required>
                                                                                <option value="" disabled selected>-- Pilih Meja --</option>
                                                                                <?php 
                                                                                    $mejaDitemukan = false;
                                                                                    foreach($mejaTersedia as $meja): 
                                                                                        if($meja['kapasitas_meja'] == $tamuTeks): 
                                                                                            $mejaDitemukan = true;
                                                                                ?>
                                                                                    <option value="<?= $meja['no_meja'] ?>">Meja <?= $meja['no_meja'] ?> (<?= $meja['kelas_meja'] ?>)</option>
                                                                                <?php 
                                                                                        endif;
                                                                                    endforeach; 
                                                                                ?>

                                                                                <?php if(!$mejaDitemukan): ?>
                                                                                    <option value="" disabled>Peringatan: Tidak ada meja <?= htmlspecialchars($tamuTeks) ?> tersedia!</option>
                                                                                <?php endif; ?>
                                                                            </select>
                                                                        </div>

                                                                        <div style="background: #eafaf1; color: #27ae60; padding: 10px; border-radius: 8px; font-size: 11px; margin-top: 10px;">
                                                                            <i class="fa fa-info-circle"></i> Anda masuk sebagai <strong><?= htmlspecialchars($nama_user) ?></strong>. Nama Anda akan tercatat sebagai petugas konfirmasi.
                                                                        </div>

                                                                        <button type="submit" class="btn btn-success btn-block" style="border-radius: 8px; font-weight: bold; padding: 12px; margin-top: 20px;" <?= !$mejaDitemukan ? 'disabled' : '' ?>>
                                                                            Konfirmasi & Kunci Meja
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="modalBatalkan<?= $row['id_reservasi'] ?>" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog custom-modal-width" role="document">
                                                            <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none;">
                                                                <div class="modal-header" style="background-color: #e74c3c; padding: 20px 25px; border-bottom: none;">
                                                                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="custom-modal-title" style="color: #fff;"><i class="fa fa-warning"></i> Batalkan Booking</h4>
                                                                </div>
                                                                <form action="<?= base_url('admin/batal_booking') ?>" method="POST">
                                                                    <div class="custom-modal-body text-center">
                                                                        <input type="hidden" name="id_reservasi" value="<?= $row['id_reservasi'] ?>">
                                                                        <input type="hidden" name="no_meja" value="<?= $row['no_meja'] ?>">
                                                                        
                                                                        <p style="font-family: 'Poppins', sans-serif; color: #555;">Apakah Anda yakin ingin membatalkan pesanan milik <strong><?= htmlspecialchars($row['nama_pemesan']) ?></strong>?</p>
                                                                        
                                                                        <div style="margin-top: 25px; display: flex; gap: 10px;">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal" style="flex: 1; border-radius: 8px;">Tutup</button>
                                                                            <button type="submit" class="btn btn-danger" style="flex: 1; border-radius: 8px; font-weight: bold;">Ya, Batalkan</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

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

        <footer class="admin-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <div style="display: flex; align-items: center; margin-bottom: 15px;">
                            <img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" width="35" style="margin-right: 10px; filter: grayscale(100%) opacity(0.8);">
                            <h4 style="margin: 0;">Portal Manajemen</h4>
                        </div>
                        <p style="font-size: 13px; line-height: 1.8;">Sistem kontrol utama Kedai Kopi Senja. Semua aktivitas konfirmasi dicatat oleh sistem.</p>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <h5>Akses Pintas</h5>
                        <ul>
                            <li><a href="#"><i class="fa fa-angle-right"></i> Dashboard</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i> Kelola Meja</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5>Bantuan Teknis</h5>
                        <div class="contact-info">
                            <p><i class="fa fa-whatsapp"></i> Developer Support: +62 811-0000-9999</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-footer-bottom">
                <div class="container">Copyright &copy; -Kedai Kopi Senja Internal Admin Portal- 2026.</div>
            </div>
        </footer>

        <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
        <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>
    </body>
</html>