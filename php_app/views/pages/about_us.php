<?php
$dark = $dark ?? false;
$baseUrl = rtrim(env('APP_URL', ''), '/');

$stats = [
    ['number' => '10,000+', 'label' => 'Happy Customers'],
    ['number' => '500+', 'label' => 'Products'],
    ['number' => '50+', 'label' => 'Categories'],
    ['number' => '4.8', 'label' => 'Rating'],
];

$values = [
    ['icon' => '💎', 'title' => 'Quality First', 'desc' => 'Every product is handpicked and quality-checked to ensure you receive only the best.'],
    ['icon' => '💰', 'title' => 'Affordable Prices', 'desc' => 'Premium accessories at prices that don\'t break the bank. Beauty should be accessible to all.'],
    ['icon' => '🤝', 'title' => 'Customer Trust', 'desc' => 'Building lasting relationships through honest service and reliable products.'],
    ['icon' => '🚚', 'title' => 'Fast Delivery', 'desc' => 'Quick and reliable shipping right to your doorstep across India.'],
    ['icon' => '🔄', 'title' => 'Easy Returns', 'desc' => 'Hassle-free return policy because your satisfaction matters most to us.'],
    ['icon' => '💝', 'title' => 'Gift Ready', 'desc' => 'Beautiful packaging and gift options for every special occasion.'],
];

