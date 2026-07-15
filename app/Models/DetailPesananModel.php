<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPesananModel extends Model
{
    protected $table            = 'detail_pesanan';
    protected $primaryKey       = 'id_detail_pesanan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['id_menu', 'id_reservasi', 'jumlah_pesanan', 'subtotal','catatan_menu'];

    // Fungsi mengambil item menu yang dipesan berdasarkan ID Reservasi
    public function getMenuOlehReservasi($id_reservasi)
    {
        return $this->db->table($this->table)
            ->join('menu', 'menu.id_menu = detail_pesanan.id_menu')
            ->where('id_reservasi', $id_reservasi)
            ->get()
            ->getResultArray();
    }
}
