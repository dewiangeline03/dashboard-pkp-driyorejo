<?php

namespace App\Models\PKP;

use CodeIgniter\Model;

class VariabelAdmenModel extends Model
{
    protected $table = 'pkp.variabel_admen';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_periode', 'id_program', 'nama', 'definisi_operasional',
        'skala_nilai_0', 'skala_nilai_4', 'skala_nilai_7', 'skala_nilai_10',
        'nilai', 'analisa_akar_penyebab_masalah', 'rencana_tindak_lanjut', 'created_at', 'updated_at'
    ];
    protected $useAutoIncrement = false;
}
