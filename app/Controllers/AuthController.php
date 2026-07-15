<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KaryawanModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function index()
    {
        return view('v_login');
    }

    public function loginAction()
    {
        $model = new KaryawanModel(); // 2. INSTANSIASI karyawanModel

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Pencarian ini sekarang akan berhasil karena mencari di tabel 'karyawan'
        $user = $model->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'id_karyawan' => $user['id_karyawan'],
                // 3. Ubah key session menjadi 'username' agar nama muncul di pojok kanan atas v_admin.php Anda
                'username' => $user['username'],
                'role' => $user['role'],
                'foto_profil' => $user['foto_profil'],
                'isLoggedIn' => true
            ]);

            // Pengecekan role menggunakan strtolower agar kebal terhadap huruf besar/kecil (Staff vs staff)
            if (strtolower($user['role']) == 'staff') {
                return redirect()->to('/jadwal_harian');
            } else {
                // Untuk Manager / Admin
                return redirect()->to('/admin/dashboard');
            }

        }

        return redirect()->back()->with('error', 'Login Gagal! Username atau password salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda berhasil logout!');
    }

    // ---------------------------------------------------------
    // LUPA PASSWORD: 1. Memvalidasi Inputan Username & Email
    // ---------------------------------------------------------
    public function validasiLupaPassword()
    {
        $model = new KaryawanModel();

        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');

        $user = $model->where('username', $username)
                      ->where('email', $email)
                      ->first();

        if ($user) {
            // Jika data cocok, simpan data ke session sementara
            session()->setFlashdata('reset_user_id', $user['id_karyawan']);
            session()->setFlashdata('reset_username', $user['username']);
            session()->setFlashdata('validasi_sukses', true);
            
            // Arahkan kembali ke halaman login, dengan memicu form reset password
            return redirect()->to('/login?action=forgot');
        }

        return redirect()->back()->with('error', 'Validasi gagal! Username atau Email tidak terdaftar.');
    }

    // ---------------------------------------------------------
    // LUPA PASSWORD: 2. Mengeksekusi Perubahan Password Baru
    // ---------------------------------------------------------
    public function resetPasswordAction()
    {
        $model = new KaryawanModel();
        
        $id_karyawan  = $this->request->getPost('id_karyawan');
        $passwordBaru = $this->request->getPost('password_baru');

        // Enkripsi dan Simpan
        $passwordHash = password_hash($passwordBaru, PASSWORD_DEFAULT);
        $model->update($id_karyawan, ['password' => $passwordHash]);

        // Hapus session sementara
        session()->remove('reset_user_id');
        session()->remove('reset_username');

        // Balik ke login normal dengan pesan sukses
        return redirect()->to('/login')->with('success', 'Password Anda berhasil diperbarui! Silakan login kembali.');
    }
}
