<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center mb-4">
    <h4 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Transaksi</h4>
    <span class="badge bg-primary ms-3 fs-6"><?= esc($username) ?></span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table datatable table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="ps-4">#</th>
                        <th scope="col">ID Pembelian</th>
                        <th scope="col">Waktu Pembelian</th>
                        <th scope="col">Grand Total</th>
                        <th scope="col">Kupon</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($transactions)) :
                        foreach ($transactions as $index => $item) :
                    ?>
                        <tr>
                            <th scope="row" class="ps-4"><?= $index + 1 ?></th>
                            <td><span class="badge bg-secondary">#<?= $item['id'] ?></span></td>
                            <td class="text-muted small"><?= $item['created_at'] ?></td>
                            <td class="fw-bold text-danger"><?= number_to_currency($item['total_harga'], 'IDR') ?></td>
                            <td>
                                <?php if (!empty($item['kupon_code'])) : ?>
                                    <span class="badge bg-warning text-dark"><?= esc($item['kupon_code']) ?></span>
                                <?php else : ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small" style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= esc($item['alamat']) ?>">
                                <?= esc($item['alamat']) ?>
                            </td>
                            <td>
                                <?php
                                $status = $item['status'];
                                if ($status === 'Selesai' || $status === '1') {
                                    echo '<span class="badge bg-success">Selesai</span>';
                                } elseif ($status === 'Diproses') {
                                    echo '<span class="badge bg-info text-dark">Diproses</span>';
                                } else {
                                    echo '<span class="badge bg-warning text-dark">Pending</span>';
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                    class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailModal-<?= $item['id'] ?>">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </button>
                            </td>
                        </tr>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===================== MODALS DETAIL TRANSAKSI ===================== -->
<?php if (!empty($transactions)) : ?>
    <?php foreach ($transactions as $item) : ?>
        <div class="modal fade" id="detailModal-<?= $item['id'] ?>" tabindex="-1" aria-labelledby="detailLabel-<?= $item['id'] ?>">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="detailLabel-<?= $item['id'] ?>">
                            <i class="bi bi-receipt me-2"></i>Detail Transaksi #<?= $item['id'] ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Kolom Kiri: Detail Produk -->
                            <div class="col-md-7">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-box-seam me-2"></i>Produk Dipesan</h6>
                                <?php if (!empty($products[$item['id']])) : ?>
                                    <?php foreach ($products[$item['id']] as $index2 => $item2) : ?>
                                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                                            <?php
                                            $imagePath = FCPATH . 'img/' . $item2['foto'];
                                            if (!empty($item2['foto']) && file_exists($imagePath)) :
                                            ?>
                                                <img src="<?= base_url('img/' . $item2['foto']) ?>" width="70" height="70" class="rounded img-thumbnail object-fit-cover" style="object-fit:cover;">
                                            <?php else : ?>
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:70px;height:70px;">
                                                    <i class="bi bi-image text-muted fs-4"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold"><?= esc($item2['nama']) ?></div>
                                                <div class="text-muted small"><?= number_to_currency($item2['harga'], 'IDR') ?> × <?= $item2['jumlah'] ?> pcs</div>
                                                <div class="fw-bold text-dark"><?= number_to_currency($item2['subtotal_harga'], 'IDR') ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p class="text-muted">Tidak ada detail produk.</p>
                                <?php endif; ?>

                                <!-- Alamat Pengiriman -->
                                <div class="mt-3 p-3 bg-light rounded-3">
                                    <div class="text-muted small mb-1"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Alamat Pengiriman</div>
                                    <div class="fw-semibold small"><?= esc($item['alamat']) ?></div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: Rincian Biaya -->
                            <div class="col-md-5">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-calculator me-2"></i>Rincian Biaya</h6>
                                <div class="bg-light rounded-3 p-3">

                                    <?php
                                    // Hitung subtotal belanja (sebelum diskon/ppn/admin)
                                    $subtotalBelanja = 0;
                                    if (!empty($products[$item['id']])) {
                                        foreach ($products[$item['id']] as $p) {
                                            $subtotalBelanja += $p['subtotal_harga'];
                                        }
                                    }
                                    $diskonKupon = (float)($item['diskon_kupon'] ?? 0);
                                    $ppn         = (float)($item['ppn']          ?? 0);
                                    $biayaAdmin  = (float)($item['biaya_admin']  ?? 0);
                                    $ongkir      = (float)($item['ongkir']       ?? 0);
                                    $grandTotal  = (float)($item['total_harga']  ?? 0);
                                    ?>

                                    <!-- Subtotal Belanja -->
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Subtotal Belanja</span>
                                        <span class="fw-semibold small"><?= number_to_currency($subtotalBelanja, 'IDR') ?></span>
                                    </div>

                                    <!-- Diskon Kupon -->
                                    <?php if ($diskonKupon > 0) : ?>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-success small">
                                            <i class="bi bi-tag-fill me-1"></i>
                                            Diskon Kupon
                                            <?php if (!empty($item['kupon_code'])) : ?>
                                                <span class="badge bg-warning text-dark ms-1"><?= esc($item['kupon_code']) ?></span>
                                            <?php endif; ?>
                                        </span>
                                        <span class="fw-semibold text-success small">- <?= number_to_currency($diskonKupon, 'IDR') ?></span>
                                    </div>
                                    <?php endif; ?>

                                    <!-- PPN -->
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">PPN (12%)</span>
                                        <span class="fw-semibold text-danger small">+ <?= number_to_currency($ppn, 'IDR') ?></span>
                                    </div>

                                    <!-- Biaya Admin -->
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Biaya Admin</span>
                                        <span class="fw-semibold text-danger small">+ <?= number_to_currency($biayaAdmin, 'IDR') ?></span>
                                    </div>

                                    <!-- Ongkos Kirim -->
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small">Ongkos Kirim</span>
                                        <span class="fw-semibold text-primary small"><?= number_to_currency($ongkir, 'IDR') ?></span>
                                    </div>

                                    <hr class="my-2">

                                    <!-- Grand Total -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Grand Total</span>
                                        <span class="fw-bold text-danger fs-6"><?= number_to_currency($grandTotal, 'IDR') ?></span>
                                    </div>
                                </div>

                                <!-- Status Transaksi -->
                                <div class="mt-3 p-3 bg-light rounded-3">
                                    <div class="text-muted small mb-1"><i class="bi bi-info-circle me-1"></i>Status Pesanan</div>
                                    <?php
                                    $status = $item['status'];
                                    if ($status === 'Selesai' || $status === '1') {
                                        echo '<span class="badge bg-success fs-6 px-3 py-2">Selesai</span>';
                                    } elseif ($status === 'Diproses') {
                                        echo '<span class="badge bg-info text-dark fs-6 px-3 py-2">Diproses</span>';
                                    } else {
                                        echo '<span class="badge bg-warning text-dark fs-6 px-3 py-2">Pending</span>';
                                    }
                                    ?>
                                </div>

                                <!-- Tanggal Transaksi -->
                                <div class="mt-3 p-3 bg-light rounded-3">
                                    <div class="text-muted small mb-1"><i class="bi bi-calendar-event me-1"></i>Tanggal Transaksi</div>
                                    <div class="fw-semibold small"><?= $item['created_at'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
