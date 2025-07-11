<?php
/**
 * @var array $variabel
 * @var array $subVariabel
 * @var array $variabelAdmen
 * @var string $selected_instrumen_nama
 * @var float $selected_instrumen_persen
 * @var string $selected_periode_nama
 */


$nama_bulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
$parts = explode(' ', $selected_periode_nama);
$bulan = isset($parts[0]) ? (int)$parts[0] : 0;
$tahun = $parts[1] ?? '';
$selected_periode_nama = ($nama_bulan[$bulan] ?? 'Bulan?') . ' ' . $tahun;
?>

<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
    table { border-collapse: collapse; margin-bottom: 30px; width: 100%; }
    th, td { border: 1px solid #888; padding: 4px 6px; }
    th { background-color: #f3f3f3; }
    .subvar { background: #f8fafc; font-style: italic; }
    .judul { background: #e2e8f0; font-weight: bold; }
</style>

<?php if (!empty($variabel)) : ?>
    <h2 style="text-align:center;">Data Variabel & Sub-Variabel - <?= esc($selected_periode_nama) ?></h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
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
        <tbody>
        <tr style="background-color:#eeeeee; font-weight:bold;">
            <td colspan="10">Instrumen: <?= esc($selected_instrumen_nama) ?></td>
            <td colspan="1"><?= esc($selected_instrumen_persen) ?></td>
            <td colspan="3"></td>
        </tr>
        <?php
        $last_id_program = null;
        $nomor_variabel = 1;
        $nomor_sub = 1;
        foreach ($variabel as $v):
            if ($last_id_program !== $v['id_program']):
                echo 
                '<tr style="background-color:#eeeeee; font-weight:bold;">
                <td colspan="10">Program: ' . esc($v['nama_program']) . ' </td>
                <td colspan="1">' . esc($v['nilai_program']) . ' </td>
                <td colspan="3"></td>
                </tr>';
                $last_id_program = $v['id_program'];
                $nomor_variabel = 1;
            endif;

            $has_sub = false;
            foreach ($subVariabel as $sv_check) {
                if ($sv_check['id_variabel'] === $v['id']) {
                    $has_sub = true;
                    break;
                }$nomor_sub = 1;
            }
            if ($has_sub):
                echo '<tr><td colspan="9">Variabel: ' . esc($v['nama']) . '</td><td>' . esc($v['persen_variabel']) . '%</td><td colspan="3"></td></tr>';
                foreach ($subVariabel as $sv):
                    if ($sv['id_variabel'] === $v['id']):
                        echo '<tr class="subvar">
                            <td>' .  $nomor_sub++ . '</td>
                            <td style="padding-left:18px;">- ' . esc($sv['nama']) . '</td>
                            <td>' . esc($sv['target_operator'] . ' ' . $sv['target_value']) . '</td>
                            <td>' . esc($sv['satuan_sasaran']) . '</td>
                            <td>' . esc($sv['total_sasaran']) . '</td>
                            <td>' . esc($sv['target_sasaran']) . '</td>
                            <td>' . esc($sv['pencapaian']) . '</td>
                            <td>' . esc($sv['cakupan_riil']) . '</td>
                            <td> </td>
                            <td>' . esc($sv['persen_variabel']) . '</td>
                            <td> </td>
                            <td>' . esc($sv['ketercapaian_target']) . '</td>
                            <td>' . esc($sv['analisa_akar_penyebab_masalah']) . '</td>
                            <td>' . esc($sv['rencana_tindak_lanjut']) . '</td>
                        </tr>';
                    endif;
                endforeach;
            else:
                echo '<tr>
                    <td>' .  $nomor_variabel++ . '</td>
                    <td>' . esc($v['nama']) . '</td>
                    <td>' . esc($v['target_operator'] . ' ' . $v['target_value']) . '</td>
                    <td>' . esc($v['satuan_sasaran']) . '</td>
                    <td>' . esc($v['total_sasaran']) . '</td>
                    <td>' . esc($v['target_sasaran']) . '</td>
                    <td>' . esc($v['pencapaian']) . '</td>
                    <td>' . esc($v['cakupan_riil']) . '</td>
                    <td></td>
                    <td>' . esc($v['persen_variabel']) . '</td>
                    <td></td>
                    <td>' . esc($v['ketercapaian_target']) . '</td>
                    <td>' . esc($v['analisa_akar_penyebab_masalah']) . '</td>
                    <td>' . esc($v['rencana_tindak_lanjut']) . '</td>
                </tr>';
            endif;
        endforeach;
        ?>
        </tbody>
    </table>
<?php endif; ?>
<?php if (!empty($variabelAdmen)) : ?>
    <h2 style="text-align:center;">Data Variabel Administrasi dan Manajemen - <?= esc($selected_periode_nama) ?></h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
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
        <tbody>
            <?php
            $last_id_program = null;
            $nomor = 1;
            foreach ($variabelAdmen as $i => $va):
                if ($last_id_program !== $va['id_program']):
                    echo '<tr style="background-color:#eeeeee; font-weight:bold;">
                        <td colspan="10">Program: ' . esc($va['nama_program']) . '</td>
                    </tr>';
                    $last_id_program = $va['id_program'];
                    $last_nilai_program = $va['nilai_program'];
                    $last_nama_program = $va['nama_program'];
                    $nomor = 1;
                endif;

                echo 
                '<tr>
                    <td>' .  $nomor++ . '</td>
                    <td>' . esc($va['nama']) . '</td>
                    <td>' . esc($va['definisi_operasional']) . '</td>
                    <td>' . esc($va['skala_nilai_0']) . '</td>
                    <td>' . esc($va['skala_nilai_4']) . '</td>
                    <td>' . esc($va['skala_nilai_7']) . '</td>
                    <td>' . esc($va['skala_nilai_10']) . '</td>
                    <td>' . esc($va['nilai']) . '</td>
                    <td>' . esc($va['ketercapaian_target']) . '</td>
                    <td>' . esc($va['analisa_akar_penyebab_masalah']) . '</td>
                    <td>' . esc($va['rencana_tindak_lanjut']) . '</td>
                </tr>';

                $next = $variabelAdmen[$i + 1] ?? null;
                if (!$next || $next['id_program'] !== $va['id_program']) {
                    echo '<tr style="background-color:#f5f5f5; font-weight:bold;">
                        <td colspan="7">Jumlah Nilai Kinerja Program: ' . esc($last_nama_program) . '</td>
                        <td colspan="1">' . esc(intval($last_nilai_program)) . '</td>
                        <td colspan="3"></td>
                    </tr>';
                }
            endforeach;
            ?>
        <tr style="background-color:#eeeeee; font-weight:bold;">
            <td colspan="7">Total Nilai Kinerja <?= esc($selected_instrumen_nama) ?></td>
            <td colspan="1"><?= esc(intval($selected_instrumen_persen)) ?></td>
            <td colspan="3"></td>
        </tr>
        <tr style="background-color:#eeeeee; font-weight:bold;">
            <td colspan="7">Rata-rata Kinerja <?= esc($selected_instrumen_nama) ?></td>
            <td colspan="1"><?= esc($rata_rata_kinerja_instrumen) ?></td>
            <td colspan="3"></td>
        </tr>
        </tbody>
    </table>
<?php endif; ?>
