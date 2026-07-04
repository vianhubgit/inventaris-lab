/**
 * Loading indicator sederhana: muncul saat navigasi/submit form.
 */
export function initLoader() {
    const bar = document.getElementById('global-loader');
    if (!bar) return;

    let width = 0;
    let timer = null;

    const start = () => {
        width = 0;
        bar.style.width = '0';
        clearInterval(timer);
        timer = setInterval(() => {
            width = Math.min(width + Math.random() * 12, 90);
            bar.style.width = width + '%';
        }, 200);
    };

    const done = () => {
        clearInterval(timer);
        bar.style.width = '100%';
        setTimeout(() => (bar.style.width = '0'), 300);
    };

    document.querySelectorAll('form:not([data-no-loader])').forEach((form) => {
        form.addEventListener('submit', start);
    });
    window.addEventListener('beforeunload', start);
    window.addEventListener('pageshow', done);
}
