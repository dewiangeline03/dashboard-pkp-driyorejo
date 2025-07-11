<?php

namespace App\Controllers\PKP;

use App\Controllers\BaseController;
use App\Models\PKP\SubVariabelModel;
use App\Models\PKP\VariabelModel;
use App\Models\PKP\PeriodeModel;

class SubVariabelController extends BaseController
{
    protected $subvariabelModel;
    protected $variabelModel;
    protected $periodeModel;

    public function __construct()
    {
        $this->subvariabelModel = new SubVariabelModel();
        $this->variabelModel = new VariabelModel();
        $this->periodeModel = new PeriodeModel();
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

        $subVariabelBuilder = $this->subvariabelModel
            ->join('pkp.variabel', 'pkp.variabel.id = pkp.sub_variabel.id_variabel')
            ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
            ->join('pkp.instrumen', 'pkp.instrumen.id = pkp.program.id_instrumen')
            ->select('pkp.sub_variabel.*, pkp.variabel.nama as nama_variabel, pkp.program.nama as nama_program, pkp.instrumen.nama as nama_instrumen')
            ->where('pkp.instrumen.id_periode', $selectedPeriodeID);

        if ($keyword) {
            $subVariabelBuilder = $subVariabelBuilder->like('LOWER(pkp.sub_variabel.nama)', strtolower($keyword));
        }

        $sub_variabel = $subVariabelBuilder
            ->orderBy('nama_instrumen', 'asc')
            ->orderBy('nama_program', 'asc')
            ->orderBy('nama_variabel', 'asc')
            ->orderBy('pkp.sub_variabel.nama', 'asc')
            ->findAll();

        $variabelBuilder = $this->variabelModel
            ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
            ->join('pkp.instrumen', 'pkp.instrumen.id = pkp.program.id_instrumen')
            ->where('pkp.instrumen.id_periode', $selectedPeriodeID)
            ->select('pkp.variabel.*')
            ->orderBy('pkp.variabel.nama', 'asc');
        $variabel = $variabelBuilder->findAll();

        $data = [
            'title' => 'Data Sub-Variabel',
            'selected_periode_id' => $selectedPeriodeID,
            'tahun' => $tahun,
            'label_periode' =>$label_periode,
            'sub_variabel' => $sub_variabel,
            'variabel' => $variabel,
            'keyword' => $keyword,
        ];
        return view('PKP/SubVariabelView', $data);
    }

    public function add()
    {
        $nama = $this->request->getPost('nama');
        $id_variabel = $this->request->getPost('id_variabel');

        if (empty($nama) || empty($id_variabel)) {
            session()->setFlashdata('error', 'Nama Sub-Variabel dan Variabel wajib diisi.');
            return redirect()->back();
        }

        try {
            $this->subvariabelModel->set('id', 'gen_random_uuid()', false);
            $this->subvariabelModel->save([
                'nama' => $nama,
                'id_variabel' => $id_variabel,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'Sub-Variabel berhasil ditambahkan!');
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->setFlashdata('error', 'Gagal menambah sub-variabel: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $nama = $this->request->getPost('nama');
        $id_variabel = $this->request->getPost('id_variabel');

        if (empty($id) || empty($nama) || empty($id_variabel)) {
            session()->setFlashdata('error', 'Semua field wajib diisi.');
            return redirect()->back();
        }

        try {
            $this->subvariabelModel->update($id, [
                'nama' => $nama,
                'id_variabel' => $id_variabel,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'Sub-Variabel berhasil diupdate!');
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->setFlashdata('error', 'Gagal update sub-variabel: ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        $this->subvariabelModel->delete($id);
        session()->setFlashdata('pesan', 'Sub-Variabel Berhasil Dihapus');
        return redirect()->back();
    }
}
