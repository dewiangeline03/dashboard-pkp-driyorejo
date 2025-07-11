<header class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 sm:py-4 lg:ps-64 dark:bg-neutral-800 dark:border-neutral-700">
    <nav class="flex justify-between items-center w-full mx-auto px-4 sm:px-6" aria-label="Global">
        <div class="w-1/2 md:w-1/4 lg:me-0 lg:hidden">
            <!-- Logo -->
            <a class="rounded-xl font-semibold focus:outline-none focus:opacity-80" href="<?= base_url('/') ?>">
                <img class="object-cover p-1 w-full" src="<?= base_url('/img/header_navbar.png') ?>" alt="">
            </a>
            <!-- End Logo -->
        </div>

        <div class="w-1/2 lg:w-full flex items-center justify-end lg:justify-between sm:gap-x-3 sm:order-3">

            <div class="hidden lg:block w-7/12">
                <h1 class="text-xl md:text-xl mb-1 sm:mx-3 lg:mx-0 font-semibold text-black-600">Dashboard Kinerja PKP Puskesmas Driyorejo Gresik</h1>
            </div>

            <div class="flex flex-row w-5/12 items-center justify-end gap-2">
                <h4><?= session('userInfo')['nama'] ?></h4>
                <div class="hs-dropdown [--placement:bottom-right] relative inline-flex">
                    <button id="hs-dropdown-with-header" type="button" class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700">
                        <img class="inline-block object-cover size-[38px] rounded-full ring-2 ring-white dark:ring-neutral-800" src="<?= base_url('/img/user.png') ?>" alt="Image Description">
                    </button>

                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 dark:bg-neutral-900 dark:border dark:border-neutral-700" aria-labelledby="hs-dropdown-with-header">
                        <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg dark:bg-neutral-800">
                            <p class="text-sm text-gray-500 dark:text-neutral-400">Login Sebagai</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-neutral-300"><?= session('userInfo')['nama'] ?></p>
                        </div>
                        <div class="mt-2 py-2 first:pt-0 last:pb-0">
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="<?= base_url('/logout') ?>">
                                <svg class="flex-shrink-0 size-4 font-semibold" fill="#000000" stroke="currentColor" height="24" width="24" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 384.971 384.971" xml:space="preserve" transform="matrix(-1, 0, 0, 1, 0, 0)">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <g>
                                            <g id="Sign_Out">
                                                <path d="M180.455,360.91H24.061V24.061h156.394c6.641,0,12.03-5.39,12.03-12.03s-5.39-12.03-12.03-12.03H12.03 C5.39,0.001,0,5.39,0,12.031V372.94c0,6.641,5.39,12.03,12.03,12.03h168.424c6.641,0,12.03-5.39,12.03-12.03 C192.485,366.299,187.095,360.91,180.455,360.91z"></path>
                                                <path d="M381.481,184.088l-83.009-84.2c-4.704-4.752-12.319-4.74-17.011,0c-4.704,4.74-4.704,12.439,0,17.179l62.558,63.46H96.279 c-6.641,0-12.03,5.438-12.03,12.151c0,6.713,5.39,12.151,12.03,12.151h247.74l-62.558,63.46c-4.704,4.752-4.704,12.439,0,17.179 c4.704,4.752,12.319,4.752,17.011,0l82.997-84.2C386.113,196.588,386.161,188.756,381.481,184.088z"></path>
                                            </g>
                                            <g> </g>
                                            <g> </g>
                                            <g> </g>
                                            <g> </g>
                                            <g> </g>
                                            <g> </g>
                                        </g>
                                    </g>
                                </svg>
                                Keluar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>