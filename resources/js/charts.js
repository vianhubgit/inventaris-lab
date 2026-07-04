import {
    Chart,
    BarController,
    BarElement,
    DoughnutController,
    ArcElement,
    LineController,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend,
} from 'chart.js';

Chart.register(
    BarController,
    BarElement,
    DoughnutController,
    ArcElement,
    LineController,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend
);

const PALETTE = ['#2563eb', '#16a34a', '#f59e0b', '#dc2626', '#7c3aed', '#0891b2', '#db2777', '#65a30d'];

/**
 * Inisialisasi semua canvas chart pada halaman.
 * Data dibaca dari atribut data-chart (JSON) sehingga tidak ada inline script.
 */
export function initCharts() {
    document.querySelectorAll('canvas[data-chart]').forEach((canvas) => {
        let config;
        try {
            config = JSON.parse(canvas.getAttribute('data-chart'));
        } catch (e) {
            console.error('Konfigurasi chart tidak valid', e);
            return;
        }

        const type = config.type || 'bar';
        const datasets = (config.datasets || [{ label: config.label || 'Data', data: config.data || [] }]).map(
            (ds, i) => ({
                label: ds.label,
                data: ds.data,
                backgroundColor: type === 'line' ? 'rgba(37,99,235,0.15)' : PALETTE,
                borderColor: type === 'line' ? PALETTE[i % PALETTE.length] : '#ffffff',
                borderWidth: type === 'doughnut' ? 2 : 1,
                fill: type === 'line',
                tension: 0.35,
            })
        );

        new Chart(canvas, {
            type,
            data: { labels: config.labels || [], datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: type === 'doughnut', position: 'bottom' },
                },
                scales:
                    type === 'doughnut'
                        ? {}
                        : { y: { beginAtZero: true, ticks: { precision: 0 } } },
            },
        });
    });
}
