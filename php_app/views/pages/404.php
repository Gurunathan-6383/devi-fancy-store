<?php $baseUrl = rtrim(env('APP_URL', ''), '/'); ?>
<div class="min-h-[80vh] flex items-center justify-center py-20">
    <div class="text-center max-w-lg mx-auto px-4 animate-scale-in">
        <div class="text-9xl font-heading font-bold text-gradient mb-4">404</div>
        <div class="w-28 h-28 mx-auto mb-6 bg-gradient-to-br from-primary-100 to-secondary-100 dark:from-primary-900/30 dark:to-secondary-900/30 rounded-full flex items-center justify-center">
            <svg class="w-14 h-14 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h2 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mb-3">Page Not Found</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">The page you are looking for doesn't exist or has been moved. Let's get you back on track!</p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="<?= $baseUrl ?>/" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Go Home
            </a>
            <a href="<?= $baseUrl ?>/products" class="btn-outline inline-flex items-center gap-2">Browse Products</a>
        </div>
    </div>
</div>
