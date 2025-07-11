<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="container-sm">
    <div class="w-full px-4 lg:mt-8 sm:px-6 md:px-8 lg:pl-72 z-[0]">
        <h1 class="!text-4xl mb-4 font-semibold text-slate-800">Daftar Program</h1>
        <h2 class="!text-3xl font-regular text-slate-800">Periode:
            <?= esc($label_periode) ?>
        </h2>

        <!-- Search & Add Button -->
        <div class="flex mt-8 h-10 justify-between">
            <div class="flex h-full gap-6 w-6/12">
                <form class="w-2/3" action="<?= base_url('/data-manajer/program') ?>" method="GET">
                    <label for="keyword"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <input type="hidden" name="periode" value="<?= esc($selected_periode_id) ?>">
                        <input type="text" name="keyword" id="search" value="<?= esc($keyword ?? '') ?>"
                            class="block w-full p-2 ps-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-white"
                            placeholder="cari program" autocomplete="off">
                        <button type="submit" class="absolute end-[3px] bottom-[5.5px]">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="none"
                                viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
            <button type="button"
                class="py-2 px-3 mr-8 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700"
                data-hs-overlay="#modal-program-add">
                Tambah
            </button>
        </div>

        <!-- Table -->
        <div class="my-4 flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="border rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-start text-m font-medium text-gray-800">Nama
                                        Program</th>
                                    <th class="py-3 text-center text-m font-medium w-1/5 text-gray-800">Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php $last_id_instrumen = null;
                                if (!empty($program)):
                                    foreach ($program as $p):
                                        if ($last_id_instrumen !== $p['id_instrumen']): ?>
                                            <tr class="bg-slate-200 font-bold text-slate-800">
                                                <td class="whitespace-nowrap text-sm font-medium text-gray-800" colspan="14">
                                                    <?= esc($p['nama_instrumen']) ?>
                                                </td>
                                            </tr>
                                            <?php $last_id_instrumen = $p['id_instrumen'];
                                        endif; ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">
                                                <?= esc($p['nama']) ?>
                                            </td>
                                            <td class="py-4 whitespace-nowrap text-center text-sm font-medium">
                                                <div class="flex items-center justify-center gap-6">
                                                    <a onclick="openEditProgramModal('<?= $p['id'] ?>', '<?= esc($p['nama']) ?>', '<?= esc($p['id_instrumen']) ?>')"
                                                        class="cursor-pointer">
                                                        <span
                                                            class="py-1 px-3 inline-flex items-center gap-2 rounded-lg border font-medium bg-white text-green-600 shadow-sm hover:bg-gray-50 hover:text-green-700 text-sm">
                                                            <svg class="feather feather-edit" fill="none" height="16"
                                                                stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                                                width="16">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                                <path
                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                                            </svg>
                                                            Edit
                                                        </span>
                                                    </a>
                                                    <a onclick="openConfirmModal('<?= $p['id'] ?>')" class="cursor-pointer">
                                                        <span
                                                            class="py-1 px-3 inline-flex items-center gap-2 rounded-lg border font-medium bg-white text-red-600 shadow-sm hover:bg-gray-50 hover:text-red-700 text-sm">
                                                            <svg class="flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                                                fill="currentColor" width="16" height="16"
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
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">Belum ada data program.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Program Update -->
        <div id="modal-program-update"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]"
            data-hs-overlay-keyboard="true">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 id="modalLabel" class="font-bold text-gray-800 dark:text-white">
                            Edit Program
                        </h3>
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-program-update" onclick="reset()">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-x-auto">
                        <form id="form-UpdateProgramModal" action="<?= site_url('/data-manajer/program/update') ?>"
                            method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" id="program-update-id">
                            <div class="mb-4">
                                <label class="block mb-1">Nama Program</label>
                                <input type="text" name="nama" id="program-update-nama"
                                    class="w-full border px-2 py-1 rounded" required>
                            </div>
                            <div class="mb-4">
                                <label class="block mb-1">Instrumen</label>
                                <select name="id_instrumen" id="program-update-instrumen"
                                    class="w-full border px-2 py-1 rounded" required>
                                    <option value="" disabled selected>Pilih Instrumen</option>
                                    <?php foreach ($instrumen as $i): ?>
                                        <option value="<?= esc($i['id']) ?>"><?= esc($i['nama']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                        <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-program-update" onclick="reset()">
                            Close
                        </button>
                        <button type="submit" form="form-UpdateProgramModal"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Program Add -->
        <div id="modal-program-add"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]"
            data-hs-overlay-keyboard="true">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 id="modalLabel" class="font-bold text-gray-800 dark:text-white">
                            Buat Program Baru
                        </h3>
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-program-add" onclick="reset()">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-x-auto">
                        <form id="form-AddProgramModal" action="<?= site_url('/data-manajer/program/add') ?>"
                            method="post">
                            <?= csrf_field() ?>
                            <div class="mb-4">
                                <label class="block mb-1">Nama Program</label>
                                <input type="text" name="nama" id="nama" class="w-full border px-2 py-1 rounded"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label class="block mb-1">Instrumen</label>
                                <select name="id_instrumen" id="program-update-instrumen"
                                    class="w-full border px-2 py-1 rounded" required>
                                    <option value="" disabled selected>Pilih Instrumen</option>
                                    <?php foreach ($instrumen as $i): ?>
                                        <option value="<?= esc($i['id']) ?>"><?= esc($i['nama']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                        <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-program-add" onclick="reset()">
                            Close
                        </button>
                        <button type="submit" form="form-AddProgramModal"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Confirm -->
        <div id="modal-confirm"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 class="font-bold text-gray-800 dark:text-white">
                            Hapus Program
                        </h3>
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-confirm">
                            <span class="sr-only">Close</span>
                            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 overflow-y-auto">
                        <p class="mt-1 text-gray-800 dark:text-gray-400">
                            Menghapus Program ini akan menghapus data turunan di bawahnya. Apakah Anda yakin?
                        </p>
                    </div>
                    <form id="delete-form" method="post" class="inline">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id_program" id="delete-id" value="">
                        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                            <button type="button" class="py-2 px-3 rounded bg-blue-100"
                                data-hs-overlay="#modal-confirm">
                                Batalkan
                            </button>
                            <button type="button"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 cursor-pointer"
                                onclick="proceedToReauth()">
                                Lanjutkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Re-auth -->
        <div id="modal-reauth"
        class="hs-overlay hidden fixed top-0 start-0 z-[90] w-full h-full overflow-x-hidden overflow-y-auto bg-black/50">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500  transform scale-95 transition-all duration-300 ease-out bg-white max-w-sm w-full m-3 sm:mx-auto rounded-xl shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                <form action="<?= site_url('/data-manajer/program/reauthDelete') ?>" method="post" class="p-6 space-y-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_program" id="reauth-id">

                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Verifikasi Password</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Masukkan password Anda untuk
                            melanjutkan.</p>
                    </div>

                    <input type="password" name="password" placeholder="Password"
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>

                    <div class="flex justify-between items-center gap-4 pt-2">
                        <button type="button"
                            class="w-full py-2 text-sm rounded bg-gray-100 text-gray-800 hover:bg-gray-200"
                            data-hs-overlay="#modal-reauth">
                            Batal
                        </button>
                        <div class="w-px h-6 bg-gray-200 dark:bg-gray-600"></div>
                        <button type="submit"
                            class="w-full py-2 text-sm rounded bg-red-600 text-white hover:bg-red-700">
                            Konfirmasi
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <script>
            function openEditProgramModal(id, nama, id_instrumen) {
                document.getElementById('program-update-id').value = id;
                document.getElementById('program-update-nama').value = nama;
                document.getElementById('program-update-instrumen').value = id_instrumen;
                HSOverlay.open('#modal-program-update');
            }

            function reset() {
                document.getElementById('form-UpdateProgramModal').reset();
                document.getElementById('form-AddProgramModal').reset();
            }

            function openConfirmModal(id) {
                document.getElementById('delete-id').value = id;
                document.getElementById('reauth-id').value = id;
                HSOverlay.open('#modal-confirm');
            }

            function proceedToReauth() {
                const confirmModal = document.getElementById('modal-confirm');
                const reauthModal = document.getElementById('modal-reauth');

                HSOverlay.close(confirmModal);
                setTimeout(() => {
                    HSOverlay.open(reauthModal);
                }, 300);
            }
        </script>
    </div>
</div>

<?= $this->endSection(); ?>