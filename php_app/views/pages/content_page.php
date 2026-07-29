<?php
$page = $page ?? null;
$error = $error ?? null;
$slug = $slug ?? '';
$dark = $dark ?? false;
$baseUrl = rtrim(env('APP_URL', ''), '/');

$PAGE_MAP = [
    'contact-us' => ['icon' => '📞', 'accent' => 'from-pink-500 to-rose-500'],
    'about-us' => ['icon' => '💡', 'accent' => 'from-purple-500 to-indigo-500'],
    'faq' => ['icon' => '❓', 'accent' => 'from-amber-500 to-orange-500'],
    'privacy-policy' => ['icon' => '🔒', 'accent' => 'from-emerald-500 to-teal-500'],
    'terms-and-conditions' => ['icon' => '📜', 'accent' => 'from-blue-500 to-cyan-500'],
    'return-policy' => ['icon' => '🔄', 'accent' => 'from-red-500 to-pink-500'],
    'shipping-policy' => ['icon' => '🚚', 'accent' => 'from-violet-500 to-purple-500'],
];
$meta = $PAGE_MAP[$slug] ?? ['icon' => '📄', 'accent' => 'from-gray-500 to-gray-600'];
?>
<?php if ($error): ?>
<div class="max-w-4xl mx-auto px-4 py-20 text-center">
    <p class="text-6xl mb-4">📭</p>
    <h2 class="text-2xl font-heading font-bold text-gray-900 dark:text-white mb-2"><?= htmlspecialchars($error) ?></h2>
    <a href="<?= $baseUrl ?>/" class="text-primary-600 hover:text-primary-700 font-medium mt-4 inline-block">&larr; Back to Home</a>
</div>
<?php elseif ($page): ?>
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Breadcrumb -->
    <div class="bg-gradient-to-r <?= $meta['accent'] ?> py-12 md:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <nav class="flex items-center gap-2 text-white/70 text-sm mb-4">
                <a href="<?= $baseUrl ?>/" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <span class="text-white"><?= htmlspecialchars($page['title'] ?? '') ?></span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-heading font-bold text-white flex items-center gap-3">
                <span class="text-4xl"><?= $meta['icon'] ?></span>
                <?= htmlspecialchars($page['title'] ?? '') ?>
            </h1>
            <?php if (!empty($page['meta_description'])): ?><p class="text-white/80 mt-2 text-sm"><?= htmlspecialchars($page['meta_description']) ?></p><?php endif; ?>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 md:py-14">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 md:p-10">
            <div class="prose prose-lg max-w-none prose-headings:font-heading prose-headings:text-gray-900 dark:prose-headings:text-white prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-li:text-gray-700 dark:prose-li:text-gray-300 prose-a:text-primary-600 hover:prose-a:text-primary-700 prose-strong:text-gray-900 dark:prose-strong:text-white">
                <?= $page['content'] ?? '' ?>
            </div>
        </div>
        <?php if (!empty($page['updated_at'])): ?>
        <div class="mt-8 text-center">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Last updated: <?= date('F d, Y', strtotime($page['updated_at'])) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php else: ?>
<div class="flex justify-center py-20">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent"></div>
</div>
<?php endif; ?>
