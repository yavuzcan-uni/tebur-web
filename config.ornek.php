<?php
/**
 * TEBUR — form ayarları
 *
 * KURULUM
 * 1. Bu dosyanın bir KOPYASINI alın ve adını "config.php" yapın.
 *    Şifreyi bu dosyaya değil, kopyaya yazın. Site yalnızca
 *    "config.php" adlı dosyayı okur.
 * 2. config.php'yi tercihen public_html'in BİR ÜST dizinine koyun
 *    (cPanel'de /home/kullanici/config.php). Orada web sunucusu
 *    dosyaya hiçbir koşulda ulaşamaz.
 *    Üst dizine koyamıyorsanız public_html içinde de çalışır;
 *    oradaki kopyayı .htaccess dışarıya kapatır.
 * 3. "smtp_sifre" satırına e-posta hesabının şifresini yazın.
 *
 * ÖNEMLİ: Şifreyi bu örnek dosyanın içinde bırakmayın. Adı "örnek"
 * olduğu için ileride birine gönderilmesi ya da paylaşılması kolaydır.
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
