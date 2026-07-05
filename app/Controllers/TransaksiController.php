<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Services\RajaOngkirService;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        helper(['number', 'form', 'transaksi']);
        $this->cart = service('cart');
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
    }

    public function index()
    {  
        $data = [
            'items' => $this->cart->contents(),
            'total' => $this->cart->total()
        ];

        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
        $this->cart->insert([
            'id'      => $this->request->getPost('id'),
            'qty'     => 1,
            'price'   => $this->request->getPost('harga'),
            'name'    => $this->request->getPost('nama'),
            'options' => [
                'foto' => $this->request->getPost('foto')
            ]
        ]);
        
        session()->setFlashdata(
            'success',
            'Produk berhasil ditambahkan ke keranjang. <a href="' . base_url('keranjang') . '">Lihat</a>'
        );
        
        return redirect()->to(base_url('/'));
    } 

    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $item) {
            $qty = $this->request->getPost('qty' . $i++);

            $this->cart->update([
                'rowid' => $item['rowid'],
                'qty'   => $qty
            ]);
        }

        session()->setFlashdata(
            'success',
            'Keranjang berhasil diperbarui'
        );

        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);

        session()->setFlashdata(
            'success',
            'Produk berhasil dihapus dari keranjang'
        );

        return redirect()->to(base_url('keranjang'));
    }

    public function cart_clear()
    {
        $this->cart->destroy();

        session()->setFlashdata(
            'success',
            'Keranjang berhasil dikosongkan'
        );

        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
        if (empty($this->cart->contents())) {
            return redirect()->to(base_url('keranjang'))->with('failed', 'Keranjang belanja Anda kosong.');
        }

        $data = [
            'items' => $this->cart->contents(),
            'total' => $this->cart->total()
        ];

        return view('v_checkout', $data);
    }

    public function destinations()
    {
        $search = $this->request->getGet('q');
        
        $service = new RajaOngkirService();
        $response = $service->getDestination($search ?? '');

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'id'   => $item['id'],
                'text' => $item['label']
            ];
        }

        return $this->response->setJSON([
            'results' => $results
        ]);
    }

    public function costs()
    {
        $origin = '64999'; // PEDURUNGAN TENGAH
        $destination = $this->request->getGet('destination');
        $weight = 1000;
        $courier = 'jne';

        $service = new RajaOngkirService();
        $response = $service->getCost($origin, $destination, $weight, $courier);

        $results = [];
        $data = $response['data'] ?? [];

        foreach ($data as $item) {
            $results[] = [
                'service'     => $item['service'],
                'description' => $item['description'],
                'cost'        => $item['cost'],
                'etd'         => $item['etd']
            ];
        }

        return $this->response->setJSON($results);
    }

    public function buy()
    {
        if (empty($this->cart->contents())) {
            return redirect()->to(base_url('/'))->with('failed', 'Keranjang belanja kosong.');
        }

        $streetAddress = $this->request->getPost('alamat');
        $kelurahanName = $this->request->getPost('kelurahan_name');
        $fullAddress   = $streetAddress . ', ' . $kelurahanName;

        // --- Kalkulasi Biaya ---
        // 1. Subtotal belanja (hanya harga produk)
        $subtotal_belanja = (float) $this->cart->total();

        // 2. Kupon diskon
        $kupon_code   = strtoupper(trim($this->request->getPost('kupon_code') ?? ''));
        $diskon_kupon = hitung_diskon_kupon($subtotal_belanja, $kupon_code);

        // 3. Subtotal setelah diskon kupon
        $subtotal_setelah_diskon = $subtotal_belanja - $diskon_kupon;

        // 4. PPN 12% dari subtotal setelah diskon
        $ppn = hitung_ppn($subtotal_setelah_diskon);

        // 5. Biaya admin berjenjang dari subtotal setelah diskon
        $biaya_admin = hitung_biaya_admin($subtotal_setelah_diskon);

        // 6. Ongkos kirim dari form
        $ongkir = (float) $this->request->getPost('ongkir');

        // 7. Grand total
        $grand_total = $subtotal_setelah_diskon + $ppn + $biaya_admin + $ongkir;

        $transactionModel      = new TransactionModel();
        $transactionDetailModel = new TransactionDetailModel();

        // Insert ke tabel transaction
        $transactionData = [
            'username'     => $this->request->getPost('username'),
            'total_harga'  => $grand_total,
            'alamat'       => $fullAddress,
            'ongkir'       => $ongkir,
            'status'       => 'Pending',
            'ppn'          => $ppn,
            'biaya_admin'  => $biaya_admin,
            'kupon_code'   => $kupon_code !== '' ? $kupon_code : null,
            'diskon_kupon' => $diskon_kupon,
        ];

        $transactionId = $transactionModel->insert($transactionData);

        if ($transactionId) {
            // Insert detail produk
            foreach ($this->cart->contents() as $item) {
                $detailData = [
                    'transaction_id' => $transactionId,
                    'product_id'     => $item['id'],
                    'jumlah'         => $item['qty'],
                    'diskon'         => 0.00,
                    'subtotal_harga' => $item['price'] * $item['qty']
                ];
                $transactionDetailModel->insert($detailData);
            }

            // Kosongkan keranjang
            $this->cart->destroy();

            return redirect()->to(base_url('history'))->with('success', 'Pesanan Anda berhasil dibuat! Silakan cek riwayat transaksi Anda.');
        }

        return redirect()->back()->withInput()->with('failed', 'Gagal memproses pesanan Anda.');
    }

    public function history()
    {
        $username = session()->get('username'); 
     
        $transactions = $this->transactionModel->where('username', $username)->findAll();
        $transactionIds = array_column($transactions, 'id');
    
        $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);
    
        $data = [
            'username'      => $username,
            'transactions'  => $transactions,
            'products'      => $products
        ]; 
    
        return view('v_history', $data);
    }
}
