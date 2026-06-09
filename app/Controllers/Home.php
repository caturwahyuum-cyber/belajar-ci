<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
<<<<<<< Updated upstream
        return view('v_home.php');
=======
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        helper(['number', 'form']);
        return view('v_home', [
            'products' => $this->productModel->findAll()
        ]);
>>>>>>> Stashed changes
    }
}
