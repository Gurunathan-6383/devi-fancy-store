<?php
$stats = $stats ?? ['categories' => 0, 'products' => 0, 'catalogues' => 0, 'orders' => 0];
$baseUrl = rtrim(env('APP_URL', ''), '/');
$adminBase = $baseUrl . '/admin';

$cards = [
    ['label' => 'Categories', 'value' => $stats['categories'], 'color' => 'from-secondary-500 to-secondary-600', 'shadow' => 'shadow-secondary-200', 'link' => $adminBase . '/categories'],
    ['label' => 'Products', 'value' => $stats['products'], 'color' => 'from-primary-500 to-primary-600', 'shadow' => 'shadow-primary-200', 'link' => $adminBase . '/products'],
    ['label' => 'Catalogues', 'value' => $stats['catalogues'], 'color' => 'from-accent-500 to-accent-600', 'shadow' => 'shadow-accent-200', 'link' => $adminBase . '/catalogues'],
    ['label' => 'Orders', 'value' => $stats['orders'], 'color' => 'from-emerald-500 to-emerald-600', 'shadow' => 'shadow-emerald-200', 'link' => $adminBase . '/orders'],
];

$actions = [
    ['label' => 'Add Product', 'link' => $adminBase . '/products', 'color' => 'bg-primary-600 hover:bg-primary-700'],
    ['label' => 'Categories', 'link' => $adminBase . '/categories', 'color' => 'bg-secondary-600 hover:bg-secondary-700'],
    ['label' => 'Catalogues', 'link' => $adminBase . '/catalogues', 'color' => 'bg-accent-600 hover:bg-accent-700'],
    ['label' => 'Settings', 'link' => $adminBase . '/settings', 'color' => 'bg-gray-800 hover:bg-gray-900'],
];
?>
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p class="text-gray-500 mt-1">Welcome back! Here's your store overview.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <?php foreach ($cards as $i => $card): ?>
        <a href="<?= $card['link'] ?>" class="card card-hover p-6 group animate-slide-up" style="animation-delay: <?= $i * 0.05 ?>s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium"><?= $card['label'] ?></p>
                    <p class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1"><?= $card['value'] ?></p>
                </div>
                <div class="bg-gradient-to-br <?= $card['color'] ?> p-3.5 rounded-xl shadow-lg <?= $card['shadow'] ?> group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <?php if ($card['label'] === 'Categories'): ?><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        <?php elseif ($card['label'] === 'Products'): ?><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        <?php elseif ($card['label'] === 'Catalogues'): ?><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        <?php else: ?><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        <?php endif; ?>
                    </svg>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

    <div class="card p-6">
        <h2 class="text-lg font-heading font-bold text-gray-900 dark:text-white mb-5">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php foreach ($actions as $a): ?>
            <a href="<?= $a['link'] ?>" class="<?= $a['color'] ?> text-white font-semibold py-3 px-6 rounded-xl transition-all text-center flex items-center justify-center gap-2 shadow-lg hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <?= $a['label'] ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
