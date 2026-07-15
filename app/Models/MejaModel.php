<?php

namespace App\Models;

use CodeIgniter\Model;

class MejaModel extends Model
{
    protected $table = 'meja';
    protected $primaryKey = 'no_meja';
    
    // MATIKAN AUTO INCREMENT karena no_meja berbentuk string (contoh: '01', 'VIP-01')
    protected $useAutoIncrement = false; 
    protected $returnType = 'array';

    protected $allowedFields = [
        'no_meja',
        'kapasitas_meja',
        'kelas_meja',
        'status_meja'
    ];

    protected $useTimestamps = false;
}