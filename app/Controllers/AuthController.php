<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function __construct()
    {
        helper('form');
    }

    public function login()
    {
        if ($this->request->getPost()) {

            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');

            $userModel = new UserModel();
            $user = $userModel->where('username', $username)->first();

            if (!$user) {
                session()->setFlashdata('failed', 'Username tidak ditemukan');
                return redirect()->back();
            }

            $passwordMatches = false;

            if (password_verify($password, $user['password'])) {
                $passwordMatches = true;
            } elseif (md5($password) === $user['password']) {
                $passwordMatches = true;
            }

            if ($passwordMatches) {
                session()->set([
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'email' => $user['email'],
                    'isLoggedIn' => true,
                    'login_time' => date('Y-m-d H:i:s')
                ]);

                return redirect()->to('/');
            }

            session()->setFlashdata('failed', 'Password salah');
            return redirect()->back();
        }

        return view('v_login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
