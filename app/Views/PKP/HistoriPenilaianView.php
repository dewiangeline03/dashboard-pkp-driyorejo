<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('entry_at') && session()->getFlashdata('entry_user') && session()->has('excelData')): ?>
    <?= $this->include('components/modalReplaceData') ?>
<?php endif ?>

<div class="container-sm">
    <!-- Content -->
    <div class="w-full px-4 lg:mt-8 sm:px-6 md:px-8 lg:pl-72 z-[0]">
        <h1 class="!text-4xl mb-8 font-semibold text-slate-800">Histori Penilaian</h1>
        <form method="get" action="" class="flex items-end gap-4 mb-4">
            <div class="flex flex-col">
                <label for="from" class="text-sm font-medium text-gray-700 mb-1">From</label>
                <input type="date" id="from" name="from" value="<?= esc($_GET['from'] ?? '') ?>" class="border rounded p-2 w-48" />
            </div>
            <div class="flex flex-col">
                <label for="to" class="text-sm font-medium text-gray-700 mb-1">To</label>
                <input type="date" id="to" name="to" value="<?= esc($_GET['to'] ?? '') ?>" class="border rounded p-2 w-48" />
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-[25px]">
                    Filter
                </button>
            </div>
        </form>
        <div class="my-6 flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="border rounded-lg overflow-hidden dark:border-gray-700">
                        <table
                            class="min-w-full divide-y table-auto overflow-hidden divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Entry
                                        ID</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">User
                                        Entry</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                        Nama Indikator</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                        Tanggal Input </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($histori as $data): ?>
                                    <tr>
                                        <td class="px-4 py-2 text-left max-w-[200px] whitespace-normal text-sm text-gray-800">
                                            <?= $data['id'] ?>
                                        </td>
                                        <td class="px-4 py-2 text-center max-w-[100px] whitespace-normal text-sm text-gray-800">
                                            <?= $data['user_entry'] ?>
                                        </td>
                                        <td class="px-6 py-4 text-left max-w-[300px] whitespace-normal text-sm text-gray-800">
                                            <?= $data['nama_indikator'] ?>
                                        </td>
                                        <td class="px-4 py-2 text-center max-w-[200px] whitespace-normal text-sm text-gray-800">
                                            <?= date('d-M-Y H:i:s', strtotime($data['created_at'])) ?>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-normal text-sm">
                                            <div class="flex items-center justify-center gap-6">
                                                <a class="cursor-pointer text-green-600" data-id="<?= $data['id'] ?>"
                                                    data-user-entry="<?= htmlspecialchars($data['user_entry'] ?? '', ENT_QUOTES) ?>"
                                                    data-tingkat-indikator="<?= htmlspecialchars($data['tingkat_indikator'] ?? '', ENT_QUOTES) ?>"
                                                    data-nama-indikator="<?= htmlspecialchars($data['nama_indikator'] ?? '', ENT_QUOTES) ?>"
                                                    data-periode="<?= htmlspecialchars($data['periode'] ?? '', ENT_QUOTES) ?>"
                                                    data-date="<?= $data['created_at'] ?? '' ?>"
                                                    data-sebelum="<?= htmlspecialchars(json_encode(json_decode($data['data_sebelum'], true)), ENT_QUOTES, 'UTF-8') ?>"
                                                    data-sesudah="<?= htmlspecialchars(json_encode(json_decode($data['data_sesudah'], true)), ENT_QUOTES, 'UTF-8') ?>"
                                                    onclick="openDetailModal(this)">
                                                    <span
                                                        class="py-1 px-3 inline-flex items-center gap-2 rounded-lg border font-medium bg-white text-green-600 shadow-sm hover:bg-gray-50 hover:text-green-700 text-sm">
                                                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z" />
                                                            <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
                                                        </svg>
                                                        Detail
                                                    </span>
                                                </a>
                                                <a class="cursor-pointer text-red-600" data-id="<?= $data['id'] ?>"
                                                    onclick="confirmModal(this.dataset.id)">
                                                    <span
                                                        class="py-1 px-3 inline-flex items-center gap-2 rounded-lg border font-medium bg-white text-red-600 shadow-sm hover:bg-gray-50 hover:text-red-700 text-sm">
                                                        <svg class="flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                                            fill="currentColor" width="16" height="14"
                                                            viewBox="0 0 128 128">
                                                            <path
                                                                d="M 49 1 C 47.34 1 46 2.34 46 4 C 46 5.66 47.34 7 49 7 L 79 7 C 80.66 7 82 5.66 82 4 C 82 2.34 80.66 1 79 1 L 49 1 z M 24 15 C 16.83 15 11 20.83 11 28 C 11 35.17 16.83 41 24 41 L 101 41 L 101 104 C 101 113.37 93.37 121 84 121 L 44 121 C 34.63 121 27 113.37 27 104 L 27 52 C 27 50.34 25.66 49 24 49 C 22.34 49 21 50.34 21 52 L 21 104 C 21 116.68 31.32 127 44 127 L 84 127 C 96.68 127 107 116.68 107 104 L 107 40.640625 C 112.72 39.280625 117 34.14 117 28 C 117 20.83 111.17 15 104 15 L 24 15 z M 24 21 L 104 21 C 107.86 21 111 24.14 111 28 C 111 31.86 107.86 35 104 35 L 24 35 C 20.14 35 17 31.86 17 28 C 17 24.14 20.14 21 24 21 z M 50 55 C 48.34 55 47 56.34 47 58 L 47 104 C 47 105.66 48.34 107 50 107 C 51.66 107 53 105.66 53 104 L 53 58 C 53 56.34 51.66 55 50 55 z M 78 55 C 76.34 55 75 56.34 75 58 L 75 104 C 75 105.66 76.34 107 78 107 C 79.66 107 81 105.66 81 104 L 81 58 C 81 56.34 79.66 55 78 55 z" />
                                                        </svg>
                                                        Hapus
                                                    </span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?= $pager->links('data', 'new_template') ?>
    </div>
    <!-- Detail Modal -->
    <div id="detail-modal"
        class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div
                class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                    <h3 class="font-bold text-gray-800 dark:text-white">
                        Detail Perubahan
                    </h3>
                    <button type="button"
                        class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                        data-hs-overlay="#detail-modal">
                        <span class="sr-only">Close</span>
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
                <form id="form-detailPerubahan" method="POST" action="<?= base_url('histori-penilaian/restore') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="detail-id">
                    <div class="p-4 overflow-y-auto mb-6">
                        <table class="min-w-full">
                            <tbody>
                                <tr>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">ID Entry</td>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">:</td>
                                    <td id="detail-id-text" name="id" class="pr-4 py-1 whitespace-nowrap text-xs text-start lg:text-sm text-gray-500">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">User Entry</td>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">:</td>
                                    <td id="detail-user-entry" class="pr-4 py-1 whitespace-nowrap text-xs text-start lg:text-sm text-gray-500">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">Tanggal Entry</td>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">:</td>
                                    <td id="detail-date" class="pr-4 py-1 whitespace-normal text-xs text-start lg:text-sm text-gray-500">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">Tingkat Indikator</td>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">:</td>
                                    <td id="detail-tingkat-indikator" class="pr-4 py-1 whitespace-normal text-xs text-start lg:text-sm text-gray-500">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">Nama Indikator</td>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">:</td>
                                    <td id="detail-nama-indikator" class="pr-4 py-1 whitespace-normal text-xs text-start lg:text-sm text-gray-500">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">Periode</td>
                                    <td class="pr-4 py-1 whitespace-normal text-xs font-medium lg:text-sm text-gray-500">:</td>
                                    <td id="detail-periode" class="pr-4 py-1 whitespace-normal text-xs text-start lg:text-sm text-gray-500">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="h-4"></div>
                        <!-- Tabel Detail -->
                        <div class="border rounded-lg shadow-sm overflow-hidden">
                            <table
                                class="min-w-full divide-y rounded-lg shadow-sm table-auto overflow-hidden divide-gray-200 dark:divide-gray-700">
                                <thead class="divide-y divide-gray-200">
                                    <tr>
                                        <th colspan="3"
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                            Perubahan Data</th>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                            Kolom
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                            Lama</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                            Baru</th>
                                    </tr>
                                </thead>
                                <tbody id=detail-json-body class="divide-y divide-gray-200">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                        <button type="submit"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700">
                            Pulihkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirm Modal -->
    <div id="confirm-modal" class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                    <h3 class="font-bold text-gray-800 dark:text-white">
                        Hapus Data
                    </h3>
                    <button type="button" class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#confirm-modal">
                        <span class="sr-only">Close</span>
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <p class="mt-1 text-gray-800 dark:text-gray-400">
                        Apakah anda yakin akan menghapus data ini ?
                    </p>
                </div>
                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#confirm-modal">
                        Cancel
                    </button>
                    <a id="delete-button" href="" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 cursor-pointer">
                        Lanjutkan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openDetailModal(el) {
        // Ambil field tetap
        document.getElementById('detail-id').value = el.dataset.id;
        document.getElementById('detail-id-text').textContent = el.dataset.id;
        document.getElementById('detail-user-entry').textContent = el.dataset.userEntry;
        document.getElementById('detail-tingkat-indikator').textContent = el.dataset.tingkatIndikator;
        document.getElementById('detail-nama-indikator').textContent = el.dataset.namaIndikator;
        document.getElementById('detail-periode').textContent = el.dataset.periode;
        document.getElementById('detail-date').textContent = el.dataset.date;

        // Parse JSON data
        const sebelum = JSON.parse(el.dataset.sebelum || '{}');
        const sesudah = JSON.parse(el.dataset.sesudah || '{}');

        const tbody = document.getElementById('detail-json-body');
        tbody.innerHTML = ''; // clear isian sebelumnya

        // Loop dan tampilkan perbedaan
        for (const key in sesudah) {
            const oldVal = sebelum[key] ?? '-';
            const newVal = sesudah[key];

            if (oldVal !== newVal) {
                const row = `
                <tr>
                    <td class="px-6 py-4 text-left whitespace-normal text-xs lg:text-sm text-gray-800">
                        ${key}
                    </td>
                    <td class="px-6 py-4 text-center whitespace-normal text-xs lg:text-sm text-red-600">
                        ${oldVal}
                    </td>
                    <td class="px-6 py-4 text-center whitespace-normal text-xs lg:text-sm text-green-600">
                        ${newVal}
                    </td>
                </tr>`;
                tbody.innerHTML += row;
            }
        }

        HSOverlay.open('#detail-modal');
    }

    function confirmModal(id) {
        document.getElementById('delete-button').setAttribute('href', '/histori-penilaian/delete/' + id)
        HSOverlay.open('#confirm-modal');
    }
</script>
<?= $this->endSection('content'); ?>