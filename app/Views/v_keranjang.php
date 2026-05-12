<?php $this->extend('layout') ?>
<?php $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h3 class="mb-4">Data Keranjang</h3>

        <form>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" placeholder="Masukkan nama barang">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" class="form-control" placeholder="0">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Harga</label>
                    <input type="number" class="form-control" placeholder="0">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        Tambah
                    </button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Laptop</td>
                    <td>2</td>
                    <td>Rp 5.000.000</td>
                    <td>Rp 10.000.000</td>
                    <td>
                        <button class="btn btn-warning btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php $this->endSection() ?>