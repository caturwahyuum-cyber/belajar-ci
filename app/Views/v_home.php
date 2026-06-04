<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<!-- Product cards -->
<div class="row">
    <?php if (! empty($products)) : ?>
        <?php foreach ($products as $item) : ?>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($item['nama']) ?></h5>
                        <p class="card-text">Harga: <?= esc($item['harga']) ?></p>
                        <?php if (isset($item['jumlah'])) : ?>
                            <p class="card-text">Stok: <?= esc($item['jumlah']) ?></p>
                        <?php endif ?>
                        <?php if (! empty($item['description'])) : ?>
                            <p class="card-text"><?= esc($item['description']) ?></p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    <?php else : ?>
        <div class="col-12">
            <div class="alert alert-info">Belum ada produk.</div>
        </div>
    <?php endif ?>
</div>
<!-- End Product cards -->
<?= $this->endSection() ?>