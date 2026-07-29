<?php
$cart = $cart_items ?? [];
$dark = $dark ?? false;
$baseUrl = rtrim(env('APP_URL', ''), '/');
$cartCount = $cart_count ?? 0;
$subtotal = 0;
foreach ($cart as $item) {
    $p = ($item['offer_price'] ?? 0) ?: ($item['price'] ?? 0);
    $subtotal += $p * ($item['quantity'] ?? 1);
}
$shipping = $subtotal >= 500 ? 0 : 49;
$total = $subtotal + $shipping;
?>
<?php if (empty($cart)): ?>
<div class="py-20 text-center min-h-[60vh] flex items-center justify-center">
    <div class="max-w-md mx-auto px-4">
        <h2 class="text-2xl font-heading font-bold text-gray-900 dark:text-white mb-3">No items to checkout</h2>
        <a href="<?= $baseUrl ?>/products" class="btn-primary inline-flex items-center gap-2">Start Shopping</a>
    </div>
</div>
<?php else: ?>
<div class="py-10 min-h-[80vh]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="<?= $baseUrl ?>/cart" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Cart
        </a>
        <h1 class="text-4xl font-heading font-bold text-gray-900 dark:text-white mb-8">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3">
                <div class="card p-8">
                    <h2 class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-6">Shipping Details</h2>
                    <form onsubmit="handlePlaceOrder(event)" class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                            <input type="text" id="checkout-name" class="input-field" placeholder="Enter your full name" required />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                            <input type="tel" id="checkout-phone" class="input-field" placeholder="Enter your phone number" required />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Delivery Address</label>
                            <textarea id="checkout-address" class="input-field" rows="4" placeholder="Enter your full address" required></textarea>
                        </div>
                        <button type="submit" id="place-order-btn" class="btn-primary w-full py-4 text-lg flex items-center justify-center gap-2">
                            <span id="place-order-text">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Place Order &bull; ₹<?= round($total) ?>
                            </span>
                            <div id="place-order-spinner" class="hidden animate-spin rounded-full h-6 w-6 border-2 border-white border-t-transparent"></div>
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="card p-6 sticky top-24">
                    <div class="mb-4"><?= view('components.logo', ['size' => 'sm']) ?></div>
                    <h3 class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">Order Summary</h3>
                    <p class="text-sm text-gray-500 mb-4"><?= $cartCount ?> item(s)</p>
                    <div class="space-y-3 mb-5 max-h-60 overflow-y-auto">
                        <?php foreach ($cart as $item):
                            $p = ($item['offer_price'] ?? 0) ?: ($item['price'] ?? 0);
                            $img = $item['image'] ?? 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=100&q=80';
                        ?>
                        <div class="flex items-center gap-3">
                            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($item['name'] ?? '') ?>" class="w-12 h-12 rounded-lg object-cover flex-shrink-0" />
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate"><?= htmlspecialchars($item['name'] ?? '') ?></p>
                                <p class="text-xs text-gray-500">Qty: <?= $item['quantity'] ?? 1 ?></p>
                            </div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">₹<?= round($p * ($item['quantity'] ?? 1)) ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="border-t pt-4 space-y-2">
                        <div class="flex justify-between text-gray-600 dark:text-gray-400 text-sm">
                            <span>Subtotal</span>
                            <span class="font-semibold">₹<?= round($subtotal) ?></span>
                        </div>
                        <div class="flex justify-between text-gray-600 dark:text-gray-400 text-sm">
                            <span>Shipping</span>
                            <span class="font-semibold <?= $shipping === 0 ? 'text-green-600' : '' ?>"><?= $shipping === 0 ? 'Free' : '₹' . $shipping ?></span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="text-2xl font-extrabold text-gray-900 dark:text-white">₹<?= round($total) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function handlePlaceOrder(e) {
    e.preventDefault();
    var btn = document.getElementById('place-order-btn');
    var text = document.getElementById('place-order-text');
    var spinner = document.getElementById('place-order-spinner');
    btn.disabled = true; text.classList.add('hidden'); spinner.classList.remove('hidden');

    var data = {
        name: document.getElementById('checkout-name').value.trim(),
        phone: document.getElementById('checkout-phone').value.trim(),
        address: document.getElementById('checkout-address').value.trim()
    };
    if (!data.name || !data.phone || !data.address) { alert('Please fill all fields'); btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden'); return; }

    fetch('<?= $baseUrl ?>/api/orders', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) {
            showToast('Order placed successfully!');
            setTimeout(function() { window.location.href = '<?= $baseUrl ?>/'; }, 800);
        } else { alert(res.message || 'Failed to place order'); btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden'); }
    })
    .catch(function() { alert('Failed to place order. Please try again.'); btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden'); });
}
</script>
<?php endif; ?>
