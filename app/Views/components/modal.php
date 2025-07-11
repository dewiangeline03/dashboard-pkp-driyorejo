<button type="button" id="modal-button" class="hidden opacity-0" data-hs-overlay="#hs-static-backdrop-modal">
</button>
<div id="hs-static-backdrop-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto [--overlay-backdrop:static]" data-hs-overlay-keyboard=" false">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 class="font-semibold text-gray-800 dark:text-white">
                Akun Baru
                </h3>
                <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-static-backdrop-modal">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <table>
                    <tr>
                        <td class="font-semibold">Email</td>
                        <td class="pl-8 pr-2"> : </td>
                        <td ><?= session()->getFlashdata('email') ?></td>
                    </tr>
                    <tr>
                        <td class="font-semibold">Password</td>
                        <td class="pl-8 pr-2"> : </td>
                        <td ><input id="textToCopy" disabled value="<?= session()->getFlashdata('creds') ?>" class="text-gray-800 bg-transparent dark:text-neutral-400">
                </input></td>
                    </tr>
                </table>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 ">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-static-backdrop-modal">
                    Close
                </button>
                <button type="button" id="copyButton" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none" onclick="copyToClipboard()">
                    <svg class="text-green-600" fill="#fff" stroke="#fff" width="20px" height="20px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                        <title />
                        <path d="M27.2,8.22H23.78V5.42A3.42,3.42,0,0,0,20.36,2H5.42A3.42,3.42,0,0,0,2,5.42V20.36a3.43,3.43,0,0,0,3.42,3.42h2.8V27.2A2.81,2.81,0,0,0,11,30H27.2A2.81,2.81,0,0,0,30,27.2V11A2.81,2.81,0,0,0,27.2,8.22ZM5.42,21.91a1.55,1.55,0,0,1-1.55-1.55V5.42A1.54,1.54,0,0,1,5.42,3.87H20.36a1.55,1.55,0,0,1,1.55,1.55v2.8H11A2.81,2.81,0,0,0,8.22,11V21.91ZM28.13,27.2a.93.93,0,0,1-.93.93H11a.93.93,0,0,1-.93-.93V11a.93.93,0,0,1,.93-.93H27.2a.93.93,0,0,1,.93.93Z" />
                    </svg> <span id="copyLabel">Copy Password to Clipboard</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- <script>
    function copyToClipboard() {
        document.getElementById("copyButton").className = "py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-zinc-400 text-white disabled:opacity-50 disabled:pointer-events-none";
        document.getElementById("copyLabel").innerText = "Copied!";
        var copyText = document.getElementById("textToCopy");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        document.execCommand('copy');
        console.log(copyText);
    }
</script> -->
<script>
        function copyToClipboard() {
            var copyButton = document.getElementById("copyButton");
            var copyLabel = document.getElementById("copyLabel");
            var copyText = document.getElementById("textToCopy");

            // Change button style and label
            copyButton.className = "py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-zinc-400 text-white disabled:opacity-50 disabled:pointer-events-none";
            copyLabel.innerText = "Copied!";

            // Try using navigator.clipboard if available and secure origin
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(copyText.value).then(function() {
                    console.log("Text copied to clipboard!");
                }, function(err) {
                    console.error("Could not copy text: ", err);
                });
            } else {
                // Fallback method for insecure origins
                var textArea = document.createElement("textarea");
                textArea.value = copyText.value;
                document.body.appendChild(textArea);
                textArea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge
                textArea.style.opacity = "0";      // Make it invisible
                textArea.select();
                textArea.setSelectionRange(0, 99999);
                try {
                    document.execCommand('copy');
                    console.log("Text copied to clipboard!");
                } catch (err) {
                    console.error("Could not copy text: ", err);
                }
                document.body.removeChild(textArea);
            }

            // Reset button style and label after 2 seconds
            setTimeout(function() {
                copyButton.className = "py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-zinc-400 text-white";
                copyLabel.innerText = "Copy to Clipboard";
            }, 2000);
        }
    </script>
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