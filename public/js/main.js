/* =====================================================================
   CRUZ DE OSSOS — DRAFT JAVASCRIPT
   ===================================================================== */
(function () {
  'use strict';

  /* ---------- HERO SLIDER ---------- */
  var heroSlides = document.querySelectorAll('.hero__slide');
  var heroDots = document.getElementById('heroDots');
  var heroNext = document.getElementById('heroNext');
  var heroPrev = document.getElementById('heroPrev');
  var heroCurrent = 0;
  var heroTimer;

  function buildHeroDots() {
    if (!heroDots) return;
    heroDots.innerHTML = '';
    heroSlides.forEach(function (_, i) {
      var b = document.createElement('button');
      if (i === 0) b.classList.add('active');
      b.addEventListener('click', function () { goHero(i); resetHeroTimer(); });
      heroDots.appendChild(b);
    });
  }

  function goHero(i) {
    if (!heroSlides.length) return;
    heroSlides[heroCurrent].classList.remove('active');
    if (heroDots) heroDots.children[heroCurrent].classList.remove('active');
    heroCurrent = (i + heroSlides.length) % heroSlides.length;
    heroSlides[heroCurrent].classList.add('active');
    if (heroDots) heroDots.children[heroCurrent].classList.add('active');
  }

  function nextHero() { goHero(heroCurrent + 1); }
  function prevHero() { goHero(heroCurrent - 1); }
  function resetHeroTimer() {
    clearInterval(heroTimer);
    heroTimer = setInterval(nextHero, 6000);
  }

  if (heroNext) heroNext.addEventListener('click', function () { nextHero(); resetHeroTimer(); });
  if (heroPrev) heroPrev.addEventListener('click', function () { prevHero(); resetHeroTimer(); });
  if (heroSlides.length) { buildHeroDots(); resetHeroTimer(); }

  /* ---------- TESTIMONIALS SLIDER ---------- */
  var testi = document.querySelectorAll('.testimonial');
  var testiDots = document.getElementById('testiDots');
  var testiCurrent = 0;
  var testiTimer;

  function buildTestiDots() {
    if (!testiDots) return;
    testiDots.innerHTML = '';
    testi.forEach(function (_, i) {
      var b = document.createElement('button');
      if (i === 0) b.classList.add('active');
      b.addEventListener('click', function () { goTesti(i); resetTestiTimer(); });
      testiDots.appendChild(b);
    });
  }

  function goTesti(i) {
    if (!testi.length) return;
    testi[testiCurrent].classList.remove('active');
    if (testiDots) testiDots.children[testiCurrent].classList.remove('active');
    testiCurrent = (i + testi.length) % testi.length;
    testi[testiCurrent].classList.add('active');
    if (testiDots) testiDots.children[testiCurrent].classList.add('active');
  }

  function resetTestiTimer() {
    clearInterval(testiTimer);
    testiTimer = setInterval(function () { goTesti(testiCurrent + 1); }, 5000);
  }
  if (testi.length) { buildTestiDots(); resetTestiTimer(); }

  /* ---------- MOBILE NAV ---------- */
  var nav = document.getElementById('nav');
  var navToggle = document.getElementById('navToggle');
  var navClose = document.getElementById('navClose');

  if (navToggle) navToggle.addEventListener('click', function () { nav.classList.add('open'); });
  if (navClose) navClose.addEventListener('click', function () { nav.classList.remove('open'); });
  if (nav) nav.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () { nav.classList.remove('open'); });
  });

  /* ---------- HEADER SCROLL ---------- */
  var header = document.getElementById('header');
  if (header) window.addEventListener('scroll', function () {
    if (window.scrollY > 60) header.classList.add('scrolled');
    else header.classList.remove('scrolled');
  });

  /* ---------- TIMELINE INTERSECTION ---------- */
  var timelineItems = document.querySelectorAll('.timeline__item');
  var timelineObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) {
        timelineItems.forEach(function (it) { it.classList.remove('active'); });
        e.target.classList.add('active');
      }
    });
  }, { threshold: 0.6 });
  timelineItems.forEach(function (it) { timelineObserver.observe(it); });

  /* ---------- ACTIVE NAV ON SCROLL ---------- */
  var sections = document.querySelectorAll('section[id]');
  var navLinks = document.querySelectorAll('.nav__list a');
  var navObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) {
        var id = e.target.getAttribute('id');
        navLinks.forEach(function (l) {
          l.classList.toggle('active', l.getAttribute('href') === '#' + id);
        });
      }
    });
  }, { threshold: 0.4, rootMargin: '-80px 0px -50% 0px' });
  sections.forEach(function (s) { navObserver.observe(s); });

  /* ---------- GALLERY LIGHTBOX ---------- */
  var galleryItems = document.querySelectorAll('.gallery__item');
  var lbItems = [];
  var currentLbIndex = 0;
  var lb = null;

  galleryItems.forEach(function (item, i) {
    var src = item.getAttribute('data-lightbox');
    if (!src) {
      var bg = item.style.backgroundImage || '';
      src = bg.slice(5, -2);
    }
    lbItems.push({
      src: src,
      title: item.getAttribute('data-title') || (item.querySelector('span') ? item.querySelector('span').textContent : ''),
      desc: item.getAttribute('data-desc') || '',
    });
    item.addEventListener('click', function () { openLb(i); });
  });

  function buildLb() {
    lb = document.createElement('div');
    lb.className = 'lightbox';
    lb.style.cssText = 'position:fixed;inset:0;z-index:99999;background:rgba(8,5,4,.96);display:none;align-items:center;justify-content:center;flex-direction:column;';
    lb.innerHTML =
      '<button class="lb-close" style="position:absolute;top:20px;right:24px;background:none;border:none;color:#fff;font-size:42px;cursor:pointer;z-index:100001;padding:10px;line-height:1;">&times;</button>' +
      '<button class="lb-prev" style="position:absolute;left:20px;top:50%;transform:translateY(-50%);background:none;border:none;color:#fff;font-size:36px;cursor:pointer;z-index:100001;padding:16px;line-height:1;">&#10094;</button>' +
      '<button class="lb-next" style="position:absolute;right:20px;top:50%;transform:translateY(-50%);background:none;border:none;color:#fff;font-size:36px;cursor:pointer;z-index:100001;padding:16px;line-height:1;">&#10095;</button>' +
      '<div class="lb-content" style="max-width:90vw;max-height:85vh;text-align:center;position:relative;z-index:100000;">' +
        '<img class="lb-img" style="max-width:88vw;max-height:72vh;object-fit:contain;border-radius:4px;box-shadow:0 20px 60px rgba(0,0,0,.6);" />' +
        '<div class="lb-caption" style="margin-top:14px;">' +
          '<h3 class="lb-title" style="font-family:Oswald,sans-serif;text-transform:uppercase;letter-spacing:2px;font-size:18px;color:#fff;margin:0 0 6px;"></h3>' +
          '<p class="lb-desc" style="font-size:14px;color:#B8A99A;max-width:600px;margin:0 auto;"></p>' +
        '</div>' +
      '</div>';
    document.body.appendChild(lb);

    lb.querySelector('.lb-close').addEventListener('click', function (e) { e.stopPropagation(); closeLb(); });
    lb.querySelector('.lb-prev').addEventListener('click', function (e) { e.stopPropagation(); navLb(-1); });
    lb.querySelector('.lb-next').addEventListener('click', function (e) { e.stopPropagation(); navLb(1); });
    lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
  }

  function openLb(i) {
    currentLbIndex = i;
    var d = lbItems[i];
    if (!d) return;
    if (!lb) buildLb();
    var img = lb.querySelector('.lb-img');
    img.src = d.src;
    img.alt = d.title;
    lb.querySelector('.lb-title').textContent = d.title;
    lb.querySelector('.lb-desc').textContent = d.desc;
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  function closeLb() {
    if (lb) lb.style.display = 'none';
    document.body.style.overflow = '';
  }

  function navLb(dir) {
    if (!lbItems.length) return;
    currentLbIndex = (currentLbIndex + dir + lbItems.length) % lbItems.length;
    openLb(currentLbIndex);
  }

  document.addEventListener('keydown', function (e) {
    if (lb && lb.style.display === 'flex') {
      if (e.key === 'Escape') closeLb();
      if (e.key === 'ArrowLeft') navLb(-1);
      if (e.key === 'ArrowRight') navLb(1);
    }
  });

  /* ---------- FORMS (placeholder) ---------- */
  document.querySelectorAll('form').forEach(function (f) {
    f.addEventListener('submit', function (e) {
      e.preventDefault();
      var btn = f.querySelector('button[type="submit"]');
      if (!btn) return;
      var original = btn.textContent;
      btn.textContent = 'Enviado!';
      setTimeout(function () { btn.textContent = original; f.reset(); }, 1800);
    });
  });
})();
