<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="row g-4">
    <!-- Left Column: Checkout Form -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Informasi Pengiriman</h5>
            </div>
            <div class="card-body p-4">
                <?= form_open('buy', ['class' => 'row g-3', 'id' => 'checkoutForm']) ?>
                
                <input type="hidden" name="username"       value="<?= esc(session()->get('username')) ?>">
                <input type="hidden" name="total_harga"    id="total_harga"    value="<?= esc($total) ?>">
                <input type="hidden" name="kelurahan_name" id="kelurahan_name" value="">
                <input type="hidden" name="layanan_name"   id="layanan_name"   value="">

                <!-- Nama Penerima -->
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

                <!-- Alamat Lengkap -->
                <div class="col-12">
                    <?= form_label('Alamat Lengkap (Jalan, No. Rumah, RT/RW)', 'alamat', ['class' => 'form-label fw-semibold']) ?>
                    <?= form_textarea([
                        'name'        => 'alamat',
                        'id'          => 'alamat',
                        'class'       => 'form-control border py-2',
                        'rows'        => 3,
                        'placeholder' => 'Masukkan alamat lengkap pengiriman...',
                        'required'    => true
                    ]) ?>
                </div>

                <!-- Kelurahan (Select2 AJAX) -->
                <div class="col-12">
                    <?= form_label('Kelurahan / Kecamatan / Kota', 'kelurahan', ['class' => 'form-label fw-semibold']) ?>
                    <div class="position-relative">
                        <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control py-2', 'style' => 'width: 100%;', 'required' => true]) ?>
                    </div>
                    <div class="form-text text-muted small mt-1">Ketik minimal 3 karakter untuk mencari daerah pengiriman.</div>
                </div>

                <!-- Layanan Pengiriman -->
                <div class="col-12">
                    <?= form_label('Layanan Pengiriman (JNE)', 'layanan', ['class' => 'form-label fw-semibold']) ?>
                    <?= form_dropdown('layanan', ['' => 'Pilih kelurahan terlebih dahulu'], '', ['id' => 'layanan', 'class' => 'form-select py-2', 'required' => true]) ?>
                </div>

                <!-- Ongkos Kirim -->
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

                <!-- Kode Kupon -->
                <div class="col-12">
                    <?= form_label('Kode Kupon Promo (Opsional)', 'kupon_code', ['class' => 'form-label fw-semibold']) ?>
                    <div class="input-group">
                        <span class="input-group-text border-0 bg-light"><i class="bi bi-tag-fill text-warning"></i></span>
                        <?= form_input([
                            'name'        => 'kupon_code',
                            'id'          => 'kupon_code',
                            'class'       => 'form-control border py-2 text-uppercase',
                            'placeholder' => 'Masukkan kode kupon...',
                            'maxlength'   => '20'
                        ]) ?>
                        <button class="btn btn-outline-warning fw-semibold" type="button" id="btnApplyKupon">
                            Terapkan
                        </button>
                    </div>
                    <div id="kuponFeedback" class="form-text mt-1"></div>
                    <div class="form-text text-muted small">
                        <i class="bi bi-info-circle me-1"></i>Kupon tersedia: 
                        <span class="badge bg-warning text-dark">HEMAT20</span>
                        <span class="badge bg-warning text-dark">HEMAT30</span>
                        <span class="badge bg-warning text-dark">MEMBER25</span>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-12 mt-4">
                    <?= form_submit(
                        'submit',
                        'Buat Pesanan',
                        ['class' => 'btn btn-primary w-100 py-2 fw-bold rounded-pill shadow-sm', 'id' => 'btnSubmit']
                    ) ?>
                </div>
                
                <?= form_close() ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Order Summary -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
            <div class="card-header bg-info text-white py-3">
                <h5 class="mb-0"><i class="bi bi-cart-check-fill me-2"></i>Ringkasan Pesanan</h5>
            </div>
            <div class="card-body p-4">
                <!-- Tabel Produk -->
                <div class="table-responsive mb-3">
                    <table class="table align-middle table-borderless mb-0">
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
                                        <td><span class="fw-semibold text-dark"><?= esc($item['name']) ?></span></td>
                                        <td class="text-end text-secondary"><?= number_to_currency($item['price'], 'IDR') ?></td>
                                        <td class="text-center bg-light rounded px-2"><?= $item['qty'] ?></td>
                                        <td class="text-end fw-semibold text-dark"><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Rincian Biaya -->
                <div class="bg-light p-3 rounded-3">
                    <!-- Subtotal Belanja -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal Belanja</span>
                        <span class="fw-semibold" id="summarySubtotal">IDR <?= number_format($total, 0, ',', '.') ?></span>
                    </div>

                    <!-- Diskon Kupon -->
                    <div class="d-flex justify-content-between mb-2" id="rowDiskon" style="display:none !important;">
                        <span class="text-success"><i class="bi bi-tag-fill me-1"></i>Diskon Kupon (<span id="labelKupon">-</span>)</span>
                        <span class="fw-semibold text-success" id="summaryDiskon">- IDR 0</span>
                    </div>

                    <!-- PPN -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">PPN (12%)</span>
                        <span class="fw-semibold text-danger" id="summaryPPN">+ IDR 0</span>
                    </div>

                    <!-- Biaya Admin -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Biaya Admin (<span id="labelAdminPct">0,5%</span>)</span>
                        <span class="fw-semibold text-danger" id="summaryAdmin">+ IDR 0</span>
                    </div>

                    <!-- Subtotal setelah pajak dan diskon -->
                    <div class="d-flex justify-content-between mb-2 border-top pt-2">
                        <span class="text-muted fw-semibold">Subtotal (incl. PPN & Admin)</span>
                        <span class="fw-bold text-dark" id="summarySubtotalFinal">IDR 0</span>
                    </div>

                    <!-- Ongkos Kirim -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span class="fw-semibold text-primary" id="summaryOngkir">IDR 0</span>
                    </div>

                    <hr class="my-2">

                    <!-- Grand Total -->
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark fs-5">Grand Total</span>
                        <span class="fw-bold text-danger fs-4" id="grandTotal">IDR <?= number_format($total, 0, ',', '.') ?></span>
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
    // -------------------------
    // Variabel utama
    // -------------------------
    let ongkir       = 0;
    let subtotal     = <?= (int)$total ?>;
    let diskonKupon  = 0;
    let kuponValid   = false;
    let kuponCode    = '';

    // Daftar kupon dan persentase diskon
    const KUPON_LIST = {
        'HEMAT20' : 0.20,
        'HEMAT30' : 0.30,
        'MEMBER25': 0.25
    };

    // Fungsi biaya admin berjenjang
    function hitungBiayaAdmin(base) {
        if (base <= 15000000) return { pct: '0,5%', val: base * 0.005 };
        if (base <= 35000000) return { pct: '0,7%', val: base * 0.007 };
        return { pct: '0,9%', val: base * 0.009 };
    }

    // Format angka ke IDR
    function formatIDR(num) {
        return 'IDR ' + Math.round(num).toLocaleString('id-ID');
    }

    // -------------------------
    // Hitung dan render ulang semua biaya
    // -------------------------
    function hitungTotal() {
        // Subtotal setelah diskon
        let subtotalSetelahDiskon = subtotal - diskonKupon;

        // PPN 12% dari subtotal setelah diskon
        let ppn = subtotalSetelahDiskon * 0.12;

        // Biaya admin berjenjang
        let admin = hitungBiayaAdmin(subtotalSetelahDiskon);

        // Subtotal final (belum ongkir)
        let subtotalFinal = subtotalSetelahDiskon + ppn + admin.val;

        // Grand total
        let grandTotal = subtotalFinal + ongkir;

        // --- Render ---
        $('#summarySubtotal').text(formatIDR(subtotal));

        // Baris diskon kupon - tampilkan hanya jika ada diskon
        if (diskonKupon > 0) {
            $('#rowDiskon').show();
            $('#labelKupon').text(kuponCode);
            $('#summaryDiskon').text('- ' + formatIDR(diskonKupon));
        } else {
            $('#rowDiskon').hide();
        }

        $('#summaryPPN').text('+ ' + formatIDR(ppn));
        $('#labelAdminPct').text(admin.pct);
        $('#summaryAdmin').text('+ ' + formatIDR(admin.val));
        $('#summarySubtotalFinal').text(formatIDR(subtotalFinal));
        $('#summaryOngkir').text(formatIDR(ongkir));
        $('#grandTotal').text(formatIDR(grandTotal));

        // Update hidden fields yang dikirim ke server
        $('#ongkir').val(ongkir);
        $('#total_harga').val(Math.round(grandTotal));
    }

    // -------------------------
    // Init Select2 untuk Kelurahan
    // -------------------------
    $('#kelurahan').select2({
        placeholder: 'Cari daerah tujuan (Kelurahan/Kecamatan/Kota)',
        minimumInputLength: 3,
        ajax: {
            url: '<?= site_url('ajax/destinations') ?>',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return { q: params.term };
            },
            processResults: function(data) {
                return data;
            },
            cache: true
        }
    });

    // -------------------------
    // Event: kelurahan dipilih
    // -------------------------
    $('#kelurahan').on('select2:select', function(e) {
        let selectedData = e.params.data;
        $('#kelurahan_name').val(selectedData.text);

        let id_kelurahan = selectedData.id;

        // Reset layanan & ongkir
        $('#layanan').empty().append('<option value="">Mengambil data layanan...</option>');
        ongkir = 0;
        hitungTotal();

        // Ambil biaya ongkir dari API
        $.ajax({
            url: "<?= site_url('ajax/costs') ?>",
            dataType: "json",
            data: { destination: id_kelurahan },
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

    // -------------------------
    // Event: layanan pengiriman dipilih
    // -------------------------
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

    // -------------------------
    // Event: Terapkan kupon
    // -------------------------
    $('#btnApplyKupon').on('click', function() {
        let inputKode = $('#kupon_code').val().toUpperCase().trim();

        if (inputKode === '') {
            // Reset kupon
            kuponValid  = false;
            kuponCode   = '';
            diskonKupon = 0;
            $('#kuponFeedback').html('<span class="text-muted">Kode kupon dihapus.</span>');
            hitungTotal();
            return;
        }

        if (KUPON_LIST.hasOwnProperty(inputKode)) {
            kuponValid   = true;
            kuponCode    = inputKode;
            let pct      = KUPON_LIST[inputKode] * 100;
            diskonKupon  = subtotal * KUPON_LIST[inputKode];
            $('#kuponFeedback').html(`<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Kupon <strong>${inputKode}</strong> berhasil diterapkan! Diskon ${pct}%</span>`);
        } else {
            kuponValid  = false;
            kuponCode   = '';
            diskonKupon = 0;
            $('#kuponFeedback').html('<span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>Kode kupon tidak valid atau tidak ditemukan.</span>');
        }
        hitungTotal();
    });

    // Terapkan kupon saat tekan Enter di input
    $('#kupon_code').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#btnApplyKupon').trigger('click');
        }
    });

    // Render awal
    hitungTotal();
});
</script>
<?= $this->endSection() ?>
