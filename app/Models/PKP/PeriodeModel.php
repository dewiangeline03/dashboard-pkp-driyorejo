<?php

namespace App\Models\PKP;

use CodeIgniter\Model;

class PeriodeModel extends Model
{
    protected $table = 'pkp.periode';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tahun', 'id_bulan', 'status', 'created_at', 'updated_at'];
    protected $useAutoIncrement = false;
}
