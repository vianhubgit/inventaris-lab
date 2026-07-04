/**
 * Dark mode berbasis class Tailwind, persisten via localStorage.
 * Skrip kecil di <head> (lihat layout) mencegah "flash" sebelum modul ini load.
 */
export function initDarkMode() {
    const apply = (dark) => {
        document.documentElement.classList.toggle('dark', dark);
        localStorage.setItem('theme', dark ? 'dark' : 'light');
    };

    const stored = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    apply(stored ? stored === 'dark' : prefersDark);

    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            apply(!document.documentElement.classList.contains('dark'));
        });
    });
}
