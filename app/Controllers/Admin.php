<?php

namespace App\Controllers;

class Admin extends BaseController
{
    // Method untuk halaman Dashboard Utama
    public function dashboard()
    {
        $db = \Config\Database::connect();

        // 1. Hitung total reservasi BULAN INI (berdasarkan bulan saat ini)
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        $reservasiHariIni = $db->table('reservasi')
            ->where('MONTH(tanggal_jadwal)', $currentMonth)
            ->where('YEAR(tanggal_jadwal)', $currentYear)
            ->countAllResults();

        // 2. Hitung estimasi tamu BULAN INI berdasarkan kapasitas meja
        $tamuHariIni = $db->table('reservasi')
            ->selectSum('meja.kapasitas_meja')
            ->join('meja', 'meja.no_meja = reservasi.no_meja')
            ->where('MONTH(reservasi.tanggal_jadwal)', $currentMonth)
            ->where('YEAR(reservasi.tanggal_jadwal)', $currentYear)
            ->get()
            ->getRow()
            ->kapasitas_meja ?? 0;

        // Filter request untuk tabel laporan
        $bulan = $this->request->getGet('bulan') ?? 'all';
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $keyword = $this->request->getGet('keyword') ?? '';

        // 3. Ambil riwayat reservasi untuk ditampilkan di tabel bawah dengan filter
        $builder = $db->table('reservasi');
        $builder->select('
            user.username AS nama_pemesan, 
            reservasi.jumlah_tamu, 
            reservasi.no_meja, 
            reservasi.tanggal_jadwal AS tanggal, 
            reservasi.waktu_jadwal AS waktu,
            reservasi.status_reservasi
        ');
        $builder->join('user', 'user.id_user = reservasi.id_user', 'left');
        
        if ($bulan !== 'all') {
            $builder->where('MONTH(reservasi.tanggal_jadwal)', $bulan);
        }
        if (!empty($tahun) && $tahun !== 'all') {
            $builder->where('YEAR(reservasi.tanggal_jadwal)', $tahun);
        }
        if (!empty($keyword)) {
            $builder->like('user.username', $keyword);
        }

        $builder->orderBy('reservasi.tanggal_jadwal', 'DESC');
        // Jika tidak ada filter yang digunakan (default view), ambil 5 data terbaru
        if ($bulan === 'all' && empty($keyword)) {
            $builder->limit(5); 
        }
        $historyData = $builder->get()->getResultArray();

        // 4. Data Dinamis untuk Grafik 7 Hari Terakhir
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $label = date('d M', strtotime($date));
            $chartLabels[] = $label;
            $count = $db->table('reservasi')->where('tanggal_jadwal', $date)->countAllResults();
            $chartData[] = $count;
        }

        // 5. Data Dinamis untuk Ranking Kopi Terfavorit Bulan Ini (Hanya untuk yang sudah bayar/bukan pending)
        $rankingKopi = $db->table('detail_pesanan')
            ->select('menu.nama_menu AS nama_kopi, SUM(detail_pesanan.jumlah_pesanan) AS jumlah_pesanan')
            ->join('menu', 'menu.id_menu = detail_pesanan.id_menu')
            ->join('reservasi', 'reservasi.id_reservasi = detail_pesanan.id_reservasi')
            ->where('MONTH(reservasi.tanggal_jadwal)', $currentMonth)
            ->where('YEAR(reservasi.tanggal_jadwal)', $currentYear)
            ->where('reservasi.status_reservasi !=', 'Pending')
            ->groupBy('detail_pesanan.id_menu')
            ->orderBy('jumlah_pesanan', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Jika data kosong, beri data default kosong agar tidak error di view
        if (empty($rankingKopi)) {
            $rankingKopi = [];
        }

        // Bungkus semua variabel ke dalam array $data
        $data = [
            'reservasiHariIni' => $reservasiHariIni,
            'totalTamuHariIni' => $tamuHariIni, 
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'rankingKopi' => $rankingKopi,
            'historyData' => $historyData,
            'selectedBulan' => $bulan,
            'selectedTahun' => $tahun,
            'keyword' => $keyword
        ];

        return view('v_admin', $data);
    }

    // ... method karyawan(), meja(), stok(), booking() ...
}