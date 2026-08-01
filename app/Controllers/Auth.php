<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function processLogin()
    {
        $session  = session();
        $model    = new UserModel();
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user) { // Bypass password_verify sementara
            $sessionData = [
                'id'         => $user['id'],
                'nama'       => $user['nama'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ];
            $session->set($sessionData);
            return redirect()->to('/dashboard');
        } else {
            $session->setFlashdata('msg', 'Email atau Password Salah');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
    public function generate()
{
    $model = new \App\Models\UserModel();
    $email = 'admin@mail.com';
    $newPassword = 'admin123';
    
    // Generate hash langsung dari PHP server kamu
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $user = $model->where('email', $email)->first();
    if ($user) {
        $model->update($user['id'], ['password' => $hashedPassword]);
        return "Password untuk {$email} berhasil diperbarui menjadi: <b>{$newPassword}</b>";
    } else {
        // Jika user belum ada, buat baru
        $model->insert([
            'nama'     => 'Admin System',
            'email'    => $email,
            'password' => $hashedPassword,
            'role'     => 'admin'
        ]);
        return "User Admin baru berhasil dibuat dengan password: <b>{$newPassword}</b>";
    }
}
}
