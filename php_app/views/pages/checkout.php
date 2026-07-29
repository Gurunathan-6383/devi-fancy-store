<?php $baseUrl = rtrim(env('APP_URL', ''), '/'); ?>
<div id="checkout-page"></div>

<script>
(function() {
    var baseUrl = '<?= $baseUrl ?>';
    var cart = getCart();
    var container = document.getElementById('checkout-page');

    function render() {
        if (cart.length === 0) {
            container.innerHTML = '<div class="py-20 text-center min-h-[60vh] flex items-center justify-center">' +
                '<div class="max-w-md mx-auto px-4">' +
                '<h2 class="text-2xl font-heading font-bold text-gray-900 dark:text-white mb-3">No items to checkout</h2>' +
                '<a href="' + baseUrl + '/products" class="btn-primary inline-flex items-center gap-2">Start Shopping</a>' +
                '</div></div>';
            return;
        }

        var subtotal = getCartTotal();
        var itemCount = getCartItemCount();
        var shipping = subtotal >= 500 ? 0 : 49;
        var total = subtotal + shipping;

        var html = '<div class="py-10 min-h-[80vh]"><div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">';
        html += '<a href="' + baseUrl + '/cart" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-3 transition-colors">';
        html += '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>';
        html += 'Back to Cart</a>';
        html += '<h1 class="text-4xl font-heading font-bold text-gray-900 dark:text-white mb-8">Checkout</h1>';

        html += '<div class="grid grid-cols-1 lg:grid-cols-5 gap-8">';

        html += '<div class="lg:col-span-3">';
        html += '<div class="card p-8">';
        html += '<h2 class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-6">Shipping Details</h2>';
        html += '<form id="checkout-form" class="space-y-5">';
        html += '<div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>';
        html += '<input type="text" id="checkout-name" class="input-field" placeholder="Enter your full name" required /></div>';
        html += '<div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>';
        html += '<input type="tel" id="checkout-phone" class="input-field" placeholder="Enter your phone number" required /></div>';
        html += '<div><label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Delivery Address</label>';
        html += '<textarea id="checkout-address" class="input-field" rows="4" placeholder="Enter your full address" required></textarea></div>';
        html += '<button type="submit" id="place-order-btn" class="btn-primary w-full py-4 text-lg flex items-center justify-center gap-2">';
        html += '<span id="place-order-text"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Place Order &bull; \u20B9' + Math.round(total) + '</span>';
        html += '<div id="place-order-spinner" class="hidden animate-spin rounded-full h-6 w-6 border-2 border-white border-t-transparent"></div>';
        html += '</button></form></div></div>';

        html += '<div class="lg:col-span-2">';
        html += '<div class="card p-6 sticky top-24">';
        html += '<div class="mb-4"><?= view("components.logo", ["size" => "sm"]) ?></div>';
        html += '<h3 class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">Order Summary</h3>';
        html += '<p class="text-sm text-gray-500 mb-4">' + itemCount + ' item(s)</p>';
        html += '<div class="space-y-3 mb-5 max-h-60 overflow-y-auto">';

        cart.forEach(function(item) {
            var price = item.offer_price || item.price;
            var img = item.image || 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=100&q=80';
            html += '<div class="flex items-center gap-3">';
            html += '<img src="' + img + '" alt="' + (item.name || '') + '" class="w-12 h-12 rounded-lg object-cover flex-shrink-0" />';
            html += '<div class="flex-1 min-w-0">';
            html += '<p class="text-sm font-semibold text-gray-900 dark:text-white truncate">' + (item.name || '') + '</p>';
            html += '<p class="text-xs text-gray-500">Qty: ' + item.quantity + '</p>';
            html += '</div>';
            html += '<p class="text-sm font-bold text-gray-900 dark:text-white">\u20B9' + Math.round(price * item.quantity) + '</p>';
            html += '</div>';
        });

        html += '</div>';
        html += '<div class="border-t pt-4 space-y-2">';
        html += '<div class="flex justify-between text-gray-600 dark:text-gray-400 text-sm"><span>Subtotal</span><span class="font-semibold">\u20B9' + Math.round(subtotal) + '</span></div>';
        html += '<div class="flex justify-between text-gray-600 dark:text-gray-400 text-sm"><span>Shipping</span>';
        html += '<span class="font-semibold ' + (shipping === 0 ? 'text-green-600' : '') + '">' + (shipping === 0 ? 'Free' : '\u20B9' + shipping) + '</span></div>';
        html += '<div class="border-t pt-3 flex justify-between">';
        html += '<span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>';
        html += '<span class="text-2xl font-extrabold text-gray-900 dark:text-white">\u20B9' + Math.round(total) + '</span>';
        html += '</div></div></div></div>';

        html += '</div></div></div>';
        container.innerHTML = html;

        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            handlePlaceOrder();
        });
    }

    function handlePlaceOrder() {
        var btn = document.getElementById('place-order-btn');
        var text = document.getElementById('place-order-text');
        var spinner = document.getElementById('place-order-spinner');
        btn.disabled = true; text.classList.add('hidden'); spinner.classList.remove('hidden');

        var name = document.getElementById('checkout-name').value.trim();
        var phone = document.getElementById('checkout-phone').value.trim();
        var address = document.getElementById('checkout-address').value.trim();

        if (!name || !phone || !address) {
            if (typeof showToast === 'function') showToast('Please fill all fields', 'error');
            btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden');
            return;
        }

        var items = cart.map(function(item) {
            return { id: item.id, name: item.name, quantity: item.quantity, price: item.offer_price || item.price };
        });
        var subtotal = getCartTotal();
        var shipping = subtotal >= 500 ? 0 : 49;
        var total = subtotal + shipping;

        fetch(baseUrl + '/api/orders', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name: name, phone: phone, address: address, items: items, total: Math.round(total) })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                clearCart();
                if (typeof showToast === 'function') showToast('Order placed successfully!');
                setTimeout(function() { window.location.href = baseUrl + '/'; }, 800);
            } else {
                if (typeof showToast === 'function') showToast(res.message || 'Failed to place order', 'error');
                btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden');
            }
        })
        .catch(function() {
            if (typeof showToast === 'function') showToast('Failed to place order. Please try again.', 'error');
            btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden');
        });
    }

    render();
})();
</script>
