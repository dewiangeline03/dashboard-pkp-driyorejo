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

class InstrumenController extends BaseController
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


        $instrumenBuilder = $this->instrumenModel
            ->where('id_periode', $selectedPeriodeID);

        if ($keyword) {
            $instrumenBuilder = $instrumenBuilder->where('LOWER(pkp.instrumen.nama) LIKE', '%' . strtolower($keyword) . '%');
        }

        $instrumen = $instrumenBuilder
            ->orderBy('nama', 'asc')
            ->findAll();

        $data = [
            'title' => 'Data Instrumen',
            'instrumen' => $instrumen,
            'selected_periode_id' => $selectedPeriodeID,
            'tahun' => $tahun,
            'label_periode' => $label_periode,
            'keyword' => $keyword,
        ];

        return view('PKP/InstrumenView', $data);
    }

    public function add()
    {
        $nama = $this->request->getVar('nama');
        $id_periode = $this->request->getPost('id_periode');

        if (empty($nama)) {
            session()->setFlashdata('error', 'Nama instrumen wajib diisi.');
            return redirect()->back();
        }
        if (empty($id_periode)) {
            session()->setFlashdata('error', 'Periode wajib dipilih.');
            return redirect()->back();
        }

        try {
            $this->instrumenModel->set('id', 'gen_random_uuid()', FALSE);
            $this->instrumenModel->save([
                'nama' => $nama,
                'id_periode' => $id_periode,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'Instrumen Berhasil Ditambahkan');

            return redirect()->to('/data-manajer/instrumen?periode=' . $id_periode);
        } catch (\Throwable $th) {
            session()->setFlashdata('error', 'Gagal menambah instrumen: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $this->instrumenModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        session()->setFlashdata('pesan', 'Instrumen Berhasil Diubah');
        return redirect()->back();
    }

    public function reauthDelete()
    {
        $id = $this->request->getPost('id_instrumen');
        $password = $this->request->getPost('password');

        $sessionUser = session()->get('userInfo');
        $user = $this->UserModel->find($sessionUser['id']);

        if (!$user || !password_verify($password, $user['password'])) {
            session()->setFlashdata('error', 'Password salah! Data gagal dihapus.');
            return redirect()->back();
        }

        try {
            $programList = $this->programModel->where('id_instrumen', $id)->findAll();
            foreach ($programList as $program) {
                $variabelList = $this->variabelModel->where('id_program', $program['id'])->findAll();
                foreach ($variabelList as $variabel) {
                    $this->subvariabelModel->where('id_variabel', $variabel['id'])->delete();
                }
                $variabeladmenList = $this->variabeladmenModel->where('id_program', $id)->findAll();
                foreach ($variabeladmenList as $variabeladmen) {
                    $this->subvariabelModel->where('id_variabel', $variabeladmen['id'])->delete();
                }
                $this->variabelModel->where('id_program', $program['id'])->delete();
                $this->variabeladmenModel->where('id_program', $program['id'])->delete();
            }
            $this->programModel->where('id_instrumen', $id)->delete();
            $this->instrumenModel->delete($id);

            session()->setFlashdata('pesan', 'Instrumen dan turunan data berhasil dihapus.');
        } catch (\Throwable $th) {
            session()->setFlashdata('error', 'Gagal menghapus: ' . $th->getMessage());
        }
        return redirect()->back();
    }
}