<?php
/**
 * TEBUR — form ayarları
 *
 * KURULUM
 * 1. Bu dosyanın adını "config.php" olarak değiştirin (kopyalayın).
 * 2. Aşağıdaki "smtp_sifre" satırına e-posta hesabının şifresini yazın.
 * 3. config.php dosyasını sunucuya yükleyin. Bu dosya (config.ornek.php)
 *    sunucuda durabilir, içinde şifre yok.
 *
 * NEDEN SMTP?
 * Alan adınızın SPF kaydı yalnızca Yandex sunucularına gönderim izni
 * veriyor. Natro'nun sunucusundan doğrudan gönderirsek mailler spam'e
 * düşer. Kendi e-posta hesabınız üzerinden gönderince inbox'a ulaşır.
 */

return [

    // Form gönderimlerinin düşeceği adres
    'alici'      => 'info@tebur.com.tr',
    'alici_adi'  => 'TEBUR Dış Ticaret',

    // --- SMTP ayarları -------------------------------------------------
    // E-posta hesabınız Yandex'te ise bu ayarlar doğrudan çalışır.
    'smtp_host'      => 'smtp.yandex.com.tr',
    'smtp_port'      => 465,
    'smtp_guvenlik'  => 'ssl',          // 'ssl' (465) veya 'tls' (587)
    'smtp_kullanici' => 'info@tebur.com.tr',
    'smtp_sifre'     => 'BURAYA_SIFRE_YAZIN',

    // Natro kurumsal e-posta kullanıyorsanız yukarıdaki üç satır yerine:
    //   'smtp_host'     => 'mail.tebur.com.tr',
    //   'smtp_port'     => 465,
    //   'smtp_guvenlik' => 'ssl',
    // Doğru sunucu adını Natro panelindeki e-posta ayarlarından görebilirsiniz.

    // Gönderen olarak görünecek adres. SMTP kullanıcısıyla aynı olmalı,
    // aksi halde Yandex göndermeyi reddeder.
    'gonderen'     => 'info@tebur.com.tr',
    'gonderen_adi' => 'tebur.com.tr formu',

    // Hata ayıklama: sorun yaşarsanız 2 yapın, SMTP konuşmasını gösterir.
    // Canlıda mutlaka 0 kalsın.
    'hata_ayikla'  => 0,
];
