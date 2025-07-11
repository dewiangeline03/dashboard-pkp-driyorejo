<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <link href="<?=base_url('/css/output.css')?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body>

<?php if (session()->getFlashdata('pesan')) : ?>
    <?= $this->include('components/alert') ?>
<?php elseif (session()->getFlashdata('error')) : ?>
    <?= $this->include('components/error') ?>
<?php endif ?>
<?= $this->include('components/header') ?>
<?= $this->include('components/sidebar') ?>

    <!-- Content -->
    <?= $this->renderSection('content')?>
    <!-- End Content -->


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="<?=base_url('/css/dist/preline/dist/preline.js')?>"></script>
<script src="<?=base_url('/js/alert.js')?>"></script>
</body>
</html>