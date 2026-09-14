# TEBUR Dış Ticaret — kurumsal web sitesi

Statik HTML + tek bir PHP dosyası (iletişim formu). Veritabanı gerekmiyor.
Natro Başlangıç paketinde sorunsuz çalışır.

## Klasör yapısı

```
site/
  index.html            Ana sayfa (TR)
  hakkimizda.html
  ne-yapiyoruz.html
  referanslar.html
  iletisim.html
  tesekkurler.html      Form gönderildikten sonra (JS kapalıysa)
  404.html              Sayfa bulunamadı

  en/                   İngilizce karşılıkları
    index.html  about.html  what-we-do.html
    references.html  contact.html  thank-you.html

  gonder.php            Form işleyicisi
  config.ornek.php      Ayar şablonu — kopyalayıp config.php yapın
  lib/PHPMailer/        E-posta kütüphanesi (dokunmayın)

  assets/
    css/style.css       Tüm tasarım burada
    js/main.js          Mobil menü, giriş animasyonu, form gönderimi
    img/                Logo, favicon, hero görseli
    img/logos/          Referans logoları

  .htaccess             Linux / cPanel sunucu ayarları
  web.config            Windows / Plesk sunucu ayarları
  sitemap.xml
  robots.txt
```

Türkçe ana dil, İngilizce ikinci dil. Sağ üstteki TR / EN geçişi her sayfayı
karşılığına bağlar.

---

## Natro'ya yükleme

### 1. Dosyaları yükleyin

cPanel → **Dosya Yöneticisi** → `public_html` klasörünün içine, ya da bir FTP
programıyla (FileZilla) aynı yere.

Yüklenecekler: yukarıdaki listedeki her şey. `OKU-BENI.md` dosyasını
yüklemeseniz de olur.

**Sunucunuz Linux ise** `web.config` dosyasını yüklemeyin.
**Sunucunuz Windows ise** `.htaccess` dosyasını yüklemeyin.
Hangisi olduğunu Natro panelindeki hosting paketi sayfasından görebilirsiniz.

`.htaccess` gizli bir dosyadır; FTP programında görünmüyorsa "gizli dosyaları
göster" seçeneğini açın.

### 2. Formu çalışır hale getirin

`config.ornek.php` dosyasının bir **kopyasını** alın, adını **`config.php`**
yapın ve içindeki `smtp_sifre` satırına `info@tebur.com.tr` hesabının şifresini
yazın. Site yalnızca `config.php` adlı dosyayı okur; şifreyi örnek dosyanın
içinde bırakmayın.

`config.php` iki yerde durabilir:

- **Tercih edilen:** `public_html`'in bir üst dizini, yani
  `/home/kullanici/config.php`. Web sunucusu bu klasöre hiçbir koşulda
  erişemez, dosya kazara dışarı açılamaz.
- **Alternatif:** `public_html` içinde. Burada da çalışır; `.htaccess`
  dosyayı dışarıya kapatır (istek 403 döner).

Site önce üst dizine, bulamazsa site köküne bakar.

Neden şifre gerekiyor: alan adınızın SPF kaydı yalnızca Yandex sunucularına
sizin adınıza mail gönderme izni veriyor. Natro'nun sunucusundan doğrudan
gönderirsek mailler spam'e düşer. Form, kendi e-posta hesabınız üzerinden
gönderdiği için inbox'a ulaşır.

Ayarları test etmek için forma bir deneme mesajı gönderin. Ulaşmazsa
`config.php` içindeki `hata_ayikla` değerini `2` yapıp tekrar deneyin; cPanel'de
**Hata Günlükleri** (Error Log) bölümünde ayrıntılı SMTP kaydını görürsünüz.
Sorunu çözünce mutlaka `0` yapın.

### 3. SSL sertifikası

cPanel → **SSL/TLS Status** → AutoSSL'i çalıştırın. Ücretsiz Let's Encrypt
sertifikası gelir. `.htaccess` zaten HTTPS'e ve www'lu adrese yönlendiriyor.

---

## Alan adı (DNS) ayarları

Natro DNS panelinde:

**Silinecek:** `@` için mevcut A kayıtları ve `www` için
`natroredirect.natrocdn.com` CNAME kaydı.

**Eklenecek:** hosting paketinizin IP adresi için A kaydı (`@` ve `www`).
Doğru IP'yi Natro panelindeki hosting bilgileri sayfasında bulabilirsiniz.

**Dokunmayın:** MX kayıtları (`kurumsaleposta.com`, `mx.yandex.net`) ve TXT
kayıtları (SPF, Google ve Yandex doğrulama). Bunları değiştirirseniz
`info@tebur.com.tr` çalışmaz.

---

## Tasarım notları

- Marka renkleri logodan alındı: `#36A9E0` (açık mavi), `#03648E` (koyu mavi).
  `assets/css/style.css` en üstündeki `:root` bloğundan değiştirilebilir.
- Yazı tipi: Archivo (Google Fonts). Helvetica ailesine yakın, nötr bir grotesk.
  Sistemde yoksa Helvetica Neue / Arial'a düşer.
- Logodaki chevron biçimi; bölüm başlıklarındaki küçük işaret, liste madde
  imleri ve CTA bandındaki geometride tekrar ediyor.
- Sayfalar ortak bir başlık ve alt bilgi kullanıyor. Menüye yeni bir sayfa
  eklerseniz 12 HTML dosyasının hepsinde güncellemeniz gerekir.
- Ana sayfadaki liman görseli (`assets/img/hero-liman.jpg`) yapay zekâ ile
  üretilmiş temsilî bir görseldir; firmaya ait bir tesis değildir. Gerçek bir
  fotoğrafınız olduğunda aynı dosya adıyla değiştirmeniz yeterli
  (1400x1050 piksel, JPEG).
- Referans logolarının kaynağı ve ölçüsü: `assets/img/logos/OKU-BENI.txt`

## Erişilebilirlik ve performans

- Tek `h1`, sıralı başlık hiyerarşisi, `aria-current`, klavye ile gezinme,
  "İçeriğe geç" bağlantısı.
- Hareketi azaltma tercihi (`prefers-reduced-motion`) açık olan cihazlarda
  animasyonlar kapanır.
- Form, JavaScript kapalıyken de çalışır (teşekkür sayfasına yönlendirir).
- Formda görünmez bir "bal küpü" alanı var; botların çoğunu sessizce eler.
- Harici kaynak yalnızca Google Fonts ve iletişim sayfasındaki Google Haritalar
  penceresi. Görseller ve kod yerel.

## Güvenlik

- `config.php` sunucuda durur ama web'den açılamaz (`.htaccess` / `web.config`
  engelliyor). Bu dosyayı kimseye göndermeyin, e-postayla paylaşmayın.
- `lib/` klasörüne doğrudan erişim kapalı.
- Form girdileri sunucu tarafında doğrulanıyor; başlık enjeksiyonuna karşı
  satır sonları temizleniyor.
