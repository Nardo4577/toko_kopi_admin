<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKopiSenjaDB extends Migration
{
    public function up()
    {
        // ==========================================
        // 1. TABEL USER
        // ==========================================
        $this->forge->addField([
            'id_user' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('id_user', true);
        $this->forge->createTable('user', true);

        // ==========================================
        // 2. TABEL KARYAWAN (Gabungan Staff & Admin)
        // ==========================================
        $this->forge->addField([
            'id_karyawan' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'VARCHAR', 'constraint' => 50],
            'foto_profil' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'default_profil.jpg'],
        ]);
        $this->forge->addKey('id_karyawan', true);
        $this->forge->createTable('karyawan', true);

        // ==========================================
        // 3. TABEL MEJA
        // ==========================================
        $this->forge->addField([
            'no_meja' => ['type' => 'VARCHAR', 'constraint' => 10], // PK
            'kelas_meja' => ['type' => 'VARCHAR', 'constraint' => 50],
            'kapasitas_meja' => ['type' => 'VARCHAR', 'constraint' => 50],
            'status_meja' => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('no_meja', true);
        $this->forge->createTable('meja', true);

        // ==========================================
        // 4. TABEL MENU (Dulu Produk)
        // ==========================================
        $this->forge->addField([
            'id_menu' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_menu' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'harga' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'status_ketersediaan' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'Tersedia',
            ],
            'is_bestseller' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'foto_menu' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true, // Boleh kosong jika tidak ada foto
            ],
        ]);
        
        $this->forge->addKey('id_menu', true); // Jadikan Primary Key
        $this->forge->createTable('menu');

        // ==========================================
        // 5. TABEL RESERVASI
        // ==========================================
        $this->forge->addField([
            'id_reservasi' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_user' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], // FK
            'id_karyawan' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true], // FK (Bisa null jika belum dikonfirmasi)
            'jumlah_tamu' => ['type' => 'VARCHAR', 'constraint' => 25],
            'whatsapp' => ['type' => 'VARCHAR','constraint' => '20','null' => true],
            'kelas_meja' => ['type' => 'VARCHAR','constraint' => '50','null' => true,],
            'catatan' => ['type' => 'TEXT', 'null' => true],
            'tanggal_jadwal' => ['type' => 'DATE'],
            'waktu_jadwal' => ['type' => 'TIME'],
            'status_reservasi' => ['type' => 'VARCHAR', 'constraint' => 50],
            'no_meja' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true], // FK
            'metode_pembayaran' => ['type' => 'VARCHAR','constraint' => 50,'null' => true],
        ]);
        $this->forge->addKey('id_reservasi', true);
        $this->forge->addForeignKey('id_user', 'user', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_karyawan', 'karyawan', 'id_karyawan', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('no_meja', 'meja', 'no_meja', 'CASCADE', 'SET NULL');
        $this->forge->createTable('reservasi', true);

        // ==========================================
        // 6. TABEL DETAIL_PESANAN (Tabel Baru)
        // ==========================================
        $this->forge->addField([
            'id_detail_pesanan' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_menu' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], // FK
            'id_reservasi' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], // FK
            'catatan_menu' => ['type' => 'VARCHAR', 'constraint' => '255','null' => true,],
            'jumlah_pesanan' => ['type' => 'INT', 'constraint' => 11],
            'subtotal' => ['type' => 'INT', 'constraint' => 11],
        ]);
        $this->forge->addKey('id_detail_pesanan', true);
        $this->forge->addForeignKey('id_menu', 'menu', 'id_menu', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_reservasi', 'reservasi', 'id_reservasi', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_pesanan', true);

        // ==========================================
        // 7. TABEL ULASAN (Tabel Baru)
        // ==========================================
        $this->forge->addField([
            'id_ulasan' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_reservasi' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], // FK
            'id_user' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], // FK
            'rating' => ['type' => 'TINYINT', 'constraint' => 1], // Bintang 1 sampai 5
            'komentar' => ['type' => 'TEXT', 'null' => true],
            'tanggal_ulasan' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id_ulasan', true);
        $this->forge->addForeignKey('id_reservasi', 'reservasi', 'id_reservasi', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_user', 'user', 'id_user', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ulasan', true);
    }

    public function down()
    {
        // Drop tabel dengan urutan terbalik dari pembuatannya untuk menghindari error Foreign Key
        $this->forge->dropTable('ulasan', true);
        $this->forge->dropTable('detail_pesanan', true);
        $this->forge->dropTable('reservasi', true);
        $this->forge->dropTable('menu', true);
        $this->forge->dropTable('meja', true);
        $this->forge->dropTable('karyawan', true);
        $this->forge->dropTable('user', true);
    }
}