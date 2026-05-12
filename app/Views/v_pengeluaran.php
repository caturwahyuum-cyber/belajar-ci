<?php $this->extend('layout') ?>
<?php $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h3 class="mb-4">Grafik Pengeluaran</h3>

        <canvas id="grafikPengeluaran" height="100"></canvas>
    </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctxPengeluaran = document.getElementById('grafikPengeluaran');

    new Chart(ctxPengeluaran, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                label: 'Total Pengeluaran',
                data: [3000000, 4000000, 3500000, 5000000, 4500000, 6000000],
                borderWidth: 2,
                tension: 0.4
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