<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{
    protected $table = 'karyawan'; 
    protected $primaryKey = 'id_karyawan'; 
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = [
        'username',
        'email',
        'role',
        'password',
        'foto_profil'
    ];

    protected $useTimestamps = false;
}