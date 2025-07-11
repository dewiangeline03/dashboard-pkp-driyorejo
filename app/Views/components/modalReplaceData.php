<button type="button" id="modal-button" class="hidden opacity-0" data-hs-overlay="#hs-static-backdrop-modal">
</button>
<div id="hs-static-backdrop-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]" data-hs-overlay-keyboard=" false">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-semibold text-gray-800 dark:text-white">
                Konfirmasi Perubahan Data
                </h3>
                <a href="/penilaian-kinerja/reset" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-static-backdrop-modal">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </a>
            </div>
            <div class="p-4 overflow-y-auto">
                <p>Data dengan periode yang sama telah tersedia pada database</br></p>
                <table>
                    <tr>
                        <td class="font-semibold">Diunggah oleh</td>
                        <td class="pl-8 pr-2"> : </td>
                        <td ><?= session()->getFlashdata('entry_user') ?></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Pada</td>
                        <td class="pl-8 pr-2"> : </td>
                        <td ><?=  date('d-M-Y', strtotime((string)session()->getFlashdata('entry_at'))) ?></td>
                    </tr>
                </table>
                <p>Apakah Anda yakin ingin melakukan  perubahan data ?</p>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 ">
                <a href="/penilaian-kinerja/reset" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-static-backdrop-modal">
                    Cancel
                </a>
                <a id="confirm-button" href="/penilaian-kinerja/replace" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 cursor-pointer">
                    Ok
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    async function dismissAlert() {
        await delay(500);
        var dismissElement = document.getElementById('modal-button');
        if (dismissElement) {
            // Simulate a click on the button
            dismissElement.click();
        }
    }
    dismissAlert()
</script>