$milestones = [
    ['year' => '2001', 'title' => 'The Beginning', 'desc' => 'Started with a small shop in Sivakasi with a big dream to bring beautiful accessories to everyone.'],
    ['year' => '2010', 'title' => 'Trusted Name', 'desc' => 'Became a well-known and trusted name across Sivakasi and nearby towns.'],
    ['year' => '2020', 'title' => 'Online Store', 'desc' => 'Launched our website to bring our collections to customers across India.'],
    ['year' => '2026', 'title' => '25 Years Strong', 'desc' => 'Celebrating 25 years of trust, quality, and 500+ products across 50+ categories.'],
];
?>
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <div class="relative bg-gradient-to-br from-secondary-600 via-secondary-700 to-primary-700 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-white rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
            <a href="<?= $baseUrl ?>/" class="text-white/60 hover:text-white text-sm mb-6 inline-flex items-center gap-1 transition-colors">&larr; Home</a>
            <div class="flex flex-col md:flex-row items-center gap-10">
                <div class="flex-1">
                    <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-4">Our <span class="text-yellow-300">Story</span></h1>
                    <p class="text-white/80 text-lg leading-relaxed max-w-lg">Discover the passion and people behind Devi Fancy Store — celebrating 25 years of bringing joy through accessories.</p>
                </div>
                <div class="hidden md:block flex-shrink-0">
                    <div class="relative">
                        <div class="w-72 h-56 rounded-2xl overflow-hidden shadow-2xl border-4 border-white/20 rotate-3">
                            <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=400&h=300&fit=crop" alt="Shopping" class="w-full h-full object-cover" />
                        </div>
                        <div class="absolute -bottom-4 -left-4 w-40 h-32 rounded-2xl overflow-hidden shadow-xl border-4 border-white/20 -rotate-6">
                            <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=300&h=250&fit=crop" alt="Store" class="w-full h-full object-cover" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 rounded-2xl p-6 shadow-xl border <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
            <?php foreach ($stats as $s): ?>
            <div class="text-center py-2">
                <p class="text-2xl md:text-3xl font-heading font-bold text-gradient"><?= $s['number'] ?></p>
                <p class="text-sm mt-1 <?= $dark ? 'text-gray-400' : 'text-gray-500' ?>"><?= $s['label'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Our Story -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <div class="rounded-2xl overflow-hidden shadow-xl">
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=450&fit=crop" alt="Our Store" class="w-full h-80 object-cover" />
                </div>
                <div class="absolute -bottom-6 -right-6 w-40 h-40 rounded-2xl overflow-hidden shadow-xl border-4 border-white dark:border-gray-800">
                    <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=200&h=200&fit=crop" alt="Products" class="w-full h-full object-cover" />
                </div>
                <div class="absolute -top-4 -left-4 rounded-xl px-4 py-2 shadow-lg <?= $dark ? 'bg-gray-800' : 'bg-white' ?>">
                    <p class="font-heading font-bold text-primary-600 text-lg">Since 2001</p>
                </div>
            </div>
            <div>
                <p class="text-primary-600 font-semibold text-sm uppercase tracking-wider mb-2">Who We Are</p>
                <h2 class="text-3xl md:text-4xl font-heading font-bold mb-5 <?= $dark ? 'text-white' : 'text-gray-900' ?>">A Passion for <span class="text-gradient">Beauty & Elegance</span></h2>
                <p class="leading-relaxed mb-4 <?= $dark ? 'text-gray-300' : 'text-gray-600' ?>">Devi Fancy Store was born over 25 years ago from a simple idea — everyone deserves access to beautiful accessories and gifts without paying premium prices. What started as a small shop in Sivakasi has grown into a trusted online destination for thousands of customers across India.</p>
                <p class="leading-relaxed mb-6 <?= $dark ? 'text-gray-300' : 'text-gray-600' ?>">For a quarter century, we have been handpicking every product with love, ensuring quality, style, and affordability go hand in hand. From stunning bangles to elegant earrings, from practical hair accessories to gorgeous gift items — we have something for every occasion and every personality.</p>
                <div class="flex flex-wrap gap-3">
                    <?php foreach (['25 Years of Trust', 'Quality Products', 'Affordable Prices', 'Pan India Delivery'] as $tag): ?>
                    <span class="px-4 py-2 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 text-sm font-medium">✓ <?= $tag ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="relative rounded-2xl p-8 shadow-lg border overflow-hidden <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary-500/10 to-transparent rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-4 shadow-lg"><span class="text-2xl">🎯</span></div>
                    <h3 class="text-xl font-heading font-bold mb-3 <?= $dark ? 'text-white' : 'text-gray-900' ?>">Our Mission</h3>
                    <p class="leading-relaxed <?= $dark ? 'text-gray-300' : 'text-gray-600' ?>">To provide high-quality, affordable accessories and gift items that bring joy to every occasion. We strive to make every customer feel special with our curated collections and exceptional service.</p>
                </div>
            </div>
            <div class="relative rounded-2xl p-8 shadow-lg border overflow-hidden <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-secondary-500/10 to-transparent rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-secondary-500 to-secondary-600 flex items-center justify-center mb-4 shadow-lg"><span class="text-2xl">🌟</span></div>
                    <h3 class="text-xl font-heading font-bold mb-3 <?= $dark ? 'text-white' : 'text-gray-900' ?>">Our Vision</h3>
                    <p class="leading-relaxed <?= $dark ? 'text-gray-300' : 'text-gray-600' ?>">To be the most loved and trusted destination for accessories and gifts in India. We envision a world where style meets affordability, and every purchase brings a smile.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="bg-gradient-to-br from-primary-50 to-secondary-50 dark:from-gray-800 dark:to-gray-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <p class="text-primary-600 font-semibold text-sm uppercase tracking-wider mb-2">Why Us</p>
                <h2 class="text-3xl md:text-4xl font-heading font-bold <?= $dark ? 'text-white' : 'text-gray-900' ?>">Why Choose <span class="text-gradient">Devi Fancy Store</span>?</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($values as $v): ?>
                <div class="rounded-2xl p-6 shadow-md border transition-all hover:-translate-y-1 hover:shadow-xl <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                    <div class="text-4xl mb-4"><?= $v['icon'] ?></div>
                    <h3 class="text-lg font-heading font-bold mb-2 <?= $dark ? 'text-white' : 'text-gray-900' ?>"><?= $v['title'] ?></h3>
                    <p class="text-sm leading-relaxed <?= $dark ? 'text-gray-400' : 'text-gray-600' ?>"><?= $v['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Journey Timeline -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <p class="text-primary-600 font-semibold text-sm uppercase tracking-wider mb-2">Our Journey</p>
            <h2 class="text-3xl md:text-4xl font-heading font-bold <?= $dark ? 'text-white' : 'text-gray-900' ?>">Milestones That <span class="text-gradient">Matter</span></h2>
        </div>
        <div class="relative">
            <div class="absolute left-1/2 -translate-x-px top-0 bottom-0 w-0.5 bg-gradient-to-b from-primary-500 to-secondary-500 hidden md:block"></div>
            <div class="space-y-8 md:space-y-0">
                <?php foreach ($milestones as $i => $m): ?>
                <div class="relative md:flex items-center <?= $i % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse' ?>">
                    <div class="md:w-1/2 <?= $i % 2 === 0 ? 'md:pr-12 md:text-right' : 'md:pl-12' ?>">
                        <div class="rounded-2xl p-6 shadow-md border <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                            <span class="text-primary-600 font-heading font-bold text-lg"><?= $m['year'] ?></span>
                            <h3 class="font-heading font-bold text-lg mt-1 <?= $dark ? 'text-white' : 'text-gray-900' ?>"><?= $m['title'] ?></h3>
                            <p class="text-sm mt-1 <?= $dark ? 'text-gray-400' : 'text-gray-600' ?>"><?= $m['desc'] ?></p>
                        </div>
                    </div>
                    <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 w-4 h-4 bg-primary-500 rounded-full border-4 border-white dark:border-gray-900 shadow-lg z-10"></div>
                    <div class="md:w-1/2"></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-secondary-700 rounded-3xl overflow-hidden shadow-2xl">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>
            </div>
            <div class="relative px-8 py-14 md:px-16 text-center">
                <h2 class="text-3xl md:text-4xl font-heading font-bold text-white mb-4">Ready to Explore?</h2>
                <p class="text-white/80 text-lg max-w-xl mx-auto mb-8">Discover our handpicked collection of accessories, cosmetics, and gift items. Find something special for yourself or your loved ones.</p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="<?= $baseUrl ?>/products" class="bg-white text-primary-700 font-semibold py-3.5 px-8 rounded-xl hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-[1.02]">Shop Now</a>
                    <a href="<?= $baseUrl ?>/page/contact-us" class="border-2 border-white text-white font-semibold py-3 px-8 rounded-xl hover:bg-white/10 transition-all duration-300">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
