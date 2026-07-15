<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservasiModel extends Model
{
    protected $table = 'reservasi';
    protected $primaryKey = 'id_reservasi';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama_pemesan',
        'email',
        'whatsapp',
        'jumlah_tamu',
        'kelas_meja',
        'tanggal_jadwal',
        'waktu_jadwal',
        'catatan',
        'status_reservasi',
        'no_meja',
        'id_user',
        'id_karyawan'
    ];

    protected $useTimestamps = false;
}