<?php

namespace App\Controllers;

use App\Models\MenuModel;

class StockController extends BaseController
{

    // ---------------------------------------------------------
    // READ: Menampilkan Data Menu & Filter Kategori
    // ---------------------------------------------------------
    public function stok()
    {
        $menuModel = new MenuModel();

        $kategori_filter = $this->request->getGet('kategori');
        $keyword         = $this->request->getGet('keyword'); 

        if (!empty($kategori_filter)) {
            $menuModel->where('kategori', $kategori_filter);
        }
        if (!empty($keyword)) {
            $menuModel->like('nama_menu', $keyword); 
        }

        $data = [
            'stokBarang'        => $menuModel->findAll(),
            'kategori_terpilih' => $kategori_filter,
            'keyword_terpilih'  => $keyword 
        ];

        return view('v_admin_stok', $data);
    }

    // ---------------------------------------------------------
    // CREATE: Menyimpan Data Menu Baru
    // ---------------------------------------------------------
    public function simpan_stok()
    {
        $menuModel = new MenuModel();

        $fileFoto = $this->request->getFile('foto_menu');
        $urlFoto  = $this->request->getPost('url_foto'); // Menangkap inputan link URL
        $namaFoto = 'default.jpg';

        // LOGIKA PEMILIHAN GAMBAR
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Opsi 1: Jika admin meng-upload file fisik
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('img/menu', $namaFoto); 
        } elseif (!empty($urlFoto)) {
            // Opsi 2: Jika file fisik tidak di-upload, tapi admin mengisi link URL
            $namaFoto = $urlFoto;
        }

        $data = [
            'nama_menu'           => $this->request->getPost('nama_menu'),
            'kategori'            => $this->request->getPost('kategori'),
            'harga'               => $this->request->getPost('harga'),
            'foto_menu'           => $namaFoto, // Bisa berisi 'x2j3.jpg' atau 'https://...'
            'status_ketersediaan' => $this->request->getPost('status_ketersediaan'),
            'is_bestseller'       => $this->request->getPost('is_bestseller') ? 1 : 0 
        ];

        $menuModel->insert($data);
        session()->setFlashdata('pesan', 'Data Menu baru berhasil ditambahkan.');
        return redirect()->to('/admin/stok');
    }

    // ---------------------------------------------------------
    // UPDATE: Memperbarui Data Menu
    // ---------------------------------------------------------
    public function update_stok()
    {
        $menuModel = new MenuModel();
        $id = $this->request->getPost('id_menu');

        $data = [
            'nama_menu'           => $this->request->getPost('nama_menu'),
            'kategori'            => $this->request->getPost('kategori'),
            'harga'               => $this->request->getPost('harga'),
            'status_ketersediaan' => $this->request->getPost('status_ketersediaan'),
            'is_bestseller'       => $this->request->getPost('is_bestseller') ? 1 : 0
        ];

        $fileFoto = $this->request->getFile('foto_menu');
        $urlFoto  = $this->request->getPost('url_foto');
        
        // Cek Opsi 1: Jika Admin Upload Foto Baru
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            
            // Hapus foto lama, TAPI JANGAN DIHAPUS JIKA ITU LINK HTTP (URL)
            $menuLama = $menuModel->find($id);
            if ($menuLama && !empty($menuLama['foto_menu']) && $menuLama['foto_menu'] != 'default.jpg') {
                if (strpos($menuLama['foto_menu'], 'http') !== 0) { // Pastikan bukan link URL
                    $path = FCPATH . 'img/menu/' . $menuLama['foto_menu'];
                    if (is_file($path) && file_exists($path)) {
                        unlink($path);
                    }
                }
            }

            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('img/menu', $namaFoto);
            $data['foto_menu'] = $namaFoto;

        // Cek Opsi 2: Jika Admin tidak upload file, tapi mengisi Link URL
        } elseif (!empty($urlFoto)) {
            
            // Jika ada perubahan URL, kita juga cek apakah foto lamanya berupa file fisik, jika ya, hapus file fisiknya agar server lega
            $menuLama = $menuModel->find($id);
            if ($menuLama && !empty($menuLama['foto_menu']) && $menuLama['foto_menu'] != 'default.jpg') {
                if (strpos($menuLama['foto_menu'], 'http') !== 0) { 
                    $path = FCPATH . 'img/menu/' . $menuLama['foto_menu'];
                    if (is_file($path) && file_exists($path)) {
                        unlink($path);
                    }
                }
            }

            $data['foto_menu'] = $urlFoto;
        }

        $menuModel->update($id, $data);
        
        session()->setFlashdata('pesan', 'Data Menu berhasil diperbarui.');
        return redirect()->to('admin/stok');
    }

    // ---------------------------------------------------------
    // DELETE: Menghapus Data Menu
    // ---------------------------------------------------------
    public function hapus_stok()
    {
        $menuModel = new MenuModel();
        $id = $this->request->getPost('id_menu');

        $menu = $menuModel->find($id);
        
        // Hanya hapus file fisik JIKA itu bukan link URL ('http')
        if ($menu && !empty($menu['foto_menu']) && $menu['foto_menu'] != 'default.jpg') {
            if (strpos($menu['foto_menu'], 'http') !== 0) { 
                $path = FCPATH . 'img/menu/' . $menu['foto_menu'];
                if (is_file($path) && file_exists($path)) {
                    unlink($path);
                }
            }
        }

        $menuModel->delete($id);
        session()->setFlashdata('pesan', 'Data Menu berhasil dihapus permanen.');
        return redirect()->to('/admin/stok');
    }
}