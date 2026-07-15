<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail = 'senjakopi3521@gmail.com';
    public string $fromName  = 'Kedai Kopi Admin';

    public string $protocol   = 'smtp';
    public string $SMTPHost   = 'smtp.gmail.com';
    public string $SMTPUser   = 'senjakopi3521@gmail.com';

    // ✅ App Password 16 digit dari Google (bukan password Gmail biasa)
    // Cara buat: https://myaccount.google.com/apppasswords
    public string $SMTPPass   = 'qxvjnfcjwoasiwqc';

    public int    $SMTPPort   = 587;
    public string $SMTPCrypto = 'tls';

    public string $mailType = 'html';
    public string $charset  = 'UTF-8';
    public string $newline  = "\r\n";
    public string $CRLF     = "\r\n";

    public bool $validate      = false;
    public bool $SMTPKeepAlive = false;
    public int  $SMTPDebug     = 0;
    public int  $priority      = 3;
}