<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Management User',
            'users' => $this->userModel->findAll(),
        ];
        return view('user/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah User Baru';
        return view('user/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,staff,viewer]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->save([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
        ]);

        return redirect()->to('/users')->with('message', 'User berhasil ditambahkan!');
    }

    public function delete($id)
    {
        // Mencegah admin menghapus akunnya sendiri yang sedang digunakan
        if (session()->get('id') == $id) {
            return redirect()->to('/users')->with('error', 'Kamu tidak bisa menghapus akun kamu sendiri saat sedang login!');
        }

        $this->userModel->delete($id);
        return redirect()->to('/users')->with('message', 'User berhasil dihapus!');
    }
    // Form Edit User
    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'Data user tidak ditemukan!');
        }

        $data = [
            'title' => 'Edit User',
            'user'  => $user,
        ];
        return view('user/edit', $data);
    }

    // Process Update User
    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'Data user tidak ditemukan!');
        }

        // Rule validasi unik email mengecualikan email user ini sendiri
        $rules = [
            'nama'  => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'  => 'required|in_list[admin,staff,viewer]',
        ];

        // Jika password diisi, tambahkan validasi password
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $saveData = [
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'role'  => $this->request->getPost('role'),
        ];

        // Update password hanya jika diisi oleh admin/user
        if ($this->request->getPost('password')) {
            $saveData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $saveData);

        return redirect()->to('/users')->with('message', 'Data user berhasil diperbarui!');
    }
}