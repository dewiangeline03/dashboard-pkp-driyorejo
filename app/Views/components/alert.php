<div class="absolute top-20 end-10">
                    <div id="dismiss-alert" class="hs-removing:translate-x-5 hs-removing:opacity-0 transition duration-300  bg-green-50 border border-green-200 text-sm text-green-800 rounded-lg p-4 dark:bg-green-800/10 dark:border-green-900 dark:text-green-500" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="flex-shrink-0 h-4 w-4 text-green-600 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                            </div>
                            <div class="ms-2">
                                <div class="text-sm font-medium">
                                    <?=session()->getFlashdata('pesan') ?>
                                </div>
                            </div>
                            <div class="ps-3 ms-auto">
                                <div class="-mx-1.5 -my-1.5">
                                    <button id="dismiss-button" type="button" class="inline-flex bg-green-50 rounded-lg p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600 dark:bg-transparent dark:hover:bg-green-800/50 dark:text-green-600" data-hs-remove-element="#dismiss-alert">
                                        <span class="sr-only">Dismiss</span>
                                        <svg class="flex-shrink-0 h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>