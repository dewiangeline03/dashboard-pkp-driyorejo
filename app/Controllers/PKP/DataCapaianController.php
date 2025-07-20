<?php

namespace App\Controllers\PKP;

use App\Controllers\BaseController;
use App\Models\PKP\PeriodeModel;
use App\Models\PKP\InstrumenModel;
use App\Models\PKP\ProgramModel;
use App\Models\PKP\VariabelModel;
use App\Models\PKP\VariabelAdmenModel;
use App\Models\PKP\SubVariabelModel;
use App\Models\PKP\HistoriPenilaianModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Ramsey\Uuid\Uuid;


class DataCapaianController extends BaseController
{
    protected $periodeModel;
    protected $instrumenModel;
    protected $programModel;
    protected $variabelModel;
    protected $variabeladmenModel;
    protected $subVariabelModel;
    protected $historiPenilaianModel;

    public function __construct()
    {
        $this->periodeModel = new PeriodeModel();
        $this->instrumenModel = new InstrumenModel();
        $this->programModel = new ProgramModel();
        $this->variabelModel = new VariabelModel();
        $this->variabeladmenModel = new VariabelAdmenModel();
        $this->subVariabelModel = new SubVariabelModel();
        $this->historiPenilaianModel = new HistoriPenilaianModel();
    }

