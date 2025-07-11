<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController extends BaseController
{
    protected $UserModel;
    public function __construct()
    {
        $this->UserModel = new UserModel();
    }

    public function index()
    {
        $search =  $this->request->getVar('keyword');
        if (!$search == null) {
            $users = $this->UserModel->search($search);
        } else {
            $users = $this->UserModel->orderBy('nama', 'asc')->paginate(10, 'users');
        }
        foreach ($users as $key => $user) {
            $payload = [
                'user' => $user['id'],
                'exp' => time() + (2 * 60)
            ];
            $token = JWT::encode($payload, getEnv('api.Key.Metabase'), 'HS256');

            $users[$key]['identifier'] = $token;
        }
        $data = [
            'title' => 'User Manajemen',
            'user' => $users,
            'pager' => $this->UserModel->pager
        ];

        return view('admin/userView', $data);
    }
    public function generatePassword()
    {
        helper('text');
        $password = random_string('alnum', 15);
        return $password;
    }

    public function add()
    {

        $checkEmail = $this->UserModel->where('email', strtolower($this->request->getVar('email')))->first();
        $password = $this->generatePassword();
        if ($checkEmail == null) {
            try {
                $this->UserModel->set('id', 'gen_random_uuid()', FALSE);
                $this->UserModel->save(
                    [
                        'nama' => $this->request->getVar('nama'),
                        'email' => strtolower($this->request->getVar('email')),
                        'password' => password_hash($password, PASSWORD_BCRYPT),
                        'akses' => $this->request->getVar('akses'),
                        'verified' => 'false',
                    ]
                );
                session()->setFlashdata('pesan', 'Data Berhasil Ditambahkan');
                session()->setFlashdata('creds', $password);
                session()->setFlashdata('email', $this->request->getVar('email'));
                return redirect()->to('/users');
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', "Data Gagal Ditambahkan, pastikan format sesuai"  . $th->getMessage());
            }
        } else {
            session()->setFlashdata('error', 'Email telah didaftarkan untuk pengguna lain');
            return redirect()->to('/users');
        }
    }

    public function delete($id)
    {
        $this->UserModel->delete($id);
        session()->setFlashdata('pesan', 'Data Berhasil Dihapus');
        return redirect()->to('/users');
    }

    public function getData($id)
    {
        $decoded = JWT::decode($id, new Key(getEnv('api.Key.Metabase'), 'HS256'));
        $decodedArray = (array) $decoded;
        return json_encode($this->UserModel->getUserById($decodedArray['user']));
        //   return json_encode($this->UserModel->getUserById($id));
    }

    public function update($id)
    {
        $checkUser = $this->UserModel->where('id', $id)->first();
        $password = $this->generatePassword();
        if ($this->request->getVar('email') == $checkUser['email']) {
            try {
                $this->UserModel->save(
                    [
                        'id' => $id,
                        'nama' => $this->request->getVar('nama'),
                        'password' => password_hash($password, PASSWORD_BCRYPT),
                        'akses' => $this->request->getVar('akses'),
                        'verified' => 'false'
                    ]
                );
                session()->setFlashdata('pesan', 'Data Berhasil Diperbaharui ');
                session()->setFlashdata('creds', $password);
                session()->setFlashdata('email', $this->request->getVar('email'));
                return redirect()->to(base_url("/users"));
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', "Data Gagal Diperbaharui\n"  . $th->getMessage());
            }
        } else {
            $checkEmail = $this->UserModel->where('email', strtolower($this->request->getVar('email')))->first();
            if ($checkEmail == null) {
                try {
                    $this->UserModel->save(
                        [
                            'id' => $id,
                            'nama' => $this->request->getVar('nama'),
                            'email' => strtolower($this->request->getVar('email')),
                            'password' => password_hash($password, PASSWORD_BCRYPT),
                            'akses' => $this->request->getVar('akses'),
                            'verified' => 'false'
                        ]
                    );
                    session()->setFlashdata('pesan', 'Data Berhasil Diperbaharui ');
                    session()->setFlashdata('creds', $password);
                    session()->setFlashdata('email', $this->request->getVar('email'));
                    return redirect()->to(base_url("/users"));
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', "Data Gagal Diperbaharui\n"  . $th->getMessage());
                }
            } else {
                session()->setFlashdata('error', 'Email telah didaftarkan untuk pengguna lain');
                return redirect()->to('/users');
            }
        }
    }

    public function resetPassword(){
        $checkUser = $this->UserModel->where('id',$this->request->getVar('id'))->first();
        $password = $this->generatePassword();
        try {
            $this->UserModel->save(
                [
                    'id' => $this->request->getVar('id'),
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                    'verified' => 'false',
                ]
            );
            session()->setFlashdata('pesan', 'Password Berhasil Diubah');
            session()->setFlashdata('creds', $password);
            session()->setFlashdata('email', $checkUser['email']);
            return redirect()->to('/users');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Password Gagal Diubah, Harap Coba Lagi"  . $th->getMessage());
        }
    }
}
