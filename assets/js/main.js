/* TEBUR Dış Ticaret — arayüz davranışları */
(function () {
  'use strict';

  /* --- Mobil menü ------------------------------------------------------- */
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('site-nav');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.style.overflow = open ? 'hidden' : '';
    });

    nav.addEventListener('click', function (e) {
      if (e.target.tagName === 'A') {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        toggle.focus();
      }
    });
  }

  /* --- Kaydırınca içerik girişi ----------------------------------------- */
  var reveals = document.querySelectorAll('.reveal');
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (!reveals.length) return;

  if (reduce || !('IntersectionObserver' in window)) {
    for (var i = 0; i < reveals.length; i++) reveals[i].classList.add('is-visible');
    return;
  }

  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        io.unobserve(entry.target);
      }
    });
  }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

  reveals.forEach(function (el) { io.observe(el); });
})();

/* --- Teklif formu -------------------------------------------------------
   Form gonder.php'ye AJAX ile gönderilir; sayfa yenilenmez.
   JavaScript kapalıysa form normal POST yapar ve teşekkür sayfasına gider. */
(function () {
  'use strict';

  var form = document.getElementById('teklif-formu');
  if (!form || !window.fetch) return;

  var durum = document.getElementById('form-durum');
  var buton = form.querySelector('button[type="submit"]');
  var isEn = document.documentElement.lang === 'en';

  var METIN = isEn
    ? { gonderiliyor: 'Sending…', gonder: 'Send',
        aglHata: 'The message could not be sent. Please write to info@tebur.com.tr.' }
    : { gonderiliyor: 'Gönderiliyor…', gonder: 'Gönder',
        aglHata: 'Mesaj gönderilemedi. Lütfen info@tebur.com.tr adresine yazın.' };

  function goster(mesaj, ok) {
    if (!durum) return;
    durum.textContent = mesaj;
    durum.className = 'form-status ' + (ok ? 'form-status--ok' : 'form-status--hata');
    durum.hidden = false;
  }

  function butonDurumu(bekliyor) {
    if (!buton) return;
    buton.disabled = bekliyor;
    buton.firstChild.nodeValue = bekliyor ? METIN.gonderiliyor : METIN.gonder;
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    if (buton && buton.disabled) return;

    if (durum) durum.hidden = true;
    butonDurumu(true);

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(function (r) { return r.json().catch(function () { return null; }); })
      .then(function (d) {
        if (d && d.ok) {
          goster(d.mesaj, true);
          form.reset();
        } else {
          goster((d && d.mesaj) || METIN.aglHata, false);
        }
      })
      .catch(function () {
        goster(METIN.aglHata, false);
      })
      .then(function () {
        butonDurumu(false);
      });
  });
})();
