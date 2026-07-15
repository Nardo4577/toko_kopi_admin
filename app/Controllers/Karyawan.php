<?php

namespace App\Controllers;

use App\Models\KaryawanModel;

class Karyawan extends BaseController
{
    // ---------------------------------------------------------
    // READ: Menampilkan Halaman dengan Filter & Search
    // ---------------------------------------------------------
    public function karyawan()
    {
        $karyawanModel = new KaryawanModel();

        // Tangkap request pencarian dan filter dari URL
        $role_filter = $this->request->getGet('role');
        $keyword     = $this->request->getGet('keyword');

        // Modifikasi Query berdasarkan inputan
        if (!empty($role_filter)) {
            $karyawanModel->where('role', $role_filter);
        }
        if (!empty($keyword)) {
            $karyawanModel->like('username', $keyword);
        }

        $data = [
            'staffData'        => $karyawanModel->findAll(),
            'role_terpilih'    => $role_filter,
            'keyword_terpilih' => $keyword
        ];

        return view('v_admin_karyawan', $data);
    }

    // ---------------------------------------------------------
    // CREATE: Menyimpan Karyawan Baru & Upload Foto
    // ---------------------------------------------------------
    public function simpan_karyawan()
    {
        $karyawanModel = new KaryawanModel();

        // 1. Mengurus Upload Foto Profil
        $fileFoto = $this->request->getFile('foto_profil');
        $namaFoto = 'default_profil.jpg'; // SUDAH BENAR: Menggunakan .jpg

        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Jika ada foto yang diupload, ganti nama file dan pindahkan ke folder img/profil
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('img/profil', $namaFoto); 
        }

        // 2. Hash Password
        $passwordAsli = $this->request->getPost('password_staff');
        $passwordHash = password_hash($passwordAsli, PASSWORD_DEFAULT);

        // 3. Susun data untuk disimpan ke Database
        $data = [
            'username'    => $this->request->getPost('username_staff'),
            'email'       => $this->request->getPost('email_staff'),
            'role'        => $this->request->getPost('role_staff'),
            'password'    => $passwordHash,
            'foto_profil' => $namaFoto 
        ];

        $karyawanModel->insert($data);
        session()->setFlashdata('pesan', 'Akun Karyawan baru berhasil ditambahkan.');
        return redirect()->to('/admin/karyawan');
    }

    // ---------------------------------------------------------
    // UPDATE: Memperbarui Data & Mengganti Foto Karyawan
    // ---------------------------------------------------------
    public function update_karyawan()
    {
        $karyawanModel = new KaryawanModel();
        $id = $this->request->getPost('id_karyawan');

        // 1. Susun data dasar terlebih dahulu
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
        ];

        // 2. LOGIKA GANTI PASSWORD
        $passwordBaru = $this->request->getPost('password_staff');
        if (!empty($passwordBaru)) {
            $data['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }

        // 3. LOGIKA GANTI FOTO PROFIL
        $fileFoto = $this->request->getFile('foto_profil');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            
            // Hapus foto lama dari folder (SUDAH BENAR: Kecuali jika fotonya masih default_profil.jpg)
            $karyawanLama = $karyawanModel->find($id);
            if ($karyawanLama && !empty($karyawanLama['foto_profil']) && $karyawanLama['foto_profil'] != 'default_profil.jpg') {
                $path = FCPATH . 'img/profil/' . $karyawanLama['foto_profil'];
                if (is_file($path) && file_exists($path)) {
                    unlink($path);
                }
            }

            // Upload foto baru dan tambahkan ke array $data
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('img/profil', $namaFoto);
            $data['foto_profil'] = $namaFoto; 
        }

        // 4. Eksekusi update
        $karyawanModel->update($id, $data);
        // --- TAMBAHAN BARU: UPDATE SESSION JIKA EDIT AKUN SENDIRI ---
        if (session()->get('id_karyawan') == $id) {
            // Jika foto profil ikut diganti, perbarui session fotonya
            if (isset($data['foto_profil'])) {
                session()->set('foto_profil', $data['foto_profil']);
            }
            // Jika username ikut diganti, perbarui juga session namanya
            if (isset($data['username'])) {
                session()->set('username', $data['username']); 
            }
        }
        // ------------------------------------------------------------
        session()->setFlashdata('pesan', 'Data Karyawan berhasil diperbarui.');
        return redirect()->to('/admin/karyawan');
    }

    // ---------------------------------------------------------
    // DELETE: Menghapus Data Karyawan Beserta File Fotonya
    // ---------------------------------------------------------
    public function hapus_karyawan()
    {
        $karyawanModel = new KaryawanModel();
        $id = $this->request->getPost('id_karyawan');

        // Hapus foto fisik dari folder (SUDAH BENAR: Kecuali jika fotonya masih default_profil.jpg)
        $karyawan = $karyawanModel->find($id);
        if ($karyawan && !empty($karyawan['foto_profil']) && $karyawan['foto_profil'] != 'default_profil.jpg') {
            $path = FCPATH . 'img/profil/' . $karyawan['foto_profil'];
            if (is_file($path) && file_exists($path)) {
                unlink($path);
            }
        }

        // Hapus data dari database
        $karyawanModel->delete($id);
        session()->setFlashdata('pesan', 'Akun Karyawan berhasil dihapus permanen.');
        return redirect()->to('/admin/karyawan');
    }
}