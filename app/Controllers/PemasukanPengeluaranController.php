<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class PemasukanPengeluaranController extends BaseController

{
    public function pemasukan()
    {
        // Cek role dari session
        if (session()->get('role') != 'manager') {
            return redirect()->to('/')->with('failed', 'Hanya Manager yang punya akses!');
        }

        $data['hlm'] = "Pemasukan";
        return view('v_pemasukan', $data);
    }

    public function pengeluaran()
    {
        if (session()->get('role') != 'manager') {
            return redirect()->to('/')->with('failed', 'Hanya Manager yang punya akses!');
        }

        $data['hlm'] = "Pengeluaran";
        return view('v_pengeluaran', $data);
    }
}
