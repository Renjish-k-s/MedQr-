/**
 * MedQR+ Interactive JavaScript
 * Handles navbar scroll effects and smooth animations
 */

document.addEventListener('DOMContentLoaded', function() {
  const navbar = document.querySelector('.medqr-nav');
  const navToggle = document.querySelector('.nav-toggle');
  const navDrawer = document.querySelector('.nav-drawer');
  const drawerLinks = document.querySelectorAll('.nav-drawer a');
  const heroVideoBtn = document.querySelector('.hero-video');

  // Navbar scroll state
  const handleNavState = () => {
    if (!navbar) return;
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  };
  handleNavState();
  window.addEventListener('scroll', handleNavState);

  // Smooth scroll links (internal only)
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const hash = this.getAttribute('href');
      if (!hash || hash === '#') return;
      const target = document.querySelector(hash);
      if (target) {
        e.preventDefault();
        const offset = navbar ? navbar.offsetHeight : 80;
        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: targetPosition, behavior: 'smooth' });
        if (navDrawer && navDrawer.classList.contains('open')) {
          navDrawer.classList.remove('open');
          document.body.classList.remove('drawer-open');
        }
      }
    });
  });

  // Drawer controls
  if (navToggle && navDrawer) {
    navToggle.addEventListener('click', () => {
      navDrawer.classList.toggle('open');
      document.body.classList.toggle('drawer-open');
    });
  }

  drawerLinks.forEach(link => {
    link.addEventListener('click', () => {
      navDrawer.classList.remove('open');
      document.body.classList.remove('drawer-open');
    });
  });

  // Intersection observer for cards
  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in-up');
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2, rootMargin: '0px 0px -50px 0px' });

  document.querySelectorAll('.feature-card, .journey-step, .stat-card').forEach(el => observer.observe(el));

  // Hero video button placeholder animation
  if (heroVideoBtn) {
    heroVideoBtn.addEventListener('click', () => {
      heroVideoBtn.classList.add('pulse');
      setTimeout(() => heroVideoBtn.classList.remove('pulse'), 800);
    });
  }
});
