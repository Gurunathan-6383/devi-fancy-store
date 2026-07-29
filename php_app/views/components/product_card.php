<?php
$product = $product ?? [];
$dark = $dark ?? false;
$in_cart = $in_cart ?? false;
$in_cart_qty = $in_cart_qty ?? 0;
$wishlisted = $wishlisted ?? false;
$price = ($product['offer_price'] ?? 0) ?: ($product['price'] ?? 0);
$hasOffer = !empty($product['offer_price']);
$discount = $hasOffer ? round((1 - ($product['offer_price'] ?? 0) / ($product['price'] ?? 1)) * 100) : 0;
$images = $product['images'] ?? [];
$firstImage = is_array($images) ? ($images[0] ?? '') : (json_decode($images, true)[0] ?? '');
$baseUrl = rtrim(env('APP_URL', ''), '/');
$slug = $product['slug'] ?? '';
?>
<a href="<?= $baseUrl ?>/products/<?= htmlspecialchars($slug) ?>" class="card card-hover group <?= $dark ? 'bg-gray-800 border-gray-700' : '' ?>">
    <div class="relative overflow-hidden aspect-square">
        <img src="<?= htmlspecialchars($firstImage ?: 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=400&q=80') ?>" alt="<?= htmlspecialchars($product['name'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out" loading="lazy" />

        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

        <?php if ($hasOffer): ?>
        <div class="absolute top-3 left-3 z-10">
            <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg shadow-red-500/30">
                -<?= $discount ?>% OFF
            </div>
        </div>
        <?php endif; ?>

        <?php if (($product['stock'] ?? 0) <= 0): ?>
        <div class="absolute inset-0 bg-black/60 flex items-center justify-center z-10 backdrop-blur-[1px]">
            <span class="text-white font-bold text-lg bg-black/50 px-4 py-2 rounded-full">Out of Stock</span>
        </div>
        <?php endif; ?>

        <div class="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0 z-10">
            <button onclick="event.preventDefault(); toggleWishlist(<?= $product['id'] ?? 0 ?>)" class="w-9 h-9 rounded-full flex items-center justify-center shadow-lg transition-all hover:scale-110 <?= $wishlisted ? 'bg-red-500 text-white' : 'bg-white/90 hover:bg-white text-gray-600 hover:text-red-500' ?>">
                <svg class="w-4 h-4 <?= $wishlisted ? 'fill-white' : '' ?>" fill="<?= $wishlisted ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
            <button onclick="event.preventDefault(); addToCart(<?= $product['id'] ?? 0 ?>)" <?= (($product['stock'] ?? 0) <= 0) ? 'disabled' : '' ?> class="w-9 h-9 bg-primary-500 hover:bg-primary-600 rounded-full flex items-center justify-center shadow-lg shadow-primary-500/30 transition-all hover:scale-110 disabled:opacity-50">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            </button>
        </div>

        <div class="absolute bottom-3 left-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 z-10">
            <a href="<?= $baseUrl ?>/products/<?= htmlspecialchars($slug) ?>" class="flex items-center justify-center gap-2 font-semibold py-2.5 rounded-xl shadow-xl transition-all text-sm <?= $dark ? 'bg-gray-800/95 hover:bg-gray-800 text-white' : 'bg-white/95 hover:bg-white text-gray-900' ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Quick View
            </a>
        </div>
    </div>

    <div class="p-4">
        <p class="text-[11px] font-semibold uppercase tracking-wider mb-1.5 <?= $dark ? 'text-primary-400' : 'text-primary-600' ?>">
            <?= htmlspecialchars($product['category_name'] ?? 'General') ?>
        </p>
        <h3 class="font-bold text-[15px] mb-2 line-clamp-1 transition-colors <?= $dark ? 'text-white group-hover:text-primary-400' : 'text-gray-900 group-hover:text-primary-600' ?>">
            <?= htmlspecialchars($product['name'] ?? '') ?>
        </h3>
        <div class="flex items-center gap-2.5">
            <span class="text-xl font-extrabold <?= $dark ? 'text-white' : 'text-gray-900' ?>">
                ₹<?= round($price) ?>
            </span>
            <?php if ($hasOffer): ?>
                <span class="text-sm line-through font-medium <?= $dark ? 'text-gray-500' : 'text-gray-400' ?>">₹<?= round($product['price'] ?? 0) ?></span>
            <?php endif; ?>
        </div>
        <?php if ($in_cart): ?>
        <div class="mt-2 flex items-center gap-1 text-xs text-primary-600 font-semibold bg-primary-50 dark:bg-primary-900/30 dark:text-primary-400 px-2.5 py-1 rounded-full w-fit">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            <?= $in_cart_qty ?> in cart
        </div>
        <?php endif; ?>
    </div>
</a>
