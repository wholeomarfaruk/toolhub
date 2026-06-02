/**
 * Scroll-triggered animations using Intersection Observer API
 * Automatically adds animations when elements come into view
 */

document.addEventListener('DOMContentLoaded', () => {
    // Configuration for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    // Create intersection observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add animation class when element enters viewport
                const animationClass = entry.target.dataset.scrollAnimation;
                const delayClass = entry.target.dataset.scrollDelay;

                if (animationClass) {
                    entry.target.classList.add(animationClass);
                }

                if (delayClass) {
                    entry.target.style.animationDelay = delayClass;
                }

                // Optional: Stop observing after animation completes
                if (entry.target.dataset.scrollOnce === 'true') {
                    observer.unobserve(entry.target);
                }
            }
        });
    }, observerOptions);

    // Observe all elements with scroll animation attributes
    document.querySelectorAll('[data-scroll-animation]').forEach(element => {
        observer.observe(element);
    });

    /**
     * Counter animation for numbers
     * Animates counting from 0 to the target value
     */
    const initCounters = () => {
        const counters = document.querySelectorAll('[data-count]');

        counters.forEach(counter => {
            const target = parseInt(counter.dataset.count);
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // ~60fps

            let current = 0;

            const updateCount = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = Math.floor(current).toLocaleString();
                    requestAnimationFrame(updateCount);
                } else {
                    counter.textContent = target.toLocaleString();
                }
            };

            // Start animation when element enters view
            const countObserver = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && current === 0) {
                    updateCount();
                    countObserver.unobserve(counter);
                }
            }, observerOptions);

            countObserver.observe(counter);
        });
    };

    initCounters();

    /**
     * Parallax effect for background elements
     * Creates depth effect as user scrolls
     */
    const initParallax = () => {
        const parallaxElements = document.querySelectorAll('[data-parallax]');

        if (parallaxElements.length === 0) return;

        window.addEventListener('scroll', () => {
            parallaxElements.forEach(element => {
                const speed = element.dataset.parallax || '0.5';
                const yOffset = window.pageYOffset * speed;
                element.style.transform = `translateY(${yOffset}px)`;
            });
        }, { passive: true });
    };

    initParallax();

    /**
     * Animated progress bars
     * Animates width from 0 to target percentage on scroll
     */
    const initProgressBars = () => {
        const progressBars = document.querySelectorAll('[data-progress]');

        progressBars.forEach(bar => {
            const target = bar.dataset.progress;
            const barObserver = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    setTimeout(() => {
                        bar.style.width = `${target}%`;
                    }, 100);
                    barObserver.unobserve(bar);
                }
            }, observerOptions);

            barObserver.observe(bar);
        });
    };

    initProgressBars();

    /**
     * Staggered list item animations
     * Animates each list item with a delay
     */
    const initStaggeredAnimation = () => {
        document.querySelectorAll('[data-stagger]').forEach(container => {
            const items = container.querySelectorAll('[data-stagger-item]');
            const baseDelay = parseFloat(container.dataset.stagger) || 0.1;

            const staggerObserver = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    items.forEach((item, index) => {
                        item.style.animationDelay = `${baseDelay * index}s`;
                        item.classList.add('animate-fade-in-up');
                    });
                    staggerObserver.unobserve(container);
                }
            }, observerOptions);

            staggerObserver.observe(container);
        });
    };

    initStaggeredAnimation();

    /**
     * Text reveal animation
     * Reveals text character by character or word by word
     */
    const initTextReveal = () => {
        const revealElements = document.querySelectorAll('[data-text-reveal]');

        revealElements.forEach(element => {
            const text = element.textContent;
            const revealType = element.dataset.textReveal || 'word'; // 'word' or 'char'

            const parts = revealType === 'char'
                ? text.split('')
                : text.split(' ');

            element.innerHTML = parts
                .map((part, i) => `<span style="opacity: 0; animation: fade-in 0.6s ease-out forwards; animation-delay: ${i * 0.05}s;">${part}${revealType === 'word' ? ' ' : ''}</span>`)
                .join('');

            const revealObserver = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    revealObserver.unobserve(element);
                }
            }, observerOptions);

            revealObserver.observe(element);
        });
    };

    initTextReveal();

    /**
     * Hover animation trigger
     * Adds animation on element hover
     */
    document.querySelectorAll('[data-hover-animation]').forEach(element => {
        element.addEventListener('mouseenter', () => {
            const animation = element.dataset.hoverAnimation;
            element.classList.add(`animate-${animation}`);
        });

        element.addEventListener('animationend', () => {
            const animation = element.dataset.hoverAnimation;
            element.classList.remove(`animate-${animation}`);
        });
    });

    /**
     * Mouse follow animation
     * Makes elements follow the cursor
     */
    const initMouseFollow = () => {
        const followers = document.querySelectorAll('[data-mouse-follow]');

        if (followers.length === 0) return;

        document.addEventListener('mousemove', (e) => {
            followers.forEach(element => {
                const speed = element.dataset.mouseFollow || '0.1';
                const x = e.clientX;
                const y = e.clientY;

                const elementX = element.getBoundingClientRect().left + element.offsetWidth / 2;
                const elementY = element.getBoundingClientRect().top + element.offsetHeight / 2;

                const angle = Math.atan2(y - elementY, x - elementX);
                const distance = 20; // pixels to move

                const moveX = Math.cos(angle) * distance * speed;
                const moveY = Math.sin(angle) * distance * speed;

                element.style.transform = `translate(${moveX}px, ${moveY}px)`;
            });
        }, { passive: true });
    };

    initMouseFollow();

    /**
     * Fade in on scroll with threshold
     * Elements fade in as they become visible
     */
    const initFadeInOnScroll = () => {
        const fadeElements = document.querySelectorAll('[data-fade-in]');

        fadeElements.forEach(element => {
            const fadeObserver = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    element.classList.add('animate-fade-in');
                    fadeObserver.unobserve(element);
                }
            }, observerOptions);

            fadeObserver.observe(element);
        });
    };

    initFadeInOnScroll();
});
