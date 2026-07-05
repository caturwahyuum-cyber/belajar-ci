<?php

/**
 * Helper Perhitungan Transaksi
 * 
 * Berisi fungsi-fungsi untuk menghitung PPN, biaya admin berjenjang,
 * dan diskon kupon promo.
 */

if (! function_exists('hitung_ppn')) {
    /**
     * Menghitung PPN 12% dari total harga belanja.
     *
     * @param float $subtotal Total harga belanja (sebelum PPN, ongkir, admin)
     * @return float Nilai PPN
     */
    function hitung_ppn(float $subtotal): float
    {
        return $subtotal * 0.12;
    }
}

if (! function_exists('hitung_biaya_admin')) {
    /**
     * Menghitung biaya admin berjenjang berdasarkan total belanja.
     *
     * Tier biaya admin:
     * - <= Rp 15.000.000  : 0,5%
     * - Rp 15.000.001 - Rp 35.000.000 : 0,7%
     * - > Rp 35.000.000   : 0,9%
     *
     * @param float $subtotal Total harga belanja (sebelum PPN, ongkir, admin)
     * @return float Nilai biaya admin
     */
    function hitung_biaya_admin(float $subtotal): float
    {
        if ($subtotal <= 15000000) {
            return $subtotal * 0.005;
        } elseif ($subtotal <= 35000000) {
            return $subtotal * 0.007;
        } else {
            return $subtotal * 0.009;
        }
    }
}

if (! function_exists('persen_biaya_admin')) {
    /**
     * Mengembalikan persentase biaya admin sebagai string untuk tampilan.
     *
     * @param float $subtotal Total harga belanja
     * @return string Persentase dalam format "0,5%"
     */
    function persen_biaya_admin(float $subtotal): string
    {
        if ($subtotal <= 15000000) {
            return '0,5%';
        } elseif ($subtotal <= 35000000) {
            return '0,7%';
        } else {
            return '0,9%';
        }
    }
}

if (! function_exists('hitung_diskon_kupon')) {
    /**
     * Menghitung nilai diskon berdasarkan kode kupon.
     *
     * Daftar kupon yang berlaku:
     * - HEMAT20  : 20%
     * - HEMAT30  : 30%
     * - MEMBER25 : 25%
     *
     * @param float  $subtotal   Total harga belanja (sebelum diskon)
     * @param string $kupon_code Kode kupon (case-insensitive)
     * @return float Nilai diskon
     */
    function hitung_diskon_kupon(float $subtotal, string $kupon_code): float
    {
        $kupon_code = strtoupper(trim($kupon_code));

        $daftar_kupon = [
            'HEMAT20'  => 0.20,
            'HEMAT30'  => 0.30,
            'MEMBER25' => 0.25,
        ];

        if (isset($daftar_kupon[$kupon_code])) {
            return $subtotal * $daftar_kupon[$kupon_code];
        }

        return 0.0;
    }
}

if (! function_exists('is_kupon_valid')) {
    /**
     * Memeriksa apakah kode kupon valid.
     *
     * @param string $kupon_code Kode kupon
     * @return bool
     */
    function is_kupon_valid(string $kupon_code): bool
    {
        $daftar_kupon = ['HEMAT20', 'HEMAT30', 'MEMBER25'];
        return in_array(strtoupper(trim($kupon_code)), $daftar_kupon);
    }
}
