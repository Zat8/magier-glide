// Navbar Scroll Effect
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Hamburger Menu (Mobile)
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');

hamburger.addEventListener('click', function() {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

// Close menu saat klik link (Mobile)
const navLinks = document.querySelectorAll('.nav-link, .logout-btn');

navLinks.forEach(link => {
    link.addEventListener('click', function() {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
    });
});

const p = document.querySelector('.exp-progress');
const max = p.style.getPropertyValue("--max");
const val = p.style.getPropertyValue("--value");

