/**
 * Kon Tum + E-Voucher - Theme Management Module
 * Supports: Light, Dark, Auto (Device Preference)
 * Prevents FOUC (Flash of Unstyled Content) by executing synchronously in <head>.
 */
(function () {
    const STORAGE_KEY = 'kt_theme_mode';

    // SVG Icon Definitions for Crisp Vector Rendering matching Web App Aesthetics
    const SVG_ICONS = {
        sun: `<svg class="theme-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>`,
        moon: `<svg class="theme-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>`,
        auto: `<svg class="theme-svg-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>`
    };

    // Get current mode: 'light', 'dark', or 'auto' (default)
    function getSavedThemeMode() {
        return localStorage.getItem(STORAGE_KEY) || 'auto';
    }

    // Determine resolved theme ('light' or 'dark') based on mode & system preference
    function getResolvedTheme(mode) {
        if (mode === 'light' || mode === 'dark') {
            return mode;
        }
        // Fallback to device / OS system preference
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
            ? 'dark'
            : 'light';
    }

    // Apply attribute to document element immediately
    function applyTheme(mode) {
        const resolved = getResolvedTheme(mode);
        document.documentElement.setAttribute('data-theme', resolved);
        document.documentElement.setAttribute('data-theme-mode', mode);
        updateToggleButtons(mode, resolved);
    }

    // Update UI elements for theme toggle buttons
    function updateToggleButtons(mode, resolved) {
        const toggleBtns = document.querySelectorAll('.theme-toggle-btn, #theme-toggle-btn');
        toggleBtns.forEach(btn => {
            const iconWrapper = btn.querySelector('.theme-icon-wrapper') || btn.querySelector('.theme-icon');
            const textEl = btn.querySelector('.theme-text');
            const iEl = btn.querySelector('i');

            let iconKey = 'auto';
            let iconClass = 'fas fa-desktop';
            let labelText = 'Tự động';

            if (mode === 'light') {
                iconKey = 'sun';
                iconClass = 'fas fa-sun';
                labelText = 'Sáng';
            } else if (mode === 'dark') {
                iconKey = 'moon';
                iconClass = 'fas fa-moon';
                labelText = 'Tối';
            } else {
                // Auto mode
                iconKey = 'auto';
                iconClass = resolved === 'dark' ? 'fas fa-moon' : 'fas fa-desktop';
                labelText = `Tự động (${resolved === 'dark' ? 'Tối' : 'Sáng'})`;
            }

            if (iconWrapper) {
                iconWrapper.innerHTML = SVG_ICONS[iconKey];
            } else if (iEl) {
                iEl.className = iconClass;
            }

            if (textEl) {
                textEl.textContent = labelText;
            }
            btn.setAttribute('title', `Giao diện hiện tại: ${labelText}. Bấm để chuyển đổi.`);
            btn.setAttribute('aria-label', `Giao diện: ${labelText}`);
        });
    }

    // Set & save new mode
    window.setThemeMode = function (mode) {
        if (!['light', 'dark', 'auto'].includes(mode)) mode = 'auto';
        localStorage.setItem(STORAGE_KEY, mode);
        applyTheme(mode);
    };

    // Cycle through: auto -> light -> dark -> auto
    window.toggleTheme = function () {
        const currentMode = getSavedThemeMode();
        let nextMode = 'auto';
        if (currentMode === 'auto') {
            nextMode = 'light';
        } else if (currentMode === 'light') {
            nextMode = 'dark';
        } else {
            nextMode = 'auto';
        }
        window.setThemeMode(nextMode);
    };

    // Apply immediately during script evaluation in <head>
    const initialMode = getSavedThemeMode();
    applyTheme(initialMode);

    // Listen to OS scheme changes in real-time when mode is 'auto'
    if (window.matchMedia) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        const handleChange = function () {
            if (getSavedThemeMode() === 'auto') {
                applyTheme('auto');
            }
        };
        if (mediaQuery.addEventListener) {
            mediaQuery.addEventListener('change', handleChange);
        } else if (mediaQuery.addListener) {
            mediaQuery.addListener(handleChange);
        }
    }

    // Bind click handlers & update buttons on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function () {
        const current = getSavedThemeMode();
        updateToggleButtons(current, getResolvedTheme(current));

        document.querySelectorAll('.theme-toggle-btn, #theme-toggle-btn').forEach(btn => {
            if (!btn.getAttribute('data-theme-bound')) {
                btn.setAttribute('data-theme-bound', 'true');
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.toggleTheme();
                });
            }
        });
    });
})();
