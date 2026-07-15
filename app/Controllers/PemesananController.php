<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DetailPesananModel;

// ✅ Load PHPMailer manual (tidak perlu autoload/composer)
require_once APPPATH . 'ThirdParty/PHPMailer/src/Exception.php';
require_once APPPATH . 'ThirdParty/PHPMailer/src/PHPMailer.php';
require_once APPPATH . 'ThirdParty/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class PemesananController extends BaseController
{
    // ============================================================
    // Kirim email pakai PHPMailer (bypass SSL verify issue)
    // ============================================================
    private function kirimEmail(string $to, string $nama, string $subject, string $htmlBody): bool
    {
        $mail = new PHPMailer(true);
        try {
            // ✅ Gunakan env() bukan getenv() agar terbaca dari .env CodeIgniter di hosting
            $smtpHost   = env('email.SMTPHost')   ?: 'smtp.gmail.com';
            $smtpUser   = env('email.SMTPUser')   ?: 'senjakopi3521@gmail.com';
            $smtpPass   = env('email.SMTPPass')   ?: '';
            $smtpPort   = (int)(env('email.SMTPPort') ?: 587);
            $smtpCrypto = env('email.SMTPCrypto') ?: 'tls';

            $mail->isSMTP();
            $mail->Host      = $smtpHost;
            $mail->SMTPAuth  = true;
            $mail->Username  = $smtpUser;
            $mail->Password  = $smtpPass;
            $mail->SMTPSecure = ($smtpCrypto === 'ssl')
                ? PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $smtpPort;

            // Matikan SSL verify agar tidak error di beberapa server hosting
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ];

            $mail->setFrom($smtpUser, 'Kedai Kopi Admin');
            $mail->addAddress($to, $nama);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;

            $mail->send();
            return true;

        } catch (Exception $e) {
            log_message('error', '[kirimEmail] Gagal kirim ke ' . $to . ' | Error: ' . $mail->ErrorInfo);
            return false;
        }
    }

    // ============================================================
    public function index()
    // ============================================================
    {
        $db = \Config\Database::connect();
        $detailPesananModel = new DetailPesananModel();

        $queryReservasi = $db->table('reservasi')
            ->select('reservasi.*, user.username as nama_pelanggan, SUM(detail_pesanan.subtotal) as total_tagihan')
            ->join('user', 'user.id_user = reservasi.id_user', 'left')
            ->join('detail_pesanan', 'detail_pesanan.id_reservasi = reservasi.id_reservasi', 'left')
            ->groupBy('reservasi.id_reservasi')
            ->orderBy('reservasi.id_reservasi', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($queryReservasi as $key => $value) {
            $queryReservasi[$key]['items'] = $detailPesananModel->getMenuOlehReservasi($value['id_reservasi']);
        }

        $data = [
            'pemesananData' => $queryReservasi
        ];

        return view('v_admin_pemesanan', $data);
    }

    // ============================================================
    public function konfirmasiPembayaran()
    // ============================================================
    {
        $db = \Config\Database::connect();
        $id = $this->request->getPost('id_reservasi');

        $reservasi = $db->table('reservasi')->where('id_reservasi', $id)->get()->getRowArray();
        if (!$reservasi) {
            return redirect()->back()->with('error', 'Data reservasi tidak ditemukan.');
        }

        $user = $db->table('user')->where('id_user', $reservasi['id_user'])->get()->getRowArray();
        $email_user = $user['email'] ?? '';
        $nama_user = $user['username'] ?? 'Pelanggan';

        log_message('debug', '[konfirmasiPembayaran] id_reservasi=' . $id . ' | email=' . $email_user);

        $db->table('reservasi')
            ->where('id_reservasi', $id)
            ->update(['status_reservasi' => 'Dikonfirmasi']);

        if (!empty($email_user)) {
            $subject = 'Pembayaran & Booking Meja Dikonfirmasi! 🎉';
            $body = "
                <h3>Halo, {$nama_user}!</h3>
                <p>Kabar baik! Pembayaran dan pemesanan meja Anda dengan kode rujukan <strong>#{$id}</strong> telah sukses <strong>DIKONFIRMASI</strong> oleh admin.</p>
                <p>Silakan datang ke kedai sesuai dengan jadwal reservasi Anda. Terima kasih banyak!</p>
            ";

            if ($this->kirimEmail($email_user, $nama_user, $subject, $body)) {
                log_message('info', '[konfirmasiPembayaran] Email berhasil dikirim ke ' . $email_user);
                session()->setFlashdata('pesan', 'Pembayaran berhasil dikonfirmasi dan email terkirim!');
            } else {
                session()->setFlashdata('pesan', 'Pembayaran dikonfirmasi, tetapi GAGAL mengirim email.');
            }
        } else {
            log_message('error', '[konfirmasiPembayaran] Email kosong untuk id_reservasi=' . $id);
            session()->setFlashdata('pesan', 'Pembayaran dikonfirmasi, tetapi email pelanggan tidak ditemukan.');
        }

        return redirect()->to('/admin/pemesanan');
    }

    // ============================================================
    public function batalPemesanan()
    // ============================================================
    {
        $db = \Config\Database::connect();
        $id = $this->request->getPost('id_reservasi');

        $reservasi = $db->table('reservasi')->where('id_reservasi', $id)->get()->getRowArray();
        if (!$reservasi) {
            return redirect()->back()->with('error', 'Data reservasi tidak ditemukan.');
        }

        $user = $db->table('user')->where('id_user', $reservasi['id_user'])->get()->getRowArray();
        $email_user = $user['email'] ?? '';
        $nama_user = $user['username'] ?? 'Pelanggan';

        log_message('debug', '[batalPemesanan] id_reservasi=' . $id . ' | email=' . $email_user);

        $db->table('reservasi')
            ->where('id_reservasi', $id)
            ->update(['status_reservasi' => 'Dibatalkan']);

        if (!empty($reservasi['no_meja'])) {
            $db->table('meja')
                ->where('no_meja', $reservasi['no_meja'])
                ->update(['status_meja' => 'Tersedia']);
        }

        if (!empty($email_user)) {
            $subject = 'Informasi Pemesanan Meja Dibatalkan ❌';
            $body = "
                <h3>Halo, {$nama_user}.</h3>
                <p>Mohon maaf, pemesanan meja Anda dengan kode rujukan <strong>#{$id}</strong> terpaksa kami <strong>BATALKAN/TOLAK</strong> karena alasan kuota penuh atau kendala teknis lainnya.</p>
                <p>Silakan melakukan pemesanan kembali di waktu yang lain. Terima kasih atas pengertiannya.</p>
            ";

            if ($this->kirimEmail($email_user, $nama_user, $subject, $body)) {
                log_message('info', '[batalPemesanan] Email berhasil dikirim ke ' . $email_user);
                session()->setFlashdata('pesan', 'Pemesanan berhasil dibatalkan dan email notifikasi terkirim.');
            } else {
                session()->setFlashdata('pesan', 'Pemesanan dibatalkan, tetapi GAGAL mengirim email notifikasi.');
            }
        } else {
            log_message('error', '[batalPemesanan] Email kosong untuk id_reservasi=' . $id);
            session()->setFlashdata('pesan', 'Pemesanan dibatalkan, tetapi email pelanggan tidak ditemukan.');
        }

        return redirect()->to('/admin/pemesanan');
    }

    // ============================================================
    public function selesaiPemesanan()
    // ============================================================
    {
        $db = \Config\Database::connect();
        $id = $this->request->getPost('id_reservasi');

        $reservasi = $db->table('reservasi')->where('id_reservasi', $id)->get()->getRowArray();
        if (!$reservasi) {
            return redirect()->back()->with('error', 'Data reservasi tidak ditemukan.');
        }

        $db->table('reservasi')
            ->where('id_reservasi', $id)
            ->update(['status_reservasi' => 'Selesai']);

        if (!empty($reservasi['no_meja'])) {
            $db->table('meja')
                ->where('no_meja', $reservasi['no_meja'])
                ->update(['status_meja' => 'Tersedia']);
        }

        session()->setFlashdata('pesan', 'Pemesanan berhasil diselesaikan. Meja sekarang kembali Tersedia.');
        return redirect()->to('/admin/pemesanan');
    }
}