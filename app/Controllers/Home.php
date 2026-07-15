<?php

namespace App\Controllers;

class Home extends BaseController
{
    // Fungsi index() akan memanggil halaman v_jadwal_harian
    public function jadwal_harian()
    {
        $reservasiModel = new \App\Models\ReservasiModel();
        
        $today = date('Y-m-d');
        $reservasiData = $reservasiModel->select('reservasi.*, user.username as nama_pemesan')
                                        ->join('user', 'user.id_user = reservasi.id_user')
                                        ->where('tanggal_jadwal', $today)
                                        ->where('status_reservasi', 'Dikonfirmasi')
                                        ->orderBy('waktu_jadwal', 'ASC')
                                        ->findAll();
                                        
        $data = [
            'reservasiData' => $reservasiData
        ];
        
        return view('v_jadwal_harian', $data);
    }

    public function riwayat_tugas(): string
    {
        return view('v_staff_riwayat');
    }
    public function admin()
    {

        return view('v_admin');
    }
    public function admin_stok(): string
    {
        return view('v_admin_stok');
    }
    public function admin_karyawan(): string
    {
        $karyawanModel = new \App\Models\karyawanModel();

        $data = [
            'karyawanData' => $karyawanModel->findAll()
        ];

        return view('v_admin_karyawan', $data);
    }
    public function admin_meja(): string
    {
        return view('v_admin_meja');
    }
    public function admin_booking(): string
    {
        return view('v_admin_booking');
    }
    public function simpan_stok()
    {
        return redirect()->to('admin/stok')->with('pesan', 'Data barang berhasil ditambahkan!');
    }

    public function update_stok()
    {
        return redirect()->to('admin/stok')->with('pesan', 'Data barang berhasil diubah!');
    }

    public function delete_stok()
    {
        return redirect()->to('admin/stok')->with('pesan', 'Data barang berhasil dihapus secara permanen!');
    }
}
