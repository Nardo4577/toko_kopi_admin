<?php
// Menangkap parameter dari URL (misal: /login?action=forgot)
$action = $_GET['action'] ?? 'login';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Portal | Kedai Kopi Senja</title>
    
    <link href="https://fonts.googleapis.com/css?family=Righteous&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        body, html { height: 100%; margin: 0; font-family: 'Poppins', sans-serif; }
        .bg-image {
            background-image: url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=1900&q=80');
            height: 100%; background-position: center; background-repeat: no-repeat; background-size: cover;
            position: relative; display: flex; align-items: center; justify-content: center;
        }
        .bg-image::after { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(62, 39, 35, 0.75); z-index: 1; }
        .login-container { position: relative; z-index: 2; width: 100%; max-width: 420px; padding: 15px; }
        .login-card { background: #fdfbf7; border-radius: 20px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5); overflow: hidden; }
        
        /* Tema Warna Berubah Tergantung Action */
        .login-header { background-color: <?= ($action == 'forgot') ? '#8d6e63' : '#3e2723' ?>; padding: 40px 20px 30px; text-align: center; position: relative; transition: 0.3s; }
        .login-header::after { content: ''; position: absolute; bottom: -25px; left: 0; width: 100%; height: 50px; background-color: #fdfbf7; border-radius: 50%; }
        .login-header img { width: 50px; margin-bottom: 10px; }
        .login-header h3 { color: #fff; font-family: 'Righteous', cursive; font-size: 32px; margin: 0; letter-spacing: 1px; }
        .login-header p { color: #f1c40f; font-size: 13px; margin-top: 5px; margin-bottom: 0; }
        
        .login-body { padding: 20px 40px 40px; background-color: #fdfbf7; }
        .form-label { font-weight: 600; color: #3e2723; font-size: 13px; margin-bottom: 8px; display: block; }
        .custom-input { border-radius: 10px; border: 1px solid #d7ccc8; padding: 12px 15px; background-color: #fff; height: auto; font-size: 14px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.02); }
        .custom-input:focus { border-color: #8d6e63; box-shadow: 0 0 5px rgba(141, 110, 99, 0.3); outline: none; }
        
        .btn-kopi { background-color: #8d6e63; color: white; border-radius: 30px; padding: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border: none; transition: all 0.3s ease; margin-top: 15px; }
        .btn-kopi:hover { background-color: #3e2723; color: #f1c40f; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(62, 39, 35, 0.3); }
        .alert-custom { border-radius: 10px; font-size: 13px; text-align: center; padding: 10px; margin-bottom: 20px; }
    </style>
</head>

<body>
    <div class="bg-image">
        <div class="login-container">
            <div class="login-card">
                
                <div class="login-header">
                    <?php if ($action == 'forgot'): ?>
                        <i class="fa fa-unlock-alt" style="font-size: 40px; color: #fff; margin-bottom: 10px;"></i>
                        <h3 style="font-size: 26px;">Pemulihan Akun</h3>
                        <p>Verifikasi identitas Staff Anda</p>
                    <?php else: ?>
                        <img src="https://cdn-icons-png.flaticon.com/64/924/924514.png" alt="Logo Kopi Senja">
                        <h3>Kopi Senja</h3>
                        <p>Silakan masuk ke Portal Sistem</p>
                    <?php endif; ?>
                </div>

                <div class="login-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-custom">
                            <i class="fa fa-exclamation-triangle" style="margin-right: 5px;"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-custom" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
                            <i class="fa fa-check-circle" style="margin-right: 5px;"></i> <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('validasi_sukses') || session()->getFlashdata('reset_user_id')): ?>
                        
                        <div class="alert alert-success alert-custom" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
                            Halo <strong><?= session()->getFlashdata('reset_username') ?></strong>! Identitas terverifikasi.
                        </div>
                        
                        <form action="<?= base_url('/reset-password-action') ?>" method="post">
                            <input type="hidden" name="id_karyawan" value="<?= session()->getFlashdata('reset_user_id') ?>">
                            <div class="form-group" style="margin-bottom: 25px;">
                                <label class="form-label">Buat Password Baru</label>
                                <input type="password" name="password_baru" class="form-control custom-input" placeholder="Minimal 6 karakter" required minlength="6">
                            </div>
                            <button type="submit" class="btn btn-kopi w-100" style="background-color: #27ae60;">
                                <i class="fa fa-save" style="margin-right: 5px;"></i> Simpan & Login
                            </button>
                        </form>


                    <?php elseif ($action == 'forgot'): ?>
                    
                        <p style="text-align: center; font-size: 13px; color: #666; margin-bottom: 25px;">
                            Masukkan Username dan Email yang terdaftar untuk membuat password baru.
                        </p>

                        <form action="<?= base_url('/validasi-lupa-password') ?>" method="post">
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control custom-input" placeholder="Masukkan username Anda" required>
                            </div>
                            <div class="form-group" style="margin-bottom: 25px;">
                                <label class="form-label">Email Terdaftar</label>
                                <input type="email" name="email" class="form-control custom-input" placeholder="Contoh: staff@kopisenja.com" required>
                            </div>
                            <button type="submit" class="btn btn-kopi w-100">
                                <i class="fa fa-search" style="margin-right: 5px;"></i> Validasi Akun
                            </button>
                        </form>

                        <div style="text-align: center; margin-top: 25px;">
                            <a href="<?= base_url('login') ?>" style="font-size: 12px; color: #8d6e63; text-decoration: none; font-weight: bold;">
                                <i class="fa fa-arrow-left"></i> Kembali ke Login Normal
                            </a>
                        </div>


                    <?php else: ?>

                        <form action="<?= base_url('/loginAction') ?>" method="post">
                            <?= csrf_field() ?> 
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control custom-input" placeholder="Masukkan username..." required>
                            </div>
                            <div class="form-group" style="margin-bottom: 25px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <label class="form-label" style="margin-bottom: 0;">Password</label>
                                    <a href="<?= base_url('login?action=forgot') ?>" style="font-size: 11px; color: #8d6e63; font-weight: bold; text-decoration: none;">Lupa Password?</a>
                                </div>
                                <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
                            </div>
                            <button type="submit" class="btn btn-kopi w-100">
                                <i class="fa fa-sign-in" style="margin-right: 5px;"></i> Masuk Sekarang
                            </button>
                        </form>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</body>
</html>