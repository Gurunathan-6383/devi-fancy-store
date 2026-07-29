<?php
$items = $wishlist_items ?? [];
$dark = $dark ?? false;
$adminBase = rtrim(env('APP_URL', ''), '/');

function parseImages($images) {
    if (!$images) return [];
    if (is_array($images)) return $images;
    $decoded = json_decode($images, true);
    return is_array($decoded) ? $decoded : [];
}
?>
<?php if (!empty($items)): ?>
<div class="py-10 min-h-[80vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="<?= $adminBase ?>/products" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Continue Shopping
            </a>
            <h1 class="text-4xl font-heading font-bold <?= $dark ? 'text-white' : 'text-gray-900' ?>">My Wishlist</h1>
            <p class="mt-1 <?= $dark ? 'text-gray-400' : 'text-gray-500' ?>"><?= count($items) ?> item(s)</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <?php foreach ($items as $item):
                $imgs = parseImages($item['images'] ?? []);
            ?>
            <div class="relative group">
                <a href="<?= $adminBase ?>/products/<?= htmlspecialchars($item['slug'] ?? '') ?>">
                    <div class="card card-hover <?= $dark ? 'bg-gray-800 border-gray-700' : '' ?>">
                        <div class="relative aspect-square overflow-hidden">
                            <img src="<?= htmlspecialchars($imgs[0] ?? 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=400&q=80') ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" />
                        </div>
                        <div class="p-4">
                            <p class="text-[11px] font-semibold uppercase tracking-wider mb-1 <?= $dark ? 'text-primary-400' : 'text-primary-600' ?>"><?= htmlspecialchars($item['category_name'] ?? '') ?></p>
                            <h3 class="font-bold text-sm line-clamp-1 <?= $dark ? 'text-white' : 'text-gray-900' ?>"><?= htmlspecialchars($item['name'] ?? '') ?></h3>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-lg font-extrabold <?= $dark ? 'text-white' : 'text-gray-900' ?>">₹<?= round(($item['offer_price'] ?? 0) ?: ($item['price'] ?? 0)) ?></span>
                                <?php if (!empty($item['offer_price'])): ?><span class="text-sm line-through <?= $dark ? 'text-gray-500' : 'text-gray-400' ?>">₹<?= round($item['price'] ?? 0) ?></span><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
                <button onclick="removeWishlistItem(<?= $item['product_id'] ?? $item['id'] ?? 0 ?>)" class="absolute top-3 right-3 w-9 h-9 bg-white/90 hover:bg-red-50 rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:scale-110 z-10">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
function removeWishlistItem(productId) {
    fetch('<?= $adminBase ?>/api/wishlist/toggle', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ product_id: productId }) })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast('Removed from wishlist'); setTimeout(function() { location.reload(); }, 300); } else { showToast('Failed to remove', 'error'); } })
    .catch(function() { showToast('Error', 'error'); });
}
</script>
<?php else: ?>
<div class="py-20 text-center min-h-[60vh] flex items-center justify-center">
    <div class="max-w-md mx-auto px-4 animate-scale-in">
        <div class="w-28 h-28 mx-auto mb-6 rounded-full flex items-center justify-center <?= $dark ? 'bg-red-900/30' : 'bg-gradient-to-br from-red-100 to-pink-100' ?>">
            <svg class="w-14 h-14 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <h2 class="text-3xl font-heading font-bold mb-3 <?= $dark ? 'text-white' : 'text-gray-900' ?>">My Wishlist is Empty</h2>
        <p class="mb-8 leading-relaxed <?= $dark ? 'text-gray-400' : 'text-gray-500' ?>">Save your favorite items here for later.</p>
        <a href="<?= $adminBase ?>/products" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            Start Shopping
        </a>
    </div>
</div>
<?php endif; ?>