    public function index()
    {
        $selectedPeriodeID = $this->request->getGet('periode');
        $selectedInstrumenID = $this->request->getGet('instrumen');
        $selectedProgramID = $this->request->getGet('program');
        $keyword = $this->request->getGet('keyword');
        $aksiMode = $this->request->getGet('aksi') === '1';
        $activeTab = $this->request->getGet('tab') ?? 'umum';


        $periode = $this->periodeModel
            ->orderBy('tahun', 'asc')
            ->orderBy('id_bulan', 'asc')
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
            $bulan_angka = (int) $p['id_bulan'];
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
                $selected_instrumen_persen = $selectedInstrumen['persen_instrumen'] ?? 0;
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

        $jumlah_program_instrumen = 0;
        if (!empty($selectedInstrumenID)) {
            $jumlah_program_instrumen = count(
                $this->programModel->where('id_instrumen', $selectedInstrumenID)->findAll()
            );
        }

        $rata_rata_kinerja_instrumen = null;
        if ($jumlah_program_instrumen > 0) {
            $rata_rata_kinerja_instrumen = intval($selected_instrumen_persen) / $jumlah_program_instrumen;
        }

        if (!empty($selectedProgramID)) {
            if ($selectedProgramID === 'all' && !empty($selectedInstrumenID)) {
                $programs = $this->programModel->where('id_instrumen', $selectedInstrumenID)->findAll();
                $programIDs = array_column($programs, 'id');

                if (!empty($programIDs)) {
                    $variabel = $this->variabelModel
                        ->select('pkp.variabel.*, pkp.program.nama AS nama_program')
                        ->select('pkp.variabel.*, pkp.program.persen_program AS nilai_program')
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
                    ->select('pkp.variabel.*, pkp.program.persen_program AS nilai_program')
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
            if ($keyword && $activeTab === 'umum') {
                $filteredVariabel = [];
                $filteredSubVariabel = [];

                foreach ($variabel as $v) {
                    $matchVariabel = (
                        stripos($v['nama'], $keyword) !== false ||
                        (isset($v['satuan_sasaran']) && stripos($v['satuan_sasaran'], $keyword) !== false)
                    );

                    $subOfThisVariabel = array_filter($subVariabel, function ($sv) use ($v) {
                        return $sv['id_variabel'] === $v['id'];
                    });

                    $subMatched = [];

                    foreach ($subOfThisVariabel as $sv) {
                        if (
                            stripos($sv['nama'], $keyword) !== false ||
                            (isset($sv['satuan_sasaran']) && stripos($sv['satuan_sasaran'], $keyword) !== false)
                        ) {
                            $subMatched[] = $sv;
                        }
                    }

                    if ($matchVariabel) {
                        // Variabel cocok → ambil semua sub-variabelnya
                        $filteredVariabel[] = $v;
                        $filteredSubVariabel = array_merge($filteredSubVariabel, $subOfThisVariabel);
                    } elseif (!empty($subMatched)) {
                        // Sub-variabel cocok → variabel harus ikut dimunculkan
                        $filteredVariabel[] = $v;
                        $filteredSubVariabel = array_merge($filteredSubVariabel, $subMatched);
                    }
                }

                $variabel = $filteredVariabel;
                $subVariabel = $filteredSubVariabel;
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
            'rata_rata_kinerja_instrumen' => $rata_rata_kinerja_instrumen,
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
        $userAgent = $this->request->getUserAgent();
        $userAgentString = $userAgent->getAgentString();

        try {
            $tingkat_indikator = '';
            $oldDataJson = null;
            $newDataJson = null;


            if ($type == 'variabel') {
                $tingkat_indikator = 'Variabel Tipe 1';
                $oldData = $this->variabelModel->find($id);

                $data = [
                    'nama' => $this->request->getPost('nama'),
                    'target_operator' => $this->request->getPost('target_operator'),
                    'target_value' => $this->request->getPost('target_value'),
                    'satuan_sasaran' => $this->request->getPost('satuan_sasaran'),
                    'total_sasaran' => $this->request->getPost('total_sasaran'),
                    'pencapaian' => $this->request->getPost('pencapaian'),
                    'analisa_akar_penyebab_masalah' => $this->request->getPost('analisa_akar_penyebab_masalah'),
                    'rencana_tindak_lanjut' => $this->request->getPost('rencana_tindak_lanjut'),
                ];

                if ($this->variabelModel->update($id, $data)) {
                    $renames = [
                        'nama' => 'Nama',
                        'target_operator' => 'Target Operator',
                        'target_value' => 'Target Value',
                        'satuan_sasaran' => 'Satuan Sasaran',
                        'total_sasaran' => 'Total Sasaran',
                        'pencapaian' => 'Pencapaian',
                        'analisa_akar_penyebab_masalah' => 'Analisa Akar Penyebab Masalah',
                        'rencana_tindak_lanjut' => 'Rencana Tindak Lanjut',
                    ];

                    $logData = $this->filterChangedFields($oldData, $data, $renames);

                    $oldDataJson = json_encode($logData['sebelum'], JSON_UNESCAPED_UNICODE);
                    $newDataJson = json_encode($logData['sesudah'], JSON_UNESCAPED_UNICODE);
                }
            }

            if ($type == 'sub_variabel') {
                $tingkat_indikator = 'Sub-Variabel';
                $oldData = $this->subVariabelModel->find($id);

                $data = [
                    'nama' => $this->request->getPost('nama'),
                    'target_operator' => $this->request->getPost('target_operator'),
                    'target_value' => $this->request->getPost('target_value'),
                    'satuan_sasaran' => $this->request->getPost('satuan_sasaran'),
                    'total_sasaran' => $this->request->getPost('total_sasaran'),
                    'pencapaian' => $this->request->getPost('pencapaian'),
                    'analisa_akar_penyebab_masalah' => $this->request->getPost('analisa_akar_penyebab_masalah'),
                    'rencana_tindak_lanjut' => $this->request->getPost('rencana_tindak_lanjut'),
                ];

                if ($this->subVariabelModel->update($id, $data)) {
                    $renames = [
                        'nama' => 'Nama',
                        'target_operator' => 'Target Operator',
                        'target_value' => 'Target Value',
                        'satuan_sasaran' => 'Satuan Sasaran',
                        'total_sasaran' => 'Total Sasaran',
                        'pencapaian' => 'Pencapaian',
                        'analisa_akar_penyebab_masalah' => 'Analisa Akar Penyebab Masalah',
                        'rencana_tindak_lanjut' => 'Rencana Tindak Lanjut',
                    ];

                    $logData = $this->filterChangedFields($oldData, $data, $renames);

                    $oldDataJson = json_encode($logData['sebelum'], JSON_UNESCAPED_UNICODE);
                    $newDataJson = json_encode($logData['sesudah'], JSON_UNESCAPED_UNICODE);
                }
            }

            if ($type == 'variabel_admen') {
                $tingkat_indikator = 'Variabel Tipe 2';
                $oldData = $this->variabeladmenModel->find($id);

                $data = [
                    'nama' => $this->request->getPost('nama'),
                    'definisi_operasional' => $this->request->getPost('definisi_operasional'),
                    'skala_nilai_0' => $this->request->getPost('skala_nilai_0'),
                    'skala_nilai_4' => $this->request->getPost('skala_nilai_4'),
                    'skala_nilai_7' => $this->request->getPost('skala_nilai_7'),
                    'skala_nilai_10' => $this->request->getPost('skala_nilai_10'),
                    'nilai' => $this->request->getPost('nilai'),
                    'analisa_akar_penyebab_masalah' => $this->request->getPost('analisa_akar_penyebab_masalah'),
                    'rencana_tindak_lanjut' => $this->request->getPost('rencana_tindak_lanjut'),
                ];

                if ($this->variabeladmenModel->update($id, $data)) {
                    $renames = [
                        'nama' => 'Nama',
                        'definisi_operasional' => 'Definisi Operasional',
                        'skala_nilai_0' => 'Skala Nilai 0',
                        'skala_nilai_4' => 'Skala Nilai 4',
                        'skala_nilai_7' => 'Skala Nilai 7',
                        'skala_nilai_10' => 'Skala Nilai 10',
                        'nilai' => 'Nilai',
                        'analisa_akar_penyebab_masalah' => 'Analisa Akar Penyebab Masalah',
                        'rencana_tindak_lanjut' => 'Rencana Tindak Lanjut',
                    ];

                    $logData = $this->filterChangedFields($oldData, $data, $renames);

                    $oldDataJson = json_encode($logData['sebelum'], JSON_UNESCAPED_UNICODE);
                    $newDataJson = json_encode($logData['sesudah'], JSON_UNESCAPED_UNICODE);
                }
            }

            // Simpan histori hanya jika ada perubahan
            if ($oldDataJson && $newDataJson) {
                $selectedPeriodeID = $this->request->getPost('periode');
                $selectedPeriode = $this->periodeModel->find($selectedPeriodeID);

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

                $periode = 'Periode Tidak Diketahui';
                if ($selectedPeriode) {
                    $bulan = (int) $selectedPeriode['id_bulan'];
                    $tahun = $selectedPeriode['tahun'];
                    $bulanNama = $nama_bulan[$bulan] ?? 'Bulan?';
                    $periode = $bulanNama . ' ' . $tahun;
                }


                $historyData = [
                    'id' => Uuid::uuid4()->toString(),
                    'id_akun' => session()->get('userInfo')['id'],
                    'id_indikator' => $id,
                    'nama_indikator' => $this->request->getPost('nama'),
                    'tingkat_indikator' => $tingkat_indikator,
                    'periode' => $periode,
                    'data_sebelum' => $oldDataJson,
                    'data_sesudah' => $newDataJson,
                    'user_agent' => $userAgentString
                ];
                $this->historiPenilaianModel->save($historyData);
            }

            session()->setFlashdata('pesan', 'Data Berhasil Diperbaharui');
        } catch (\Throwable $th) {
            session()->setFlashdata('error', 'Data Gagal Diperbaharui: ' . $th->getMessage());
        }

        return redirect()->to(previous_url());
    }

    private function filterChangedFields(array $old, array $new, array $renames = []): array
    {
        $before = [];
        $after = [];

        foreach ($new as $key => $value) {
            $oldValue = $old[$key] ?? null;
            if ((string)$oldValue !== (string)$value) {
                $label = $renames[$key] ?? $key;
                $before[$label] = $oldValue;
                $after[$label] = $value;
            }
        }

        return ['sebelum' => $before, 'sesudah' => $after];
    }





    public function downloadExcel()
    {
        $selectedPeriodeID = $this->request->getGet('periode');
        $selectedInstrumenID = $this->request->getGet('instrumen');
        $selectedProgramID = $this->request->getGet('program');

        $selectedPeriode = $this->periodeModel->find($selectedPeriodeID);
        $selectedInstrumen = $this->instrumenModel->find($selectedInstrumenID);
        $selected_periode_nama = $selectedPeriode['id_bulan'] . ' ' . $selectedPeriode['tahun'] ?? '';
        $selected_instrumen_nama = $selectedInstrumen['nama'] ?? '-';
        $selected_instrumen_persen = $selectedInstrumen['persen_instrumen'] ?? '-';

        $variabel = $subVariabel = $variabelAdmen = [];

        if (!empty($selectedProgramID)) {
            if ($selectedProgramID === 'all' && !empty($selectedInstrumenID)) {
                $programs = $this->programModel->where('id_instrumen', $selectedInstrumenID)->findAll();
                $programIDs = array_column($programs, 'id');
            } else {
                $programIDs = [$selectedProgramID];
            }

            if (!empty($programIDs)) {
                $variabel = $this->variabelModel
                    ->select('pkp.variabel.*, pkp.program.nama AS nama_program, pkp.program.persen_program')
                    ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
                    ->whereIn('pkp.variabel.id_program', $programIDs)
                    ->orderBy('pkp.program.nama', 'ASC')
                    ->orderBy('pkp.variabel.nama', 'ASC')
                    ->findAll();

                $variabelIDs = array_column($variabel, 'id');
                if (!empty($variabelIDs)) {
                    $subVariabel = $this->subVariabelModel
                        ->whereIn('id_variabel', $variabelIDs)
                        ->findAll();
                }

                $variabelAdmen = $this->variabeladmenModel
                    ->select('pkp.variabel_admen.*, pkp.program.nama AS nama_program, pkp.program.persen_program AS nilai_program')
                    ->join('pkp.program', 'pkp.program.id = pkp.variabel_admen.id_program')
                    ->whereIn('pkp.variabel_admen.id_program', $programIDs)
                    ->orderBy('pkp.program.nama', 'ASC')
                    ->orderBy('pkp.variabel_admen.nama', 'ASC')
                    ->findAll();
            }
        }

        $spreadsheet = new Spreadsheet();

        // === Sheet 1: Variabel Tipe 1 ===
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Variabel Tipe 1');

        $row = 1;

        // Tulis header kolom
        $headers1 = [
            'Nama',
            'Target Tahun',
            'Satuan Sasaran',
            'Total Sasaran',
            'Target Sasaran',
            'Pencapaian',
            'Cakupan Riil',
            '% Sub',
            '% Variabel',
            '% Program',
            'Ketercapaian Target',
            'Analisa',
            'Rencana'
        ];
        $sheet1->fromArray($headers1, null, 'A' . $row);
        $row++;

        // Tulis baris instrumen sebagai bagian dari isi tabel
        $sheet1->setCellValue('A' . $row, 'Instrumen: ' . $selected_instrumen_nama);
        $sheet1->setCellValue('J' . $row, $selected_instrumen_persen);
        $row++;
        $last_id_program = null;
        foreach ($variabel as $v) {
            if ($last_id_program !== $v['id_program']) {
                $sheet1->setCellValue('A' . $row, 'Program: ' . $v['nama_program']);
                $sheet1->setCellValue('J' . $row, $v['persen_program']);
                $row++;
                $last_id_program = $v['id_program'];
            }

            $hasSub = false;
            foreach ($subVariabel as $sv) {
                if ($sv['id_variabel'] === $v['id']) {
                    $hasSub = true;
                    break;
                }
            }

            if ($hasSub) {
                $sheet1->setCellValue('A' . $row, 'Variabel: ' . $v['nama']);
                $sheet1->setCellValue('I' . $row, $v['persen_variabel']);
                $row++;
                foreach ($subVariabel as $sv) {
                    if ($sv['id_variabel'] === $v['id']) {
                        $sheet1->fromArray([
                            '  - ' . $sv['nama'],
                            $sv['target_operator'] . ' ' . $sv['target_value'],
                            $sv['satuan_sasaran'],
                            $sv['total_sasaran'],
                            $sv['target_sasaran'],
                            $sv['pencapaian'],
                            $sv['cakupan_riil'],
                            $sv['persen_sub_variabel'],
                            $sv['persen_variabel'],
                            '',
                            $sv['ketercapaian_target'],
                            $sv['analisa_akar_penyebab_masalah'],
                            $sv['rencana_tindak_lanjut'],
                        ], null, 'A' . $row++);
                    }
                }
            } else {
                $sheet1->fromArray([
                    $v['nama'],
                    $v['target_operator'] . ' ' . $v['target_value'],
                    $v['satuan_sasaran'],
                    $v['total_sasaran'],
                    $v['target_sasaran'],
                    $v['pencapaian'],
                    $v['cakupan_riil'],
                    '',
                    $v['persen_variabel'],
                    '',
                    $v['ketercapaian_target'],
                    $v['analisa_akar_penyebab_masalah'],
                    $v['rencana_tindak_lanjut'],
                ], null, 'A' . $row++);
            }
        }

        // === Sheet 2: Variabel Tipe 2 ===
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('Variabel Tipe 2');

        $headers2 = [
            'Nama',
            'Definisi Operasional',
            'Skala Nilai 0',
            'Skala Nilai 4',
            'Skala Nilai 7',
            'Skala Nilai 10',
            'Nilai',
            'Ketercapaian Target',
            'Analisa',
            'Rencana'
        ];
        $sheet2->fromArray($headers2, null, 'A' . $row);
        $row++;

        $sheet2->setCellValue('A' . $row, 'Instrumen: ' . $selected_instrumen_nama);
        $sheet2->setCellValue('G' . $row, $selected_instrumen_persen);
        $last_id_program = null;
        foreach ($variabelAdmen as $i => $va) {
            if ($last_id_program !== $va['id_program']) {
                $sheet2->setCellValue('A' . $row, 'Program: ' . $va['nama_program']);
                $row++;
                $last_id_program = $va['id_program'];
            }

            $sheet2->fromArray([
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

            $next = $variabelAdmen[$i + 1] ?? null;
            if (!$next || $next['id_program'] !== $va['id_program']) {
                $sheet2->setCellValue('A' . $row, 'Jumlah Nilai Kinerja Program: ' . $va['nama_program']);
                $sheet2->setCellValue('G' . $row, intval($va['nilai_program']));
                $row++;
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

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
        $selectedPeriodeID = $this->request->getGet('periode');
        $selectedInstrumenID = $this->request->getGet('instrumen');
        $selectedProgramID = $this->request->getGet('program');

        $selectedPeriode = $this->periodeModel->find($selectedPeriodeID);
        $selectedInstrumen = $this->instrumenModel->find($selectedInstrumenID);

        $selected_periode_nama = $selectedPeriode['id_bulan'] . ' ' . $selectedPeriode['tahun'] ?? '';
        $selected_instrumen_nama = $selectedInstrumen['nama'] ?? '-';
        $selected_instrumen_persen = $selectedInstrumen['persen_instrumen'] ?? '-';

        $jumlah_program_instrumen = count(
            $this->programModel->where('id_instrumen', $selectedInstrumenID)->findAll()
        );

        $rata_rata_kinerja_instrumen = null;
        if ($jumlah_program_instrumen > 0) {
            $rata_rata_kinerja_instrumen = intval($selected_instrumen_persen) / $jumlah_program_instrumen;
        }

        $variabel = $subVariabel = $variabelAdmen = [];

        if (!empty($selectedProgramID)) {
            if ($selectedProgramID === 'all' && !empty($selectedInstrumenID)) {
                $programs = $this->programModel->where('id_instrumen', $selectedInstrumenID)->findAll();
                $programIDs = array_column($programs, 'id');
                if (!empty($programIDs)) {
                    $variabel = $this->variabelModel
                        ->select('pkp.variabel.*, pkp.program.nama AS nama_program')
                        ->select('pkp.variabel.*, pkp.program.persen_program AS nilai_program')
                        ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
                        ->whereIn('pkp.variabel.id_program', $programIDs)
                        ->orderBy('pkp.program.nama', 'ASC')
                        ->orderBy('pkp.variabel.nama', 'ASC')
                        ->findAll();

                    if (!empty($variabel)) {
                        $variabelIDs = array_column($variabel, 'id');
                        $subVariabel = $this->subVariabelModel->whereIn('id_variabel', $variabelIDs)->findAll();
                    }

                    $variabelAdmen = $this->variabeladmenModel
                        ->select('pkp.variabel_admen.*, pkp.program.nama AS nama_program')
                        ->select('pkp.variabel_admen.*, pkp.program.persen_program AS nilai_program')
                        ->join('pkp.program', 'pkp.program.id = pkp.variabel_admen.id_program')
                        ->whereIn('pkp.variabel_admen.id_program', $programIDs)
                        ->orderBy('pkp.program.nama', 'ASC')
                        ->orderBy('pkp.variabel_admen.nama', 'ASC')
                        ->findAll();
                }
            } else {
                $variabel = $this->variabelModel
                    ->select('pkp.variabel.*, pkp.program.nama AS nama_program')
                    ->select('pkp.variabel.*, pkp.program.persen_program AS nilai_program')
                    ->join('pkp.program', 'pkp.program.id = pkp.variabel.id_program')
                    ->where('pkp.variabel.id_program', $selectedProgramID)
                    ->orderBy('pkp.variabel.nama', 'ASC')
                    ->findAll();

                $variabelAdmen = $this->variabeladmenModel
                    ->select('pkp.variabel_admen.*, pkp.program.nama AS nama_program')
                    ->select('pkp.variabel_admen.*, pkp.program.persen_program AS nilai_program')
                    ->join('pkp.program', 'pkp.program.id = pkp.variabel_admen.id_program')
                    ->where('pkp.variabel_admen.id_program', $selectedProgramID)
                    ->orderBy('pkp.program.nama', 'ASC')
                    ->orderBy('pkp.variabel_admen.nama', 'ASC')
                    ->findAll();

                if (!empty($variabel)) {
                    $variabelIDs = array_column($variabel, 'id');
                    $subVariabel = $this->subVariabelModel->whereIn('id_variabel', $variabelIDs)->findAll();
                }
            }
        }

        $html = view('export/template_pdf', [
            'variabel' => $variabel,
            'subVariabel' => $subVariabel,
            'variabelAdmen' => $variabelAdmen,
            'selected_instrumen_nama' => $selected_instrumen_nama,
            'selected_instrumen_persen' => $selected_instrumen_persen,
            'selected_periode_nama' => $selected_periode_nama,
            'rata_rata_kinerja_instrumen' => $rata_rata_kinerja_instrumen
        ]);

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream('Data_Capaian_' . date('Ymd_His') . '.pdf', ["Attachment" => true]);
        exit;
    }
}
