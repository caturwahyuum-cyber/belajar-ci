<?php
/**
 * @var array $items
 * @var float $total
 */
?>
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

<!-- Table with stripped rows -->
<?= form_open('keranjang/edit') ?>
<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">Nama</th>
            <th scope="col">Foto</th>
            <th scope="col">Harga</th> 
            <th scope="col">Jumlah</th>
            <th scope="col">Subtotal</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        if (!empty($items)) :
            foreach ($items as $index => $item) :
        ?>
                <tr>
                    <td><?= esc($item['name']) ?></td>
                    <td>
                        <?php if (isset($item['options']['foto']) && $item['options']['foto'] != '' && file_exists("img/" . $item['options']['foto'])) : ?>
                            <img src="<?= base_url() . "img/" . $item['options']['foto'] ?>" width="100px" class="img-thumbnail">
                        <?php else : ?>
                            <span class="text-muted">Tidak ada gambar</span>
                        <?php endif; ?>
                    </td>
                    <td><?= number_to_currency($item['price'], 'IDR') ?></td> 
                    <td>
                        <input type="number" min="1" name="qty<?= $i++ ?>" class="form-control" style="width: 80px;" value="<?= $item['qty'] ?>">
                    </td>
                    <td><?= number_to_currency($item['subtotal'], 'IDR') ?></td>
                    <td>
                        <a href="<?= base_url('keranjang/delete/' . $item['rowid']) ?>" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
        <?php
            endforeach;
        else:
        ?>
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                    Keranjang masih kosong. <a href="<?= base_url('/') ?>">Ayo belanja!</a>
                </td>
            </tr>
        <?php
        endif;
        ?>
    </tbody>
</table> 

<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="alert alert-info mb-0 py-2 px-3">
        <strong>Total = <?= number_to_currency($total, 'IDR') ?></strong>
    </div>
    <div>
        <?php if (!empty($items)) : ?>
            <button type="submit" class="btn btn-primary me-2">Perbarui Keranjang</button>
            <a class="btn btn-warning text-white me-2" href="<?= base_url('keranjang/clear') ?>">Kosongkan Keranjang</a>
            <a class="btn btn-success" href="<?= base_url('checkout') ?>">Selesai Belanja</a>
        <?php endif; ?>
    </div>
</div>
<?= form_close() ?>

<?= $this->endSection() ?>