<?php
$catalogues = $catalogues ?? [];
$catalogue = $catalogue ?? null;
$dark = $dark ?? false;
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<?php if ($catalogue): ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="<?= $baseUrl ?>/catalogues" class="text-primary-600 hover:text-primary-700 font-medium mb-4 inline-block">&larr; Back to Catalogues</a>
        <div class="mb-8">
            <?php if (!empty($catalogue['image'])): ?>
                <img src="<?= htmlspecialchars($catalogue['image']) ?>" alt="<?= htmlspecialchars($catalogue['title'] ?? '') ?>" class="w-full h-72 object-cover rounded-2xl mb-6 shadow-xl" />
            <?php endif; ?>
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Collection</span>
            <h1 class="text-4xl font-heading font-bold text-gray-900 dark:text-white mt-2"><?= htmlspecialchars($catalogue['title'] ?? '') ?></h1>
            <div class="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4"></div>
            <?php if (!empty($catalogue['description'])): ?><p class="text-gray-500 mt-4 text-lg"><?= htmlspecialchars($catalogue['description']) ?></p><?php endif; ?>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <?php $products = $catalogue['products'] ?? []; ?>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                    <?= view('components.product_card', ['product' => $p, 'dark' => $dark]) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-16 text-gray-500">
                    <p class="text-lg">No products in this catalogue yet.</p>
                    <a href="<?= $baseUrl ?>/products" class="text-primary-600 hover:text-primary-700 font-medium mt-2 inline-block">Browse all products</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <span class="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Curated for you</span>
            <h1 class="text-4xl md:text-5xl font-heading font-bold text-gray-900 dark:text-white mt-2">Catalogues</h1>
            <div class="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4"></div>
            <p class="text-gray-500 mt-4">Explore our curated collections</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($catalogues)): ?>
                <?php foreach ($catalogues as $cat): ?>
                <a href="<?= $baseUrl ?>/catalogues/<?= htmlspecialchars($cat['slug'] ?? '') ?>" class="card group overflow-hidden">
                    <?php if (!empty($cat['image'])): ?>
                        <div class="h-48 overflow-hidden">
                            <img src="<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['title'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
                        </div>
                    <?php else: ?>
                        <div class="h-48 bg-gradient-to-br from-primary-100 to-secondary-100 dark:from-primary-900/30 dark:to-secondary-900/30 flex items-center justify-center">
                            <span class="text-4xl font-heading font-bold text-primary-600"><?= strtoupper(substr($cat['title'] ?? 'C', 0, 1)) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="p-5">
                        <h3 class="text-xl font-heading font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($cat['title'] ?? '') ?></h3>
                        <?php if (!empty($cat['description'])): ?><p class="text-gray-500 mt-1 text-sm line-clamp-2"><?= htmlspecialchars($cat['description']) ?></p><?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="card animate-pulse">
                    <div class="h-48 bg-gray-200 dark:bg-gray-600 rounded-t-xl"></div>
                    <div class="p-5 space-y-3">
                        <div class="h-6 bg-gray-200 dark:bg-gray-600 rounded w-2/3"></div>
                        <div class="h-4 bg-gray-200 dark:bg-gray-600 rounded w-full"></div>
                    </div>
                </div>
                <?php endfor; ?>
                <div class="col-span-full text-center py-16 text-gray-500">
                    <p class="text-lg">No catalogues available yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>
