<?php

namespace App\Controllers\PKP;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PKP\PeriodeModel;
use App\Models\PKP\InstrumenModel;
use App\Models\PKP\ProgramModel;
use App\Models\PKP\VariabelModel;
use App\Models\PKP\VariabelAdmenModel;
use App\Models\PKP\SubVariabelModel;
use Ramsey\Uuid\Uuid;


class DataManajerController extends BaseController
{
    protected $UserModel;
    protected $periodeModel;
    protected $instrumenModel;
    protected $programModel;
    protected $variabelModel;
    protected $variabeladmenModel;
    protected $subVariabelModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
        $this->periodeModel = new PeriodeModel();
        $this->instrumenModel = new InstrumenModel();
        $this->programModel = new ProgramModel();
        $this->variabelModel = new VariabelModel();
        $this->variabeladmenModel = new VariabelAdmenModel();
        $this->subVariabelModel = new SubVariabelModel();
    }

    public function index()
    {
        $periode = $this->periodeModel
            ->orderBy('tahun', 'asc')
            ->orderBy('id_bulan', 'asc')
            ->findAll();
        
        $nama_bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        foreach ($periode as &$p) {
            $bulan_angka = (int) $p['id_bulan'];
            $bulan_str = isset($nama_bulan[$bulan_angka]) ? $nama_bulan[$bulan_angka] : 'Bulan?';
            $p['label_periode'] = $bulan_str . ' ' . $p['tahun']; 
        }
        unset($p);
        

        $data = [
            'title' => 'Data Manajer',
            'periode' => $periode,
        ];

        return view('PKP/DataManajerView', $data);
    }

    public function create()
    {
        $mode = $this->request->getPost('mode');

        if ($mode === 'copy') {
            $sourcePeriodeId = $this->request->getPost('copy_from');
            $tahun = $this->request->getPost('tahun');
            $bulan = $this->request->getPost('bulan');

            if (empty($sourcePeriodeId)) {
                session()->setFlashdata('error', 'Periode sumber tidak dipilih.');
                return redirect()->to('/data-manajer');
            }

            try {
                $periodeLama = $this->periodeModel->find($sourcePeriodeId);
                if (!$periodeLama) {
                    session()->setFlashdata('error', 'Periode sumber tidak ditemukan.');
                    return redirect()->to('/data-manajer');
                }
                if (empty($tahun)) {
                    session()->setFlashdata('error', 'Tahun periode harus diisi.');
                    return redirect()->to('/data-manajer');
                }
                if (empty($bulan)) {
                    session()->setFlashdata('error', 'Bulan periode harus diisi.');
                    return redirect()->to('/data-manajer');
                }

                $newPeriodeId = Uuid::uuid4()->toString();
                $this->periodeModel->insert([
                    'id' => $newPeriodeId,
                    'tahun' => $tahun,
                    'id_bulan' => $bulan,
                    'status' => 'draft',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $instrumenMap = [];
                $instrumen = $this->instrumenModel->where('id_periode', $sourcePeriodeId)->findAll();
                foreach ($instrumen as $i) {
                    $newInstrumenId = Uuid::uuid4()->toString();
                    $this->instrumenModel->insert([
                        'id' => $newInstrumenId,
                        'id_periode' => $newPeriodeId,
                        'nama' => $i['nama'],
                        'persen_instrumen' => $i['persen_instrumen'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $instrumenMap[$i['id']] = $newInstrumenId;
                }

                $programMap = [];
                $program = $this->programModel
                    ->join('pkp.instrumen', 'program.id_instrumen = instrumen.id')
                    ->where('instrumen.id_periode', $sourcePeriodeId)
                    ->select('program.*')
                    ->findAll();
                foreach ($program as $p) {
                    $newProgramId = Uuid::uuid4()->toString();
                    $this->programModel->insert([
                        'id' => $newProgramId,
                        'id_instrumen' => $instrumenMap[$p['id_instrumen']] ?? null,
                        'nama' => $p['nama'],
                        'persen_program' => $p['persen_program'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $programMap[$p['id']] = $newProgramId;
                }

                $variabelMap = [];
                $variabel = $this->variabelModel
                    ->join('pkp.program', 'variabel.id_program = program.id')
                    ->join('pkp.instrumen', 'program.id_instrumen = instrumen.id')
                    ->where('instrumen.id_periode', $sourcePeriodeId)
                    ->select('variabel.*')
                    ->findAll();
                foreach ($variabel as $v) {
                    $newVariabelId = Uuid::uuid4()->toString();
                    $this->variabelModel->insert([
                        'id' => $newVariabelId,
                        'id_program' => $programMap[$v['id_program']] ?? null,
                        'nama' => $v['nama'],
                        'target_operator' => $v['target_operator'] ?? null,
                        'target_value' => $v['target_value'] ?? null,
                        'satuan_sasaran' => $v['satuan_sasaran'] ?? null,
                        'total_sasaran' => $v['total_sasaran'] ?? null,
                        'target_sasaran' => $v['target_sasaran'] ?? null,
                        'pencapaian' => $v['pencapaian'] ?? null,
                        'cakupan_riil' => $v['cakupan_riil'] ?? null,
                        'persen_sub_variabel' => $v['persen_sub_variabel'] ?? null,
                        'persen_variabel' => $v['persen_variabel'] ?? null,
                        'persen_program' => $v['persen_program'] ?? null,
                        'ketercapaian_target' => $v['ketercapaian_target'] ?? null,
                        'analisa_akar_penyebab_masalah' => $v['analisa_akar_penyebab_masalah'] ?? null,
                        'rencana_tindak_lanjut' => $v['rencana_tindak_lanjut'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $variabelMap[$v['id']] = $newVariabelId;
                }

                $subVariabel = $this->subVariabelModel
                    ->join('pkp.variabel', 'sub_variabel.id_variabel = variabel.id')
                    ->join('pkp.program', 'variabel.id_program = program.id')
                    ->join('pkp.instrumen', 'program.id_instrumen = instrumen.id')
                    ->where('instrumen.id_periode', $sourcePeriodeId)
                    ->select('sub_variabel.*')
                    ->findAll();
                foreach ($subVariabel as $sv) {
                    $newSubVariabelId = Uuid::uuid4()->toString();
                    $this->subVariabelModel->insert([
                        'id' => $newSubVariabelId,
                        'id_variabel' => $variabelMap[$sv['id_variabel']] ?? null,
                        'nama' => $sv['nama'],
                        'target_operator' => $sv['target_operator'] ?? null,
                        'target_value' => $sv['target_value'] ?? null,
                        'satuan_sasaran' => $sv['satuan_sasaran'] ?? null,
                        'total_sasaran' => $sv['total_sasaran'] ?? null,
                        'target_sasaran' => $sv['target_sasaran'] ?? null,
                        'pencapaian' => $sv['pencapaian'] ?? null,
                        'cakupan_riil' => $sv['cakupan_riil'] ?? null,
                        'persen_sub_variabel' => $sv['persen_sub_variabel'] ?? null,
                        'persen_variabel' => $sv['persen_variabel'] ?? null,
                        'persen_program' => $sv['persen_program'] ?? null,
                        'ketercapaian_target' => $sv['ketercapaian_target'] ?? null,
                        'analisa_akar_penyebab_masalah' => $sv['analisa_akar_penyebab_masalah'] ?? null,
                        'rencana_tindak_lanjut' => $sv['rencana_tindak_lanjut'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                $variabeladmen = $this->variabeladmenModel
                    ->join('pkp.program', 'variabel_admen.id_program = program.id')
                    ->join('pkp.instrumen', 'program.id_instrumen = instrumen.id')
                    ->where('instrumen.id_periode', $sourcePeriodeId)
                    ->select('variabel_admen.*')
                    ->findAll();
                foreach ($variabeladmen as $va) {
                    $newVariabelAdmenId = Uuid::uuid4()->toString();
                    $this->variabeladmenModel->insert([
                        'id' => $newVariabelAdmenId,
                        'id_program' => $programMap[$va['id_program']] ?? null,
                        'nama' => $va['nama'] ?? null,
                        'definisi_operasional' => $va['definisi_operasional'] ?? null,
                        'skala_nilai_0' => $va['skala_nilai_0'] ?? null,
                        'skala_nilai_4' => $va['skala_nilai_4'] ?? null,
                        'skala_nilai_7' => $va['skala_nilai_7'] ?? null,
                        'skala_nilai_10' => $va['skala_nilai_10'] ?? null,
                        'nilai' => $va['nilai'] ?? null,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
                session()->setFlashdata('pesan', 'Duplikat Periode Berhasil.');
                return redirect()->to('/data-manajer');

            } catch (\Throwable $th) {
                session()->setFlashdata('error', 'Gagal menduplikat periode: ' . $th->getMessage());
                return redirect()->to('/data-manajer');
            }
        }

        if ($mode === 'new') {
            $tahun = $this->request->getPost('tahun');
            $bulan = $this->request->getPost('bulan');
            if (empty($tahun)) {
                session()->setFlashdata('error', 'Tahun periode harus diisi.');
                return redirect()->to('/data-manajer');
            }
            if (empty($bulan)) {
                session()->setFlashdata('error', 'Bulan periode harus diisi.');
                return redirect()->to('/data-manajer');
            }

            try {
                $newPeriodeId = Uuid::uuid4()->toString();
                $this->periodeModel->insert([
                    'id' => $newPeriodeId,
                    'tahun' => $tahun,
                    'id_bulan' => $bulan,
                    'status' => 'draft',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                session()->setFlashdata('pesan', 'Periode baru berhasil ditambahkan.');
                return redirect()->to('/data-manajer');
            } catch (\Throwable $th) {
                session()->setFlashdata('error', 'Gagal membuat periode baru: ' . $th->getMessage());
                return redirect()->back();
            }
        }

        session()->setFlashdata('error', 'Metode tidak valid.');
        return redirect()->back();
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $this->periodeModel->update($id, [
            'tahun' => $this->request->getPost('tahun'),
            'id_bulan' => $this->request->getPost('bulan'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        session()->setFlashdata('pesan', 'Periode Berhasil Diubah');
        return redirect()->to('/data-manajer');
    }

    public function reauthDelete()
    {
    $id = $this->request->getPost('id');
    $password = $this->request->getPost('password');

    $sessionUser = session()->get('userInfo');
    $user = $this->UserModel->find($sessionUser['id']);

    if (!$user || !password_verify($password, $user['password'])) {
        session()->setFlashdata('error', 'Password salah! Data gagal dihapus.');
        return redirect()->to('/data-manajer');
    }

    try {
        $instrumenList = $this->instrumenModel->where('id_periode', $id)->findAll();
        foreach ($instrumenList as $instrumen) {
            $programList = $this->programModel->where('id_instrumen', $instrumen['id'])->findAll();
            foreach ($programList as $program) {
                $variabelList = $this->variabelModel->where('id_program', $program['id'])->findAll();
                foreach ($variabelList as $variabel) {
                    $this->subVariabelModel->where('id_variabel', $variabel['id'])->delete();
                }
                $variabeladmenList = $this->variabeladmenModel->where('id_program', $program['id'])->findAll();
                foreach ($variabeladmenList as $variabeladmen) {
                    $this->subVariabelModel->where('id_variabel', $variabeladmen['id'])->delete();
                }
                $this->variabelModel->where('id_program', $program['id'])->delete();
                $this->variabeladmenModel->where('id_program', $program['id'])->delete();
            }
            $this->programModel->where('id_instrumen', $instrumen['id'])->delete();
        }

        $this->instrumenModel->where('id_periode', $id)->delete();
        $this->periodeModel->delete($id);

        session()->setFlashdata('pesan', 'Periode dan data turunan berhasil dihapus.');
    } catch (\Throwable $th) {
        session()->setFlashdata('error', 'Gagal menghapus: ' . $th->getMessage());
    }
    return redirect()->to('/data-manajer');
    }
}