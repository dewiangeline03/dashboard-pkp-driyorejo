<?php
/**
 * File: app/Views/dashboard_home.php
 * Description: Responsive home dashboard view with fixed layout
 */
?>
<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Wrapper: retains original flex layout and padding -->
<div class="w-full px-4 sm:px-6 md:px-8 lg:pl-72 lg:mt-8 z-0">

  <!-- Header: always horizontal, centered vertically -->
  <div class="flex items-center justify-between mb-8 print:hidden pr-6">
    <h1 class="!text-4xl font-semibold text-slate-800">Dashboard</h1>
    <button onclick="window.print()"
            class="px-8 py-1.5 rounded bg-gray-100 border border-gray-200 hover:bg-gray-400 text-black font-semibold">
      Print
    </button>
  </div>

  <!-- Iframe container: full width, dynamic height with min-height to prevent overflow -->
  <div class="w-full">
    <iframe
      title="Dashboard_RSB_Nganjuk_Final"
      class="w-full min-h-screen md:h-screen"
      src="https://app.powerbi.com/view?r=eyJrIjoiMDIzMTA3YWMtZDU2Yy00ZDRmLTgxMTMtNDNkMzcwM2YzYWE1IiwidCI6IjFkNTE2OWFjLWM3Y2ItNDI3NS05NzY0LWJmOGM5YzM2NGE0YyIsImMiOjEwfQ%3D%3D"
      frameborder="0"
      allowfullscreen>
    </iframe>
  </div>

</div>

<?= $this->endSection(); ?>
