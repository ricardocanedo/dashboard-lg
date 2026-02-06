(function () {
    'use strict';

    function initEfficiencyChart() {
        const canvas = document.getElementById('efficiencyChart');
        const chartDataEl = document.getElementById('chartData');

        if (!canvas || !chartDataEl) {
            console.error('Canvas ou dados do gráfico não encontrados');
            return;
        }

        const isEmpty = chartDataEl.dataset.empty === '1';

        if (isEmpty) {
            const ctx = canvas.getContext('2d');
            ctx.font = '16px Arial';
            ctx.fillStyle = '#6c757d';
            ctx.textAlign = 'center';
            ctx.fillText('Sem dados para exibir', canvas.width / 2, canvas.height / 2);
            return;
        }

        try {
            const labels = JSON.parse(chartDataEl.dataset.labels || '[]');
            const efficiencyData = JSON.parse(chartDataEl.dataset.values || '[]');

            const colors = efficiencyData.map(value => {
                if (value >= 95) return '#198754'; // success
                if (value >= 90) return '#ffc107'; // warning
                return '#dc3545'; // danger
            });

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Eficiência (%)',
                        data: efficiencyData,
                        backgroundColor: colors,
                        borderColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Eficiência: ' + context.parsed.y.toFixed(2) + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function (value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Erro ao criar gráfico:', error);
        }
    }

    // Aguarda o Chart.js estar carregado
    function waitForChart() {
        if (typeof Chart !== 'undefined') {
            initEfficiencyChart();
        } else {
            setTimeout(waitForChart, 100);
        }
    }

    // Inicializa quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', waitForChart);
    } else {
        waitForChart();
    }
})();
