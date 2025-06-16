<?php

namespace App\Controllers\PKP;

use App\Controllers\BaseController;
use App\Models\PKP\PeriodeModel;
use App\Models\PKP\InstrumenModel;
use App\Models\PKP\ProgramModel;
use App\Models\PKP\VariabelModel;
use App\Models\PKP\VariabelAdmenModel;
use App\Models\PKP\SubVariabelModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;


class DataCapaianController extends BaseController
{
    protected $periodeModel;
    protected $instrumenModel;
    protected $programModel;
    protected $variabelModel;
    protected $variabeladmenModel;
    protected $subVariabelModel;

    public function __construct()
    {
        $this->periodeModel = new PeriodeModel();
        $this->instrumenModel = new InstrumenModel();
        $this->programModel = new ProgramModel();
        $this->variabelModel = new VariabelModel();
        $this->variabeladmenModel = new VariabelAdmenModel();
        $this->subVariabelModel = new SubVariabelModel();
    }
    public function index()
    {
        $selectedPeriodeID = $this->request->getGet('periode');
        $selectedInstrumenID = $this->request->getGet('instrumen');
        $selectedProgramID = $this->request->getGet('program');
        $keyword = $this->request->getGet('keyword');
        $aksiMode = $this->request->getGet('aksi') === '1';
        $activeTab = $this->request->getGet('tab') ?? 'umum';

        /*$periode = $this->periodeModel
        ->orderBy('tahun', 'asc')
        ->findAll();*/

        $periode = $this->periodeModel
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->findAll();

        $nama_bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        foreach ($periode as &$p) {
            $bulan_angka = (int) $p['bulan'];
            $bulan_str = isset($nama_bulan[$bulan_angka]) ? $nama_bulan[$bulan_angka] : 'Bulan?';
            $p['label_periode'] = $bulan_str . ' ' . $p['tahun'];
        }
        unset($p);

        $instrumen = (!empty($selectedPeriodeID))
            ? $this->instrumenModel
                ->where('id_periode', $selectedPeriodeID)
                ->orderBy('nama', 'ASC')
                ->findAll()
            : [];
        $selectedInstrumen = null;
        $selected_instrumen_nama = '-';
        $selected_instrumen_persen = 0;

        if (!empty($selectedInstrumenID)) {
            $selectedInstrumen = $this->instrumenModel->find($selectedInstrumenID);
            if ($selectedInstrumen) {
                $selected_instrumen_nama = $selectedInstrumen['nama'] ?? '-';
                $selected_instrumen_nilai = $selectedInstrumen['persen_instrumen'] ?? 0;
            }
        }


        $program = (!empty($selectedInstrumenID))
            ? $this->programModel
                ->where('id_instrumen', $selectedInstrumenID)
                ->orderBy('nama', 'ASC')
                ->findAll()
            : [];

        $variabel = [];
        $subVariabel = [];
        $variabelAdmen = [];

        if (!empty($selectedProgramID)) {
            if ($selectedProgramID === 'all' && !empty($selectedInstrumenID)) {
                $programs = $this->programModel->where('id_instrumen', $selectedInstrumenID)->findAll();
                $programIDs = array_column($programs, 'id');

                if (!empty($programIDs)) {
                    $variabel = $this->variabelModel
                        ->select('pkp.variabel.*, pkp.program.nama AS nama_program')
                        ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
                        ->whereIn('pkp.variabel.id_program', $programIDs)
                        ->orderBy('pkp.program.nama', 'ASC')
                        ->orderBy('pkp.variabel.nama', 'ASC')
                        ->findAll();

                    if (!empty($variabel)) {
                        $variabelIDs = array_column($variabel, 'id');
                        if (!empty($variabelIDs)) {
                            $subVariabel = $this->subVariabelModel
                                ->whereIn('id_variabel', $variabelIDs)
                                ->findAll();
                        }
                    }

                    $variabelAdmen = $this->variabeladmenModel
                        ->select('pkp.variabel_admen.*, pkp.program.nama AS nama_program, pkp.program.persen_program AS nilai_program')
                        ->join('pkp.program', 'pkp.program.id = pkp.variabel_admen.id_program')
                        ->whereIn('pkp.variabel_admen.id_program', $programIDs)
                        ->orderBy('pkp.program.nama', 'ASC')
                        ->orderBy('pkp.variabel_admen.nama', 'ASC')
                        ->findAll();
                }
            } else {
                $variabel = $this->variabelModel
                    ->select('pkp.variabel.*, pkp.program.nama AS nama_program')
                    ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
                    ->where('pkp.variabel.id_program', $selectedProgramID)
                    ->orderBy('pkp.variabel.nama', 'ASC')
                    ->findAll();

                $variabelAdmen = $this->variabeladmenModel
                    ->select('pkp.variabel_admen.*, pkp.program.nama AS nama_program, pkp.program.persen_program AS nilai_program')
                    ->join('pkp.program', 'pkp.program.id = pkp.variabel_admen.id_program')
                    ->where('pkp.variabel_admen.id_program', $selectedProgramID)
                    ->orderBy('pkp.program.nama', 'ASC')
                    ->orderBy('pkp.variabel_admen.nama', 'ASC')
                    ->findAll();

                if (!empty($variabel)) {
                    $variabelIDs = array_column($variabel, 'id');
                    if (!empty($variabelIDs)) {
                        $subVariabel = $this->subVariabelModel
                            ->whereIn('id_variabel', $variabelIDs)
                            ->findAll();
                    }
                }
            }
            if ($keyword) {
                if ($activeTab === 'umum') {
                    $variabel = array_filter($variabel, function ($row) use ($keyword) {
                        return (
                            stripos($row['nama'], $keyword) !== false ||
                            (isset($row['satuan_sasaran']) && stripos($row['satuan_sasaran'], $keyword) !== false)

                        );
                    });
                    $subVariabel = array_filter($subVariabel, function ($row) use ($keyword) {
                        return (
                            stripos($row['nama'], $keyword) !== false ||
                            (isset($row['satuan_sasaran']) && stripos($row['satuan_sasaran'], $keyword) !== false)
                        );
                    });
                } elseif ($activeTab === 'admen') {
                    $variabelAdmen = array_filter($variabelAdmen, function ($row) use ($keyword) {
                        return (
                            stripos($row['nama'], $keyword) !== false ||
                            (isset($row['satuan_sasaran']) && stripos($row['satuan_sasaran'], $keyword) !== false)
                        );
                    });
                }
            }
        }

        $data = [
            'title' => 'Data Capaian',
            'periode' => $periode,
            'instrumen' => $instrumen,
            'program' => $program,
            'variabel' => $variabel,
            'sub_variabel' => $subVariabel,
            'variabel_admen' => $variabelAdmen,
            'selected_periode_id' => $selectedPeriodeID,
            'selected_instrumen_id' => $selectedInstrumenID,
            'selected_instrumen_nama' => $selected_instrumen_nama,
            'selected_instrumen_persen' => $selected_instrumen_persen,
            'selected_program_id' => $selectedProgramID,
            'keyword' => $keyword,
            'aksiMode' => $aksiMode,
            'activeTab' => $activeTab,
        ];

        return view('PKP/DataCapaianView', $data);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $type = $this->request->getPost('type');
        $activeTab = $this->request->getGet('tab') ?? 'umum';

        try {
            if ($type == 'variabel') {
                $data = [
                    'nama' => $this->request->getPost('nama'),
                    'target_operasi' => $this->request->getPost('target_operasi'),
                    'target_value' => $this->request->getPost('target_value'),
                    'satuan_sasaran' => $this->request->getPost('satuan_sasaran'),
                    'total_sasaran' => $this->request->getPost('total_sasaran'),
                    'pencapaian' => $this->request->getPost('pencapaian'),
                    'analisa_akar_penyebab_masalah' => $this->request->getPost('analisa_akar_penyebab_masalah'),
                    'rencana_tindak_lanjut' => $this->request->getPost('rencana_tindak_lanjut'),
                ];
                $this->variabelModel->update($id, $data);
            } elseif ($type == 'sub_variabel') {
                $data = [
                    'nama' => $this->request->getPost('nama'),
                    'target_operasi' => $this->request->getPost('target_operasi'),
                    'target_value' => $this->request->getPost('target_value'),
                    'satuan_sasaran' => $this->request->getPost('satuan_sasaran'),
                    'total_sasaran' => $this->request->getPost('total_sasaran'),
                    'pencapaian' => $this->request->getPost('pencapaian'),
                    'analisa_akar_penyebab_masalah' => $this->request->getPost('analisa_akar_penyebab_masalah'),
                    'rencana_tindak_lanjut' => $this->request->getPost('rencana_tindak_lanjut'),
                ];
                $this->subVariabelModel->update($id, $data);
            } elseif ($type == 'variabel_admen') {
                $data = [
                    'nama' => $this->request->getPost('nama'),
                    'definisi_operasional' => $this->request->getPost('nama'),
                    'skala_nilai_0' => $this->request->getPost('skala_nilai_0'),
                    'skala_nilai_4' => $this->request->getPost('skala_nilai_4'),
                    'skala_nilai_7' => $this->request->getPost('skala_nilai_7'),
                    'skala_nilai_10' => $this->request->getPost('skala_nilai_10'),
                    'nilai' => $this->request->getPost('nilai'),
                    'analisa_akar_penyebab_masalah' => $this->request->getPost('analisa_akar_penyebab_masalah'),
                    'rencana_tindak_lanjut' => $this->request->getPost('rencana_tindak_lanjut'),
                ];
                $this->variabeladmenModel->update($id, $data);
            }
            session()->setFlashdata('pesan', 'Data Berhasil Diperbaharui');
        } catch (\Throwable $th) {
            session()->setFlashdata('error', 'Data Gagal Diperbaharui: ' . $th->getMessage());
        }

        return redirect()->to(previous_url());
    }
    public function downloadExcel()
    {
        // Ambil parameter GET filter (sama seperti index)
        $selectedPeriodeID = $this->request->getGet('periode');
        $selectedInstrumenID = $this->request->getGet('instrumen');
        $selectedProgramID = $this->request->getGet('program');

        // --- Ambil Data (copy logic dari index) ---
        $variabel = [];
        $subVariabel = [];
        $variabelAdmen = [];

        if (!empty($selectedProgramID)) {
            if ($selectedProgramID === 'all' && !empty($selectedInstrumenID)) {
                $programs = $this->programModel->where('id_instrumen', $selectedInstrumenID)->findAll();
                $programIDs = array_column($programs, 'id');
                if (!empty($programIDs)) {
                    $variabel = $this->variabelModel
                        ->select('pkp.variabel.*, pkp.program.nama AS nama_program')
                        ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
                        ->whereIn('pkp.variabel.id_program', $programIDs)
                        ->orderBy('pkp.program.nama', 'ASC')
                        ->orderBy('pkp.variabel.nama', 'ASC')
                        ->findAll();

                    if (!empty($variabel)) {
                        $variabelIDs = array_column($variabel, 'id');
                        if (!empty($variabelIDs)) {
                            $subVariabel = $this->subVariabelModel
                                ->whereIn('id_variabel', $variabelIDs)
                                ->findAll();
                        }
                    }

                    $variabelAdmen = $this->variabeladmenModel
                        ->select('pkp.variabel_admen.*, pkp.program.nama AS nama_program')
                        ->join('pkp.program', 'pkp.program.id = pkp.variabel_admen.id_program')
                        ->whereIn('pkp.variabel_admen.id_program', $programIDs)
                        ->orderBy('pkp.program.nama', 'ASC')
                        ->orderBy('pkp.variabel_admen.nama', 'ASC')
                        ->findAll();
                }
            } else {
                $variabel = $this->variabelModel
                    ->select('pkp.variabel.*, pkp.program.nama AS nama_program')
                    ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
                    ->where('pkp.variabel.id_program', $selectedProgramID)
                    ->orderBy('pkp.variabel.nama', 'ASC')
                    ->findAll();

                $variabelAdmen = $this->variabeladmenModel
                    ->select('pkp.variabel_admen.*, pkp.program.nama AS nama_program')
                    ->join('pkp.program', 'pkp.program.id = pkp.variabel_admen.id_program')
                    ->where('pkp.variabel_admen.id_program', $selectedProgramID)
                    ->orderBy('pkp.program.nama', 'ASC')
                    ->orderBy('pkp.variabel_admen.nama', 'ASC')
                    ->findAll();

                if (!empty($variabel)) {
                    $variabelIDs = array_column($variabel, 'id');
                    if (!empty($variabelIDs)) {
                        $subVariabel = $this->subVariabelModel
                            ->whereIn('id_variabel', $variabelIDs)
                            ->findAll();
                    }
                }
            }
        }

        // --- Generate Spreadsheet ---
        $spreadsheet = new Spreadsheet();

        // === Sheet 1: Variabel & Sub-Variabel ===
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Variabel & Sub-Variabel');
        // Header
        $headers1 = [
            'Nama Program',
            'Nama',
            'Target Tahun',
            'Satuan Sasaran',
            'Total Sasaran',
            'Target Sasaran',
            'Pencapaian',
            'Cakupan Riil (%)',
            '% Sub',
            '% Variabel',
            '% Program',
            'Ketercapaian Target Tahun',
            'Analisa Akar Penyebab Masalah',
            'Rencana Tindak Lanjut'
        ];
        $sheet1->fromArray($headers1, null, 'A1');
        $row = 2;
        foreach ($variabel as $v) {
            $sheet1->fromArray([
                $v['nama_program'],
                $v['nama'],
                $v['target_operator'] . ' ' . $v['target_value'],
                $v['satuan_sasaran'],
                $v['total_sasaran'],
                $v['target_sasaran'],
                $v['pencapaian'],
                $v['cakupan_riil'],
                $v['persen_sub_variabel'],
                $v['persen_variabel'],
                $v['persen_program'],
                $v['ketercapaian_target'],
                $v['analisa_akar_penyebab_masalah'],
                $v['rencana_tindak_lanjut'],
            ], null, 'A' . $row);

            // Tambahkan sub-variabel (jika ada)
            foreach ($subVariabel as $sv) {
                if ($sv['id_variabel'] == $v['id']) {
                    $sheet1->fromArray([
                        $v['nama_program'], // program tetap
                        '  - ' . $sv['nama'], // nama subvariabel, indent
                        $sv['target_operator'] . ' ' . $sv['target_value'],
                        $sv['satuan_sasaran'],
                        $sv['total_sasaran'],
                        $sv['target_sasaran'],
                        $sv['pencapaian'],
                        $sv['cakupan_riil'],
                        $sv['persen_sub_variabel'],
                        $sv['persen_variabel'],
                        $sv['persen_program'],
                        $sv['ketercapaian_target'],
                        $sv['analisa_akar_penyebab_masalah'],
                        $sv['rencana_tindak_lanjut'],
                    ], null, 'A' . (++$row));
                }
            }
            $row++;
        }

        // === Sheet 2: Variabel Admen ===
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Variabel Admen');
        $headers2 = [
            'Nama Program',
            'Nama',
            'Definisi Operasional',
            'Skala Nilai 0',
            'Skala Nilai 4',
            'Skala Nilai 7',
            'Skala Nilai 10',
            'Ketercapaian Target Tahun',
            'Analisa Akar Penyebab Masalah',
            'Rencana Tindak Lanjut'
        ];
        $sheet2->fromArray($headers2, null, 'A1');
        $row = 2;
        foreach ($variabelAdmen as $va) {
            $sheet2->fromArray([
                $va['nama_program'],
                $va['nama'],
                $va['definisi_operasional'],
                $va['skala_nilai_0'],
                $va['skala_nilai_4'],
                $va['skala_nilai_7'],
                $va['skala_nilai_10'],
                $va['nilai'],
                $va['ketercapaian_target'],
                $va['analisa_akar_penyebab_masalah'],
                $va['rencana_tindak_lanjut'],
            ], null, 'A' . $row++);
        }

        // Set active sheet ke Sheet1
        $spreadsheet->setActiveSheetIndex(0);

        // --- Download response ---
        $filename = 'Data_Capaian_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function printPdf()
    {
        // Ambil parameter GET (sama seperti index)
        $selectedPeriodeID = $this->request->getGet('periode');
        $selectedInstrumenID = $this->request->getGet('instrumen');
        $selectedProgramID = $this->request->getGet('program');

        // --- Ambil Data (copy dari index) ---
        $variabel = [];
        $subVariabel = [];
        $variabelAdmen = [];

        if (!empty($selectedProgramID)) {
            if ($selectedProgramID === 'all' && !empty($selectedInstrumenID)) {
                $programs = $this->programModel->where('id_instrumen', $selectedInstrumenID)->findAll();
                $programIDs = array_column($programs, 'id');
                if (!empty($programIDs)) {
                    $variabel = $this->variabelModel
                        ->select('pkp.variabel.*, pkp.program.nama AS nama_program')
                        ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
                        ->whereIn('pkp.variabel.id_program', $programIDs)
                        ->orderBy('pkp.program.nama', 'ASC')
                        ->orderBy('pkp.variabel.nama', 'ASC')
                        ->findAll();

                    if (!empty($variabel)) {
                        $variabelIDs = array_column($variabel, 'id');
                        if (!empty($variabelIDs)) {
                            $subVariabel = $this->subVariabelModel
                                ->whereIn('id_variabel', $variabelIDs)
                                ->findAll();
                        }
                    }

                    $variabelAdmen = $this->variabeladmenModel
                        ->select('pkp.variabel_admen.*, pkp.program.nama AS nama_program')
                        ->join('pkp.program', 'pkp.program.id = pkp.variabel_admen.id_program')
                        ->whereIn('pkp.variabel_admen.id_program', $programIDs)
                        ->orderBy('pkp.program.nama', 'ASC')
                        ->orderBy('pkp.variabel_admen.nama', 'ASC')
                        ->findAll();
                }
            } else {
                $variabel = $this->variabelModel
                    ->select('pkp.variabel.*, pkp.program.nama AS nama_program')
                    ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
                    ->where('pkp.variabel.id_program', $selectedProgramID)
                    ->orderBy('pkp.variabel.nama', 'ASC')
                    ->findAll();

                $variabelAdmen = $this->variabeladmenModel
                    ->select('pkp.variabel_admen.*, pkp.program.nama AS nama_program')
                    ->join('pkp.program', 'pkp.program.id = pkp.variabel_admen.id_program')
                    ->where('pkp.variabel_admen.id_program', $selectedProgramID)
                    ->orderBy('pkp.program.nama', 'ASC')
                    ->orderBy('pkp.variabel_admen.nama', 'ASC')
                    ->findAll();

                if (!empty($variabel)) {
                    $variabelIDs = array_column($variabel, 'id');
                    if (!empty($variabelIDs)) {
                        $subVariabel = $this->subVariabelModel
                            ->whereIn('id_variabel', $variabelIDs)
                            ->findAll();
                    }
                }
            }
        }

        // ==== Generate HTML PDF ====
        $html = '<style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
            table { border-collapse: collapse; margin-bottom:30px;}
            th, td { border: 1px solid #888; padding: 4px 6px; }
            th { background-color: #f3f3f3; }
            .subvar { background: #f8fafc; font-style:italic; }
            .judul { background: #e2e8f0; font-weight:bold; }
            h2 { margin-top:20px; margin-bottom:8px; }
        </style>';

        // --- Tabel Variabel & Sub-Variabel ---
        $html .= '<h2 style="text-align:center;">Data Variabel & Sub-Variabel</h2>
            <table width="100%">
                <thead>
                    <tr>
                        <th>Nama Program</th>
                        <th>Nama</th>
                        <th>Target Tahun</th>
                        <th>Satuan Sasaran</th>
                        <th>Total Sasaran</th>
                        <th>Target Sasaran</th>
                        <th>Pencapaian</th>
                        <th>Cakupan Riil (%)</th>
                        <th>% Sub</th>
                        <th>% Variabel</th>
                        <th>% Program</th>
                        <th>Ketercapaian Target Tahun</th>
                        <th>Analisa</th>
                        <th>Rencana</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($variabel as $v) {
            $html .= '<tr>
                <td>' . htmlspecialchars($v['nama_program']) . '</td>
                <td>' . htmlspecialchars($v['nama']) . '</td>
                <td>' . htmlspecialchars($v['target_operator'] . ' ' . $v['target_value']) . '</td>
                <td>' . htmlspecialchars($v['satuan_sasaran']) . '</td>
                <td>' . htmlspecialchars($v['total_sasaran']) . '</td>
                <td>' . htmlspecialchars($v['target_sasaran']) . '</td>
                <td>' . htmlspecialchars($v['pencapaian']) . '</td>
                <td>' . htmlspecialchars($v['cakupan_riil']) . '</td>
                <td>' . htmlspecialchars($v['persen_sub_variabel']) . '</td>
                <td>' . htmlspecialchars($v['persen_variabel']) . '</td>
                <td>' . htmlspecialchars($v['persen_program']) . '</td>
                <td>' . htmlspecialchars($v['ketercapaian_target']) . '</td>
                <td>' . htmlspecialchars($v['analisa_akar_penyebab_masalah']) . '</td>
                <td>' . htmlspecialchars($v['rencana_tindak_lanjut']) . '</td>
            </tr>';
            foreach ($subVariabel as $sv) {
                if ($sv['id_variabel'] == $v['id']) {
                    $html .= '<tr class="subvar">
                        <td>' . htmlspecialchars($v['nama_program']) . '</td>
                        <td style="padding-left:18px;">- ' . htmlspecialchars($sv['nama']) . '</td>
                        <td>' . htmlspecialchars($sv['target_operator'] . ' ' . $sv['target_value']) . '</td>
                        <td>' . htmlspecialchars($sv['satuan_sasaran']) . '</td>
                        <td>' . htmlspecialchars($sv['total_sasaran']) . '</td>
                        <td>' . htmlspecialchars($sv['target_sasaran']) . '</td>
                        <td>' . htmlspecialchars($sv['pencapaian']) . '</td>
                        <td>' . htmlspecialchars($sv['cakupan_riil']) . '</td>
                        <td>' . htmlspecialchars($sv['persen_sub_variabel']) . '</td>
                        <td>' . htmlspecialchars($sv['persen_variabel']) . '</td>
                        <td>' . htmlspecialchars($sv['persen_program']) . '</td>
                        <td>' . htmlspecialchars($sv['ketercapaian_target']) . '</td>
                        <td>' . htmlspecialchars($sv['analisa_akar_penyebab_masalah']) . '</td>
                        <td>' . htmlspecialchars($sv['rencana_tindak_lanjut']) . '</td>
                    </tr>';
                }
            }
        }
        $html .= '</tbody></table>';

        // --- Tabel Variabel Admen ---
        $html .= '<h2 style="text-align:center;">Data Variabel Admen</h2>
            <table width="100%">
                <thead>
                    <tr>
                        <th>Nama Program</th>
                        <th>Nama</th>
                        <th>Definisi Operasional</th>
                        <th>Skala Nilai 0</th>
                        <th>Skala Nilai 4</th>
                        <th>Skala Nilai 7</th>
                        <th>Skala Nilai 10</th>
                        <th>Nilai</th>
                        <th>Ketercapaian Target Tahun</th>
                        <th>Analisa</th>
                        <th>Rencana</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($variabelAdmen as $va) {
            $html .= '<tr>
                <td>' . htmlspecialchars($va['nama_program']) . '</td>
                <td>' . htmlspecialchars($va['nama']) . '</td>
                <td>' . htmlspecialchars($va['definisi_operasional']) . '</td>
                <td>' . htmlspecialchars($va['skala_nilai_0']) . '</td>
                <td>' . htmlspecialchars($va['skala_nilai_4']) . '</td>
                <td>' . htmlspecialchars($va['skala_nilai_7']) . '</td>
                <td>' . htmlspecialchars($va['skala_nilai_10']) . '</td>
                <td>' . htmlspecialchars($va['nilai']) . '</td>
                <td>' . htmlspecialchars($va['ketercapaian_target']) . '</td>
                <td>' . htmlspecialchars($va['analisa_akar_penyebab_masalah']) . '</td>
                <td>' . htmlspecialchars($va['rencana_tindak_lanjut']) . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';

        // ==== Dompdf: Generate & Output ====
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream('Data_Capaian_' . date('Ymd_His') . '.pdf', ["Attachment" => true]);
        exit;
    }


}