<?php
$dark = $dark ?? false;
$footerLinks = [
    ['label' => 'Contact Us', 'slug' => 'contact-us'],
    ['label' => 'About Us', 'slug' => 'about-us'],
    ['label' => 'FAQ', 'slug' => 'faq'],
    ['label' => 'Privacy Policy', 'slug' => 'privacy-policy'],
    ['label' => 'Terms & Conditions', 'slug' => 'terms-and-conditions'],
    ['label' => 'Return Policy', 'slug' => 'return-policy'],
    ['label' => 'Shipping Policy', 'slug' => 'shipping-policy'],
];
$currentYear = date('Y');
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<footer class="relative overflow-hidden <?= $dark ? 'bg-gray-950 text-gray-400' : 'bg-gray-900 text-gray-300' ?>">
    <div class="absolute inset-0 bg-gradient-to-br from-primary-900/20 via-transparent to-secondary-900/20"></div>
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-primary-500/50 to-transparent"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <div>
                <?= view('components.logo', ['size' => 'md']) ?>
                <p class="text-gray-400 leading-relaxed mt-4 text-sm">
                    Your one-stop destination for beautiful accessories, cosmetics, and gift items. Discover elegance with every purchase.
                </p>
            </div>
            <div>
                <h4 class="text-lg font-heading font-semibold text-white mb-4">Quick Links</h4>
                <div class="space-y-2">
                    <a href="<?= $baseUrl ?>/" class="block text-sm text-gray-400 hover:text-primary-400 transition-colors">Home</a>
                    <a href="<?= $baseUrl ?>/categories" class="block text-sm text-gray-400 hover:text-primary-400 transition-colors">Categories</a>
                    <a href="<?= $baseUrl ?>/catalogues" class="block text-sm text-gray-400 hover:text-primary-400 transition-colors">Catalogues</a>
                    <a href="<?= $baseUrl ?>/products" class="block text-sm text-gray-400 hover:text-primary-400 transition-colors">Products</a>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-heading font-semibold text-white mb-4">Policies</h4>
                <div class="space-y-2">
                    <?php foreach ($footerLinks as $link): ?>
                        <a href="<?= $baseUrl ?>/page/<?= htmlspecialchars($link['slug']) ?>" class="block text-sm text-gray-400 hover:text-primary-400 transition-colors"><?= $link['label'] ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <h4 class="text-lg font-heading font-semibold text-white mb-4">Contact</h4>
                <div class="space-y-2 text-sm text-gray-400">
                    <p>Phone: +91 63838 11702</p>
                    <p>Email: contact@devifancystore.com</p>
                    <p>Address: Thiruthangal, Sivakasi</p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">&copy; <?= $currentYear ?> Devi Fancy Store. All rights reserved.</p>
            <div class="flex items-center gap-4 text-sm text-gray-500">
                <a href="<?= $baseUrl ?>/admin/login" class="hover:text-primary-400 transition-colors">Admin</a>
                <span>&middot;</span>
                <div class="flex items-center gap-1">
                    Made with <span class="text-primary-500">&hearts;</span> for Devi Fancy Store
                </div>
            </div>
        </div>
    </div>
</footer>
