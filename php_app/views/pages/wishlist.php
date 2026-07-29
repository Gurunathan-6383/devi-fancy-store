<?php $baseUrl = rtrim(env('APP_URL', ''), '/'); ?>
<div id="wishlist-page"></div>

<script>
(function() {
    var baseUrl = '<?= $baseUrl ?>';
    var container = document.getElementById('wishlist-page');
    var token = localStorage.getItem('customerToken');

    function parseImages(images) {
        if (!images) return [];
        if (Array.isArray(images)) return images;
        try { return JSON.parse(images); } catch(e) { return []; }
    }

    function showLoading() {
        var html = '<div class="py-12 max-w-7xl mx-auto px-4"><div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">';
        for (var i = 0; i < 4; i++) {
            html += '<div class="card animate-pulse"><div class="aspect-square bg-gray-200 dark:bg-gray-700"></div>';
            html += '<div class="p-4 space-y-2"><div class="h-4 rounded w-3/4 bg-gray-200 dark:bg-gray-700"></div>';
            html += '<div class="h-6 rounded w-1/2 bg-gray-200 dark:bg-gray-700"></div></div></div>';
        }
        html += '</div></div>';
        container.innerHTML = html;
    }

    function showEmpty() {
        container.innerHTML = '<div class="py-20 text-center min-h-[60vh] flex items-center justify-center">' +
            '<div class="max-w-md mx-auto px-4 animate-scale-in">' +
            '<div class="w-28 h-28 mx-auto mb-6 rounded-full flex items-center justify-center bg-gradient-to-br from-red-100 to-pink-100">' +
            '<svg class="w-14 h-14 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>' +
            '</div>' +
            '<h2 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mb-3">My Wishlist is Empty</h2>' +
            '<p class="text-gray-500 mb-8 leading-relaxed">Save your favorite items here for later.</p>' +
            '<a href="' + baseUrl + '/products" class="btn-primary inline-flex items-center gap-2">' +
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>' +
            'Start Shopping</a></div></div>';
    }

    function renderItems(items) {
        if (items.length === 0) { showEmpty(); return; }

        var html = '<div class="py-10 min-h-[80vh]"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">';
        html += '<div class="mb-8">';
        html += '<a href="' + baseUrl + '/products" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-3 transition-colors">';
        html += '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>';
        html += 'Continue Shopping</a>';
        html += '<h1 class="text-4xl font-heading font-bold text-gray-900 dark:text-white">My Wishlist</h1>';
        html += '<p class="text-gray-500 mt-1">' + items.length + ' item(s)</p>';
        html += '</div>';
        html += '<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">';

        items.forEach(function(item) {
            var imgs = parseImages(item.images);
            var img = imgs[0] || 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=400&q=80';
            var price = item.offer_price || item.price;
            html += '<div class="relative group">';
            html += '<a href="' + baseUrl + '/products/' + (item.slug || '') + '">';
            html += '<div class="card card-hover">';
            html += '<div class="relative aspect-square overflow-hidden">';
            html += '<img src="' + img + '" alt="' + (item.name || '') + '" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" />';
            html += '</div>';
            html += '<div class="p-4">';
            html += '<p class="text-[11px] font-semibold uppercase tracking-wider mb-1 text-primary-600">' + (item.category_name || '') + '</p>';
            html += '<h3 class="font-bold text-sm line-clamp-1 text-gray-900 dark:text-white">' + (item.name || '') + '</h3>';
            html += '<div class="flex items-center gap-2 mt-2">';
            html += '<span class="text-lg font-extrabold text-gray-900 dark:text-white">\u20B9' + Math.round(price) + '</span>';
            if (item.offer_price) {
                html += '<span class="text-sm line-through text-gray-400">\u20B9' + Math.round(item.price) + '</span>';
            }
            html += '</div></div></div></a>';
            html += '<button onclick="removeWishlistItem(' + (item.product_id || item.id) + ')" class="absolute top-3 right-3 w-9 h-9 bg-white/90 hover:bg-red-50 rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:scale-110 z-10">';
            html += '<svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
            html += '</button></div>';
        });

        html += '</div></div></div>';
        container.innerHTML = html;
    }

    window.removeWishlistItem = function(productId) {
        var token = localStorage.getItem('customerToken');
        if (!token) { window.location.href = baseUrl + '/login'; return; }
        fetch(baseUrl + '/api/wishlist/toggle', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token },
            body: JSON.stringify({ product_id: productId })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success || res.action) {
                if (typeof showToast === 'function') showToast('Removed from wishlist');
                loadWishlist();
            } else {
                if (typeof showToast === 'function') showToast('Failed to remove', 'error');
            }
        })
        .catch(function() { if (typeof showToast === 'function') showToast('Error', 'error'); });
    };

    function loadWishlist() {
        if (!token) { showEmpty(); return; }
        showLoading();
        fetch(baseUrl + '/api/wishlist', {
            headers: { 'Authorization': 'Bearer ' + token }
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            var items = (res.data && Array.isArray(res.data)) ? res.data : (Array.isArray(res) ? res : []);
            renderItems(items);
        })
        .catch(function() { showEmpty(); });
    }

    loadWishlist();
})();
</script>
