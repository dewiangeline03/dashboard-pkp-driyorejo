<?php

namespace App\Controllers\PKP;

use App\Controllers\BaseController;
use App\Models\PKP\HistoriPenilaianModel;
use App\Models\UserModel;

class HistoriPenilaianController extends BaseController
{
    protected $historiPenilaianModel;
    protected $UserModel;

    public function __construct()
    {
        $this->historiPenilaianModel = new HistoriPenilaianModel();
        $this->UserModel = new UserModel();
    }

    public function index()
    {
        $from = $this->request->getGet('from');
        $to = $this->request->getGet('to');

        $builder = $this->historiPenilaianModel
            ->join('public.akun', 'public.akun.id = pkp.histori_penilaian.id_akun')
            ->select('pkp.histori_penilaian.*, public.akun.nama AS user_entry');

        if ($from && $to) {
            $builder
                ->where('pkp.histori_penilaian.created_at >=', $from)
                ->where('pkp.histori_penilaian.created_at <=', $to . ' 23:59:59');
        }

        $histori_penilaian = $builder
        ->orderBy('created_at', 'DESC')->paginate(12, 'data');

        $data = [
            'title' => 'Histori Penilaian',
            'histori' => $histori_penilaian,
            'pager' => $this->historiPenilaianModel->pager
        ];

        return view('PKP/HistoriPenilaianView', $data);
    }


    public function restore()
    {
        $id = $this->request->getPost('id');
        try {
            $histori = $this->historiPenilaianModel->find($id);
            if (!$histori) {
                return redirect()->back()->with('error', 'Data histori tidak ditemukan.');
            }

            // Tentukan target model berdasarkan tingkat_indikator
            $model = null;
            switch ($histori['tingkat_indikator']) {
                case 'Variabel Tipe 1':
                    $model = new \App\Models\PKP\VariabelModel();
                    break;
                case 'Variabel Tipe 2':
                    $model = new \App\Models\PKP\VariabelAdmenModel();
                    break;
                case 'Sub-Variabel':
                    $model = new \App\Models\PKP\SubVariabelModel();
                    break;
                default:
                    return redirect()->back()->with('error', 'Tipe indikator tidak dikenali.');
            }

            // Decode data_sesudah dari histori
            $dataBaru = json_decode($histori['data_sesudah'], true);
            if (!$dataBaru) {
                return redirect()->back()->with('error', 'Data tidak valid untuk dipulihkan.');
            }

            // Update data ke model target
            $model->update($histori['id_indikator'], $dataBaru);

            return redirect()->back()->with('pesan', 'Data berhasil dipulihkan.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal memulihkan data: ' . $th->getMessage());
        }
    }

    // Dalam controller HistoriPenilaianController
    public function delete($id)
    {
        try {
            $this->historiPenilaianModel->delete($id);
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $th->getMessage());
        }
    }
}
