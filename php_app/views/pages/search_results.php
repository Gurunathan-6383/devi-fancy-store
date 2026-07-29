<?php
$query = $query ?? '';
$products = $products ?? [];
$sort = $sort ?? 'newest';
$dark = $dark ?? false;
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Discover</span>
            <h1 class="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mt-2">Search Results</h1>
            <div class="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4"></div>
            <p class="text-gray-500 mt-4">
                <?= $query ? 'Showing results for "' . htmlspecialchars($query) . '"' : 'Enter a search term to find products' ?>
            </p>
        </div>

        <?php if (!empty($products)): ?>
        <div class="flex justify-end mb-6">
            <form method="GET" action="<?= $baseUrl ?>/search">
                <input type="hidden" name="q" value="<?= htmlspecialchars($query) ?>">
                <select name="sort" onchange="this.form.submit()" class="input-field w-auto">
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                    <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                    <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name: A-Z</option>
                </select>
            </form>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                    <?= view('components.product_card', ['product' => $p, 'dark' => $dark]) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <?php for ($i = 0; $i < 8; $i++): ?>
                    <?= view('components.skeleton', ['type' => 'product_card']) ?>
                <?php endfor; ?>
                <?php if ($query): ?>
                <div class="col-span-full text-center py-16">
                    <p class="text-lg text-gray-500 mb-2">No products found for "<?= htmlspecialchars($query) ?>"</p>
                    <p class="text-gray-400 mb-6">Try different keywords or browse our categories.</p>
                    <a href="<?= $baseUrl ?>/products" class="btn-primary inline-block">Browse All Products</a>
                </div>
                <?php else: ?>
                <div class="col-span-full text-center py-16">
                    <p class="text-gray-500 mb-6">Use the search bar above to find products.</p>
                    <a href="<?= $baseUrl ?>/products" class="btn-primary inline-block">Browse All Products</a>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
