<?php $this->extend('layout') ?>
<?php $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h3 class="mb-4">Grafik Pemasukan</h3>

        <canvas id="grafikPemasukan" height="100"></canvas>
    </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctxPemasukan = document.getElementById('grafikPemasukan');

    new Chart(ctxPemasukan, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Total Pemasukan',
                data: [5000000, 7000000, 6000000, 8000000, 9000000, 7500000],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<?php $this->endSection() ?>