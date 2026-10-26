import './bootstrap';

// Initialize Dark Mode based on preference or system
(function initTheme() {
    try {
        const storedTheme = localStorage.getItem('fixhome-theme');
        if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    } catch (e) {
        // Fallback gracefully
    }
})();

// Helper function on window for toggling theme
window.toggleDarkMode = function() {
    const isDark = document.documentElement.classList.toggle('dark');
    try {
        localStorage.setItem('fixhome-theme', isDark ? 'dark' : 'light');
    } catch (e) {}
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { dark: isDark } }));
};
