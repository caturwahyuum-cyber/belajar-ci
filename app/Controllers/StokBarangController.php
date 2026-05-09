<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class StokBarangController extends BaseController
{
    public function index()
    {
        // Cek role dari session
        if (session()->get('role') != 'staf' && session()->get('role') != 'manager') {
            return redirect()->to('/')->with('failed', 'Hanya Staf dan manager yang punya akses!');
        }

        $data['hlm'] = "Stok Barang";
        return view('v_stok_barang', $data);
    }
}
