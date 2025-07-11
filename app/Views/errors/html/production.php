<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?=base_url('/css/output.css')?>" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Include TensorFlow.js library -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    

    

    <title><?= $title ?></title>
</head>

<body>
    <div class="container mx-auto ml-0">
        <div class="w-screen h-screen">
        <div class="min-h-screen bg-white flex flex-col justify-center items-center relative">
        <div class="text-center z-10 pb-16">
            <h1 class="text-6xl font-bold text-black mb-4">Terjadi Kesalahan</h1>
            <p class="text-2xl text-black">Data tidak ditemukan atau Terdapat Error</p>
        </div>


        <div class="flex justify-center items-center z-10">
                <div class="w-full md:w-auto sm:w-auto lg:w-auto">
                    <a class="py-2 px-12 sm:px-6 inline-flex justify-center items-center gap-2 rounded-lg border border-green-600 font-medium bg-green-600 text-white shadow-sm align-middle hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 " href="/">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>


    </div>
        </div>

        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="<?= base_url('/css/preline/dist/preline.js') ?>"></script>
    </div>


</body>

</html>