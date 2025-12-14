const state = {
    activeTab: 'hotels'
};

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// Navbar transparency on scroll - make it transparent when at top, visible when scrolled
function updateNavbar() {
    const navbar = document.querySelector('.navbar');
    const heroSection = document.querySelector('.hero-section');
    const heroHeight = heroSection ? heroSection.offsetHeight : 500;
    
    if (window.scrollY > heroHeight - 150) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
}

window.addEventListener('scroll', updateNavbar);

// Ensure navbar is transparent on page load
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    navbar.classList.remove('scrolled');
    updateNavbar(); // Check initial state
});
