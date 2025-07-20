<!-- Navigation Toggle -->
<div class="flex justify-end pt-5 pr-5">
  <button type="button"
    class="py-2 px-3 lg:hidden flex justify-center items-center gap-x-1.5 text-xs rounded-lg border border-gray-200 text-gray-500 hover:text-gray-600 dark:border-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200"
    data-hs-overlay="#docs-sidebar" aria-controls="docs-sidebar" aria-label="Toggle navigation">
    <span class="sr-only">Toggle Navigation</span>
    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M17 8L21 12L17 16M3 12H13M3 6H13M3 18H13" />
    </svg>
    <span class="sr-only">Sidebar</span>
  </button>
</div>
<!-- End Navigation Toggle -->
<div id="docs-sidebar"
  class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 start-0 bottom-0 z-[60] w-64 bg-white border-e border-gray-200 pb-10 overflow-y-auto lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-gray-800 dark:border-gray-700">
  <img class="bg-cover w-full pt-5 pl-5 pr-5" src="<?= base_url('/img/header_navbar.png') ?>" alt="">
  <?php $active = service('uri')->getSegment(1); ?>
  <nav class="hs-accordion-group p-6 w-full max-h-full flex flex-col justify-between flex-wrap"
    data-hs-accordion-always-open>
    <ul class="space-y-1.5">
      <li>
        <a href="<?= base_url('/user/home') ?>" class="flex items-center gap-x-3.5 py-2 px-2.5 text-[16px] rounded-lg
    <?= $active === 'user'
      ? 'bg-gray-100 text-gray-900'
      : 'text-slate-700 hover:bg-gray-100' ?> 
    dark:bg-gray-900 dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
          <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
            <polyline points="9 22 9 12 15 12 15 22" />
          </svg>
          Dashboard
        </a>

      </li>
      <!-- Manajemen Data -->
      <li class="hs-accordion" id="manage-dashboard-accordion">
        <button type="button"
          class="cursor-default w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-[16px] text-slate-700 rounded-lg  dark:bg-gray-800 dark:hover:bg-gray-900 dark:text-slate-400 dark:hover:text-slate-300 dark:hs-accordion-active:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
              <path d="M4 18V6" stroke="#000000" stroke-width="1.5" stroke-linecap="round"></path>
              <path d="M20 6V18" stroke="#000000" stroke-width="1.5" stroke-linecap="round"></path>
              <path
                d="M12 10C16.4183 10 20 8.20914 20 6C20 3.79086 16.4183 2 12 2C7.58172 2 4 3.79086 4 6C4 8.20914 7.58172 10 12 10Z"
                stroke="#000000" stroke-width="1.5"></path>
              <path d="M20 12C20 14.2091 16.4183 16 12 16C7.58172 16 4 14.2091 4 12" stroke="#000000"
                stroke-width="1.5"></path>
              <path d="M20 18C20 20.2091 16.4183 22 12 22C7.58172 22 4 20.2091 4 18" stroke="#000000"
                stroke-width="1.5"></path>
            </g>
          </svg>
          Manajemen Data
          <svg
            class="hs-accordion-active:block ms-auto hidden w-4 h-4 text-gray-600 group-hover:text-gray-500 dark:text-gray-400"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m18 15-6-6-6 6" />
          </svg>
        </button>

        <ul class="pt-2 ps-2 space-y-1.5">
          <li>
            <a href="<?= base_url('data-capaian') ?>"
              class="flex items-center gap-x-3.5 py-2 px-2.5 text-[16px] rounded-lg 
                 <?= $active === 'data-capaian' ? 'bg-gray-100 text-gray-900 ' : 'text-slate-700 hover:bg-gray-100' ?>">
              Data Capaian
            </a>
          </li>
          <?php if (session("userInfo")["role"] == 1000): ?>
            <li>
              <a href="<?= base_url('data-manajer') ?>"
                class="flex items-center gap-x-3.5 py-2 px-2.5 text-[16px] rounded-lg 
                 <?= $active === 'data-manajer' ? 'bg-gray-100 text-gray-900 ' : 'text-slate-700 hover:bg-gray-100' ?>">
                Data Manajer
              </a>
            </li>
          <?php endif ?>
          <li>
              <a href="<?= base_url('histori-penilaian') ?>"
                class="flex items-center gap-x-3.5 py-2 px-2.5 text-[16px] rounded-lg 
                 <?= $active === 'histori-penilaian' ? 'bg-gray-100 text-gray-900 ' : 'text-slate-700 hover:bg-gray-100' ?>">
                Histori Penilaian
              </a>
            </li>
        </ul>
      </li>
      <?php if (session("userInfo")["role"] == 1000): ?>
        <!-- Manajemen User -->
        <li>
          <a href="<?= base_url('/users') ?>"
            class="w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 text-[16px] rounded-lg 
       <?= $active === 'users'
         ? 'bg-gray-100 text-gray-900'
         : 'text-slate-700 hover:bg-gray-100' ?> 
       dark:bg-gray-800 dark:hover:bg-gray-900 dark:text-slate-400 dark:hover:text-slate-300 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="18" cy="15" r="3" />
              <circle cx="9" cy="7" r="4" />
              <path d="M10 15H6a4 4 0 0 0-4 4v2" />
              <path d="m21.7 16.4-.9-.3" />
              <path d="m15.2 13.9-.9-.3" />
              <path d="m16.6 18.7.3-.9" />
              <path d="m19.1 12.2.3-.9" />
              <path d="m19.6 18.7-.4-1" />
              <path d="m16.8 12.3-.4-1" />
              <path d="m14.3 16.6 1-.4" />
              <path d="m20.7 13.8 1-.4" />
            </svg>
            Manajemen User
          </a>
        </li>
      <?php endif ?>
    </ul>
  </nav>
</div>