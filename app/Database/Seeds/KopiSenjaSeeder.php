<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KopiSenjaSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // 1. Seeding Tabel USER (Pelanggan)
        // ==========================================
        $user = [
            ['username' => 'budi_santoso', 'email' => 'budi@gmail.com', 'password' => password_hash('user123', PASSWORD_DEFAULT), 'role' => 'Customer'],
            ['username' => 'siti_aminah', 'email' => 'siti@gmail.com', 'password' => password_hash('user123', PASSWORD_DEFAULT), 'role' => 'Customer'],
            ['username' => 'andi', 'email' => 'andi@gmail.com', 'password' => password_hash('user123', PASSWORD_DEFAULT), 'role' => 'Customer'],
        ];
        $this->db->table('user')->insertBatch($user);

        // ==========================================
        // 2. Seeding Tabel KARYAWAN (Gabungan Staff & Admin)
        // ==========================================
        $karyawan = [
            ['username' => 'admin', 'email' => 'admin@kopisenja.com', 'password' => password_hash('admin123', PASSWORD_DEFAULT), 'role' => 'admin'],
            ['username' => 'ashleigh', 'email' => 'ashleigh@kopisenja.com', 'password' => password_hash('staff123', PASSWORD_DEFAULT), 'role' => 'staff'],
            ['username' => 'joko', 'email' => 'joko@kopisenja.com', 'password' => password_hash('staff123', PASSWORD_DEFAULT), 'role' => 'staff'],
        ];
        $this->db->table('karyawan')->insertBatch($karyawan);

        // ==========================================
        // 3. Seeding Tabel MEJA
        // ==========================================
        $meja = [
            [
                'no_meja'        => '01',
                'kapasitas_meja' => '1 - 2 Orang',
                'kelas_meja'     => 'Reguler',
                'status_meja'    => 'Tersedia'
            ],
            [
                'no_meja'        => '02',
                'kapasitas_meja' => '1 - 2 Orang',
                'kelas_meja'     => 'Reguler',
                'status_meja'    => 'Tersedia'
            ],
            [
                'no_meja'        => '03',
                'kapasitas_meja' => '3 - 4 Orang',
                'kelas_meja'     => 'Reguler',
                'status_meja'    => 'Tersedia'
            ],
            [
                'no_meja'        => 'VIP-01',
                'kapasitas_meja' => '6 - 8 Orang',
                'kelas_meja'     => 'VIP AC',
                'status_meja'    => 'Tersedia'
            ],
        ];
        $this->db->table('meja')->insertBatch($meja);

        // ==========================================
        // 4. Seeding Tabel MENU (Pengganti Produk)
        // ==========================================
        $data = [
            [
                'nama_menu' => 'Kopi Susu Gula Aren',
                'kategori'  => 'Minuman',
                'harga'     => 25000,
                'foto_menu' => '',
            ],
            [
                'nama_menu' => 'Americano Ice',
                'kategori'  => 'Minuman',
                'harga'     => 20000,
                'foto_menu' => '',
            ],
            [
                'nama_menu' => 'Croissant Butter',
                'kategori'  => 'Cemilan',
                'harga'     => 30000,
                'foto_menu' => '',
            ],
            [
                'nama_menu' => 'Nasi Goreng Senja',
                'kategori'  => 'Makanan',
                'harga'     => 35000,
                'foto_menu' => '',
            ]
        ];

        // Insert data ke tabel menu
        $this->db->table('menu')->insertBatch($data);

        // ==========================================
        // 5. Seeding Tabel RESERVASI
        // ==========================================
        // Catatan: id_user dan id_karyawan merujuk pada auto-increment (1, 2, dst)
        $reservasi = [
            [
                'id_user' => 1, // Budi Santoso
                'id_karyawan' => 2, // Dikonfirmasi oleh Ashleigh
                'jumlah_tamu' => 4,
                'catatan' => 'Tolong siapkan kursi tinggi untuk bayi.',
                'tanggal_jadwal' => date('Y-m-d'),
                'waktu_jadwal' => '19:00:00',
                'status_reservasi' => 'Dikonfirmasi',
                'no_meja' => '02',
                'metode_pembayaran' => ''
            ],
            [
                'id_user' => 2, // Siti Aminah
                'id_karyawan' => null, // Belum dikonfirmasi (null)
                'jumlah_tamu' => 2,
                'catatan' => 'Dekat jendela kalau bisa',
                'tanggal_jadwal' => '2026-05-11',
                'waktu_jadwal' => '15:30:00',
                'status_reservasi' => 'Pending',
                'no_meja' => null, // Belum dapat meja karena pending
                'metode_pembayaran' => ''
            ],
            [
                'id_user' => 3, // andi
                'id_karyawan' => null, // Belum dikonfirmasi (null)
                'jumlah_tamu' => 2,
                'catatan' => 'Dekat jendela kalau bisa',
                'tanggal_jadwal' => '2026-05-11',
                'waktu_jadwal' => '15:30:00',
                'status_reservasi' => 'Pending',
                'no_meja' => null, // Belum dapat meja karena pending
                'metode_pembayaran' => ''
            ],
        ];
        $this->db->table('reservasi')->insertBatch($reservasi);

        // ==========================================
        // 6. Seeding Tabel DETAIL_PESANAN (Relasi Reservasi & Menu)
        // ==========================================
        // Budi Santoso (Reservasi #1) memesan Kopi Susu (Menu #1) dan Kentang (Menu #4)
        $detail_pesanan = [
            [
                'id_reservasi' => 1,
                'id_menu' => 1, // Kopi Susu
                'jumlah_pesanan' => 4, // Beli 4 gelas
                'subtotal' => 100000 // 4 x 25.000
            ],
            [
                'id_reservasi' => 1,
                'id_menu' => 4, // Kentang Goreng
                'jumlah_pesanan' => 2, // Beli 2 porsi
                'subtotal' => 44000 // 2 x 22.000
            ],
            // Pesanan milik Siti (ID 2)
            [
                'id_reservasi'   => 2,
                'id_menu'        => 3, // Misal ini ID Nasi Goreng
                'jumlah_pesanan' => 4,
                'subtotal'       => 120000, 
            ],

            // Pesanan milik Andi (ID 3)
            [
                'id_reservasi'   => 3,
                'id_menu'        => 1,
                'jumlah_pesanan' => 5,
                'subtotal'       => 100000, 
            ],
            [
                'id_reservasi'   => 3,
                'id_menu'        => 3,
                'jumlah_pesanan' => 3,
                'subtotal'       => 90000, 
            ]
        ];
        $this->db->table('detail_pesanan')->insertBatch($detail_pesanan);
    }
}