<?php

namespace App\Controllers;

use App\Models\MejaModel; 

class MejaController extends BaseController
{
    // =========================================================================
    // FITUR KELOLA MEJA
    // =========================================================================

    // 1. READ: Menampilkan Halaman Meja
    public function meja()
    {
        $mejaModel = new MejaModel();

        // Tangkap request pencarian dan filter
        $status_filter = $this->request->getGet('status_meja');
        $keyword       = $this->request->getGet('keyword');

        // Query
        if (!empty($status_filter)) {
            $mejaModel->where('status_meja', $status_filter);
        }
        if (!empty($keyword)) {
            $mejaModel->like('no_meja', $keyword);
        }

        $data = [
            'mejaData'        => $mejaModel->findAll(),
            'status_terpilih' => $status_filter,
            'keyword_terpilih'=> $keyword
        ];

        return view('v_admin_meja', $data);
    }

    // 2. CREATE: Menyimpan Data Meja Baru
    public function simpan_meja()
    {
        $mejaModel = new MejaModel();

        $data = [
            'no_meja'        => $this->request->getPost('no_meja'),
            'kapasitas_meja' => $this->request->getPost('kapasitas_meja'),
            'kelas_meja'     => $this->request->getPost('kelas_meja'),
            'status_meja'    => 'Tersedia',
        ];

        // Validasi: Cek apakah No Meja sudah ada di database agar tidak duplikat
        if ($mejaModel->find($data['no_meja'])) {
            session()->setFlashdata('pesan', 'Gagal! Nomor Meja tersebut sudah terdaftar.');
            return redirect()->to('/admin/meja');
        }

        $mejaModel->insert($data);
        session()->setFlashdata('pesan', 'Data Meja baru berhasil ditambahkan.');
        return redirect()->to('/admin/meja');
    }

    // 3. UPDATE: Memperbarui Data Meja
    public function update_meja()
    {
        $mejaModel = new MejaModel();
        
        $id = $this->request->getPost('no_meja');

        // status_meja dihapus dari array agar tidak tertimpa saat diedit
        $data = [
            'kapasitas_meja' => $this->request->getPost('kapasitas_meja'),
            'kelas_meja'     => $this->request->getPost('kelas_meja'),
        ];

        $mejaModel->update($id, $data);
        session()->setFlashdata('pesan', 'Data Meja berhasil diperbarui.');
        return redirect()->to('/admin/meja');
    }

    // 4. DELETE: Menghapus Data Meja
    public function hapus_meja()
    {
        $mejaModel = new MejaModel();
        $id = $this->request->getPost('no_meja');

        $mejaModel->delete($id);
        session()->setFlashdata('pesan', 'Data Meja berhasil dihapus permanen.');
        return redirect()->to('/admin/meja');
    }
}