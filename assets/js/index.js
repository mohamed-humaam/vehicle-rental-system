document.addEventListener('DOMContentLoaded', function() {
    // Animate features on scroll
    const featureCards = document.querySelectorAll('.feature-card');
    const statItems = document.querySelectorAll('.stat-item');
    
    // Initialize statistics counters
    const stats = {
        vehicles: 150,
        customers: 1200,
        bookings: 5000,
        satisfaction: 98
    };
    
    // Intersection Observer for features
    const featureObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                entry.target.style.animation = `fadeInUp 0.8s ease ${index * 0.2}s forwards`;
                featureObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });
    
    featureCards.forEach(card => featureObserver.observe(card));
    
    // Intersection Observer for stats
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'scaleIn 0.8s ease forwards';
                
                // Animate number counting
                const numberElement = entry.target.querySelector('.number');
                const targetNumber = parseInt(numberElement.dataset.target);
                animateNumber(numberElement, targetNumber);
                
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });
    
    statItems.forEach(item => statsObserver.observe(item));
    
    // Number animation function
    function animateNumber(element, target) {
        let current = 0;
        const increment = target / 50;
        const duration = 1500;
        const stepTime = duration / 50;
        
        const counter = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target + (element.dataset.suffix || '');
                clearInterval(counter);
            } else {
                element.textContent = Math.floor(current) + (element.dataset.suffix || '');
            }
        }, stepTime);
    }
    
    // Particle background effect for hero section
    const hero = document.querySelector('.hero');
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.cssText = `
            position: absolute;
            width: ${Math.random() * 5 + 2}px;
            height: ${Math.random() * 5 + 2}px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            left: ${Math.random() * 100}%;
            top: ${Math.random() * 100}%;
            animation: float ${Math.random() * 8 + 4}s linear infinite;
        `;
        hero.appendChild(particle);
    }
});