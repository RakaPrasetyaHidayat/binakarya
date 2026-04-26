import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);
Alpine.plugin(collapse);

window.Alpine = Alpine;
window.gsap = gsap;

Alpine.start();

const initDarkMode = () => {
    const darkModePreference = localStorage.getItem('darkMode');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDarkMode = darkModePreference === 'true' || (darkModePreference === null && prefersDark);
    
    if (isDarkMode) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

const initSoftAnimations = () => {
    gsap.config({ force3D: false });

    document.querySelectorAll('article').forEach((card, index) => {
        gsap.fromTo(card,
            {
                opacity: 0.3,
                y: 20
            },
            {
                opacity: 1,
                y: 0,
                duration: 0.6,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: card,
                    start: 'top 85%',
                    toggleActions: 'play none none none'
                }
            }
        );
    });

    document.querySelectorAll('.rounded-xl').forEach((element) => {
        element.addEventListener('mouseenter', function() {
            gsap.to(this, {
                y: -4,
                duration: 0.3,
                ease: 'power2.out',
                overwrite: 'auto'
            });
        });

        element.addEventListener('mouseleave', function() {
            gsap.to(this, {
                y: 0,
                duration: 0.3,
                ease: 'power2.out',
                overwrite: 'auto'
            });
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initDarkMode();
    setTimeout(() => {
        initSoftAnimations();
    }, 300);
});

window.addEventListener('storage', (event) => {
    if (event.key === 'darkMode') {
        const isDarkMode = event.newValue === 'true';
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
});
