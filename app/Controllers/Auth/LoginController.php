<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use Firebase\JWT\JWT;
use App\Models\UserModel;

class LoginController extends BaseController
{
    protected $UserModel;
    public function __construct()
    {
        $this->UserModel = new UserModel();
    }

    public function login()
    {
        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation()
        ];
        return view('auth/login', $data);
    }
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url("/login"));
    }

    public function auth()
    {
        $validation = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Harap masukan email dengan format yang sesuai'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password wajib diisi',
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            $data['validation'] = $this->validator;
            return view('auth/login', $data);
        } else {
            $session = session();

            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');
            $checkUser = $this->UserModel->where('email', $email)->first();
            if ($checkUser !== null) {
                $verifyPassword = password_verify($password, $checkUser['password']);
                if ($verifyPassword) {
                    $payload = [
                        'name' => $checkUser['nama'],
                        'role' => $checkUser['akses'],
                        'exp' => time() + (45 * 60)
                    ];
                    $token = JWT::encode($payload, getenv("api.Key"), 'HS256');
                    $userInfo = [
                        'nama' => $checkUser['nama'],
                        'role' => $checkUser['akses'],
                        'id' => $checkUser['id'],
                        'token' => $token,
                        'verified' => $checkUser['verified']
                    ];
                    if ($checkUser['verified'] == 'true') {
                        session()->set('userInfo', $userInfo);
                        session()->setFlashdata('pesan', 'Login Berhasil');
                        return redirect()->to(base_url("/user/home"));
                    } else {
                        session()->set('userInfo', $userInfo);
                        session()->setFlashdata('pesan', 'Login Berhasil, Harap Ubah Password Anda');
                        return redirect()->to(base_url("/validate-password"));
                    }
                } else {
                    $session->setFlashdata('error', 'Login Gagal, Email atau Password Salah.');
                    return redirect()->to(base_url("/login"));
                }
            } else {
                $session->setFlashdata('error', 'Login Gagal, Email atau Password Salah.');
                return redirect()->to(base_url("/login"));
            }
        }
    }
    public function changePassword()
    {

        $data = [
            'title' => 'Change Password',
        ];
        return view('auth/changePassword', $data);
    }
    public function validatePassword()
    {
        $rules = [
            'newPassword' => [
                'rules' => 'required|min_length[8]|regex_match[/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W])/]',
                'errors' => [
                    'required' => 'The {field} field is required.',
                    'min_length' => 'The {field} field must be at least 8 characters long.',
                    'regex_match' => 'The {field} field must contain at least one lowercase letter, one uppercase letter, one number, and one special character.',
                ],
            ],
        ];
        $checkUser = $this->UserModel->where('id', $this->request->getVar('id'))->first();
        $oldPassword = $this->request->getVar('oldPassword');
        $verifyPassword = password_verify($oldPassword, $checkUser['password']);
        if ($verifyPassword == false) {
            return redirect()->back()->with('error', "Ubah Password Gagal, Password lama tidak sesuai");
        }else if(!$this->validate($rules)){
            return redirect()->back()->with('error', "Format Password Tidak Sesuai");
        } else {
            $this->UserModel->save(
                [
                    'id' => $this->request->getVar('id'),
                    'password' => password_hash($this->request->getVar('newPassword'), PASSWORD_BCRYPT),
                    'verified' => true
                ]
            );
            session()->remove('userInfo')    ;   
            session()->setFlashdata('pesan', 'Ubah Password Berhasil, Silahkan Login Kembali');           
            return redirect()->to(base_url("/login"));
        }
    }
}
