<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $user = $this->userModel->find(session()->get('id'));
        if (!$user) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Profil Saya',
            'user'  => $user,
        ];

        return view('profile/index', $data);
    }

    public function update()
    {
        $userId = (int) session()->get('id');
        $user   = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('/login');
        }

        $rules = [
            'nama'  => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
        ];

        // Jika ingin ganti password, wajib isi password lama & baru
        $newPassword     = (string) $this->request->getPost('new_password');
        $currentPassword = (string) $this->request->getPost('current_password');

        if ($newPassword !== '') {
            $rules['current_password'] = 'required';
            $rules['new_password']     = 'min_length[6]';
            $rules['confirm_password'] = 'required|matches[new_password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Verifikasi password lama jika ganti password
        if ($newPassword !== '') {
            if (!password_verify($currentPassword, $user['password'])) {
                return redirect()->back()->withInput()->with('error', 'Password saat ini tidak sesuai!');
            }
        }

        $saveData = [
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
        ];

        if ($newPassword !== '') {
            $saveData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $this->userModel->update($userId, $saveData);

        // Perbarui session
        session()->set([
            'nama'  => $saveData['nama'],
            'email' => $saveData['email'],
        ]);

        return redirect()->to('/profile')->with('message', 'Profil berhasil diperbarui!');
    }
}