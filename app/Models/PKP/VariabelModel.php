<?php

namespace App\Models\PKP;

use CodeIgniter\Model;

class VariabelModel extends Model
{
    protected $table = 'pkp.variabel';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_periode', 'id_program', 'nama', 'target_operator', 'target_value',
        'satuan_sasaran', 'total_sasaran', 'target_sasaran', 'pencapaian',
        'cakupan_riil', 'persen_sub_variabel', 'persen_variabel', 'persen_program',
        'ketercapaian_target', 'analisa_akar_penyebab_masalah', 'rencana_tindak_lanjut',
        'created_at', 'updated_at'
    ];
    protected $useAutoIncrement = false;
}
