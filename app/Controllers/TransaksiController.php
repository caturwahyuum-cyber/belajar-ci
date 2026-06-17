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

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = service('cart');
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
        $fullAddress = $streetAddress . ', ' . $kelurahanName;

        $transactionModel = new TransactionModel();
        $transactionDetailModel = new TransactionDetailModel();

        // 1. Insert into transaction table
        $transactionData = [
            'username'    => $this->request->getPost('username'),
            'total_harga' => $this->request->getPost('total_harga'),
            'alamat'      => $fullAddress,
            'ongkir'      => $this->request->getPost('ongkir'),
            'status'      => 'Pending'
        ];

        $transactionId = $transactionModel->insert($transactionData);

        if ($transactionId) {
            // 2. Insert into transaction_detail table
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

            // 3. Destroy cart
            $this->cart->destroy();

            return redirect()->to(base_url('/'))->with('success', 'Pesanan Anda berhasil dibuat dengan status Pending.');
        }

        return redirect()->back()->withInput()->with('failed', 'Gagal memproses pesanan Anda.');
    }
}
