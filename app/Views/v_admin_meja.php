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
        <title>Portal Admin - Kelola Meja Kopi Senja</title>
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
            
            .badge-status { padding: 6px 12px; border-radius: 6px; font-weight: 600; color: white; display: inline-block; min-width: 120px; text-align: center; }
            .status-tersedia { background-color: #27ae60; }
            .status-tidak-tersedia { background-color: #e74c3c; }
            
            .badge-kelas { background-color: #8d6e63; padding: 5px 10px; border-radius: 5px; font-weight: normal; color: white; }
            
            .btn-action-kopi { background-color: #8d6e63; color: white; border: none; border-radius: 5px; transition: 0.3s; padding: 8px 15px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: bold; }
            .btn-action-kopi:hover { background-color: #3e2723; color: white; }

            .custom-modal-width { max-width: 450px; width: 100%; margin: 60px auto; }
            .custom-modal-header { background-color: #8d6e63; padding: 20px 25px; border-bottom: none; }
            .custom-modal-title { font-family: 'Poppins', sans-serif; font-weight: bold; color: #fff; margin: 0; font-size: 18px; }
            .custom-modal-body { background-color: #fdfbf7; padding: 30px 25px; }
            .custom-form-label { font-family: 'Poppins', sans-serif; font-size: 13px; color: #3e2723; font-weight: 600; margin-bottom: 8px; display: block; }
            .custom-input { border-radius: 8px; border: 1px solid #d7ccc8; padding: 10px 15px; font-family: 'Poppins', sans-serif; background-color: #fff; box-shadow: none; height: auto; width: 100%; margin-bottom: 15px; }
            .custom-input:focus { border: 2px solid #8d6e63 !important; box-shadow: 0 0 8px rgba(141, 110, 99, 0.3) !important; outline: none !important; background-color: #fff !important; color: #333 !important; }
            .custom-input:disabled { background-color: #f0e9e1; opacity: 0.8; }

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
                                                <li class="nav-item"><a href="<?= base_url('admin/dashboard') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Dashboard</a></li>
                                                <li class="nav-item"><a href="<?= base_url('admin/stok') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Menu</a></li>
                                                <li class="nav-item"><a href="<?= base_url('admin/karyawan') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Staff</a></li>
                                                <li class="nav-item active"><a href="<?= base_url('admin/meja') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Meja</a></li>
                                                <li class="nav-item"><a href="<?= base_url('admin/booking') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Reservasi</a></li>
                                                <li class="nav-item"><a href="<?= base_url('admin/pemesanan') ?>" class="nav-link text-uppercase font-weight-bold" style="color: #fff; text-shadow: 1px 1px 2px rgba(0,0,0,0.8);">Kelola Pemesanan</a></li>
                                                
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
                    <h2 style="color: white; font-family: 'Righteous', cursive; font-size: 45px; margin-bottom: 10px;">Ketersediaan Meja</h2>
                    <h4 style="color: #f1c40f; font-family: 'Poppins', sans-serif;">Kelola kapasitas area makan Kedai Kopi Senja.</h4>
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
                                        <h4 style="font-family: 'Righteous', cursive; color: #3e2723; margin: 0; font-size: 22px;">Daftar Meja</h4>
    
                                        <div style="display: flex; gap: 10px; align-items: center;">
        
                                            <form action="<?= base_url('admin/meja') ?>" method="GET" style="margin: 0; display: flex; gap: 10px;">
                                                <input type="text" id="searchMeja" name="keyword" class="custom-input" placeholder="Cari No Meja..." value="<?= isset($keyword_terpilih) ? htmlspecialchars($keyword_terpilih) : '' ?>" style="margin-bottom: 0; padding: 7px 15px; width: 180px;" autocomplete="off">
            
                                                <select name="status_meja" class="custom-input" style="margin-bottom: 0; padding: 7px 15px; cursor: pointer; width: auto;" onchange="this.form.submit()">
                                                    <option value="">-- Semua Status --</option>
                                                    <option value="Tersedia" <?= (isset($status_terpilih) && $status_terpilih == 'Tersedia') ? 'selected' : '' ?>>🟢 Tersedia</option>
                                                    <option value="Tidak Tersedia" <?= (isset($status_terpilih) && $status_terpilih == 'Tidak Tersedia') ? 'selected' : '' ?>>🔴 Tidak Tersedia</option>
                                                </select>

                                                <button type="submit" class="btn-action-kopi" style="padding: 7px 15px;" title="Cari Data"><i class="fa fa-search"></i></button>
                                            </form>

                                            <button type="button" class="btn-action-kopi" data-toggle="modal" data-target="#modalTambah">
                                                <i class="fa fa-plus"></i> Tambah Meja
                                            </button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-laporan">
                                            <thead>
                                                <tr>
                                                    <th scope="col" width="5%">#</th>
                                                    <th scope="col" width="15%">No Meja</th>
                                                    <th scope="col" width="20%">Kelas / Area</th>
                                                    <th scope="col" width="15%">Kapasitas</th>
                                                    <th scope="col" width="25%">Status Reservasi</th>
                                                    <th scope="col" width="20%" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $no = 1; 
                                                    foreach ($mejaData as $row): 
                                                    $safe_id = md5($row['no_meja']);
                                                ?>
                                                    <tr>
                                                        <th scope="row">
                                                            <?= $no++ ?>
                                                        </th>
                                                        <td style="font-weight: bold; color: #3e2723; font-size: 16px;">
                                                            Meja <?= htmlspecialchars($row['no_meja']) ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge-kelas"><?= htmlspecialchars($row['kelas_meja']) ?></span>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-users" style="color: #888;"></i> <?= htmlspecialchars($row['kapasitas_meja']) ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $badgeClass = ($row['status_meja'] == 'Tersedia') ? 'status-tersedia' : 'status-tidak-tersedia';
                                                                $statusTeks = ($row['status_meja'] == 'Tersedia') ? '🟢 Tersedia' : '🔴 Tidak Tersedia';
                                                            ?>
                                                            <span class="badge-status <?= $badgeClass ?>"><?= $statusTeks ?></span>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-warning text-white btn-edit-meja" title="Edit" data-toggle="modal" data-target="#modalEdit<?= $safe_id ?>">
                                                                <i class="fa fa-pencil"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger" title="Hapus" data-toggle="modal" data-target="#modalHapus<?= $safe_id ?>">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <div class="modal fade modal-meja" id="modalEdit<?= $safe_id ?>" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog custom-modal-width" role="document">
                                                            <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                                                                <div class="custom-modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="custom-modal-title"><i class="fa fa-edit"></i> Edit Meja <?= htmlspecialchars($row['no_meja']) ?></h4>
                                                                </div>
                                                                <form action="<?= base_url('admin/update_meja') ?>" method="POST">
                                                                    <div class="custom-modal-body">
                                                                        <input type="hidden" name="no_meja" value="<?= $row['no_meja'] ?>">

                                                                        <div class="row">
                                                                            <div class="col-xs-6">
                                                                                <label class="custom-form-label">No Meja</label>
                                                                                <input type="text" class="custom-input" value="<?= htmlspecialchars($row['no_meja']) ?>" disabled>
                                                                            </div>
                                                                            <div class="col-xs-6">
                                                                                <label class="custom-form-label">Kapasitas Tamu</label>
                                                                                <select class="custom-input" name="kapasitas_meja" id="kap_<?= $safe_id ?>" onchange="gantiArea('<?= $safe_id ?>')" required>
                                                                                    <option value="1 - 2 Orang" <?= $row['kapasitas_meja'] == '1 - 2 Orang' ? 'selected' : '' ?>>1 - 2 Orang</option>
                                                                                    <option value="3 - 4 Orang" <?= $row['kapasitas_meja'] == '3 - 4 Orang' ? 'selected' : '' ?>>3 - 4 Orang</option>
                                                                                    <option value="5 - 8 Orang" <?= $row['kapasitas_meja'] == '5 - 8 Orang' ? 'selected' : '' ?>>5 - 8 Orang</option>
                                                                                    <option value="Lebih dari 8 Orang" <?= $row['kapasitas_meja'] == 'Lebih dari 8 Orang' ? 'selected' : '' ?>>Lebih dari 8 Orang</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <label class="custom-form-label">Kelas / Area</label>
                                                                        <select class="custom-input" name="kelas_meja" id="area_<?= $safe_id ?>" required>
                                                                            <option value="Reguler" <?= $row['kelas_meja'] == 'Reguler' ? 'selected' : '' ?>>Reguler (Semi-Outdoor)</option>
                                                                            <option value="VIP AC" <?= $row['kelas_meja'] == 'VIP AC' ? 'selected' : '' ?>>VIP AC (Private)</option>
                                                                        </select>

                                                                        <div class="alert alert-warning" style="font-size: 11px; padding: 8px; border-radius: 8px; margin-top: 10px;">
                                                                            <i class="fa fa-info-circle"></i> Kelas meja otomatis terkunci berdasarkan kapasitas.
                                                                        </div>

                                                                        <button type="submit" class="btn btn-action-kopi" style="width: 100%; border-radius: 8px; margin-top: 5px;">Simpan Perubahan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="modal fade" id="modalHapus<?= $safe_id ?>" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog custom-modal-width" role="document" style="margin-top: 100px;">
                                                            <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                                                                <div class="modal-header" style="background-color: #e74c3c; padding: 20px 25px; border-bottom: none;">
                                                                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="custom-modal-title"><i class="fa fa-warning"></i> Konfirmasi Hapus</h4>
                                                                </div>
                                                                <form action="<?= base_url('admin/hapus_meja') ?>" method="POST">
                                                                    <div class="custom-modal-body text-center">
                                                                        <input type="hidden" name="no_meja" value="<?= $row['no_meja'] ?>">
                                                                        <p style="font-family: 'Poppins', sans-serif; color: #555;">Hapus Meja <strong><?= htmlspecialchars($row['no_meja']) ?></strong> secara permanen?</p>
                                                                        <div style="margin-top: 25px; display: flex; gap: 10px;">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal" style="flex: 1; border-radius: 8px;">Batal</button>
                                                                            <button type="submit" class="btn btn-danger" style="flex: 1; border-radius: 8px;">Ya, Hapus</button>
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
                        <p style="font-size: 13px;">Sistem kontrol utama Kedai Kopi Senja.</p>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <h5>Akses Pintas</h5>
                        <ul>
                            <li><a href="#">Pengaturan Akun Admin</a></li>
                            <li><a href="#">Ekspor Laporan Bulanan</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <h5>Bantuan Teknis</h5>
                        <p style="font-size: 13px;">Developer Support: +62 811-0000-9999</p>
                    </div>
                </div>
            </div>
            <div class="admin-footer-bottom">
                <div class="container">Copyright &copy; -Kedai Kopi Senja Internal Admin Portal- 2026.</div>
            </div>
        </footer>

        <div class="modal fade modal-meja" id="modalTambah" tabindex="-1" role="dialog">
            <div class="modal-dialog custom-modal-width" role="document">
                <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
                    <div class="custom-modal-header">
                        <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                        <h4 class="custom-modal-title"><i class="fa fa-plus-circle"></i> Tambah Meja Baru</h4>
                    </div>
                    <form action="<?= base_url('admin/simpan_meja') ?>" method="POST">
                        <div class="custom-modal-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label class="custom-form-label">No Meja</label>
                                    <input type="text" class="custom-input" name="no_meja" placeholder="Cth: 06" required>
                                </div>
                                <div class="col-xs-6">
                                    <label class="custom-form-label">Kapasitas Tamu</label>
                                    <select class="custom-input" name="kapasitas_meja" id="kap_tambah" onchange="gantiArea('tambah')" required>
                                        <option value="" disabled selected>Pilih...</option>
                                        <option value="1 - 2 Orang">1 - 2 Orang</option>
                                        <option value="3 - 4 Orang">3 - 4 Orang</option>
                                        <option value="5 - 8 Orang">5 - 8 Orang</option>
                                        <option value="Lebih dari 8 Orang">Lebih dari 8 Orang</option>
                                    </select>
                                </div>
                            </div>
                            <label class="custom-form-label">Kelas / Area</label>
                            <select class="custom-input" name="kelas_meja" id="area_tambah" required>
                                <option value="" disabled selected>Pilih Area...</option>
                                <option value="Reguler">Reguler (Semi-Outdoor)</option>
                                <option value="VIP AC">VIP AC (Private)</option>
                            </select>
                            <button type="submit" class="btn btn-action-kopi" style="width: 100%; border-radius: 8px; margin-top: 10px;">Simpan Data Meja</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="<?= base_url('js/vendor/jquery-1.12.0.min.js') ?>"></script>
        <script src="<?= base_url('js/bootstrap.min.js') ?>"></script>

        <script>
            // Script Live Search 3 Huruf untuk Meja
            $(document).ready(function() {
                $('#searchMeja').on('input', function() {
                    var keyword = $(this).val().toLowerCase();
                    
                    if (keyword.length >= 1) { // Meja bisa dicari dengan 1 atau 2 digit (misal: "01")
                        $('.table-laporan tbody tr').each(function() {
                            // Mengambil teks dari tag <td> pertama (indeks 0) yang berisi "Meja XX"
                            var noMeja = $(this).find('td:eq(0)').text().toLowerCase();
                            
                            if (noMeja.indexOf(keyword) > -1) {
                                $(this).show(); 
                            } else {
                                $(this).hide(); 
                            }
                        });
                    } else {
                        $('.table-laporan tbody tr').show(); 
                    }
                });

                // Mencegah reload jika user menekan enter di kotak pencarian
                $('#searchMeja').on('keydown', function(e) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                        return false;
                    }
                });
            });

            // Fungsi utama untuk mengubah opsi berdasarkan ID elemen
            function gantiArea(idSuffix) {
                var kapasitas = document.getElementById('kap_' + idSuffix).value;
                var selectKelas = document.getElementById('area_' + idSuffix);

                if (kapasitas === 'Lebih dari 8 Orang') {
                    selectKelas.value = 'VIP AC';
                    // Loop untuk mengunci/membuka opsi
                    for (var i = 0; i < selectKelas.options.length; i++) {
                        if (selectKelas.options[i].value === 'Reguler') {
                            selectKelas.options[i].disabled = true;
                        }
                        if (selectKelas.options[i].value === 'VIP AC') {
                            selectKelas.options[i].disabled = false;
                        }
                    }
                } else if (kapasitas !== '' && kapasitas !== null) {
                    selectKelas.value = 'Reguler';
                    // Loop untuk mengunci/membuka opsi
                    for (var j = 0; j < selectKelas.options.length; j++) {
                        if (selectKelas.options[j].value === 'Reguler') {
                            selectKelas.options[j].disabled = false;
                        }
                        if (selectKelas.options[j].value === 'VIP AC') {
                            selectKelas.options[j].disabled = true;
                        }
                    }
                }
            }

            // Fungsi ini memastikan bahwa saat tombol 'Edit' diklik, logikanya langsung berjalan
            // untuk menyesuaikan data yang diambil dari database.
            $(document).ready(function() {
                $('.modal').on('shown.bs.modal', function () {
                    // Cari elemen select kapasitas di dalam modal yang sedang terbuka
                    var selectElement = $(this).find('select[name="kapasitas_meja"]')[0];
                    if (selectElement) {
                        // Paksa JavaScript memicu event onchange secara virtual
                        selectElement.dispatchEvent(new Event('change'));
                    }
                });
            });
        </script>
    </body>
</html>