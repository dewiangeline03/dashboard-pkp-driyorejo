<?php

namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model{
    // protected $table = 'app.users';
    protected $table = 'akun';
    protected $primaryKey = 'id';
    protected $allowedFields =  ['nama','email','password','akses','verified'];
    protected $useAutoIncrement = false;

    public function getUserById($id)
    {
        return $this->select('nama, email, akses')->where(['id' => $id])->first();
    }
    public function search($keyword){
        return $this->table('users')->like('LOWER(nama)', strtolower($keyword))->orLike('LOWER(email)',strtolower($keyword))->orderBy('id', 'asc')->paginate(10,'users');
        /*return $this->like('LOWER(nama)', strtolower($keyword))
            ->orLike('LOWER(email)', strtolower($keyword))
            ->orderBy('id', 'asc')
            ->paginate(10,'users');*/
    }
}