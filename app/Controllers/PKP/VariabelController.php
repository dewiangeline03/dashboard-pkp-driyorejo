<?php

namespace App\Controllers\PKP;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PKP\VariabelModel;
use App\Models\PKP\VariabelAdmenModel;
use App\Models\PKP\ProgramModel;
use App\Models\PKP\InstrumenModel;
use App\Models\PKP\PeriodeModel;
use App\Models\PKP\SubVariabelModel;

class VariabelController extends BaseController
{
    protected $UserModel;
    protected $variabelModel;
    protected $variabeladmenModel;
    protected $programModel;
    protected $instrumenModel;
    protected $periodeModel;
    protected $subvariabelModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
        $this->programModel = new ProgramModel();
        $this->variabelModel = new VariabelModel();
        $this->variabeladmenModel = new VariabelAdmenModel();
        $this->periodeModel = new PeriodeModel();
        $this->instrumenModel = new InstrumenModel();
        $this->subvariabelModel = new SubVariabelModel();
    }

    public function index()
    {
        $selectedPeriodeID = $this->request->getGet('periode');
        $periode = $this->periodeModel->find($selectedPeriodeID);
        $tahun = $periode ? $periode['tahun'] : '-';
        $keyword = $this->request->getGet('keyword');

        $label_periode = '-';
        if ($periode) {
            $nama_bulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $bulan_angka = (int) $periode['id_bulan'];
            $bulan_str = $nama_bulan[$bulan_angka] ?? 'Bulan?';
            $label_periode = $bulan_str . ' ' . $periode['tahun'];
        }

        $instrumen = $this->instrumenModel
            ->where('id_periode', $selectedPeriodeID)
            ->get()
            ->getResultArray();
        $instrumenIds = array_column($instrumen, 'id');

        $program = !empty($instrumenIds)
            ? $this->programModel
            ->whereIn('id_instrumen', $instrumenIds)
            ->orderBy('pkp.program.nama', 'asc')
            ->findAll()
            : [];

        //pkp.variabel_admen = Tipe 2
        $variabel2Builder = $this->variabeladmenModel
            ->join('pkp.program', 'pkp.program.id = pkp.variabel_admen.id_program')
            ->join('pkp.instrumen', 'pkp.instrumen.id = pkp.program.id_instrumen')
            ->select('pkp.variabel_admen.*, pkp.program.nama as nama_program')
            ->where('pkp.instrumen.id_periode', $selectedPeriodeID)
            ->orderBy('pkp.program.nama', 'asc')
            ->orderBy('pkp.variabel_admen.nama', 'asc');

        if ($keyword) {
            $variabel2Builder = $variabel2Builder->like('LOWER(pkp.variabel_admen.nama)', strtolower($keyword));
        }
        $variabel2 = $variabel2Builder->findAll();
        foreach ($variabel2 as &$v2) {
            $v2['tipe_variabel'] = 'Tipe 2';
        }

        //pkp.variabel = Tipe 1
        $variabel1Builder = $this->variabelModel
            ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
            ->join('pkp.instrumen', 'pkp.instrumen.id = pkp.program.id_instrumen')
            ->select('pkp.variabel.*, pkp.program.nama as nama_program')
            ->where('pkp.instrumen.id_periode', $selectedPeriodeID)
            ->orderBy('pkp.program.nama', 'asc')
            ->orderBy('pkp.variabel.nama', 'asc');

        if ($keyword) {
            $variabel1Builder = $variabel1Builder->like('LOWER(pkp.variabel.nama)', strtolower($keyword));
        }
        $variabel1 = $variabel1Builder->findAll();
        foreach ($variabel1 as &$v1) {
            $v1['tipe_variabel'] = 'Tipe 1';
        }


        $allVariabel = array_merge($variabel2, $variabel1);

        $data = [
            'title' => 'Data Variabel',
            'selected_periode_id' => $selectedPeriodeID,
            'tahun' => $tahun,
            'label_periode' => $label_periode,
            'variabel' => $allVariabel,
            'program' => $program,
            'keyword' => $keyword,
        ];
        return view('PKP/VariabelView', $data);
    }

    public function add()
    {
        $nama = $this->request->getPost('nama');
        $tipe = $this->request->getPost('tipe_variabel');
        $id_program = $this->request->getPost('id_program');

        if (empty($nama)) {
            session()->setFlashdata('error', 'Nama Variabel wajib diisi.');
            return redirect()->back();
        }
        if (empty($tipe)) {
            session()->setFlashdata('error', 'Tipe Variabel wajib dipilih.');
            return redirect()->back();
        }
        if (empty($id_program)) {
            session()->setFlashdata('error', 'Program wajib dipilih.');
            return redirect()->back();
        }

        try {
            if ($tipe == 'Tipe 1') {
                $this->variabelModel->set('id', 'gen_random_uuid()', FALSE);
                $this->variabelModel->save([
                    'nama' => $this->request->getPost('nama'),
                    'id_program' => $this->request->getPost('id_program'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else if ($tipe == 'Tipe 2') {
                $this->variabeladmenModel->set('id', 'gen_random_uuid()', FALSE);
                $this->variabeladmenModel->save([
                    'nama' => $this->request->getPost('nama'),
                    'id_program' => $this->request->getPost('id_program'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
            session()->setFlashdata('pesan', 'Variabel Berhasil Ditambahkan');
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->setFlashdata('error', 'Gagal menambah variabel: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $tipe = $this->request->getPost('tipe_variabel');
        $nama = $this->request->getPost('nama');
        $id_program = $this->request->getPost('id_program');

        if ($tipe == 'Tipe 1') {
            $this->variabelModel->update($id, [
                'nama' => $this->request->getPost('nama'),
                'id_program' => $this->request->getPost('id_program'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else if ($tipe == 'Tipe 2') {
            $this->variabeladmenModel->update($id, [
                'nama' => $this->request->getPost('nama'),
                'id_program' => $this->request->getPost('id_program'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        return redirect()->back()->with('pesan', 'Variabel berhasil diupdate!');
    }

    public function reauthDelete()
    {
    $id = $this->request->getPost('id_variabel');
    $tipe = $this->request->getPost('tipe_variabel');
    $password = $this->request->getPost('password');

    $sessionUser = session()->get('userInfo');
    $user = $this->UserModel->find($sessionUser['id']);

    if (!$user || !password_verify($password, $user['password'])) {
        session()->setFlashdata('error', 'Password salah! Data gagal dihapus.');
        return redirect()->back();
    }

    try {
        if ($tipe == 'Tipe 1'){
            $this->subvariabelModel->where('id_variabel', $id)->delete();
            $this->variabelModel->delete($id);
        } else if ($tipe == 'Tipe 2') {
            $this->subvariabelModel->where('id_variabel', $id)->delete();
            $this->variabeladmenModel->delete($id);
        }
        session()->setFlashdata('pesan', 'Variabel turunan data berhasil dihapus.');
    } catch (\Throwable $th) {
        session()->setFlashdata('error', 'Gagal menghapus: ' . $th->getMessage());
    }
    return redirect()->back();
    }

}