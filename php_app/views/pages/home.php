<?php
$featured_products = $featured_products ?? [];
$categories = $categories ?? [];
$catalogues = $catalogues ?? [];
$dark = $dark ?? false;
$categoryGradients = [
    'from-rose-400 to-pink-500', 'from-purple-400 to-violet-500',
    'from-amber-400 to-orange-500', 'from-emerald-400 to-teal-500',
    'from-blue-400 to-cyan-500', 'from-fuchsia-400 to-pink-500',
    'from-indigo-400 to-blue-500', 'from-red-400 to-rose-500',
    'from-lime-400 to-green-500', 'from-cyan-400 to-sky-500',
    'from-rose-400 to-red-500',
];
$features = [
    ['icon' => 'M9 17a1 1 0 018 0H9z', 'title' => 'Free Shipping', 'desc' => 'On orders above ₹500'],
    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Secure Payment', 'desc' => '100% secure checkout'],
    ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Best Prices', 'desc' => 'Guaranteed lowest prices'],
    ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'Gift Wrapping', 'desc' => 'Beautiful packaging'],
];
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<div>
    <!-- Hero -->
    <section class="relative min-h-[92vh] flex items-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=1920&q=80" alt="" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-r from-primary-900/95 via-primary-800/80 to-secondary-900/85"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/20"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
            <div class="max-w-2xl animate-slide-up">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-5 py-2 mb-8">
                    <svg class="w-4 h-4 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    <span class="text-white/90 text-sm font-medium tracking-wide">New Collection 2026</span>
                </div>
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-heading font-bold text-white mb-6 leading-[1.1]">
                    Discover Your
                    <span class="block text-transparent bg-clip-text bg-gradient-to-r from-accent-300 via-accent-400 to-accent-500">Style</span>
                </h1>
                <p class="text-lg md:text-xl text-white/75 mb-10 leading-relaxed max-w-lg font-light">
                    Explore our exclusive collection of beautiful accessories, cosmetics, and gift items crafted just for you.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?= $baseUrl ?>/catalogues" class="group bg-gradient-to-r from-accent-500 to-accent-600 hover:from-accent-600 hover:to-accent-700 text-white font-bold py-4 px-10 rounded-2xl transition-all flex items-center space-x-3 shadow-xl shadow-accent-500/30 hover:shadow-accent-500/50 hover:-translate-y-0.5">
                        <span class="text-lg">Explore Catalogues</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="<?= $baseUrl ?>/products" class="bg-white/10 hover:bg-white/20 text-white font-semibold py-4 px-10 rounded-2xl transition-all backdrop-blur-md border border-white/20 hover:border-white/40 hover:-translate-y-0.5">View All Products</a>
                </div>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-float">
            <span class="text-white/50 text-xs tracking-widest uppercase">Scroll</span>
            <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center pt-2">
                <div class="w-1.5 h-3 bg-white/50 rounded-full animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Features Strip -->
    <section class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100 dark:divide-gray-700">
                <?php foreach ($features as $f): ?>
                <div class="py-6 md:py-8 px-4 md:px-6 text-center group hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                    <svg class="w-8 h-8 text-primary-600 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $f['icon'] ?>"/></svg>
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm"><?= $f['title'] ?></h4>
                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5"><?= $f['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Catalogues -->
    <?php if (count($catalogues) > 0): ?>
    <section class="py-20 bg-mesh relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Curated for you</span>
                <h2 class="text-3xl md:text-5xl font-heading font-bold text-gray-900 dark:text-white mt-3">Our Catalogues</h2>
                <div class="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mx-auto mt-5"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($catalogues as $i => $cat): ?>
                <a href="<?= $baseUrl ?>/catalogues/<?= htmlspecialchars($cat['slug'] ?? '') ?>" class="card card-hover group relative">
                    <div class="relative h-64 overflow-hidden">
                        <?php if (!empty($cat['image'])): ?>
                            <img src="<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['title'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" />
                        <?php else: ?>
                            <div class="w-full h-full bg-gradient-to-br <?= $categoryGradients[$i % count($categoryGradients)] ?> flex items-center justify-center">
                                <span class="text-7xl font-heading font-bold text-white/90"><?= strtoupper(substr($cat['title'] ?? 'C', 0, 1)) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <h3 class="text-2xl font-heading font-bold text-white mb-1"><?= htmlspecialchars($cat['title'] ?? '') ?></h3>
                            <?php if (!empty($cat['description'])): ?><p class="text-white/70 text-sm line-clamp-2"><?= htmlspecialchars($cat['description']) ?></p><?php endif; ?>
                            <div class="mt-3 inline-flex items-center gap-2 text-accent-400 font-semibold text-sm">
                                View Collection <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Featured Products -->
    <section class="py-20 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <span class="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Handpicked</span>
                    <h2 class="text-3xl md:text-5xl font-heading font-bold text-gray-900 dark:text-white mt-2">Featured Products</h2>
                    <div class="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4"></div>
                </div>
                <a href="<?= $baseUrl ?>/products" class="hidden md:inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold transition-colors group">
                    View All
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                <?php if (!empty($featured_products)): ?>
                    <?php foreach ($featured_products as $p): ?>
                        <?= view('components.product_card', ['product' => $p, 'dark' => $dark]) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php for ($i = 0; $i < 8; $i++): ?>
                        <?= view('components.skeleton', ['type' => 'product_card']) ?>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
            <div class="text-center mt-10 md:hidden">
                <a href="<?= $baseUrl ?>/products" class="btn-primary inline-flex items-center gap-2">
                    View All Products <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <?php if (count($categories) > 0): ?>
    <section class="py-20 bg-mesh relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Browse by</span>
                <h2 class="text-3xl md:text-5xl font-heading font-bold text-gray-900 dark:text-white mt-3">Shop by Category</h2>
                <div class="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mx-auto mt-5"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-5">
                <?php foreach ($categories as $i => $cat): ?>
                <a href="<?= $baseUrl ?>/categories/<?= htmlspecialchars($cat['slug'] ?? '') ?>" class="card card-hover group text-center p-6 bg-white dark:bg-gray-800">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br <?= $categoryGradients[$i % count($categoryGradients)] ?> rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-lg">
                        <span class="text-3xl font-heading font-bold text-white"><?= strtoupper(substr($cat['name'] ?? 'C', 0, 1)) ?></span>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors"><?= htmlspecialchars($cat['name'] ?? '') ?></h3>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA -->
    <section class="relative py-28 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=1920&q=80" alt="" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-r from-primary-900/95 to-secondary-900/95"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 text-center animate-slide-up">
            <svg class="w-12 h-12 text-accent-400 mx-auto mb-6 animate-float" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            <h2 class="text-4xl md:text-6xl font-heading font-bold text-white mb-6">Quality & Elegance</h2>
            <p class="text-lg text-white/75 mb-10 max-w-2xl mx-auto leading-relaxed font-light">Every piece is carefully curated to bring you the finest accessories and gifts. Add a touch of sparkle to your everyday look.</p>
            <a href="<?= $baseUrl ?>/categories" class="inline-flex items-center gap-3 bg-gradient-to-r from-accent-500 to-accent-600 hover:from-accent-600 hover:to-accent-700 text-white font-bold py-5 px-12 rounded-2xl transition-all shadow-xl shadow-accent-500/30 hover:shadow-accent-500/50 hover:-translate-y-0.5 text-lg">
                Start Shopping
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </section>
</div>
