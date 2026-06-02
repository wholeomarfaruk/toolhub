import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import scrollbar from 'tailwind-scrollbar'
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                // Fade and slide animations
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-in-left': {
                    '0%': { opacity: '0', transform: 'translateX(-20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                'fade-in-right': {
                    '0%': { opacity: '0', transform: 'translateX(20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                'slide-in-left': {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                'slide-in-right': {
                    '0%': { transform: 'translateX(100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                // Scale animations
                'scale-in': {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                'scale-up': {
                    '0%': { transform: 'scale(1)' },
                    '100%': { transform: 'scale(1.05)' },
                },
                // Floating animation
                'float': {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                'float-slow': {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-15px)' },
                },
                // Glow animation
                'pulse-glow': {
                    '0%, 100%': { boxShadow: '0 0 20px rgba(99, 102, 241, 0.4)' },
                    '50%': { boxShadow: '0 0 40px rgba(99, 102, 241, 0.6)' },
                },
                // Shimmer animation
                'shimmer': {
                    '0%': { backgroundPosition: '-1000px 0' },
                    '100%': { backgroundPosition: '1000px 0' },
                },
                // Bounce animations
                'bounce-subtle': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-5px)' },
                },
                // Infinity animations
                'orbit': {
                    '0%': { transform: 'rotate(0deg) translateX(50px) rotate(0deg)' },
                    '100%': { transform: 'rotate(360deg) translateX(50px) rotate(-360deg)' },
                },
                'orbit-slow': {
                    '0%': { transform: 'rotate(0deg) translateX(70px) rotate(0deg)' },
                    '100%': { transform: 'rotate(360deg) translateX(70px) rotate(-360deg)' },
                },
                'orbit-reverse': {
                    '0%': { transform: 'rotate(0deg) translateX(60px) rotate(0deg)' },
                    '100%': { transform: 'rotate(-360deg) translateX(60px) rotate(360deg)' },
                },
                'pulse-ring': {
                    '0%': { boxShadow: '0 0 0 0 rgba(99, 102, 241, 0.7)' },
                    '50%': { boxShadow: '0 0 0 20px rgba(99, 102, 241, 0)' },
                    '100%': { boxShadow: '0 0 0 0 rgba(99, 102, 241, 0)' },
                },
                'glow-pulse': {
                    '0%, 100%': { opacity: '0.5', filter: 'drop-shadow(0 0 8px rgba(99, 102, 241, 0.5))' },
                    '50%': { opacity: '1', filter: 'drop-shadow(0 0 20px rgba(99, 102, 241, 0.8))' },
                },
                'sway': {
                    '0%, 100%': { transform: 'translateX(0px) rotate(0deg)' },
                    '50%': { transform: 'translateX(10px) rotate(1deg)' },
                },
                'sway-reverse': {
                    '0%, 100%': { transform: 'translateX(0px) rotate(0deg)' },
                    '50%': { transform: 'translateX(-10px) rotate(-1deg)' },
                },
                'wobble': {
                    '0%, 100%': { transform: 'translateX(0)' },
                    '25%': { transform: 'translateX(-5px) rotate(-1deg)' },
                    '75%': { transform: 'translateX(5px) rotate(1deg)' },
                },
                'spin-slow': {
                    '0%': { transform: 'rotate(0deg)' },
                    '100%': { transform: 'rotate(360deg)' },
                },
                'bounce-infinity': {
                    '0%, 100%': { transform: 'translateY(-20px)' },
                    '50%': { transform: 'translateY(0)' },
                },
                'jiggle': {
                    '0%, 100%': { transform: 'scale(1)' },
                    '25%': { transform: 'scale(1.05)' },
                    '75%': { transform: 'scale(0.95)' },
                },
                'blink': {
                    '0%, 49%, 100%': { opacity: '1' },
                    '50%, 99%': { opacity: '0.3' },
                },
                'zoom-in-out': {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.1)' },
                },
                'rotate-circle': {
                    '0%': { transform: 'rotate(0deg)' },
                    '100%': { transform: 'rotate(360deg)' },
                },
                'gradient-shift': {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
            },
            animation: {
                'fade-in': 'fade-in 0.6s ease-out',
                'fade-in-up': 'fade-in-up 0.6s ease-out',
                'fade-in-left': 'fade-in-left 0.6s ease-out',
                'fade-in-right': 'fade-in-right 0.6s ease-out',
                'slide-in-left': 'slide-in-left 0.5s ease-out',
                'slide-in-right': 'slide-in-right 0.5s ease-out',
                'scale-in': 'scale-in 0.5s ease-out',
                'scale-up': 'scale-up 0.3s ease-out',
                'float': 'float 3s ease-in-out infinite',
                'float-slow': 'float-slow 4s ease-in-out infinite',
                'pulse-glow': 'pulse-glow 2s ease-in-out infinite',
                'shimmer': 'shimmer 2s infinite',
                'bounce-subtle': 'bounce-subtle 2s ease-in-out infinite',
                // Infinity animations
                'orbit': 'orbit 8s linear infinite',
                'orbit-slow': 'orbit-slow 12s linear infinite',
                'orbit-reverse': 'orbit-reverse 10s linear infinite',
                'pulse-ring': 'pulse-ring 2s infinite',
                'glow-pulse': 'glow-pulse 3s ease-in-out infinite',
                'sway': 'sway 4s ease-in-out infinite',
                'sway-reverse': 'sway-reverse 4s ease-in-out infinite',
                'wobble': 'wobble 1s ease-in-out infinite',
                'spin-slow': 'spin-slow 20s linear infinite',
                'bounce-infinity': 'bounce-infinity 2s ease-in-out infinite',
                'jiggle': 'jiggle 1s ease-in-out infinite',
                'blink': 'blink 1.5s ease-in-out infinite',
                'zoom-in-out': 'zoom-in-out 3s ease-in-out infinite',
                'rotate-circle': 'rotate-circle 10s linear infinite',
                'gradient-shift': 'gradient-shift 4s ease infinite',
            },
        },
    },

    plugins: [forms, typography,
         scrollbar
        ],
};
