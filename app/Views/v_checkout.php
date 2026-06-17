<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row">
    <!-- Left Column: Checkout Form -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Informasi Pengiriman</h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('buy', ['class' => 'row g-3', 'id' => 'checkoutForm']) ?>
                
                <input type="hidden" name="username" value="<?= esc(session()->get('username')) ?>">
                <input type="hidden" name="total_harga" id="total_harga" value="<?= esc($total) ?>">
                <input type="hidden" name="kelurahan_name" id="kelurahan_name" value="">
                <input type="hidden" name="layanan_name" id="layanan_name" value="">

                <div class="col-12">
                    <?= form_label('Nama Penerima', 'nama', ['class' => 'form-label fw-semibold']) ?>
                    <?= form_input([
                        'name'     => 'nama',
                        'id'       => 'nama',
                        'class'    => 'form-control bg-light border-0 py-2',
                        'value'    => session()->get('username'),
                        'readonly' => true
                    ]) ?>
                </div>

                <div class="col-12">
                    <?= form_label('Alamat Lengkap (Jalan, No. Rumah, RT/RW)', 'alamat', ['class' => 'form-label fw-semibold']) ?>
                    <?= form_textarea([
                        'name'  => 'alamat',
                        'id'    => 'alamat',
                        'class' => 'form-control border py-2',
                        'rows'  => 3,
                        'placeholder' => 'Masukkan alamat lengkap pengiriman...',
                        'required' => true
                    ]) ?>
                </div> 

                <div class="col-12"> 
                    <?= form_label('Kelurahan / Kecamatan / Kota', 'kelurahan', ['class' => 'form-label fw-semibold']) ?>
                    <div class="position-relative">
                        <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control py-2', 'style' => 'width: 100%;', 'required' => true]) ?>
                    </div>
                    <div class="form-text text-muted small mt-1">Ketik minimal 3 karakter untuk mencari daerah pengiriman.</div>
                </div>

                <div class="col-12"> 
                    <?= form_label('Layanan Pengiriman (JNE)', 'layanan', ['class' => 'form-label fw-semibold']) ?> 
                    <?= form_dropdown('layanan', ['' => 'Pilih kelurahan terlebih dahulu'], '', ['id' => 'layanan', 'class' => 'form-select py-2', 'required' => true]) ?>
                </div>

                <div class="col-12">
                    <?= form_label('Biaya Ongkos Kirim', 'ongkir', ['class' => 'form-label fw-semibold']) ?>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light">IDR</span>
                        <?= form_input([
                            'name'     => 'ongkir',
                            'id'       => 'ongkir',
                            'class'    => 'form-control bg-light border-0 fw-bold py-2',
                            'value'    => '0',
                            'readonly' => true
                        ]) ?>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <?= form_submit(
                        'submit',
                        'Buat Pesanan',
                        ['class' => 'btn btn-primary w-100 py-2 fw-bold rounded-pill shadow-sm']
                    ) ?>
                </div>
                
                <?= form_close() ?> 
            </div>
        </div>
    </div>

    <!-- Right Column: Cart Summary -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-info text-white py-3">
                <h5 class="mb-0"><i class="bi bi-cart-check-fill me-2"></i>Ringkasan Belanja</h5>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table align-middle table-borderless">
                        <thead>
                            <tr class="border-bottom text-muted">
                                <th scope="col" style="width: 50%;">Nama Produk</th>
                                <th scope="col" class="text-end">Harga</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)) : ?>
                                <?php foreach ($items as $item) : ?>
                                    <tr class="border-bottom-dashed">
                                        <td>
                                            <span class="fw-semibold text-dark"><?= esc($item['name']) ?></span>
                                        </td>
                                        <td class="text-end text-secondary"><?= number_to_currency($item['price'], 'IDR') ?></td>
                                        <td class="text-center bg-light rounded px-2"><?= $item['qty'] ?></td>
                                        <td class="text-end fw-semibold text-dark"><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-light p-3 rounded-3 mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal Belanja</span>
                        <span class="fw-semibold" id="summarySubtotal"><?= number_to_currency($total, 'IDR') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span class="fw-semibold text-primary" id="summaryOngkir">IDR 0</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark fs-5">Total Pembayaran</span>
                        <span class="fw-bold text-danger fs-4" id="total"><?= number_to_currency($total, 'IDR') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let ongkir = 0;
    let subtotal = <?= $total ?>;
    
    // Initialize select2 for destination search
    $('#kelurahan').select2({
        placeholder: 'Cari daerah tujuan (Kelurahan/Kecamatan/Kota)',
        minimumInputLength: 3,
        ajax: {
            url: '<?= site_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        }
    });

    // Event on kelurahan selected
    $('#kelurahan').on('select2:select', function(e) {
        let selectedData = e.params.data;
        $('#kelurahan_name').val(selectedData.text);
        
        let id_kelurahan = selectedData.id;

        // Reset and show loading state
        $('#layanan').empty().append('<option value="">Mengambil data layanan...</option>');
        ongkir = 0;
        hitungTotal();

        // Get shipping costs
        $.ajax({
            url: "<?= site_url('ajax/costs') ?>",
            dataType: "json",
            data: {
                destination: id_kelurahan
            },
            success: function(data) {
                $('#layanan').empty();
                if (data.length === 0) {
                    $('#layanan').append('<option value="">Layanan tidak tersedia</option>');
                    return;
                }
                
                $('#layanan').append('<option value="">-- Pilih Layanan Pengiriman --</option>');
                data.forEach(function(item) {
                    $('#layanan').append(
                        $('<option>', {
                            value: item.cost,
                            text: `${item.description} (${item.service}) - ${item.cost.toLocaleString('id-ID')} (Est. ${item.etd})`
                        })
                    );
                });
            },
            error: function() {
                $('#layanan').empty().append('<option value="">Gagal mengambil layanan ongkir</option>');
            }
        });
    });

    // Event on shipping service selection
    $('#layanan').on('change', function() {
        let val = $(this).val();
        if (val) {
            ongkir = parseInt(val);
            $('#layanan_name').val($("#layanan option:selected").text());
        } else {
            ongkir = 0;
            $('#layanan_name').val('');
        }
        hitungTotal();
    });

    // Function to compute and render total pricing
    function hitungTotal() {
        let total = subtotal + ongkir;

        $("#ongkir").val(ongkir);
        $("#summaryOngkir").text(`IDR ${ongkir.toLocaleString('id-ID')}`);
        $("#total").text(`IDR ${total.toLocaleString('id-ID')}`);
        $("#total_harga").val(total);
    }
});
</script>
<?= $this->endSection() ?>
