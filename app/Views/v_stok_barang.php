<?php $this->extend('layout') ?>
<?php $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h3 class="mb-4">Cek Stok Barang</h3>

        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Cari barang...">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    Cari
                </button>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Laptop Asus</td>
                    <td>Elektronik</td>
                    <td>15</td>
                    <td>
                        <span class="badge bg-success">Tersedia</span>
                    </td>
                </tr>
    </div>
    <tr>
        <td>2</td>
        <td>Mouse Logitech</td>
        <td>Elektronik</td>
        <td>0</td>
        <td>
            <span class="badge bg-danger">Habis</span>
        </td>
    </tr>
    </tbody>
    </table>
</div>
<?php $this->endSection() ?>