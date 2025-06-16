<?php

namespace App\Models\PKP;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table = 'pkp.program';
    protected $primaryKey = 'id';

    protected $allowedFields = ['id_periode', 'id_instrumen', 'nama', 'nilai', 'created_at', 'updated_at'];
    protected $useAutoIncrement = false;
}

