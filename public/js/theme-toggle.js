/**
 * Global Theme Toggle Controller with Circle Transition Effect
 * Based on View Transitions API & SVG mask (https://theme-toggle.rdsx.dev/)
 * 
 * Author: OSS Display TV
 */
(function () {
    'use strict';

    /**
     * Synchronize mobile browser status bar and meta theme-color
     */
    function syncThemeColor(isDark) {
        const color = isDark ? '#020617' : '#ffffff';
        document.querySelectorAll('meta[name="theme-color"]').forEach(meta => {
            meta.setAttribute('content', color);
        });
    }

    /**
     * Update icon states (Sun/Moon) for all theme toggle buttons
     */
    function updateThemeIcons() {
        const isDark = document.documentElement.classList.contains('dark');
        document.querySelectorAll('.themeIconSun').forEach(icon => {
            icon.style.display = isDark ? 'inline-block' : 'none';
        });
        document.querySelectorAll('.themeIconMoon').forEach(icon => {
            icon.style.display = isDark ? 'none' : 'inline-block';
        });
    }

    /**
     * Apply theme state to DOM and persist to localStorage
     */
    function applyThemeState(isDark) {
        if (isDark) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
        syncThemeColor(isDark);
        updateThemeIcons();
    }

    /**
     * Main Theme Toggle with View Transitions Circle Effect
     */
    function toggleTheme(event) {
        if (event && typeof event.preventDefault === 'function') {
            event.preventDefault();
        }

        const currentlyDark = document.documentElement.classList.contains('dark');
        const targetDark = !currentlyDark;

        // Check for reduced motion preference
        const prefersReducedMotion = window.matchMedia && 
            window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Fallback for browsers without View Transitions API or if reduced motion is requested
        if (!document.startViewTransition || prefersReducedMotion) {
            applyThemeState(targetDark);
            return;
        }

        // Add scoping class to HTML so circular transition applies without breaking MPA transitions
        document.documentElement.classList.add('theme-transitioning');

        const transition = document.startViewTransition(() => {
            applyThemeState(targetDark);
        });

        // Ensure cleanup of the transition scoping class once the animation finishes
        transition.finished.finally(() => {
            document.documentElement.classList.remove('theme-transitioning');
        });
    }

    // Expose helpers globally for backward compatibility
    window.toggleTheme = toggleTheme;
    window.updateThemeIcons = updateThemeIcons;

    // Initialize icons and bindings on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcons();
        });
    } else {
        updateThemeIcons();
    }

    // Use event delegation for all current and future .btnThemeToggle elements
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.btnThemeToggle');
        if (btn) {
            toggleTheme(e);
        }
    });
})();
