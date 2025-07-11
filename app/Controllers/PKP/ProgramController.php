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

class ProgramController extends BaseController
{
    protected $UserModel;
    protected $periodeModel;
    protected $instrumenModel;
    protected $programModel;
    protected $variabelModel;
    protected $variabeladmenModel;
    protected $subvariabelModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
        $this->periodeModel = new PeriodeModel();
        $this->instrumenModel = new InstrumenModel();
        $this->programModel = new ProgramModel();
        $this->variabelModel = new VariabelModel();
        $this->variabeladmenModel = new VariabelAdmenModel();
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
            ->orderBy('nama', 'asc')
            ->findAll();

        $programBuilder = $this->programModel
            ->where('id_periode', $selectedPeriodeID)
            ->join('pkp.instrumen', 'pkp.instrumen.id = pkp.program.id_instrumen')
            ->select('pkp.program.*, pkp.instrumen.nama as nama_instrumen');

        if ($keyword) {
            $programBuilder = $programBuilder->where('LOWER(pkp.program.nama) LIKE', '%' . strtolower($keyword) . '%');
        }

        $program = $programBuilder
            ->orderBy('nama_instrumen', 'asc')
            ->orderBy('pkp.program.nama', 'asc')
            ->findAll();

        $data = [
            'title' => 'Data Instrumen',
            'selected_periode_id' => $selectedPeriodeID,
            'tahun' => $tahun,
            'label_periode' => $label_periode,
            'program' => $program,
            'instrumen' => $instrumen,
            'keyword' => $keyword,
        ];

        return view('PKP/ProgramView', $data);
    }


    public function add()
    {
        $nama = $this->request->getVar('nama');
        $id_instrumen = $this->request->getVar('id_instrumen');

        if (empty($nama)) {
            session()->setFlashdata('error', 'Nama program wajib diisi.');
            return redirect()->back();
        }
        if (empty($id_instrumen)) {
            session()->setFlashdata('error', 'Instrumen wajib dipilih.');
            return redirect()->back();
        }

        try {
            $this->programModel->set('id', 'gen_random_uuid()', FALSE);
            $this->programModel->save([
                'nama' => $nama,
                'id_instrumen' => $id_instrumen,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'Program Berhasil Ditambahkan');
            return redirect()->back();

        } catch (\Throwable $th) {
            session()->setFlashdata('error', 'Gagal menambah program: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $this->programModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'id_instrumen' => $this->request->getPost('id_instrumen'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->with('pesan', 'Program berhasil diupdate!');
    }

    public function reauthDelete()
    {
        $id = $this->request->getPost('id_program');
        $password = $this->request->getPost('password');

        $sessionUser = session()->get('userInfo');
        $user = $this->UserModel->find($sessionUser['id']);

        if (!$user || !password_verify($password, $user['password'])) {
            session()->setFlashdata('error', 'Password salah! Data gagal dihapus.');
            return redirect()->back();
        }

        try {
            $variabelList = $this->variabelModel->where('id_program', $id)->findAll();
            foreach ($variabelList as $variabel) {
                $this->subvariabelModel->where('id_variabel', $variabel['id'])->delete();
            }
            $variabeladmenList = $this->variabeladmenModel->where('id_program', $id)->findAll();
            foreach ($variabeladmenList as $variabeladmen) {
                $this->subvariabelModel->where('id_variabel', $variabeladmen['id'])->delete();
            }
            $this->variabelModel->where('id_program',  $id)->delete();
            $this->variabeladmenModel->where('id_program',  $id)->delete();

            $this->programModel->delete($id);

            session()->setFlashdata('pesan', 'Program dan turunan data berhasil dihapus.');
        } catch (\Throwable $th) {
            session()->setFlashdata('error', 'Gagal menghapus: ' . $th->getMessage());
        }
        return redirect()->back();
    }
}