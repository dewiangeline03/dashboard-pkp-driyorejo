<?php

namespace App\Models\PKP;

use CodeIgniter\Model;

class InstrumenModel extends Model
{
    protected $table = 'pkp.instrumen';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_periode', 'nama', 'persen_instrumen', 'created_at', 'updated_at'];
    protected $useAutoIncrement = false;

}
