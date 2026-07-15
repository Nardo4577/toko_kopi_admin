<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReservasiModel;
use App\Models\MejaModel;

//  Load PHPMailer manual (tidak perlu autoload/composer)
require_once APPPATH . 'ThirdParty/PHPMailer/src/Exception.php';
require_once APPPATH . 'ThirdParty/PHPMailer/src/PHPMailer.php';
require_once APPPATH . 'ThirdParty/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ReservasiController extends BaseController
{
    // ============================================================
    // HELPER: Kirim email pakai PHPMailer (bypass SSL verify issue)
    // ============================================================
    private function kirimEmail(string $to, string $nama, string $subject, string $htmlBody): bool
    {
        $mail = new PHPMailer(true);
        try {
            //  Setting SMTP
            $mail->isSMTP();
            $mail->Host = getenv('email.SMTPHost') ?: 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = getenv('email.SMTPUser') ?: 'senjakopi3521@gmail.com';
            $mail->Password = getenv('email.SMTPPass');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = getenv('email.SMTPPort') ?: 587;

            //  INI KUNCI FIX-NYA: matikan SSL verify agar tidak error di Windows/XAMPP
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            // Pengirim & penerima
            $mail->setFrom('senjakopi3521@gmail.com', 'Kedai Kopi Admin');
            $mail->addAddress($to, $nama);

            // Konten
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;

            $mail->send();
            return true;

        } catch (Exception $e) {
            log_message('error', '[kirimEmail] Gagal kirim ke ' . $to . ' | Error: ' . $mail->ErrorInfo);
            return false;
        }
    }

    // ============================================================
    public function booking()
    // ============================================================
    {
        $db = \Config\Database::connect();
        $builder = $db->table('reservasi');

        $builder->select('
            reservasi.id_reservasi, 
            u_pelanggan.username AS nama_pemesan, 
            reservasi.jumlah_tamu,
            reservasi.whatsapp,
            reservasi.kelas_meja,
            reservasi.catatan, 
            reservasi.status_reservasi, 
            reservasi.no_meja, 
            reservasi.tanggal_jadwal, 
            reservasi.waktu_jadwal,
            reservasi.id_karyawan,
            k_admin.username AS nama_admin
        ');

        $builder->join('user AS u_pelanggan', 'u_pelanggan.id_user = reservasi.id_user', 'left');
        $builder->join('karyawan AS k_admin', 'k_admin.id_karyawan = reservasi.id_karyawan', 'left');
        $builder->orderBy('reservasi.id_reservasi', 'DESC');

        $mejaModel = new MejaModel();
        $mejaTersedia = $mejaModel->where('status_meja', 'Tersedia')->findAll();

        $data = [
            'bookingData' => $builder->get()->getResultArray(),
            'mejaTersedia' => $mejaTersedia
        ];

        return view('v_admin_booking', $data);
    }

    // ============================================================
    public function konfirmasi_booking()
    // ============================================================
    {
        $db = \Config\Database::connect();
        $reservasiModel = new ReservasiModel();
        $mejaModel = new MejaModel();

        $id_reservasi = $this->request->getPost('id_reservasi');
        $no_meja = $this->request->getPost('no_meja');
        $id_admin_login = session()->get('id_karyawan');

        if (!$id_admin_login) {
            return redirect()->back()->with('error', 'Sesi admin hilang. Silakan Logout dan Login kembali.');
        }

        $dataReservasi = $reservasiModel->find($id_reservasi);
        if (!$dataReservasi) {
            return redirect()->back()->with('error', 'Data reservasi tidak ditemukan.');
        }

        $userData = $db->table('user')->where('id_user', $dataReservasi['id_user'])->get()->getRowArray();
        $email_user = $userData['email'] ?? '';
        $nama_user = $userData['username'] ?? 'Pelanggan';

        log_message('debug', '[konfirmasi_booking] id_reservasi=' . $id_reservasi . ' | email=' . $email_user);

        // Update database dulu
        $reservasiModel->update($id_reservasi, [
            'status_reservasi' => 'Dikonfirmasi',
            'no_meja' => $no_meja,
            'id_karyawan' => $id_admin_login
        ]);

        if (!empty($no_meja)) {
            $mejaModel->update($no_meja, ['status_meja' => 'Tidak Tersedia']);
        }

        // Kirim email via PHPMailer
        if (!empty($email_user)) {
            $subject = 'Booking Meja Anda Telah Dikonfirmasi! 🎉';
            $body = "
                <h3>Halo, {$nama_user}!</h3>
                <p>Kabar baik! Pemesanan meja Anda telah <strong>DIKONFIRMASI</strong> oleh admin.</p>
                <p>Berikut adalah rincian pesanan Anda:</p>
                <table border='0' cellpadding='5'>
                    <tr><td><strong>ID Reservasi</strong></td><td>: #{$id_reservasi}</td></tr>
                    <tr><td><strong>Nomor Meja</strong></td><td>: Meja Nomor {$no_meja}</td></tr>
                    <tr><td><strong>Status</strong></td><td>: <span style='color:green;'>Dikonfirmasi</span></td></tr>
                </table>
                <p>Silakan datang tepat waktu sesuai jadwal reservasi Anda. Terima kasih!</p>
            ";

            if ($this->kirimEmail($email_user, $nama_user, $subject, $body)) {
                log_message('info', '[konfirmasi_booking] Email berhasil dikirim ke ' . $email_user);
                session()->setFlashdata('pesan', 'Reservasi dikonfirmasi & Notifikasi email terkirim.');
            } else {
                session()->setFlashdata('pesan', 'Reservasi dikonfirmasi, tetapi GAGAL mengirim email.');
            }
        } else {
            log_message('error', '[konfirmasi_booking] Email kosong untuk id_reservasi=' . $id_reservasi);
            session()->setFlashdata('pesan', 'Reservasi dikonfirmasi, tetapi email pelanggan tidak ditemukan.');
        }

        return redirect()->to('/admin/booking');
    }

    // ============================================================
    public function batal_booking()
    // ============================================================
    {
        $reservasiModel = new ReservasiModel();
        $mejaModel = new MejaModel();

        $id_reservasi = $this->request->getPost('id_reservasi');
        $id_admin_login = session()->get('id_karyawan');

        // Cari tahu dulu data reservasi aslinya di database
        $cek_reservasi = $reservasiModel->find($id_reservasi);

        if (!$cek_reservasi) {
            return redirect()->back()->with('error', 'Data reservasi tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $userData = $db->table('user')->where('id_user', $cek_reservasi['id_user'])->get()->getRowArray();
        $email_user = $userData['email'] ?? '';
        $nama_user = $userData['username'] ?? 'Pelanggan';

        // 1. Batalkan pesanannya
        $reservasiModel->update($id_reservasi, [
            'status_reservasi' => 'Dibatalkan',
            'id_karyawan' => $id_admin_login
        ]);

        // 2. Ambil nomor meja langsung dari database (Anti-gagal walau form HTML rusak)
        $meja_yang_disandera = $cek_reservasi['no_meja'] ?? null;

        if (!empty($meja_yang_disandera)) {
            $mejaModel->update($meja_yang_disandera, ['status_meja' => 'Tersedia']);
        }

        // Kirim email via PHPMailer
        if (!empty($email_user)) {
            $subject = 'Booking Meja Anda Dibatalkan ❌';
            $body = "
                <h3>Halo, {$nama_user}.</h3>
                <p>Mohon maaf, pemesanan meja Anda dengan kode <strong>#{$id_reservasi}</strong> terpaksa kami batalkan karena beberapa alasan operasional.</p>
                <p>Silakan melakukan pemesanan kembali di waktu yang lain. Terima kasih.</p>
            ";

            if ($this->kirimEmail($email_user, $nama_user, $subject, $body)) {
                log_message('info', '[batal_booking] Email berhasil dikirim ke ' . $email_user);
                session()->setFlashdata('pesan', 'Reservasi berhasil dibatalkan dan email notifikasi terkirim.');
            } else {
                session()->setFlashdata('pesan', 'Reservasi dibatalkan, tetapi GAGAL mengirim email notifikasi.');
            }
        } else {
            log_message('error', '[batal_booking] Email kosong untuk id_reservasi=' . $id_reservasi);
            session()->setFlashdata('pesan', 'Reservasi dibatalkan, tetapi email pelanggan tidak ditemukan.');
        }

        return redirect()->to('/admin/booking');
    }
}