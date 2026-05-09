<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class UserController extends BaseController
{
    public function profile()
    {
        $data = [
            'username'   => session()->get('username'),
            'role'       => session()->get('role'),
            'email'      => session()->get('email'),
            'login_time' => session()->get('login_time'),
            'status'     => 'Sudah Login'
        ];
        return view('v_user', $data);
    }
}
