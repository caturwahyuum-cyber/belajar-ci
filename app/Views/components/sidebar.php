<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == '') ? "" : "collapsed" ?>" href="/">
                <i class="bi bi-grid"></i>
                <span>Home</span>
            </a>
        </li><!-- End Home Nav -->

        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'keranjang') ? "" : "collapsed" ?>" href="keranjang">
                <i class="bi bi-cart-check"></i>
                <span>Keranjang</span>
            </a>
        </li><!-- End Keranjang Nav -->

        <?php
        if (session()->get('role') == 'admin') {
        ?>
            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'produk') ? "" : "collapsed" ?>" href="produk">
                    <i class="bi bi-receipt"></i>
                    <span>Produk</span>
                </a>
            </li><!-- End Produk Nav -->
        <?php
        }
        ?>

        <li class="nav-item">
            <a class="nav-link <?php echo (uri_string() == 'history') ? "" : "collapsed" ?>" href="history">
                <i class="bi bi-person"></i>
                <span>History</span>
            </a>
        </li><!-- End History Nav -->

        <?php
        if (session()->get('role') == 'manager') {
        ?>
            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'pemasukan') ? "" : "collapsed" ?>" href="pemasukan">
                    <i class="bi bi-cash-stack"></i>
                    <span>Pemasukan</span>
                </a>
            </li><!-- End Pemasukan Nav -->

            <li class="nav-item">
                <a class="nav-link <?php echo (uri_string() == 'pengeluaran') ? "" : "collapsed" ?>" href="pengeluaran">
                    <i class="bi bi-wallet"></i>
                    <span>Pengeluaran</span>
                </a>
            </li><!-- End Pengeluaran Nav -->
        <?php
        }
        ?>
        <?php if (session()->get('role') == 'staf' || session()->get('role') == 'manager') : ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="/stok">
                    <i class="bi bi-box-seam"></i>
                    <span>Cek Stok Barang</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>

</aside><!-- End Sidebar-->