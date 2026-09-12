/* =====================================================================
   CRUZ DE OSSOS — DRAFT JAVASCRIPT
   ===================================================================== */
(function () {
  'use strict';

  /* ---------- HERO SLIDER ---------- */
  var heroSlides = document.querySelectorAll('.hero__slide');
  var heroDots = document.getElementById('heroDots');
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
    heroSlides[heroCurrent].classList.remove('active');
    heroDots.children[heroCurrent].classList.remove('active');
    heroCurrent = (i + heroSlides.length) % heroSlides.length;
    heroSlides[heroCurrent].classList.add('active');
    heroDots.children[heroCurrent].classList.add('active');
  }

  function nextHero() { goHero(heroCurrent + 1); }
  function prevHero() { goHero(heroCurrent - 1); }
  function resetHeroTimer() {
    clearInterval(heroTimer);
    heroTimer = setInterval(nextHero, 6000);
  }

  document.getElementById('heroNext').addEventListener('click', function () { nextHero(); resetHeroTimer(); });
  document.getElementById('heroPrev').addEventListener('click', function () { prevHero(); resetHeroTimer(); });
  buildHeroDots();
  resetHeroTimer();

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
    testi[testiCurrent].classList.remove('active');
    testiDots.children[testiCurrent].classList.remove('active');
    testiCurrent = (i + testi.length) % testi.length;
    testi[testiCurrent].classList.add('active');
    testiDots.children[testiCurrent].classList.add('active');
  }

  function resetTestiTimer() {
    clearInterval(testiTimer);
    testiTimer = setInterval(function () { goTesti(testiCurrent + 1); }, 5000);
  }
  buildTestiDots();
  resetTestiTimer();

  /* ---------- MOBILE NAV ---------- */
  var nav = document.getElementById('nav');
  var navToggle = document.getElementById('navToggle');
  var navClose = document.getElementById('navClose');

  navToggle.addEventListener('click', function () { nav.classList.add('open'); });
  navClose.addEventListener('click', function () { nav.classList.remove('open'); });
  nav.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () { nav.classList.remove('open'); });
  });

  /* ---------- HEADER SCROLL ---------- */
  var header = document.getElementById('header');
  window.addEventListener('scroll', function () {
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
  var lightbox = document.getElementById('lightbox');
  var lightboxImg = document.getElementById('lightboxImg');
  var lightboxTitle = document.getElementById('lightboxTitle');
  var lightboxDesc = document.getElementById('lightboxDesc');
  var lightboxClose = document.getElementById('lightboxClose');
  var lightboxPrev = document.getElementById('lightboxPrev');
  var lightboxNext = document.getElementById('lightboxNext');
  var currentLbIndex = 0;
  var lbItems = [];

  galleryItems.forEach(function (item, i) {
    lbItems.push({
      src: item.getAttribute('data-lightbox') || (item.style.backgroundImage || '').slice(5, -2),
      title: item.getAttribute('data-title') || item.querySelector('span')?.textContent || '',
      desc: item.getAttribute('data-desc') || '',
    });
    item.addEventListener('click', function () { openLb(i); });
  });

  function openLb(i) {
    currentLbIndex = i;
    var d = lbItems[i];
    if (!d) return;
    lightboxImg.src = d.src;
    lightboxImg.alt = d.title;
    lightboxTitle.textContent = d.title;
    lightboxDesc.textContent = d.desc;
    if (lightbox) {
      lightbox.classList.add('active');
    } else {
      var lb = document.createElement('div');
      lb.className = 'lightbox active';
      lb.innerHTML = '<img src="' + d.src + '" alt="' + d.title + '" /><span class="lightbox__close">&times;</span>';
      lb.addEventListener('click', function () { lb.remove(); document.body.style.overflow = ''; });
      document.body.appendChild(lb);
    }
    document.body.style.overflow = 'hidden';
  }

  function closeLb() {
    if (lightbox) lightbox.classList.remove('active');
    document.body.style.overflow = '';
  }

  function navLb(dir) {
    currentLbIndex = (currentLbIndex + dir + lbItems.length) % lbItems.length;
    openLb(currentLbIndex);
  }

  if (lightboxClose) lightboxClose.addEventListener('click', closeLb);
  if (lightboxPrev) lightboxPrev.addEventListener('click', function (e) { e.stopPropagation(); navLb(-1); });
  if (lightboxNext) lightboxNext.addEventListener('click', function (e) { e.stopPropagation(); navLb(1); });
  if (lightbox) lightbox.addEventListener('click', function (e) { if (e.target === lightbox) closeLb(); });
  document.addEventListener('keydown', function (e) {
    if (lightbox && lightbox.classList.contains('active')) {
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
