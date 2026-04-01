// Animations légères pour la page d'accueil
(function () {
  function initReveal() {
    const els = document.querySelectorAll('.reveal');
    if (!els.length) return;

    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((e) => {
          if (e.isIntersecting) {
            e.target.classList.add('reveal-visible');
            io.unobserve(e.target);
          }
        });
      },
      { threshold: 0.12 }
    );

    els.forEach((el) => io.observe(el));
  }

  function initCarousel() {
    const carousel = document.querySelector('[data-carousel]');
    if (!carousel) return;

    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;

    carousel.addEventListener('mousedown', (e) => {
      isDown = true;
      carousel.classList.add('dragging');
      startX = e.pageX - carousel.offsetLeft;
      scrollLeft = carousel.scrollLeft;
    });

    window.addEventListener('mouseup', () => {
      isDown = false;
      carousel.classList.remove('dragging');
    });

    carousel.addEventListener('mouseleave', () => {
      isDown = false;
      carousel.classList.remove('dragging');
    });

    carousel.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - carousel.offsetLeft;
      const walk = (x - startX) * 1.4;
      carousel.scrollLeft = scrollLeft - walk;
    });

    // Auto-scroll doux (pause si l'utilisateur interagit)
    let raf = 0;
    let pausedUntil = 0;

    const tick = () => {
      const now = Date.now();
      if (now > pausedUntil && !isDown) {
        carousel.scrollLeft += 0.35;
        if (carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth - 2) {
          carousel.scrollLeft = 0;
        }
      }
      raf = window.requestAnimationFrame(tick);
    };

    const pause = (ms) => { pausedUntil = Date.now() + ms; };
    carousel.addEventListener('wheel', () => pause(1500), { passive: true });
    carousel.addEventListener('touchstart', () => pause(2000), { passive: true });
    carousel.addEventListener('mouseenter', () => pause(2000));

    raf = window.requestAnimationFrame(tick);
    window.addEventListener('beforeunload', () => window.cancelAnimationFrame(raf));
  }

  function initHeroParallax() {
    const hero = document.querySelector('.hero-dynamic');
    if (!hero) return;

    let raf = 0;
    const onMove = (e) => {
      const rect = hero.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width;
      const y = (e.clientY - rect.top) / rect.height;
      const px = (x - 0.5) * 18;
      const py = (y - 0.5) * 18;

      window.cancelAnimationFrame(raf);
      raf = window.requestAnimationFrame(() => {
        hero.style.setProperty('--mx', px.toFixed(2) + 'px');
        hero.style.setProperty('--my', py.toFixed(2) + 'px');
      });
    };

    hero.addEventListener('mousemove', onMove);
    hero.addEventListener('mouseleave', () => {
      hero.style.setProperty('--mx', '0px');
      hero.style.setProperty('--my', '0px');
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initCarousel();
    initHeroParallax();
  });
})();

