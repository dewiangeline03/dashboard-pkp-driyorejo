<!DOCTYPE html>
<html class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="<?=base_url('/css/output.css')?>" rel="stylesheet">
</head>
<?php if (session()->getFlashdata('pesan')) : ?>
    <?= $this->include('components/alert') ?>
<?php elseif (session()->getFlashdata('error')) : ?>
    <?= $this->include('components/error') ?>
<?php endif ?>

<!-- <body class="bg-cover bg-blur bg-center bg-no-repeat flex h-full items-center py-16" style="background-image: url('/img/Rs.png') "> -->
<body class="bg-svg flex h-full items-center py-16">
    <main class="w-full max-w-md mx-auto p-6">
        <div class="mt-7 bg-white border opacity-95 border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="p-4 sm:p-7">
                    <img class="my-10" src="<?= base_url("/img/frame_login.png")?>" alt="">
                    <!-- Form -->
                    <form action="/auth" method="POST">
                        <div class="grid gap-y-4">
                            <div class="relative">
                                <input type="text" id="email" name="email" class=" border peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2" placeholder="you@email.com">
                                <label for="hs-floating-input-email" class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:text-xs peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">Email</label>
                                <p class="text-red-500 text-sm ml-2 my-1"><?= $validation -> getError('email')?></p>
                            </div>

                            <div class="relative">
                                <input type="password" id="password" name="password" class="border peer p-4 block w-full border-gray-200 rounded-lg text-sm placeholder:text-transparent focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600 focus:pt-6 focus:pb-2 [&:not(:placeholder-shown)]:pt-6 [&:not(:placeholder-shown)]:pb-2 autofill:pt-6 autofill:pb-2" placeholder="********">
                                <label for="hs-floating-input-passowrd" class="absolute top-0 start-0 p-4 h-full text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent dark:text-white peer-disabled:opacity-50 peer-disabled:pointer-events-none peer-focus:text-xs peer-focus:-translate-y-1.5 peer-focus:text-gray-500 peer-[:not(:placeholder-shown)]:text-xs peer-[:not(:placeholder-shown)]:-translate-y-1.5 peer-[:not(:placeholder-shown)]:text-gray-500">Password</label>
                                <p class="text-red-500 text-sm ml-2 my-1"><?= $validation -> getError('password')?></p>
                            </div>

                            <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">Masuk</button>
                        </div>
                    </form>
                    <!-- End Form -->
            </div>
        </div>
    </main>
    <script src="<?=base_url('/css/dist/preline/dist/preline.js')?>"></script>
<script src="<?=base_url('/js/alert.js')?>"></script>
</body>

</html>