<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $role = session()->get('userInfo')['role']; ?>

<div class="container-sm" x-data="{ 
    aksiMode: '<?= $aksiMode ? true : false ?>',
    periode: '<?= $selected_periode_id ?>',
    instrumen: '<?= $selected_instrumen_id ?>',
    program: '<?= $selected_program_id ?? '' ?>',
    keyword: '<?= esc($keyword ?? '') ?>',
    tab: '<?= esc($activeTab ?? '') ?>'}">
    <div class="w-full px-4 lg:mt-8 sm:px-6 md:px-8 lg:pl-72 z-[0]">
        <h1 class="!text-4xl mb-8 font-semibold text-slate-800">Data Capaian</h1>
        <!-- Dropdown Filters -->
        <form method="get">
            <div class="flex gap-4">
                <!-- Periode -->
                <div>
                    <label class="block text-sm font-medium mb-1">Periode</label>
                    <select name="periode" x-model="periode" @change="$el.form.submit()"
                        class="w-48 border rounded px-3 py-2 rounded-lg shadow-sm"
                        style="height: 40px; background-color: white;" id="periode-dropdown">
                        <option value="">Pilih Periode</option>
                        <?php foreach ($periode as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= $p['label_periode'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Instrumen -->
                <div>
                    <label class="block text-sm font-medium mb-1">Instrumen</label>
                    <select name="instrumen" x-model="instrumen" @change="$el.form.submit()" :disabled="!periode"
                        class="w-full block appearance-none border rounded px-3 py-2 rounded-lg shadow-sm truncate"
                        :class="{ 'bg-gray-100 text-gray-400': !periode }" style="width: 550px; height: 40px;"
                        id="instrumen-dropdown">
                        <option value="">Pilih Instrumen</option>
                        <?php foreach ($instrumen as $i): ?>
                            <option value="<?= $i['id'] ?>"><?= $i['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Program -->
                <div>
                    <label class="block text-sm font-medium mb-1">Program</label>
                    <select name="program" x-model="program" @change="$el.form.submit()" :disabled="!instrumen"
                        class="w-64 border rounded px-3 py-2 rounded-lg shadow-sm"
                        :class="{ 'bg-gray-100 text-gray-400': !instrumen }" style="width: 350px; height: 40px;"
                        id="program-dropdown">
                        <option value="" disable selected>Pilih Program</option>
                        <option value="all" <?= ($selected_program_id === 'all') ? 'selected' : '' ?>>Tampilkan Semua
                            Program</option>
                        <?php foreach ($program as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= $p['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>

        <!-- Functions -->
        <div class="flex w-full items-center mt-3 h-10">
            <div class="flex w-1/3 gap-2">
                <a href="<?= base_url('data-capaian/download-excel?periode=' . urlencode($selected_periode_id) . '&instrumen=' . urlencode($selected_instrumen_id) . '&program=' . urlencode($selected_program_id)) ?>"
                    class="py-2 px-3 items-center text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700">
                    Download Excel
                </a>
                <a href="<?= base_url('data-capaian/print-pdf?periode=' . urlencode($selected_periode_id) . '&instrumen=' . urlencode($selected_instrumen_id) . '&program=' . urlencode($selected_program_id)) ?>"
                    class="py-2 px-3 items-center text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700"
                    target="_blank">
                    Print PDF
                </a>
                <button type="button"
                    class="py-2 px-3 items-center text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                    data-hs-overlay="#modal"
                    @click="let params = new URLSearchParams(window.location.search);aksiMode ? params.delete('aksi') : params.set('aksi', '1'); window.location.search = params.toString();">
                    <span x-show="!aksiMode">Edit Data</span>
                    <span x-show="aksiMode"> Edit Selesai</span>
                </button>
            </div>
            <div class="w-1/3"></div>
            <div class="w-1/3 flex justify-end">
                <form class="w-full" action="<?= base_url('/data-capaian') ?>" method="GET">
                    <label for="keyword"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <input type="hidden" name="periode" x-model="periode">
                        <input type="hidden" name="instrumen" x-model="instrumen">
                        <input type="hidden" name="program" x-model="program">
                        <input type="hidden" name="aksi" value="<?= esc($aksiMode ? '1' : '') ?>">
                        <input type="hidden" name="tab" value="<?= esc($activeTab ?? '') ?>">
                        <input name="keyword" x-model="keyword"
                            class="block w-full p-2 ps-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white "
                            placeholder="Search" autocomplete="off">
                        <button type="submit"
                            class="text-white absolute end-[3px] bottom-[5.5px] font-medium rounded-lg text-sm px-4 py-[5px]">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Result table -->
        <div class="my-8 mb-16 flex flex-col">
            <nav class="flex space-x-8 border-b mt-2 mb-4">
                <button @click="tab = 'umum';
                let params = new URLSearchParams(window.location.search);
                params.set('tab', 'umum');
                history.replaceState(null, '', '?' + params.toString());"
                    :class="tab === 'umum' ? 'border-b-2 border-black-500 text-black-600 font-semibold' : 'text-gray-500 hover:text-white-500'"
                    class="px-4 py-2 focus:outline-none">Variabel Tipe 1</button>
                <button @click="tab = 'admen';
                let params = new URLSearchParams(window.location.search);
                params.set('tab', 'admen');
                history.replaceState(null, '', '?' + params.toString());"
                    :class="tab === 'admen' ? 'border-b-2 border-black-500 text-black-600 font-semibold' : 'text-gray-500 hover:text-white-500'"
                    class="px-4 py-2 focus:outline-none">Variabel Tipe 2</button>
            </nav>

            <!-- Tabel Variabel & Sub-variabel -->
            <div x-show="tab === 'umum'" class="transition-all w-full">
                <?php if (!empty($variabel)): ?>
                    <div class="overflow-x-auto overflow-y-auto border rounded-lg">
                        <div class="max-h-[600px] ">
                            <table class="max-w-full border border-gray-300 bg-white text-sm border-collapse">
                                <thead class="bg-gray-100 sticky top-0 z-20 border-b">
                                    <tr>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold"
                                            x-show="aksiMode">Aksi</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">No.</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold"
                                            style="min-width:320px; max-width:600px;">Nama</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold"
                                            style="min-width:100px; max-width:150px;">Target Tahun
                                        </th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Satuan Sasaran
                                        </th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Total Sasaran
                                        </th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Target Sasaran
                                        </th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Pencapaian
                                            (dalam satu sasaran)</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">% Cakupan Riil
                                        </th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">% Sub</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">% Variabel</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">% Program</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Ketercapaian
                                            Target Tahun</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Analisa Akar
                                            Penyebab Masalah</th>
                                        <th class="px-4 py-2">Rencana Tindak Lanjut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border border-gray-300 px-4 py-2">
                                        <td colspan="9" class="font-semibold">
                                            Instrumen:
                                            <?= esc($selected_instrumen_nama) ?>
                                        </td>
                                        <td class="text-center align-middle font-semibold">
                                            <?= esc($selected_instrumen_persen) ?>%
                                        </td>
                                        <td colspan="4"></td>
                                    </tr>
                                    <?php
                                    $last_id_program = null;
                                    $nomor = 1; foreach ($variabel as $v):
                                        if ($last_id_program !== $v['id_program']):
                                            ?>
                                            <tr class="border border-gray-300 px-4 py-2 ">
                                                <td colspan="9">Program:
                                                    <?= esc($v['nama_program']) ?>
                                                </td>
                                                <td colspan="1" class="text-center  align-middle">
                                                    <?= esc($v['persen_program']) ?>%
                                                </td>
                                            </tr>
                                            <?php $last_id_program = $v['id_program'];
                                            $nomor = 1;
                                        endif; ?>
                                        <?php
                                        $has_sub = false; foreach ($sub_variabel as $sv_check) {
                                            if ($sv_check['id_variabel'] == $v['id']) {
                                                $has_sub = true;
                                                break;
                                            }
                                        }
                                        
                                        ?>
                                        <tr class="hover:bg-blue-50">
                                            <td class="border border-gray-300 px-4 py-2" x-show="aksiMode">
                                                <a onclick="openEditVariabelModal('<?= $v['id'] ?>', '<?= htmlspecialchars($v['nama'] ?? '') ?>', '<?= htmlspecialchars($v['target_operator'] ?? '') ?>', '<?= htmlspecialchars($v['target_value'] ?? '') ?>', '<?= htmlspecialchars($v['satuan_sasaran'] ?? '') ?>', '<?= $v['total_sasaran'] ?? '' ?>', '<?= $v['target_sasaran'] ?? '' ?>', '<?= $v['pencapaian'] ?? '' ?>', '<?= $v['cakupan_riil'] ?? '' ?>', '<?= $v['persen_sub_variabel'] ?? '' ?>', '<?= $v['persen_variabel'] ?? '' ?>', '<?= $v['persen_program'] ?? '' ?>', '<?= htmlspecialchars($v['ketercapaian_target'] ?? '') ?>', '<?= htmlspecialchars($v['analisa_akar_penyebab_masalah'] ?? '') ?>', '<?= htmlspecialchars($v['rencana_tindak_lanjut'] ?? '') ?>')"
                                                    class="cursor-pointer">
                                                    <span
                                                        class="py-1 px-2 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-green-600 shadow-sm align-middle hover:bg-gray-50 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-green-600 transition-all text-sm dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                                        <svg class="feather feather-edit mr-2" fill="none" height="16"
                                                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" viewBox="0 0 24 24" width="16"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                        </svg>
                                                        Edit
                                                    </span>
                                                </a>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $has_sub ? '' : $nomor++ ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['nama'] ?>
                                            </td>
                                            <!-- Target Tahun (operator + value) -->
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['target_operator'] . ' ' . intval($v['target_value']) . '%' ?>
                                            </td>
                                            <!-- Satuan Sasaran -->
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['satuan_sasaran'] ?>
                                            </td>
                                            <!-- Total Sasaran -->
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['total_sasaran'] ?>
                                            </td>
                                            <!-- Target Sasaran -->
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['target_sasaran'] ?>
                                            </td>
                                            <!-- Pencapaian -->
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['pencapaian'] ?>
                                            </td>
                                            <!-- % Cakupan Riil -->
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['cakupan_riil'] ?>%
                                            </td>
                                            <!-- % Sub -->
                                            <td class="border border-gray-300 px-4 py-2">
                                            </td>
                                            <!-- % Variabel -->
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['persen_variabel'] ?>%
                                            </td>
                                            <!-- % Program -->
                                            <td class="border border-gray-300 px-4 py-2">

                                            </td>
                                            <!-- Ketercapaian Target Tahun -->
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['ketercapaian_target'] ?>
                                            </td>
                                            <!-- Analisa Akar Penyebab Masalah -->
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['analisa_akar_penyebab_masalah'] ?>
                                            </td>
                                            <!-- Rencana Tindak Lanjut -->
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $v['rencana_tindak_lanjut'] ?>
                                            </td>
                                        </tr>
                                        <?php

                                        foreach ($sub_variabel as $sv): ?>
                                            <?php if ($sv['id_variabel'] == $v['id']): ?>
                                                <tr class="hover:bg-blue-50">
                                                    <td class="border border-gray-300 px-4 py-2" x-show="aksiMode">
                                                        <a onclick="openEditSubVariabelModal('<?= $sv['id'] ?>', '<?= htmlspecialchars($sv['nama'] ?? '') ?>', '<?= htmlspecialchars($sv['target_operator'] ?? '') ?>', '<?= htmlspecialchars($sv['target_value'] ?? '') ?>', '<?= htmlspecialchars($sv['satuan_sasaran'] ?? '') ?>', '<?= $sv['total_sasaran'] ?? '' ?>', '<?= $sv['target_sasaran'] ?? '' ?>', '<?= $sv['pencapaian'] ?? '' ?>', '<?= $sv['cakupan_riil'] ?? '' ?>', '<?= $sv['persen_sub_variabel'] ?? '' ?>', '<?= $sv['persen_variabel'] ?? '' ?>', '<?= $sv['persen_program'] ?? '' ?>', '<?= htmlspecialchars($sv['ketercapaian_target'] ?? '') ?>', '<?= htmlspecialchars($sv['analisa_akar_penyebab_masalah'] ?? '') ?>', '<?= htmlspecialchars($sv['rencana_tindak_lanjut'] ?? '') ?>')"
                                                            class="cursor-pointer">
                                                            <span
                                                                class="py-1 px-2 inline-flex justify-center items-center gap-2 rounded-lg border font-medium bg-white text-green-600 shadow-sm align-middle hover:bg-gray-50 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-green-600 transition-all text-sm dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                                                <svg class="feather feather-edit mr-2" fill="none" height="16"
                                                                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" viewBox="0 0 24 24" width="16"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                                </svg>
                                                                Edit
                                                            </span>
                                                        </a>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $nomor++ ?>
                                                    </td>
                                                    <td span class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['nama'] ?>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['target_operator'] . ' ' . intval($sv['target_value']) . '%' ?>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['satuan_sasaran'] ?>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['total_sasaran'] ?>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['target_sasaran'] ?>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['pencapaian'] ?>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['cakupan_riil'] ?>%
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['persen_sub_variabel'] ?>%
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['persen_variabel'] ?>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['persen_program'] ?>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['ketercapaian_target'] ?>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['analisa_akar_penyebab_masalah'] ?>
                                                    </td>
                                                    <td class="border border-gray-300 px-4 py-2">
                                                        <?= $sv['rencana_tindak_lanjut'] ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- No Data -->
                    <div class="my-2 flex flex-col">
                        <div
                            class="min-h-[250px] max-h-[350px] border rounded-lg shadow text-gray-500 text-center italic flex items-center justify-center bg-white px-8">
                            <span class="block w-full text-center text-lg font-medium">Tidak ada data yang tersedia.</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Tabel Variabel Admen -->
            <div x-show="tab === 'admen'" class="transition-all">
                <?php if (!empty($variabel_admen)): ?>
                    <div class="overflow-x-auto border rounded-lg">
                        <div class="max-h-[600px] overflow-y-auto">
                            <table class="min-w-full border border-gray-300 bg-white text-sm">
                                <thead class="bg-gray-100 sticky top-0 z-20">
                                    <tr>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold"
                                            x-show="aksiMode">Aksi</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">No.</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Nama</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold"
                                            style="min-width:320px;max-width:600px;">Definisi Operasional</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold"
                                            style="min-width:200px; max-width:400px;">Skala Nilai 0
                                        </th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold"
                                            style="min-width:200px; max-width:400px;">Skala Nilai 4
                                        </th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold"
                                            style="min-width:200px; max-width:400px;">Skala Nilai 7
                                        </th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold"
                                            style="min-width:200px; max-width:400px;">Skala Nilai 10
                                        </th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Nilai</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Ketercapaian
                                            Target Tahun</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Analisa Akar
                                            Penyebab Masalah</th>
                                        <th class="order border-gray-300 px-4 py-2 text-left font-semibold">Rencana Tindak
                                            Lanjut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $last_id_program = null;
                                    $program_nilai = 0;
                                    $totalNilaiAdmen = 0;
                                    $totalRows = count($variabel_admen);
                                    $nomor = 1; foreach ($variabel_admen as $i => $va):
                                        if ($last_id_program !== $va['id_program']):
                                            // Jika ini bukan baris pertama, tampilkan subtotal program sebelumnya
                                            if ($last_id_program !== null):
                                                ?>
                                                <tr class="border border-gray-300 px-4 py-2 bg-gray-100">
                                                    <td colspan="6">Jumlah Nilai Kinerja Program
                                                        <?= esc($last_nama_program) ?>
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <?= esc(number_format($program_nilai)) ?>
                                                    </td>
                                                    <td colspan="4"></td>
                                                </tr>
                                                <?php
                                                $totalNilaiAdmen += $program_nilai;
                                                $program_nilai = 0;
                                                $nomor = 1;
                                            endif;
                                            ?>
                                            <tr class="border border-gray-300 px-4 py-2 bg-gray-50">
                                                <td colspan="11">Program:
                                                    <?= esc($va['nama_program']) ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $last_id_program = $va['id_program'];
                                            $last_nama_program = $va['nama_program'];
                                        endif;
                                        ?>

                                        <!-- Row isi variabel_admen -->
                                        <tr class="hover:bg-blue-50">
                                            <!-- kolom aksi dan data -->
                                            <td class="border border-gray-300 px-4 py-2" x-show="aksiMode">...</td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= $nomor++ ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= esc($va['nama']) ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= esc($va['definisi_operasional']) ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= esc($va['skala_nilai_0']) ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= esc($va['skala_nilai_4']) ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= esc($va['skala_nilai_7']) ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= esc($va['skala_nilai_10']) ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= esc($va['nilai']) ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= esc($va['ketercapaian_target']) ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= esc($va['analisa_akar_penyebab_masalah']) ?>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <?= esc($va['rencana_tindak_lanjut']) ?>
                                            </td>
                                        </tr>

                                        <?php
                                        $program_nilai += floatval($va['nilai']);
                                        if ($i === $totalRows - 1):
                                            $totalNilaiAdmen += $program_nilai;
                                            ?>
                                            <tr class="border border-gray-300 px-4 py-2 bg-gray-100">
                                                <td colspan="6">Jumlah Nilai Kinerja Program
                                                    <?= esc($va['nama_program']) ?>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <?= esc(number_format($program_nilai)) ?>
                                                </td>
                                                <td colspan="4"></td>
                                            </tr>
                                            <?php
                                        endif;
                                    endforeach;
                                    ?>

                                    <!-- Total Semua Program -->
                                    <tr class="border border-gray-300 px-4 py-2 bg-blue-100 font-semibold">
                                        <td colspan="6">Total Nilai Kinerja Administrasi dan Manajemen</td>
                                        <td class="px-4 py-2">
                                            <?= esc(number_format($totalNilaiAdmen)) ?>
                                        </td>
                                        <td colspan="4"></td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- No Data -->
                    <div class="my-2 flex flex-col">
                        <div
                            class="min-h-[250px] max-h-[350px] border rounded-lg shadow text-gray-500 text-center italic flex items-center justify-center bg-white px-8">
                            <span class="block w-full text-center text-lg font-medium">Tidak ada data yang tersedia.</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal Update Variabel -->
        <div id="modal-variabel-update"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]"
            data-hs-overlay-keyboard="true">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                    <!-- Header -->
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 class="font-bold text-gray-800 dark:text-white">Edit Variabel</h3>
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-variabel-update" onclick="reset()">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <form id="form-UpdateVariabelModal" method="POST" action="<?= base_url('data-capaian/update') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" value="variabel">
                        <input type="hidden" name="id" id="var-id">
                        <div class="p-4 overflow-y-auto space-y-6">

                            <!-- Nama Variabel -->
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Nama Variabel</label>
                                <input type="text" name="nama" id="var-nama"
                                    class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                    <?= ($role == 1000 ? '' : 'readonly') ?>>
                            </div>

                            <!-- 1 baris: Target Operasi, Target Value, Ketercapaian Target Tahun -->
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                                <!-- Target Tahun -->
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Target Tahun</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="col-span-1">
                                            <select name="target_operator" id="var-target-operator"
                                                class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                                placeholder="Persen Target" <?= ($role == 1000 ? '' : 'readonly') ?>>
                                                <option value="<">
                                                    Pilih Target Operasi
                                                <option value=">">></option>
                                                <option value="=">=</option>
                                                <option value="≥">≥</option>
                                                <option value="≤">≤</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <input type="text" name="target_value" id="var-target-value"
                                                class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                                placeholder="Persen Target" <?= ($role == 1000 ? '' : 'readonly') ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Ketercapaian Target
                                        Tahun</label>
                                    <input type="text" name="ketercapaian_target" id="var-ketercapaian-target"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Satuan Sasaran</label>
                                    <input type="text" name="satuan_sasaran" id="var-satuan-sasaran"
                                        class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                        <?= ($role == 1000 ? '' : 'readonly') ?>>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Total Sasaran</label>
                                    <input type="number" name="total_sasaran" id="var-total-sasaran"
                                        class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                        <?= ($role == 1000 ? '' : 'readonly') ?>>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Target Sasaran</label>
                                    <input type="number" name="target_sasaran" id="var-target-sasaran"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Pencapaian (dalam satu
                                        sasaran)</label>
                                    <input type="number" name="pencapaian" id="var-pencapaian"
                                        class="block w-full border rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">% Cakupan Riil</label>
                                    <input type="number" name="cakupan_riil" id="var-cakupan-riil" step="0.01"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                            </div>

                            <!-- 1 baris: % Sub, % Variabel, % Program -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">% Sub</label>
                                    <input type="number" name="persen_sub_variabel" id="var-persen-sub" step="0.01"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">% Variabel</label>
                                    <input type="number" name="persen_variabel" id="var-persen-variabel" step="0.01"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">% Program</label>
                                    <input type="number" name="persen_program" id="var-persen-program" step="0.01"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                            </div>

                            <!-- Analisa dan Rencana -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Analisa Akar Penyebab
                                        Masalah</label>
                                    <textarea name="analisa_akar_penyebab_masalah" id="var-analisa"
                                        class="block w-full border rounded-lg px-3 py-2"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Rencana Tindak
                                        Lanjut</label>
                                    <textarea name="rencana_tindak_lanjut" id="var-rencana"
                                        class="block w-full border rounded-lg px-3 py-2"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                            <button type="button"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800"
                                data-hs-overlay="#modal-variabel-update" onclick="reset()">
                                Close
                            </button>
                            <button type="submit"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Update Sub-Variabel -->
        <div id="modal-sub-variabel-update"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]"
            data-hs-overlay-keyboard="true">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 class="font-bold text-gray-800 dark:text-white">Edit Sub-Variabel</h3>
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-sub-variabel-update" onclick="reset()">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <form id="form-UpdateSubVariabelModal" method="POST"
                        action="<?= base_url('data-capaian/update') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" value="sub_variabel">
                        <input type="hidden" name="id" id="subvar-id">
                        <div class="p-4 overflow-y-auto space-y-6">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Nama Sub-Variabel</label>
                                <input type="text" name="nama" id="subvar-nama"
                                    class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                    <?= ($role == 1000 ? '' : 'readonly') ?>>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Target Tahun</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div class="col-span-1">
                                            <select name="target_operator" id="subvar-target-operator"
                                                class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                                placeholder="Persen Target" <?= ($role == 1000 ? '' : 'readonly') ?>>
                                                <option value="<">
                                                    << /option>
                                                <option value=">">></option>
                                                <option value="=">=</option>
                                                <option value="≥">≥</option>
                                                <option value="≤">≤</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <input type="text" name="target_value" id="subvar-target-value"
                                                class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                                placeholder="Persen Target" <?= ($role == 1000 ? '' : 'readonly') ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Ketercapaian Target
                                        Tahun</label>
                                    <input type="text" name="ketercapaian_target" id="subvar-ketercapaian-target"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Satuan Sasaran</label>
                                    <input type="text" name="satuan_sasaran" id="subvar-satuan-sasaran"
                                        class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                        <?= ($role == 1000 ? '' : 'readonly') ?>>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Total Sasaran</label>
                                    <input type="number" name="total_sasaran" id="subvar-total-sasaran"
                                        class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                        <?= ($role == 1000 ? '' : 'readonly') ?>>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Target Sasaran</label>
                                    <input type="number" name="target_sasaran" id="subvar-target-sasaran"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Pencapaian (dalam satu
                                        sasaran)</label>
                                    <input type="number" name="pencapaian" id="subvar-pencapaian"
                                        class="block w-full border rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">% Cakupan Riil</label>
                                    <input type="number" name="cakupan_riil" id="subvar-cakupan-riil" step="0.01"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">% Sub</label>
                                    <input type="number" name="persen_sub_variabel" id="subvar-persen-sub" step="0.01"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">% Variabel</label>
                                    <input type="number" name="persen_variabel" id="subvar-persen-variabel" step="0.01"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">% Program</label>
                                    <input type="number" name="persen_program" id="subvar-persen-program" step="0.01"
                                        class="block w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500"
                                        readonly>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Analisa Akar Penyebab
                                        Masalah</label>
                                    <textarea name="analisa_akar_penyebab_masalah" id="subvar-analisa"
                                        class="block w-full border rounded-lg px-3 py-2"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Rencana Tindak
                                        Lanjut</label>
                                    <textarea name="rencana_tindak_lanjut" id="subvar-rencana"
                                        class="block w-full border rounded-lg px-3 py-2"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Footer -->
                        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                            <button type="button"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800"
                                data-hs-overlay="#modal-sub0variabel-update" onclick="reset()">
                                Close
                            </button>
                            <button type="submit"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Modal Update Variabel Admen -->
        <div id="modal-variabel-admen-update"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]"
            data-hs-overlay-keyboard="true">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 class="font-bold text-gray-800 dark:text-white">Ubah Variabel</h3>
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-variabel-admen-update" onclick="reset()">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <form id="form-UpdateVariabelAdmenModal" method="POST"
                        action="<?= base_url('data-capaian/update') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="type" value="variabel_admen">
                        <div class="p-4 overflow-y-auto space-y-6">
                            <input type="hidden" name="id" id="admen-id">

                            <div>
                                <label for="var-nama"
                                    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Nama</label>
                                <input type="text" name="nama" id="admen-nama" required
                                    class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                    <?= ($role == 1000 ? '' : 'readonly') ?> />
                            </div>
                            <div>
                                <label for="var-definisi-operasional"
                                    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Definisi
                                    Operasional</label>
                                <textarea name="definisi_operasional" id="admen-definisi-operasional" rows="2"
                                    class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                    <?= ($role == 1000 ? '' : 'readonly') ?>></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="var-skala-nilai-0"
                                        class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Skala
                                        Nilai
                                        0</label>
                                    <input type="text" name="skala_nilai_0" id="admen-skala-nilai-0"
                                        class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                        <?= ($role == 1000 ? '' : 'readonly') ?> />
                                </div>
                                <div>
                                    <label for="var-skala-nilai-4"
                                        class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Skala
                                        Nilai
                                        4</label>
                                    <input type="text" name="skala_nilai_4" id="admen-skala-nilai-4"
                                        class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                        <?= ($role == 1000 ? '' : 'readonly') ?> />
                                </div>
                                <div>
                                    <label for="var-skala-nilai-7"
                                        class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Skala
                                        Nilai
                                        7</label>
                                    <input type="text" name="skala_nilai_7" id="admen-skala-nilai-7"
                                        class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                        <?= ($role == 1000 ? '' : 'readonly') ?> />
                                </div>
                                <div>
                                    <label for="var-skala-nilai-10"
                                        class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Skala
                                        Nilai
                                        10</label>
                                    <input type="text" name="skala_nilai_10" id="admen-skala-nilai-10"
                                        class="block w-full border rounded-lg px-3 py-2 <?= ($role == 1000 ? '' : 'bg-gray-100 text-gray-500') ?>"
                                        <?= ($role == 1000 ? '' : 'readonly') ?> />
                                </div>
                            </div>
                            <div>
                                <label for="var-nilai"
                                    class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-200">Nilai</label>
                                <input type="number" name="nilai" id="admen-nilai"
                                    class="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-green-600 outline-none dark:bg-gray-700 dark:text-white" />
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Analisa Akar
                                        Penyebab
                                        Masalah</label>
                                    <textarea name="analisa_akar_penyebab_masalah" id="admen-analisa"
                                        class="block w-full border rounded-lg px-3 py-2"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1 dark:text-white">Rencana Tindak
                                        Lanjut</label>
                                    <textarea name="rencana_tindak_lanjut" id="admen-rencana"
                                        class="block w-full border rounded-lg px-3 py-2"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                                <button type="button"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800"
                                    data-hs-overlay="#modal-variabel-admen-update" onclick="reset()">
                                    Close
                                </button>
                                <button type="submit"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700">Simpan</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>


        <script>
            function openEditVariabelModal(
                id, nama, target_operator, target_value, satuan_sasaran, total_sasaran, target_sasaran, pencapaian,
                cakupan_riil, persen_sub_variabel, persen_variabel, persen_program,
                ketercapaian_target, analisa, rencana
            ) {
                console.log("target_operator param:", target_operator, "| typeof:", typeof target_operator, "| all params:", arguments);
                document.getElementById('var-id').value = id;
                document.getElementById('var-nama').value = nama;
                document.getElementById('var-target-operator').value = (target_operator ?? '').trim();
                document.getElementById('var-target-value').value = target_value;
                document.getElementById('var-satuan-sasaran').value = satuan_sasaran;
                document.getElementById('var-total-sasaran').value = total_sasaran;
                document.getElementById('var-target-sasaran').value = target_sasaran;
                document.getElementById('var-pencapaian').value = pencapaian;
                document.getElementById('var-cakupan-riil').value = cakupan_riil;
                document.getElementById('var-persen-sub').value = persen_sub_variabel;
                document.getElementById('var-persen-variabel').value = persen_variabel;
                document.getElementById('var-persen-program').value = persen_program;
                document.getElementById('var-ketercapaian-target').value = ketercapaian_target;
                document.getElementById('var-analisa').value = analisa;
                document.getElementById('var-rencana').value = rencana;

                HSOverlay.open('#modal-variabel-update');
            }

            function openEditSubVariabelModal(
                id, nama, target_operator, target_value, satuan_sasaran, total_sasaran, target_sasaran, pencapaian,
                cakupan_riil, persen_sub_variabel, persen_variabel, persen_program,
                ketercapaian_target, analisa, rencana
            ) {
                console.log("target_operator param:", target_operator, "| typeof:", typeof target_operator, "| all params:", arguments);
                document.getElementById('subvar-id').value = id;
                document.getElementById('subvar-nama').value = nama;
                document.getElementById('subvar-target-operator').value = (target_operator ?? '').trim();
                document.getElementById('subvar-target-value').value = target_value;
                document.getElementById('subvar-satuan-sasaran').value = satuan_sasaran;
                document.getElementById('subvar-total-sasaran').value = total_sasaran;
                document.getElementById('subvar-target-sasaran').value = target_sasaran;
                document.getElementById('subvar-pencapaian').value = pencapaian;
                document.getElementById('subvar-cakupan-riil').value = cakupan_riil;
                document.getElementById('subvar-persen-sub').value = persen_sub_variabel;
                document.getElementById('subvar-persen-variabel').value = persen_variabel;
                document.getElementById('subvar-persen-program').value = persen_program;
                document.getElementById('subvar-ketercapaian-target').value = ketercapaian_target;
                document.getElementById('subvar-analisa').value = analisa;
                document.getElementById('subvar-rencana').value = rencana;

                HSOverlay.open('#modal-sub-variabel-update');
            }

            function openEditVariabelAdmenModal(
                id, nama, definisi_operasional, skala_nilai_0, skala_nilai_4, skala_nilai_7, skala_nilai_10, nilai, analisa, rencana,
            ) {
                console.log('mulai buka modal admen');
                document.getElementById('admen-id').value = id;
                document.getElementById('admen-nama').value = nama;
                document.getElementById('admen-definisi-operasional').value = definisi_operasional;
                document.getElementById('admen-skala-nilai-0').value = skala_nilai_0;
                document.getElementById('admen-skala-nilai-4').value = skala_nilai_4;
                document.getElementById('admen-skala-nilai-7').value = skala_nilai_7;
                document.getElementById('admen-skala-nilai-10').value = skala_nilai_10;
                document.getElementById('admen-nilai').value = nilai;
                document.getElementById('admen-analisa').value = analisa;
                document.getElementById('admen-rencana').value = rencana;

                HSOverlay.open('#modal-variabel-admen-update');
            }
        </script>

        <?= $this->endSection(); ?>
    </div>