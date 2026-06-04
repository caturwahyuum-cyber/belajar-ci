<?php /** @var array $products */ ?>
<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<!-- Custom Notion-like Styling -->
<style>
    /* Clean, modern sans-serif typography */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    .notion-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
        color: rgb(55, 53, 47);
        background: #ffffff;
        padding: 10px 0;
    }
    
    /* Header Section */
    .notion-header {
        margin-bottom: 24px;
        position: relative;
    }
    .notion-emoji {
        font-size: 48px;
        margin-bottom: 8px;
        line-height: 1;
        display: block;
    }
    .notion-title {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 8px 0;
        color: rgb(55, 53, 47);
        letter-spacing: -0.02em;
    }
    .notion-description {
        font-size: 14px;
        color: rgba(55, 53, 47, 0.65);
        margin: 0;
        line-height: 1.5;
    }

    /* Actions Bar */
    .notion-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        border-bottom: 1px solid rgba(55, 53, 47, 0.09);
        padding-bottom: 12px;
    }

    /* Notion Button Style */
    .btn-notion-primary {
        background-color: transparent;
        color: rgb(55, 53, 47);
        border: 1px solid rgba(55, 53, 47, 0.16);
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s ease, border-color 0.15s ease;
        box-shadow: rgba(15, 15, 15, 0.02) 0px 1px 2px;
    }
    .btn-notion-primary:hover {
        background-color: rgba(55, 53, 47, 0.04);
        color: rgb(55, 53, 47);
        border-color: rgba(55, 53, 47, 0.3);
    }
    .btn-notion-primary i {
        font-size: 16px;
    }

    /* Table Styles */
    .notion-table-wrapper {
        overflow-x: auto;
    }
    table.notion-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    table.notion-table th {
        color: rgba(55, 53, 47, 0.6) !important;
        font-weight: 500 !important;
        font-size: 12px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        text-align: left;
        padding: 10px 12px !important;
        border-bottom: 1px solid rgba(55, 53, 47, 0.09) !important;
        background: transparent !important;
    }
    table.notion-table td {
        padding: 12px 12px !important;
        border-bottom: 1px solid rgba(55, 53, 47, 0.09) !important;
        color: rgb(55, 53, 47) !important;
        vertical-align: middle !important;
        background: transparent !important;
    }
    table.notion-table tr:hover td {
        background-color: rgba(55, 53, 47, 0.02) !important;
    }

    /* Product Thumbnail */
    .product-thumb {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid rgba(55, 53, 47, 0.09);
        box-shadow: rgba(0, 0, 0, 0.04) 0px 2px 4px;
    }
    .no-thumb {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 4px;
        background: rgba(55, 53, 47, 0.05);
        color: rgba(55, 53, 47, 0.4);
        font-size: 20px;
        border: 1px solid rgba(55, 53, 47, 0.05);
    }

    /* Numeric columns alignment */
    .col-num {
        font-variant-numeric: tabular-nums;
    }

    /* Notion Badges */
    .notion-badge-stock {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        line-height: 1.2;
    }
    .badge-instock {
        background-color: rgba(219, 237, 219, 0.6);
        color: rgb(28, 56, 41);
    }
    .badge-lowstock {
        background-color: rgba(253, 245, 207, 0.6);
        color: rgb(64, 44, 27);
    }
    .badge-outstock {
        background-color: rgba(255, 226, 221, 0.6);
        color: rgb(93, 23, 21);
    }

    /* Actions buttons */
    .btn-notion-action {
        background: transparent;
        border: none;
        color: rgba(55, 53, 47, 0.6);
        padding: 4px 8px;
        font-size: 13px;
        border-radius: 3px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
    }
    .btn-notion-action:hover {
        background: rgba(55, 53, 47, 0.06);
        color: rgb(55, 53, 47);
    }
    .btn-notion-action.delete:hover {
        background: rgba(255, 226, 221, 0.6);
        color: rgb(93, 23, 21);
    }

    /* Clean Search Input overrides (from simple-datatables) */
    .datatable-top {
        padding: 0 0 16px 0 !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .datatable-search input {
        border: 1px solid rgba(55, 53, 47, 0.16) !important;
        border-radius: 4px !important;
        padding: 6px 12px !important;
        font-size: 14px !important;
        background: transparent !important;
        color: rgb(55, 53, 47) !important;
        outline: none !important;
        transition: border-color 0.15s ease !important;
        width: 220px;
    }
    .datatable-search input:focus {
        border-color: rgba(55, 53, 47, 0.5) !important;
        box-shadow: none !important;
    }
    .datatable-dropdown select {
        border: 1px solid rgba(55, 53, 47, 0.16) !important;
        border-radius: 4px !important;
        padding: 6px 8px !important;
        font-size: 14px !important;
        background: transparent !important;
        color: rgb(55, 53, 47);
    }
    .datatable-info, .datatable-selector {
        font-size: 13px !important;
        color: rgba(55, 53, 47, 0.6);
    }
    
    /* Pagination overrides */
    .datatable-pagination a {
        border: 1px solid rgba(55, 53, 47, 0.12) !important;
        color: rgb(55, 53, 47) !important;
        border-radius: 4px !important;
        padding: 4px 8px !important;
        font-size: 13px !important;
        background: transparent !important;
        margin: 0 2px !important;
        transition: background 0.15s ease;
    }
    .datatable-pagination a:hover {
        background-color: rgba(55, 53, 47, 0.04) !important;
    }
    .datatable-pagination .active a {
        background-color: rgba(55, 53, 47, 0.06) !important;
        border-color: rgba(55, 53, 47, 0.3) !important;
        font-weight: 600 !important;
    }

    /* Notion Modals */
    .modal-content {
        border-radius: 8px !important;
        border: none !important;
        box-shadow: rgba(15, 15, 15, 0.05) 0px 0px 0px 1px, rgba(15, 15, 15, 0.1) 0px 3px 6px, rgba(15, 15, 15, 0.2) 0px 9px 24px !important;
        background-color: #ffffff !important;
        color: rgb(55, 53, 47) !important;
    }
    .modal-header {
        border-bottom: 1px solid rgba(55, 53, 47, 0.09) !important;
        padding: 16px 20px !important;
    }
    .modal-title {
        font-weight: 600 !important;
        color: rgb(55, 53, 47) !important;
        font-size: 16px !important;
    }
    .modal-body {
        padding: 20px !important;
    }
    .modal-footer {
        border-top: 1px solid rgba(55, 53, 47, 0.09) !important;
        padding: 12px 20px !important;
    }
    .form-control {
        border: 1px solid rgba(55, 53, 47, 0.16) !important;
        border-radius: 4px !important;
        padding: 8px 12px !important;
        font-size: 14px !important;
        color: rgb(55, 53, 47) !important;
        background: transparent !important;
        transition: border-color 0.15s ease !important;
    }
    .form-control:focus {
        border-color: rgba(55, 53, 47, 0.5) !important;
        box-shadow: none !important;
        background: transparent !important;
    }
    .form-label {
        font-weight: 500 !important;
        font-size: 13px !important;
        color: rgba(55, 53, 47, 0.65) !important;
        margin-bottom: 6px !important;
    }

    /* Bootstrap alerts styling in Notion way */
    .alert {
        border-radius: 4px !important;
        font-size: 14px !important;
        padding: 10px 14px !important;
        border: none !important;
        display: flex;
        align-items: center;
    }
    .alert-info {
        background-color: rgba(219, 237, 219, 0.6) !important;
        color: rgb(28, 56, 41) !important;
    }
    .alert-danger {
        background-color: rgba(255, 226, 221, 0.6) !important;
        color: rgb(93, 23, 21) !important;
    }
</style>

<div class="notion-container">
    <!-- Header Page -->
    <div class="notion-header">
        <span class="notion-emoji">📦</span>
        <h1 class="notion-title">Database Produk</h1>
        <p class="notion-description">Gunakan database ini untuk melacak detail inventaris produk, mengunggah foto, memperbarui stok, serta mengelola harga secara terpusat.</p>
    </div>

    <!-- Alert Message Flashdata -->
    <?php if (session()->getFlashData('success')) : ?>
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashData('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashData('failed')) : ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashData('failed') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Actions Bar -->
    <div class="notion-actions">
        <button type="button" class="btn-notion-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="bi bi-plus-lg"></i> Tambah Produk Baru
        </button>
    </div>

    <!-- Table Container -->
    <div class="notion-table-wrapper">
        <table class="table notion-table datatable">
            <thead>
                <tr>
                    <th scope="col" style="width: 5%">#</th>
                    <th scope="col" style="width: 15%">Foto</th>
                    <th scope="col" style="width: 35%">Nama Produk</th>
                    <th scope="col" style="width: 15%">Harga</th>
                    <th scope="col" style="width: 15%">Stok</th>
                    <th scope="col" style="width: 15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $index => $produk) : ?>
                    <tr>
                        <td class="col-num"><?= $index + 1 ?></td>
                        <td>
                            <?php if ($produk['foto'] != '' and file_exists("img/" . $produk['foto'] . "")) : ?>
                                <img src="<?= base_url() . "img/" . $produk['foto'] ?>" class="product-thumb" alt="Product Image">
                            <?php else : ?>
                                <span class="no-thumb"><i class="bi bi-image"></i></span>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight: 500;"><?= esc($produk['nama']) ?></td>
                        <td class="col-num">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></td>
                        <td>
                            <?php 
                                $stock = intval($produk['jumlah']);
                                if ($stock <= 0) {
                                    echo '<span class="notion-badge-stock badge-outstock">Habis</span>';
                                } elseif ($stock <= 5) {
                                    echo '<span class="notion-badge-stock badge-lowstock">Menipis (' . $stock . ')</span>';
                                } else {
                                    echo '<span class="notion-badge-stock badge-instock">' . $stock . ' Pcs</span>';
                                }
                            ?>
                        </td>
                        <td>
                            <button type="button" class="btn-notion-action" data-bs-toggle="modal" data-bs-target="#editModal-<?= $produk['id'] ?>">
                                <i class="bi bi-pencil-square"></i> Ubah
                            </button>
                            <a href="<?= base_url('produk/delete/' . $produk['id']) ?>" class="btn-notion-action delete" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->include('produk/modal_add') ?>
<?= $this->include('produk/modal_edit') ?>

<?= $this->endSection() ?>
