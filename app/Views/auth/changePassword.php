<!DOCTYPE html>
<html class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="<?= base_url('/css/output.css') ?>" rel="stylesheet">
</head>
<?php if (session()->getFlashdata('pesan')) : ?>
    <?= $this->include('components/alert') ?>
<?php elseif (session()->getFlashdata('error')) : ?>
    <?= $this->include('components/error') ?>
<?php endif ?>

<body class="dark:bg-slate-900 bg-gray-100 flex h-full items-center py-16">
    <main class="w-full max-w-md mx-auto p-6">
        <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="p-4 sm:p-7">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold">Ubah Password</h1>
                    <a href="/logout">
                        <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 96.943 96.943" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M61.168,83.92H11.364V13.025H61.17c1.104,0,2-0.896,2-2V3.66c0-1.104-0.896-2-2-2H2c-1.104,0-2,0.896-2,2v89.623
			c0,1.104,0.896,2,2,2h59.168c1.105,0,2-0.896,2-2V85.92C63.168,84.814,62.274,83.92,61.168,83.92z" />
                                    <path d="M96.355,47.058l-26.922-26.92c-0.75-0.751-2.078-0.75-2.828,0l-6.387,6.388c-0.781,0.781-0.781,2.047,0,2.828
			l12.16,12.162H19.737c-1.104,0-2,0.896-2,2v9.912c0,1.104,0.896,2,2,2h52.644L60.221,67.59c-0.781,0.781-0.781,2.047,0,2.828
			l6.387,6.389c0.375,0.375,0.885,0.586,1.414,0.586c0.531,0,1.039-0.211,1.414-0.586l26.922-26.92
			c0.375-0.375,0.586-0.885,0.586-1.414C96.943,47.941,96.73,47.433,96.355,47.058z" />
                                </g>
                            </g>
                        </svg>
                    </a>
                </div>

                <div class="mt-5">
                    <!-- Form -->
                    <form action="<?= base_url('/validate-password') ?>" method="POST">
                        <div class="grid gap-y-4">
                            <div class="relative">
                                <input type="password" id="password" name="oldPassword" class="border peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2" placeholder="********" required>
                                <input hidden id="id" name="id" value="<?= session('userInfo')['id'] ?>">
                                <label for="hs-floating-input-passowrd" class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:text-xs peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">Password Lama</label>
                            </div>

                            <!-- Strong Password -->
                            <div class="max-w-sm">
                                <div class="flex mb-2">
                                    <div class="flex-1">
                                        <div class="relative">
                                            <input type="password" id="newPassword" name="newPassword" class="border peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2" placeholder="Enter password" required>
                                            <label for="hs-floating-input-passwo1rd" class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:text-xs peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">Password Baru</label>
                                            <div class="flex my-4">
                                                <input data-hs-toggle-password='{ "target": ["#newPassword", "#password"] }' id="hs-toggle-password-checkbox" type="checkbox" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                                <label for="hs-toggle-password-checkbox" class="text-sm text-gray-500 ms-3 dark:text-gray-400">Show password</label>
                                            </div>
                                            <div id="hs-strong-password" data-hs-strong-password='{"target": "#newPassword", "hints": "#hs-strong-password-hints", "stripClasses": "hs-strong-password:opacity-100 hs-strong-password-accepted:bg-green-500 h-2 flex-auto rounded-full bg-green-400 opacity-50 mx-1", "minLength": "8" }' class="flex mt-2 -mx-1"></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="hs-strong-password-hints" class="mb-3">
                                    <div>
                                        <span class="text-sm text-gray-800 dark:text-gray-200">Level:</span>
                                        <span data-hs-strong-password-hints-weakness-text='["Kosong", "Sangat Lemah", "Lemah", "Kuat", "Sangat Kuat", "Aman"]' class="text-sm font-semibold text-gray-800 dark:text-gray-200"></span>
                                    </div>

                                    <h4 class="my-2 text-sm font-semibold text-gray-800 dark:text-white">
                                        Password Anda Harus Mengandung,
                                    </h4>

                                    <ul class="space-y-1 text-sm text-gray-500">
                                        <li data-hs-strong-password-hints-rule-text="min-length" class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                            <span class="hidden" data-check>
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </span>
                                            <span data-uncheck>
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 6 6 18" />
                                                    <path d="m6 6 12 12" />
                                                </svg>
                                            </span>
                                            Minimal 8 karakter.
                                        </li>
                                        <li data-hs-strong-password-hints-rule-text="lowercase" class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                            <span class="hidden" data-check>
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </span>
                                            <span data-uncheck>
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 6 6 18" />
                                                    <path d="m6 6 12 12" />
                                                </svg>
                                            </span>
                                            Harus Mengandung Huruf Kecil.
                                        </li>
                                        <li data-hs-strong-password-hints-rule-text="uppercase" class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                            <span class="hidden" data-check>
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </span>
                                            <span data-uncheck>
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 6 6 18" />
                                                    <path d="m6 6 12 12" />
                                                </svg>
                                            </span>
                                            Harus Mengandung Huruf Kapital.
                                        </li>
                                        <li data-hs-strong-password-hints-rule-text="numbers" class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                            <span class="hidden" data-check>
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </span>
                                            <span data-uncheck>
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 6 6 18" />
                                                    <path d="m6 6 12 12" />
                                                </svg>
                                            </span>
                                            Harus Mengandung Angka.
                                        </li>
                                        <li data-hs-strong-password-hints-rule-text="special-characters" class="hs-strong-password-active:text-teal-500 flex items-center gap-x-2">
                                            <span class="hidden" data-check>
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12" />
                                                </svg>
                                            </span>
                                            <span data-uncheck>
                                                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 6 6 18" />
                                                    <path d="m6 6 12 12" />
                                                </svg>
                                            </span>
                                            Harus Mengandung Karakter Spesial.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End Strong Password -->

                            <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">Ubah Password</button>
                        </div>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </div>
    </main>
    <script src="<?= base_url('/css/dist/preline/dist/preline.js') ?>"></script>
    <script src="<?= base_url('/js/alert.js') ?>"></script>
    <script>
        function validatePassword() {
            let passwordInput = document.getElementById('newPassword');
            let password = passwordInput.value;

            // Check for minimum length
            if (password.length < 6) {
                alert("Password should be at least 6 characters long.");
                return false;
            }

            // Check for lowercase letters
            if (!/[a-z]/.test(password)) {
                alert("Password should contain at least one lowercase letter.");
                return false;
            }

            // Check for uppercase letters
            if (!/[A-Z]/.test(password)) {
                alert("Password should contain at least one uppercase letter.");
                return false;
            }

            // Check for numbers
            if (!/\d/.test(password)) {
                alert("Password should contain at least one number.");
                return false;
            }

            // Check for special characters
            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                alert("Password should contain at least one special character.");
                return false;
            }
        }
    </script>
</body>

</html>