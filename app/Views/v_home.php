<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?> 

<!-- Product cards -->
<div class="row">
    <?php if (! empty($products)) : ?>
        <?php foreach ($products as $item) : ?>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 mb-4 bg-light">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <?php if ($item['foto'] != '' and file_exists("img/" . $item['foto'] . "")) : ?>
                                    <img src="<?php echo base_url() . "img/" . $item['foto'] ?>" class="img-fluid rounded-3 border" style="max-height: 120px; object-fit: cover;">
                                <?php else : ?>
                                    <div class="bg-white border rounded-3 d-flex align-items-center justify-content-center" style="height: 120px;">
                                        <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <h5 class="card-title mb-1 text-dark"><?= esc($item['nama']) ?></h5>
                                <p class="card-text mb-1 text-primary fw-bold"><?= number_to_currency($item['harga'], 'IDR') ?></p>
                                <?php if (isset($item['jumlah'])) : ?>
                                    <p class="card-text mb-2 text-secondary small">Stok: <?= esc($item['jumlah']) ?></p>
                                <?php endif ?>
                                
                                <?= form_open('keranjang') ?>
                                <?= form_hidden([
                                    'id'    => (string) $item['id'],
                                    'nama'  => (string) $item['nama'],
                                    'harga' => (string) $item['harga'],
                                    'foto'  => (string) ($item['foto'] ?? '')]) ?>
                                <button type="submit" class="btn btn-info rounded-pill btn-sm text-white px-3">
                                    <i class="bi bi-cart-plus me-1"></i> Beli
                                </button>
                                <?= form_close() ?>
                            </div>
                        </div>
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