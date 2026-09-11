# TEBUR Dış Ticaret — kurumsal web sitesi

Statik site. Sunucu tarafı hiçbir şey gerekmiyor; klasörü olduğu gibi
hosting'e yükleyip çalıştırabilirsiniz.

## Klasör yapısı

```
site/
  index.html            Ana sayfa (TR)
  hakkimizda.html
  ne-yapiyoruz.html
  urunler.html
  referanslar.html
  iletisim.html
  en/
    index.html          Home (EN)
    about.html
    what-we-do.html
    products.html
    references.html
    contact.html
  assets/
    css/style.css       Tüm tasarım burada
    js/main.js          Mobil menü, giriş animasyonu, form
    img/                Logo ve favicon dosyaları
    img/logos/          Referans logoları (buraya siz koyacaksınız)
  sitemap.xml
  robots.txt
```

Türkçe ana dil, İngilizce ikinci dil. Sağ üstteki TR / EN geçişi her
sayfayı karşılığına bağlar.

## Yerelde önizleme

`index.html` dosyasına çift tıklamak yeterli. Ya da klasörde:

```
python -m http.server 8000
```

Sonra tarayıcıda `http://localhost:8000`.

## Yayına almadan önce yapılması gerekenler

### 1. Referans logoları

`assets/img/logos/` klasörüne şu dosyaları koyun:

```
sok.png    a101.png    metro.png    bim.png
```

Şeffaf arka planlı PNG, en az 120 piksel yüksekliğinde. Dosyalar
konulduğu anda logolar sitede görünür. Dosya yoksa markanın adı yazıyla
gösterilir, site bozulmaz. Detay: `assets/img/logos/OKU-BENI.txt`

### 2. İletişim formu

Form şu anda bir servise bağlı değil. Bu haliyle "Gönder"e basıldığında
bilgiler ziyaretçinin e-posta uygulamasında hazır bir mesaja dönüşür —
yani çalışır, ama ziyaretçinin mail programını açar.

Gerçek form servisine bağlamak için (ücretsiz, 2 dakika):

1. formspree.io üzerinden bir form oluşturun, size bir adres verir.
2. `iletisim.html` ve `en/contact.html` içinde şu satırı bulun:
   `action="https://formspree.io/f/FORM_ID"`
3. `FORM_ID` yerine size verilen kodu yazın.

Bunu yaptığınızda e-posta yönlendirmesi otomatik devre dışı kalır.

### 3. Alan adı

Tüm `canonical`, `hreflang`, `og:url` ve `sitemap.xml` adresleri
`https://www.tebur.com.tr` olarak yazıldı. Farklı bir adres
kullanacaksanız bu değerleri değiştirin.

### 4. Google Search Console

Yayına aldıktan sonra `sitemap.xml` adresini Search Console'a bildirin.

## Tasarım notları

- Marka renkleri logodan alındı: `#36A9E0` (açık mavi),
  `#03648E` (koyu mavi). `assets/css/style.css` en üstündeki
  `:root` bloğundan değiştirilebilir.
- Yazı tipi: Archivo (Google Fonts). Helvetica ailesine yakın,
  nötr bir grotesk. Sistemde yoksa Helvetica Neue / Arial'a düşer.
- Logodaki chevron biçimi; bölüm başlıklarındaki küçük işaret,
  liste madde imleri ve hero panelindeki geometride tekrar ediyor.
- Sayfalar ortak bir başlık ve alt bilgi kullanıyor. Menüye yeni bir
  sayfa eklerseniz 12 HTML dosyasının hepsinde güncellemeniz gerekir.

## Erişilebilirlik ve performans

- Tek `h1`, sıralı başlık hiyerarşisi, `aria-current`, klavye ile
  gezinme, "İçeriğe geç" bağlantısı.
- Hareketi azaltma tercihi (`prefers-reduced-motion`) açık olan
  cihazlarda animasyonlar kapanır.
- Harici kaynak yalnızca Google Fonts ve iletişim sayfasındaki
  Google Haritalar penceresi. Görsel dosyaları yerel.
