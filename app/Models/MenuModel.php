<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id_menu';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama_menu',
        'kategori',
        'harga',
        'foto_menu',
        'status_ketersediaan',
        'is_bestseller'
    ];

    protected $useTimestamps = false;
}