<?php
$products = $products ?? [];
$categories = $categories ?? [];
$current_category = $current_category ?? null;
$dark = $dark ?? false;
$baseUrl = rtrim(env('APP_URL', ''), '/');
$sort = $sort ?? 'newest';
?>
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Our Collection</span>
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-gray-900 dark:text-white mt-2">
                <?= $current_category ? htmlspecialchars($current_category['name'] ?? 'Products') : 'All Products' ?>
            </h1>
            <div class="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4"></div>
            <p class="text-gray-500 mt-4 max-w-md">Discover our complete collection</p>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div class="flex flex-wrap gap-2">
                <a href="<?= $baseUrl ?>/products" class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all <?= empty($current_category) ? 'bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg shadow-primary-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' ?>">All</a>
                <?php foreach ($categories as $cat): ?>
                <a href="<?= $baseUrl ?>/categories/<?= htmlspecialchars($cat['slug'] ?? '') ?>" class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all <?= ($current_category && ($current_category['slug'] ?? '') === ($cat['slug'] ?? '')) ? 'bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg shadow-primary-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' ?>"><?= htmlspecialchars($cat['name'] ?? '') ?></a>
                <?php endforeach; ?>
            </div>
            <select onchange="window.location.href='<?= $baseUrl ?>/products<?= $current_category ? '?category=' . urlencode($current_category['slug'] ?? '') . '&' : '?' ?>sort='+this.value" class="input-field w-auto">
                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name: A-Z</option>
            </select>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                    <?= view('components.product_card', ['product' => $p, 'dark' => $dark]) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <?php for ($i = 0; $i < 12; $i++): ?>
                    <?= view('components.skeleton', ['type' => 'product_card']) ?>
                <?php endfor; ?>
                <div class="col-span-full text-center py-16 text-gray-500">
                    <p class="text-lg mb-2">No products found.</p>
                    <p>Try adjusting your filters or check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
