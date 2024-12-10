document.addEventListener('DOMContentLoaded', function() {
    // Add active class based on current page
    const currentPage = window.location.pathname;
    const navLinks = document.querySelectorAll('header nav a');
    
    navLinks.forEach(link => {
        if (currentPage.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
        
        // Add hover sound effect
        link.addEventListener('mouseenter', () => {
            playHoverSound();
        });
    });

    // Scroll effect for header
    let lastScroll = 0;
    const header = document.querySelector('header');
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > lastScroll && currentScroll > 100) {
            // Scrolling down - hide header
            header.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up - show header
            header.style.transform = 'translateY(0)';
        }
        
        lastScroll = currentScroll;
    });

    // Add title animation
    const title = document.querySelector('header h1');
    const titleText = title.textContent;
    title.innerHTML = titleText.split('').map(char => 
        `<span class="char">${char}</span>`
    ).join('');

    // Animate characters on hover
    title.addEventListener('mouseover', () => {
        const chars = title.querySelectorAll('.char');
        chars.forEach((char, index) => {
            setTimeout(() => {
                char.style.transform = 'translateY(-5px)';
                setTimeout(() => {
                    char.style.transform = 'translateY(0)';
                }, 200);
            }, index * 50);
        });
    });
});

// Hover sound function
function playHoverSound() {
    const audio = new Audio();
    audio.src = 'data:audio/mp3;base64,SUQzBAAAAAAAI1RTU0UAAAAPAAADTGF2ZjU4Ljc2LjEwMAAAAAAAAAAAAAAA//tQwAAAAAAAAAAAAAAAAAAAAAAASW5mbwAAAA8AAAACAAABhgC1tbW1tbW1tbW1tbW1tbW1tbW1tbW1tbW1tbW1tbW1tbW1tbW1tbW1tbW1tbW1tbW1//////////////////////////////////////////////////////////////////8AAAAATGF2YzU4LjEzAAAAAAAAAAAAAAAAJAAAAAAAAAAAAYZYxqmFAAAAAAAAAAAAAAAAAAAA//sQxAADwAABpAAAACAAADSAAAAETEFNRTMuMTAwVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVf/7EMQpg8AAAaQAAAAgAAA0gAAABFVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVU=';
    audio.volume = 0.2;
    audio.play().catch(() => {
        // Ignore error if browser blocks autoplay
    });
}