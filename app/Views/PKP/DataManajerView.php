<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>


<div class="container-sm" x-data="{ selectedPeriode: null }">
    <div class="w-full px-4 lg:mt-8 sm:px-6 md:px-8 lg:pl-72 z-[0]">
        <h1 class="!text-4xl mb-8 font-semibold text-slate-800">Data Manajer</h1>
        <div class="flex mt-8 h-10 justify-between">
            <div class="flex h-full gap-6 w-6/12">
            </div>
            <button type="button"
                class="py-2 px-3 mr-8 items-center text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                data-hs-overlay="#modal">
                Tambah
            </button>
        </div>
        <div class="flex w-full mt-8 border">
            <!-- Periode -->
            <div class="w-1/3 p-4 overflow-y-auto h-full ">
                <table class="min-w-full">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-2">Periode</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="align-center">
                        <?php foreach ($periode as $p): ?>
                            <tr :class="selectedPeriode === '<?= $p['id'] ?>' ? 'bg-gray-50 font-bold text-black-900' : 'hover:bg-gray-50'"
                                class="transition cursor-pointer group" @click="selectedPeriode = '<?= $p['id'] ?>'">
                                <td class="px-4 py-2 pl-8">
                                    <?= esc($p['label_periode']) ?>
                                </td>
                                <td class="px-4 py-2 pr-6 text-right relative">
                                    <div x-data="{ open: false }" class="relative inline-block text-left z-20" @click.stop>
                                        <button @click="open = !open"
                                            class="p-2 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-1 focus:ring-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 7a1 1 0 110-2 1 1 0 010 2zm0 6a1 1 0 110-2 1 1 0 010 2zm0 6a1 1 0 110-2 1 1 0 010 2z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition
                                            class="absolute right-0 mt-2 w-28 bg-white border border-gray-200 rounded-md shadow-xl z-50"
                                            style="z-index: 9999; pointer-events: auto;">
                                            <a onclick="openEditPeriodeModal('<?= $p['id'] ?>', '<?= esc($p['tahun']) ?>', '<?= esc($p['id_bulan']) ?>')"
                                                class="block px-4 py-2 text-sm bg-white text-gray-700 hover:bg-gray-100">Edit</a>
                                            <a onclick="openConfirmPeriodeModal('<?= $p['id'] ?>', '<?= esc($p['tahun']) ?>')"
                                                class="block px-4 py-2 text-sm bg-white text-red-600 hover:bg-gray-100">Hapus</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Kolom kanan -->
            <div class="w-2/3 bg-white p-4 border flex items-center justify-center">
                <template x-if="selectedPeriode">
                    <div class="flex flex-col w-full gap-4 justify-center">
                        <div class="flex w-full gap-4 flex-1">
                            <a :href="'<?= site_url('data-manajer/instrumen') ?>?periode=' + selectedPeriode"
                                class="w-1/2 p-4 flex items-center justify-center rounded-lg shadow hover:bg-blue-300 transition">Instrumen</a>
                            <a :href="'<?= site_url('data-manajer/program') ?>?periode=' + selectedPeriode"
                                class="w-1/2 p-4 flex items-center justify-center rounded-lg shadow hover:bg-green-300 transition">Program</a>
                        </div>
                        <div class="flex w-full gap-4 flex-1">
                            <a :href="'<?= site_url('data-manajer/variabel') ?>?periode=' + selectedPeriode"
                                class="w-1/2 p-4 flex items-center justify-center rounded-lg shadow hover:bg-yellow-300 transition">Variabel</a>
                            <a :href="'<?= site_url('data-manajer/sub-variabel') ?>?periode=' + selectedPeriode"
                                class="w-1/2 p-4 flex items-center justify-center rounded-lg shadow hover:bg-pink-300 transition">Sub-Variabel</a>
                        </div>
                    </div>
                </template>
                <template x-if="!selectedPeriode">
                    <div class="text-gray-500 text-lg text-center w-full">
                        Pilih salah satu Periode di kiri untuk menampilkan menu.
                    </div>
                </template>
            </div>
        </div>

        <!-- Modal Periode Update -->
        <div id="modal-periode-update"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]"
            data-hs-overlay-keyboard="true">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 id="modalLabel" class="font-bold text-gray-800 dark:text-white">
                            Edit Periode
                        </h3>
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-periode-update" onclick="reset()">
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
                        <form id="form-UpdateModal" action="<?= site_url('/data-manajer/update') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" id="periode-update-id">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Bulan</label>
                                <select id='periode-update-bulan' name="bulan" x-model="bulan" class="block w-full border rounded-lg px-3 py-2"
                                    :required="mode === 'new'">
                                    <option value=''>pilih bulan</option>
                                    <option value='1'>Januari</option>
                                    <option value='2'>Februari</option>
                                    <option value='3'>Maret</option>
                                    <option value='4'>April</option>
                                    <option value='5'>Mei</option>
                                    <option value='6'>Juni</option>
                                    <option value='7'>Juli</option>
                                    <option value='8'>Agustus</option>
                                    <option value='9'>September</option>
                                    <option value='10'>Oktober</option>
                                    <option value='11'>November</option>
                                    <option value='12'>December</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Tahun</label>
                                <select id='periode-update-tahun' name="tahun" x-model="tahun" class="block w-full border rounded-lg px-3 py-2"
                                    :required="mode === 'new'">
                                    <option value="">pilih tahun</option>
                                    <?php
                                    for ($i = 2000; $i <= 3000; $i++) {
                                        echo "<option value=\"$i\">$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                        <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-periode-update" onclick="reset()">
                            Close
                        </button>
                        <button type="submit" form="form-UpdateModal"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Periode Confirm -->
        <div id="modal-periode-confirm"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
                <div
                    class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 class="font-bold text-gray-800 dark:text-white">
                            Hapus Data Periode
                        </h3>
                        <button type="button"
                            class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                            data-hs-overlay="#modal-periode-confirm">
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
                            Apakah anda yakin akan menghapus periode ini ?
                        </p>
                    </div>
                    <form id="delete-periode-form" method="post" class="inline">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id_periode" id="periode-delete-id" value="">
                        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                            <button type="button" class="py-2 px-3 rounded bg-blue-100"
                                data-hs-overlay="#modal-periode-confirm">
                                Batalkan
                            </button>
                            <button type="button"
                                class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 cursor-pointer"
                                onclick="proceedToReauth()">
                                Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Modal Re-auth -->
        <div id="modal-periode-reauth"
        class="hs-overlay hidden fixed top-0 start-0 z-[90] w-full h-full overflow-x-hidden overflow-y-auto bg-black/50">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500  transform scale-95 transition-all duration-300 ease-out bg-white max-w-sm w-full m-3 sm:mx-auto rounded-xl shadow-sm border dark:bg-gray-800 dark:border-gray-700">
                <form action="<?= site_url('/data-manajer/reauthDelete') ?>" method="post" class="p-6 space-y-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="periode-reauth-id">
                    <input type="hidden" name="tipe" value="periode">

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
                            data-hs-overlay="#modal-periode-reauth">
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
            function openEditPeriodeModal(id, tahun, bulan) {
                document.getElementById('periode-update-id').value = id;
                document.getElementById('periode-update-tahun').value = tahun;
                document.getElementById('periode-update-bulan').value = bulan;
                HSOverlay.open('#modal-periode-update');
            }

            function openConfirmPeriodeModal(id, tahun) {
                document.getElementById('periode-delete-id').value = id;
                document.getElementById('periode-reauth-id').value = id;
                HSOverlay.open('#modal-periode-confirm');
            }

            function proceedToReauth() {
                const confirmModal = document.getElementById('modal-periode-confirm');
                const reauthModal = document.getElementById('modal-periode-reauth');

                HSOverlay.close(confirmModal);
                setTimeout(() => {
                    HSOverlay.open(reauthModal);
                }, 300);
            }

        </script>
    </div>
</div>

<!-- Modal Create Periode -->
<div id="modal"
    class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]"
    data-hs-overlay-keyboard="true">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div
            class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                <h3 class="font-bold text-gray-800 dark:text-white">Buat Periode Baru</h3>
                <button type="button"
                    class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                    data-hs-overlay="#modal" onclick="reset()">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
            <form action="<?= base_url('/data-manajer/create') ?>" method="post"
                x-data="{ mode: '', selectedCopy: '', tahun: '' }">
                <?= csrf_field() ?>
                <div class="p-4 space-y-4">
                    <div class="space-y-2">
                        <label class="font-semibold">Pilih Metode</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="mode" value="copy" x-model="mode">
                                <span>Duplikat dari data periode</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="mode" value="new" x-model="mode">
                                <span>Buat Baru</span>
                            </label>
                        </div>
                    </div>

                    <div x-show="mode === 'copy'" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-white">Pilih Periode</label>
                            <select name="copy_from" x-model="selectedCopy"
                                class="block w-full border rounded-lg px-3 py-2">
                                <option value="">pilih periode yang akan diduplikasi</option>
                                <?php foreach ($periode as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= esc($p['label_periode']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Bulan (baru)</label>
                                <select id='bulan' name="bulan" :name="mode === 'copy' ? 'bulan' : null" x-model="bulan" class="block w-full border rounded-lg px-3 py-2">
                                    <option value=''>pilih bulan</option>
                                    <option value='1'>Januari</option>
                                    <option value='2'>Februari</option>
                                    <option value='3'>Maret</option>
                                    <option value='4'>April</option>
                                    <option value='5'>Mei</option>
                                    <option value='6'>Juni</option>
                                    <option value='7'>Juli</option>
                                    <option value='8'>Agustus</option>
                                    <option value='9'>September</option>
                                    <option value='10'>Oktober</option>
                                    <option value='11'>November</option>
                                    <option value='12'>December</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Tahun (baru)</label>
                                <select id='tahun' name="tahun" :name="mode === 'copy' ? 'tahun' : null" x-model="tahun" class="block w-full border rounded-lg px-3 py-2">
                                    <option value="">pilih tahun</option>
                                    <?php
                                    for ($i = 2000; $i <= 3000; $i++) {
                                        echo "<option value=\"$i\">$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div x-show="mode === 'new'" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Bulan</label>
                                <select id='bulan' name="bulan" :name="mode === 'new' ? 'bulan' : null" x-model="bulan" class="block w-full border rounded-lg px-3 py-2">
                                    <option value=''>pilih bulan</option>
                                    <option value='1'>Januari</option>
                                    <option value='2'>Februari</option>
                                    <option value='3'>Maret</option>
                                    <option value='4'>April</option>
                                    <option value='5'>Mei</option>
                                    <option value='6'>Juni</option>
                                    <option value='7'>Juli</option>
                                    <option value='8'>Agustus</option>
                                    <option value='9'>September</option>
                                    <option value='10'>Oktober</option>
                                    <option value='11'>November</option>
                                    <option value='12'>December</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-white">Tahun</label>
                                <select id='tahun' name="tahun" :name="mode === 'new' ? 'tahun' : null" x-model="tahun" class="block w-full border rounded-lg px-3 py-2">
                                    <option value="">pilih tahun</option>
                                    <?php
                                    for ($i = 2000; $i <= 3000; $i++) {
                                        echo "<option value=\"$i\">$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                        <button type="button"
                            class="py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50"
                            data-hs-overlay="#modal" onclick="reset()">
                            Close
                        </button>
                        <button type="submit"
                            class="py-2 px-4 text-sm font-semibold rounded-lg bg-green-600 text-white hover:bg-green-700">
                            Simpan
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>