<?php

namespace App\Models\PKP;

use CodeIgniter\Model;

class HistoriPenilaianModel extends Model
{
    protected $table = 'pkp.histori_penilaian';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_akun', 'id_indikator', 'tingkat_indikator', 'aksi', 'data_sebelum',
        'data_sesudah','created_at', 'user_agent', 'nama_indikator', 'periode'
    ];
    protected $useTimestamps = false;

}
