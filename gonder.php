<?php
/**
 * TEBUR — teklif formu işleyicisi
 * PHP 5.5 - 8.x ile çalışır. Veritabanı gerektirmez.
 */

require __DIR__ . '/lib/PHPMailer/Exception.php';
require __DIR__ . '/lib/PHPMailer/PHPMailer.php';
require __DIR__ . '/lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/* --- Yardımcılar -------------------------------------------------------- */

function kes($metin, $sinir)
{
    if (function_exists('mb_substr')) {
        return mb_substr($metin, 0, $sinir, 'UTF-8');
    }
    return substr($metin, 0, $sinir);
}

function istek_ajax()
{
    $x = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : '';
    return strtolower($x) === 'xmlhttprequest';
}

function alan($ad, $sinir = 500)
{
    $v = isset($_POST[$ad]) ? $_POST[$ad] : '';
    if (!is_string($v)) {
        return '';
    }
    $v = trim($v);
    // Başlık enjeksiyonuna karşı satır sonlarını temizle
    $v = str_replace(["\r", "\n", "%0a", "%0d"], ' ', $v);
    return kes($v, $sinir);
}

function bitir($ok, $mesaj, $dil)
{
    if (istek_ajax()) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($ok ? 200 : 400);
        echo json_encode(['ok' => $ok, 'mesaj' => $mesaj], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // JavaScript kapalıysa: teşekkür sayfasına ya da forma geri dön
    if ($ok) {
        $hedef = $dil === 'en' ? 'en/thank-you' : 'tesekkurler';
    } else {
        $hedef = ($dil === 'en' ? 'en/contact' : 'iletisim') . '?hata=1#form';
    }
    header('Location: ' . $hedef, true, 303);
    exit;
}

/* --- Giriş kontrolleri -------------------------------------------------- */

$dil = (isset($_POST['dil']) && $_POST['dil'] === 'en') ? 'en' : 'tr';

$M = $dil === 'en'
    ? [
        'yontem'  => 'Invalid request.',
        'eksik'   => 'Please fill in all required fields.',
        'eposta'  => 'Please enter a valid e-mail address.',
        'ayar'    => 'The form is not configured yet. Please write to info@tebur.com.tr.',
        'hata'    => 'The message could not be sent. Please write to info@tebur.com.tr.',
        'tamam'   => 'Thank you — your enquiry has reached us. We will get back to you shortly.',
      ]
    : [
        'yontem'  => 'Geçersiz istek.',
        'eksik'   => 'Lütfen zorunlu alanları doldurun.',
        'eposta'  => 'Lütfen geçerli bir e-posta adresi yazın.',
        'ayar'    => 'Form henüz yapılandırılmadı. Lütfen info@tebur.com.tr adresine yazın.',
        'hata'    => 'Mesaj gönderilemedi. Lütfen info@tebur.com.tr adresine yazın.',
        'tamam'   => 'Teşekkürler, talebiniz bize ulaştı. En kısa sürede dönüş yapacağız.',
      ];

if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    bitir(false, $M['yontem'], $dil);
}

// Bal küpü: botlar gizli alanı doldurur, insanlar görmez.
// Sessizce başarılı gibi davran, bot tekrar denemesin.
if (alan('website') !== '') {
    bitir(true, $M['tamam'], $dil);
}

$ad     = alan('ad', 120);
$firma  = alan('firma', 160);
$eposta = alan('eposta', 160);
$ulke   = alan('ulke', 80);
$mesaj  = isset($_POST['mesaj']) ? trim((string) $_POST['mesaj']) : '';
$mesaj  = kes($mesaj, 5000);

if ($ad === '' || $firma === '' || $eposta === '' || $mesaj === '') {
    bitir(false, $M['eksik'], $dil);
}
if (!filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
    bitir(false, $M['eposta'], $dil);
}

/* --- Ayarlar ------------------------------------------------------------ */

// config.php once public_html'in bir ust dizininde aranir. Orada durursa
// web sunucusu o dosyaya hicbir kosulda ulasamaz, en guvenli yer orasi.
// Bulunamazsa site kokune bakilir; oradaki kopya .htaccess ile korunur.
$cfg = null;
foreach (array(dirname(__DIR__) . '/config.php', __DIR__ . '/config.php') as $cfg_yolu) {
    if (is_file($cfg_yolu)) {
        $cfg = require $cfg_yolu;
        break;
    }
}
if ($cfg === null) {
    bitir(false, $M['ayar'], $dil);
}
if (!is_array($cfg) || empty($cfg['smtp_sifre']) || $cfg['smtp_sifre'] === 'BURAYA_SIFRE_YAZIN') {
    bitir(false, $M['ayar'], $dil);
}

/* --- Gönderim ----------------------------------------------------------- */

$satirlar = [
    'Ad Soyad : ' . $ad,
    'Firma    : ' . $firma,
    'E-posta  : ' . $eposta,
    'Ülke     : ' . ($ulke !== '' ? $ulke : '-'),
    'Dil      : ' . strtoupper($dil),
    '',
    'Talep:',
    $mesaj,
    '',
    str_repeat('-', 48),
    'Gönderim : ' . date('d.m.Y H:i'),
    'IP       : ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '-'),
];
$govde = implode("\n", $satirlar);

$mail = new PHPMailer(true);

try {
    $mail->CharSet  = PHPMailer::CHARSET_UTF8;
    $mail->Encoding = PHPMailer::ENCODING_BASE64;

    $mail->isSMTP();
    $mail->Host       = $cfg['smtp_host'];
    $mail->Port       = (int) $cfg['smtp_port'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $cfg['smtp_kullanici'];
    $mail->Password   = $cfg['smtp_sifre'];
    $mail->SMTPSecure = ($cfg['smtp_guvenlik'] === 'tls')
        ? PHPMailer::ENCRYPTION_STARTTLS
        : PHPMailer::ENCRYPTION_SMTPS;
    $mail->Timeout    = 20;
    $mail->SMTPDebug  = isset($cfg['hata_ayikla']) ? (int) $cfg['hata_ayikla'] : 0;
    if ($mail->SMTPDebug > 0) {
        $mail->Debugoutput = 'error_log';
    }

    $mail->setFrom($cfg['gonderen'], isset($cfg['gonderen_adi']) ? $cfg['gonderen_adi'] : 'tebur.com.tr');
    $mail->addAddress($cfg['alici'], isset($cfg['alici_adi']) ? $cfg['alici_adi'] : '');
    $mail->addReplyTo($eposta, $ad !== '' ? $ad : $eposta);

    $mail->Subject = 'Teklif talebi — ' . ($firma !== '' ? $firma : $ad);
    $mail->isHTML(false);
    $mail->Body    = $govde;

    $mail->send();
    bitir(true, $M['tamam'], $dil);

} catch (Exception $e) {
    error_log('TEBUR form hatasi: ' . $mail->ErrorInfo);
    bitir(false, $M['hata'], $dil);
}
