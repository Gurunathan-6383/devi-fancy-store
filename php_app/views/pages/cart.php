<?php $baseUrl = rtrim(env('APP_URL', ''), '/'); ?>
<div id="cart-page"></div>

<script>
(function() {
    var baseUrl = '<?= $baseUrl ?>';
    var cart = getCart();
    var container = document.getElementById('cart-page');

    function render() {
        if (cart.length === 0) {
            container.innerHTML = '<div class="py-20 text-center min-h-[60vh] flex items-center justify-center">' +
                '<div class="max-w-md mx-auto px-4 animate-scale-in">' +
                '<div class="w-28 h-28 mx-auto mb-6 bg-gradient-to-br from-primary-100 to-secondary-100 rounded-full flex items-center justify-center">' +
                '<svg class="w-14 h-14 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>' +
                '</div>' +
                '<h2 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mb-3">Your Cart is Empty</h2>' +
                '<p class="text-gray-500 mb-8 leading-relaxed">Looks like you haven\'t added anything yet. Start exploring our collection!</p>' +
                '<a href="' + baseUrl + '/products" class="btn-primary inline-flex items-center gap-2">' +
                '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>' +
                'Start Shopping</a></div></div>';
            return;
        }

        var subtotal = getCartTotal();
        var itemCount = getCartItemCount();
        var shipping = subtotal >= 500 ? 0 : 49;
        var total = subtotal + shipping;

        var html = '<div class="py-10 min-h-[80vh]"><div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">';
        html += '<div class="mb-8">';
        html += '<a href="' + baseUrl + '/products" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-3 transition-colors">';
        html += '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>';
        html += 'Continue Shopping</a>';
        html += '<h1 class="text-4xl font-heading font-bold text-gray-900 dark:text-white">Shopping Cart</h1>';
        html += '<p class="text-gray-500 mt-1">' + itemCount + ' item(s) in your cart</p>';
        html += '</div>';

        html += '<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">';
        html += '<div class="lg:col-span-2 space-y-4">';

        cart.forEach(function(item, i) {
            var price = item.offer_price || item.price;
            var img = item.image || 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=200&q=80';
            html += '<div class="card p-5 flex items-center gap-5 animate-slide-up" style="animation-delay:' + (i * 0.05) + 's">';
            html += '<a href="' + baseUrl + '/products/' + (item.slug || '') + '" class="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700 shadow-inner">';
            html += '<img src="' + img + '" alt="' + (item.name || '') + '" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" />';
            html += '</a>';
            html += '<div class="flex-1 min-w-0">';
            html += '<a href="' + baseUrl + '/products/' + (item.slug || '') + '" class="font-bold text-gray-900 dark:text-white hover:text-primary-600 transition-colors line-clamp-1 text-lg">' + (item.name || '') + '</a>';
            html += '<p class="text-primary-600 font-bold text-lg mt-1">\u20B9' + Math.round(price) + '</p>';
            html += '</div>';
            html += '<div class="flex items-center border-2 border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">';
            html += '<button onclick="cartUpdateQty(\'' + item.id + '\', -1)" class="p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">';
            html += '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>';
            html += '</button>';
            html += '<span class="px-5 py-3 font-bold text-lg min-w-[3rem] text-center bg-gray-50 dark:bg-gray-700">' + item.quantity + '</span>';
            html += '<button onclick="cartUpdateQty(\'' + item.id + '\', 1)" class="p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">';
            html += '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>';
            html += '</button>';
            html += '</div>';
            html += '<p class="font-extrabold text-gray-900 dark:text-white w-20 text-right text-lg">\u20B9' + Math.round(price * item.quantity) + '</p>';
            html += '<button onclick="cartRemoveItem(\'' + item.id + '\')" class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">';
            html += '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
            html += '</button>';
            html += '</div>';
        });

        html += '</div>';

        html += '<div class="lg:col-span-1">';
        html += '<div class="card p-6 sticky top-24">';
        html += '<h3 class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-5">Order Summary</h3>';
        html += '<div class="space-y-3 mb-6">';
        html += '<div class="flex justify-between text-gray-600 dark:text-gray-400">';
        html += '<span>Subtotal (' + itemCount + ' items)</span>';
        html += '<span class="font-semibold">\u20B9' + Math.round(subtotal) + '</span>';
        html += '</div>';
        html += '<div class="flex justify-between text-gray-600 dark:text-gray-400">';
        html += '<span>Shipping</span>';
        html += '<span class="font-semibold ' + (shipping === 0 ? 'text-green-600' : '') + '">' + (shipping === 0 ? 'Free' : '\u20B9' + shipping) + '</span>';
        html += '</div>';
        if (shipping > 0) {
            html += '<p class="text-xs text-green-600 bg-green-50 px-3 py-2 rounded-lg">Add \u20B9' + Math.max(0, 500 - Math.round(subtotal)) + ' more for free shipping!</p>';
        }
        html += '</div>';
        html += '<div class="border-t pt-4 mb-6">';
        html += '<div class="flex justify-between">';
        html += '<span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>';
        html += '<span class="text-2xl font-extrabold text-gray-900 dark:text-white">\u20B9' + Math.round(total) + '</span>';
        html += '</div></div>';
        html += '<a href="' + baseUrl + '/checkout" class="btn-primary w-full text-center flex items-center justify-center gap-2 py-4 text-lg">Proceed to Checkout</a>';
        html += '<a href="' + baseUrl + '/products" class="block text-center text-primary-600 hover:text-primary-700 font-medium mt-4 transition-colors">Continue Shopping</a>';
        html += '</div></div>';

        html += '</div></div></div>';
        container.innerHTML = html;
    }

    window.cartUpdateQty = function(id, delta) {
        updateCartQuantity(id, delta);
        cart = getCart();
        render();
    };

    window.cartRemoveItem = function(id) {
        var item = cart.find(function(c) { return String(c.id) === String(id); });
        removeFromCart(id);
        cart = getCart();
        if (typeof showToast === 'function' && item) showToast(item.name + ' removed from cart');
        render();
    };

    render();
})();
</script>
