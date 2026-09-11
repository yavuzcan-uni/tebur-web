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
   Form servisi (Formspree vb.) henüz tanımlanmadıysa, form gönderimi
   kullanıcının e-posta uygulamasında hazır bir mesaja dönüştürülür.
   action değerini gerçek uç nokta ile değiştirdiğinizde bu devre dışı kalır. */
(function () {
  'use strict';
  var form = document.getElementById('teklif-formu');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    if (form.action.indexOf('FORM_ID') === -1) return; // gerçek servis tanımlı

    e.preventDefault();

    var to = form.getAttribute('data-mailto');
    var d = new FormData(form);
    var isEn = document.documentElement.lang === 'en';

    var labels = isEn
      ? { subject: 'Enquiry', name: 'Name', company: 'Company', email: 'E-mail', country: 'Country', msg: 'Request' }
      : { subject: 'Teklif talebi', name: 'Ad Soyad', company: 'Firma', email: 'E-posta', country: 'Ülke', msg: 'Talep' };

    var lines = [
      labels.name + ': ' + (d.get('ad') || ''),
      labels.company + ': ' + (d.get('firma') || ''),
      labels.email + ': ' + (d.get('eposta') || ''),
      labels.country + ': ' + (d.get('ulke') || ''),
      '',
      labels.msg + ':',
      d.get('mesaj') || ''
    ];

    var subject = labels.subject + ' — ' + (d.get('firma') || d.get('ad') || '');
    window.location.href = 'mailto:' + to +
      '?subject=' + encodeURIComponent(subject) +
      '&body=' + encodeURIComponent(lines.join('\n'));
  });
})();
