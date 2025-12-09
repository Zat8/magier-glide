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

// ===== SLIDER FUNCTIONALITY =====
const slides = document.getElementById('slides');
const slideElements = document.querySelectorAll('.slide');

let currentIndex = 1;
let autoSlideInterval;
let isDragging = false;
let startX = 0;
let isMobile = false;

// Deteksi ukuran layar
function checkMobile() {
    isMobile = window.innerWidth <= 768;
}

function updateSlider() {
    checkMobile();
   
    const slideWidth = slideElements[0].offsetWidth;
    const gap = isMobile ? 15 : 30;
    const offset = currentIndex * (slideWidth + gap);
    
    slides.style.transform = `translateX(-${offset}px)`;
    
    // Update active slide
    slideElements.forEach((slide, index) => {
        slide.classList.toggle('active', index === currentIndex);
    });
}

function moveSlide(direction) {
    currentIndex += direction;
    
    if (currentIndex < 0) {
        currentIndex = slideElements.length - 1;
    } else if (currentIndex >= slideElements.length) {
        currentIndex = 0;
    }
    
    updateSlider();
    resetAutoSlide();
}

function startAutoSlide() {
    // Waktu auto slide berbeda untuk mobile dan desktop
    const slideTime = isMobile ? 3000 : 5000; // 3 detik mobile, 5 detik desktop
    
    autoSlideInterval = setInterval(() => {
        moveSlide(1);
    }, slideTime);
}

function stopAutoSlide() {
    clearInterval(autoSlideInterval);
}

function resetAutoSlide() {
    stopAutoSlide();
    startAutoSlide();
}

// Mouse drag functionality (untuk desktop)
slides.addEventListener('mousedown', (e) => {
    if (isMobile) return; // Skip di mobile
    
    isDragging = true;
    startX = e.clientX;
    slides.style.cursor = 'grabbing';
    stopAutoSlide();
});

slides.addEventListener('mousemove', (e) => {
    if (!isDragging || isMobile) return;
    
    const currentX = e.clientX;
    const diff = currentX - startX;
    
    if (Math.abs(diff) > 50) {
        if (diff > 0) {
            moveSlide(-1);
        } else {
            moveSlide(1);
        }
        isDragging = false;
    }
});

slides.addEventListener('mouseup', () => {
    if (isMobile) return;
    
    isDragging = false;
    slides.style.cursor = 'pointer';
    resetAutoSlide();
});

slides.addEventListener('mouseleave', () => {
    if (isMobile) return;
    
    isDragging = false;
    slides.style.cursor = 'pointer';
});

// Touch support (untuk mobile)
let touchStartX = 0;
let touchEndX = 0;

slides.addEventListener('touchstart', (e) => {
    touchStartX = e.touches[0].clientX;
    stopAutoSlide();
}, { passive: true });

slides.addEventListener('touchmove', (e) => {
    touchEndX = e.touches[0].clientX;
}, { passive: true });

slides.addEventListener('touchend', () => {
    const diff = touchStartX - touchEndX;
    const threshold = isMobile ? 30 : 50; // Threshold lebih kecil untuk mobile
    
    if (Math.abs(diff) > threshold) {
        if (diff > 0) {
            moveSlide(1); // Swipe left
        } else {
            moveSlide(-1); // Swipe right
        }
    }
    
    resetAutoSlide();
});

// Pause on hover (hanya untuk desktop)
const sliderElement = document.querySelector('.slider');

sliderElement.addEventListener('mouseenter', () => {
    if (!isMobile) {
        stopAutoSlide();
    }
});

sliderElement.addEventListener('mouseleave', () => {
    if (!isMobile) {
        startAutoSlide();
    }
});

// Initialize
checkMobile();
updateSlider();
startAutoSlide();

// Handle window resize
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        checkMobile();
        updateSlider();
        resetAutoSlide();
    }, 250);
});
