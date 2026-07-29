<?php
$cart = $cart_items ?? [];
$dark = $dark ?? false;
$baseUrl = rtrim(env('APP_URL', ''), '/');
$cartCount = $cart_count ?? 0;
$subtotal = 0;
$shipping = 0;
$total = 0;
foreach ($cart as $item) {
    $p = ($item['offer_price'] ?? 0) ?: ($item['price'] ?? 0);
    $subtotal += $p * ($item['quantity'] ?? 1);
}
$shipping = $subtotal >= 500 ? 0 : 49;
$total = $subtotal + $shipping;
?>
<?php if (empty($cart)): ?>
<div class="py-20 text-center min-h-[60vh] flex items-center justify-center">
    <div class="max-w-md mx-auto px-4 animate-scale-in">
        <div class="w-28 h-28 mx-auto mb-6 bg-gradient-to-br from-primary-100 to-secondary-100 rounded-full flex items-center justify-center">
            <svg class="w-14 h-14 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
        </div>
        <h2 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mb-3">Your Cart is Empty</h2>
        <p class="text-gray-500 mb-8 leading-relaxed">Looks like you haven't added anything yet. Start exploring our collection!</p>
        <a href="<?= $baseUrl ?>/products" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            Start Shopping
        </a>
    </div>
</div>
<?php else: ?>
<div class="py-10 min-h-[80vh]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="<?= $baseUrl ?>/products" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-3 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Continue Shopping
            </a>
            <h1 class="text-4xl font-heading font-bold text-gray-900 dark:text-white">Shopping Cart</h1>
            <p class="text-gray-500 mt-1"><?= $cartCount ?> item(s) in your cart</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                <?php foreach ($cart as $i => $item):
                    $p = ($item['offer_price'] ?? 0) ?: ($item['price'] ?? 0);
                    $img = $item['image'] ?? 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=200&q=80';
                ?>
                <div class="card p-5 flex items-center gap-5 animate-slide-up" style="animation-delay: <?= $i * 0.05 ?>s">
                    <a href="<?= $baseUrl ?>/products/<?= htmlspecialchars($item['slug'] ?? '') ?>" class="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700 shadow-inner">
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" />
                    </a>
                    <div class="flex-1 min-w-0">
                        <a href="<?= $baseUrl ?>/products/<?= htmlspecialchars($item['slug'] ?? '') ?>" class="font-bold text-gray-900 dark:text-white hover:text-primary-600 transition-colors line-clamp-1 text-lg"><?= htmlspecialchars($item['name'] ?? '') ?></a>
                        <p class="text-primary-600 font-bold text-lg mt-1">₹<?= round($p) ?></p>
                    </div>
                    <div class="flex items-center border-2 border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                        <button onclick="updateCartItem(<?= $item['id'] ?? 0 ?>, -1)" class="p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <span class="px-5 py-3 font-bold text-lg min-w-[3rem] text-center bg-gray-50 dark:bg-gray-700"><?= $item['quantity'] ?? 1 ?></span>
                        <button onclick="updateCartItem(<?= $item['id'] ?? 0 ?>, 1)" class="p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                    <p class="font-extrabold text-gray-900 dark:text-white w-20 text-right text-lg">₹<?= round($p * ($item['quantity'] ?? 1)) ?></p>
                    <button onclick="removeCartItem(<?= $item['id'] ?? 0 ?>)" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-24">
                    <h3 class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-5">Order Summary</h3>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-600 dark:text-gray-400">
                            <span>Subtotal (<?= $cartCount ?> items)</span>
                            <span class="font-semibold">₹<?= round($subtotal) ?></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-400">
                            <span>Shipping</span>
                            <span class="font-semibold <?= $shipping === 0 ? 'text-green-600' : '' ?>"><?= $shipping === 0 ? 'Free' : '₹' . $shipping ?></span>
                        </div>
                        <?php if ($shipping > 0): ?>
                        <p class="text-xs text-green-600 bg-green-50 px-3 py-2 rounded-lg">Add ₹<?= max(0, 500 - round($subtotal)) ?> more for free shipping!</p>
                        <?php endif; ?>
                    </div>
                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="text-2xl font-extrabold text-gray-900 dark:text-white">₹<?= round($total) ?></span>
                        </div>
                    </div>
                    <a href="<?= $baseUrl ?>/checkout" class="btn-primary w-full text-center flex items-center justify-center gap-2 py-4 text-lg">Proceed to Checkout</a>
                    <a href="<?= $baseUrl ?>/products" class="block text-center text-primary-600 hover:text-primary-700 font-medium mt-4 transition-colors">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateCartItem(id, delta) {
    fetch('<?= $baseUrl ?>/api/cart/update', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ product_id: id, delta: delta }) })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) location.reload(); else showToast(res.message || 'Error', 'error'); })
    .catch(function() { showToast('Error updating cart', 'error'); });
}
function removeCartItem(id) {
    fetch('<?= $baseUrl ?>/api/cart/remove', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ product_id: id }) })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) location.reload(); else showToast(res.message || 'Error', 'error'); })
    .catch(function() { showToast('Error removing item', 'error'); });
}
</script>
<?php endif; ?>
