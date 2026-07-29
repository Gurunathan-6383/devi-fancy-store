<!DOCTYPE html>
<html lang="en" class="<?= (isset($_COOKIE['devi_theme']) && $_COOKIE['devi_theme'] === 'dark') ? 'dark' : '' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Devi Fancy Store') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Your one-stop destination for beautiful accessories, cosmetics, and gift items.') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: { heading: ['Playfair Display', 'serif'], body: ['Inter', 'sans-serif'] },
                colors: {
                    primary: { 50: '#fdf2f4', 100: '#fce7eb', 200: '#f9d0da', 300: '#f4a8ba', 400: '#ec7897', 500: '#e04a6f', 600: '#d6335e', 700: '#b8234a', 800: '#9a1f40', 900: '#801e3a', 950: '#470b1c' },
                    secondary: { 50: '#f5f3ff', 100: '#ede9fe', 200: '#ddd6fe', 300: '#c4b5fd', 400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9', 800: '#5b21b6', 900: '#4c1d95', 950: '#2e1065' },
                    accent: { 50: '#fffde7', 100: '#fff9c4', 200: '#fff59d', 300: '#fff176', 400: '#ffee58', 500: '#ffc800', 600: '#f9a825', 700: '#f57f17', 800: '#f9a825', 900: '#f57f17' },
                },
            },
        },
    };
    </script>
    <style>
        * { scrollbar-width: thin; scrollbar-color: #d6335e #f1f1f1; }
        .dark * { scrollbar-color: #d6335e #1f2937; }
        *::-webkit-scrollbar { width: 6px; }
        *::-webkit-scrollbar-track { background: #f1f1f1; }
        .dark *::-webkit-scrollbar-track { background: #1f2937; }
        *::-webkit-scrollbar-thumb { background: linear-gradient(to bottom, #e04a6f, #7c3aed); border-radius: 3px; }

        @keyframes slide-down { from { opacity: 0; transform: translateY(-12px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
        @keyframes scale-in { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-8px); } }
        @keyframes pulse-glow { 0%, 100% { box-shadow: 0 0 0 0 rgba(224, 74, 111, 0.4); } 50% { box-shadow: 0 0 20px 10px rgba(224, 74, 111, 0); } }
        .animate-slide-down { animation: slide-down 0.4s ease-out; }
        .animate-slide-up { animation: slide-up 0.5s ease-out; }
        .animate-fade-in { animation: fade-in 0.6s ease-out; }
        .animate-scale-in { animation: scale-in 0.4s ease-out; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
        .animate-shimmer { background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.3) 50%, transparent 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; }
        .line-clamp-1 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1; }
        .line-clamp-2 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }

        .btn-primary { @apply bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3 px-7 rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40; }
        .btn-secondary { @apply bg-gradient-to-r from-secondary-600 to-secondary-700 hover:from-secondary-700 hover:to-secondary-800 text-white font-semibold py-3 px-7 rounded-xl transition-all duration-300 shadow-lg shadow-secondary-500/25 hover:shadow-secondary-500/40; }
        .btn-accent { @apply bg-gradient-to-r from-accent-500 to-accent-600 hover:from-accent-600 hover:to-accent-700 text-white font-semibold py-3 px-7 rounded-xl transition-all duration-300 shadow-lg shadow-accent-500/25 hover:shadow-accent-500/40; }
        .btn-outline { @apply border-2 border-primary-500 text-primary-600 hover:bg-primary-600 hover:text-white font-semibold py-3 px-7 rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-primary-500/25; }
        .btn-ghost { @apply text-gray-600 hover:text-primary-600 hover:bg-primary-50 font-medium py-2 px-4 rounded-xl transition-all duration-200; }
        .dark .btn-ghost { @apply text-gray-400 hover:text-primary-400 hover:bg-primary-900/20; }
        .card { @apply bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100/80; }
        .dark .card { @apply bg-gray-800 border-gray-700/80 text-gray-100; }
        .card-hover { @apply hover:-translate-y-1.5 transition-all duration-500; }
        .input-field { @apply w-full px-5 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all duration-300 bg-gray-50/50 focus:bg-white text-gray-900 placeholder-gray-400; }
        .dark .input-field { @apply bg-gray-700/50 border-gray-600 text-white focus:bg-gray-700 placeholder-gray-500; }
        .section-title { @apply text-3xl md:text-5xl font-heading font-bold text-gray-900 relative inline-block; }
        .dark .section-title { @apply text-white; }
        .section-title::after { content: ''; @apply absolute -bottom-3 left-0 w-16 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full; }
        .glass-card { @apply bg-white/70 backdrop-blur-xl border border-white/30 rounded-2xl shadow-xl; }
        .dark .glass-card { @apply bg-gray-800/70 border-gray-700/30; }
        .text-gradient { @apply bg-gradient-to-r from-primary-600 via-primary-500 to-secondary-600 bg-clip-text text-transparent; }
        .text-gradient-gold { @apply bg-gradient-to-r from-accent-400 via-accent-300 to-accent-500 bg-clip-text text-transparent; }
        .bg-mesh { background-image: radial-gradient(at 40% 20%, rgba(224, 74, 111, 0.12) 0px, transparent 50%), radial-gradient(at 80% 0%, rgba(124, 58, 237, 0.08) 0px, transparent 50%), radial-gradient(at 0% 50%, rgba(255, 200, 0, 0.06) 0px, transparent 50%); }

        @layer base {
            body { @apply font-body text-gray-800 bg-gray-50 antialiased; }
            .dark body, .dark { @apply text-gray-100 bg-gray-900; }
            h1, h2, h3, h4, h5, h6 { @apply font-heading; }
            .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6 { @apply text-white; }
            label { @apply text-gray-700; }
            .dark label { @apply text-gray-300; }
            p, span, li, td, th, div { @apply transition-colors duration-200; }
            ::selection { @apply bg-primary-500/30 text-primary-900; }
            .dark ::selection { @apply bg-primary-500/40 text-white; }
            table { @apply text-gray-900; }
            .dark table { @apply text-gray-100; }
        }

        /* Toast notification styles */
        #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
        .toast { display: flex; align-items: center; gap: 8px; padding: 12px 20px; border-radius: 12px; font-size: 14px; font-weight: 500; box-shadow: 0 10px 30px rgba(0,0,0,0.15); animation: slide-down 0.3s ease-out; max-width: 380px; }
        .toast-success { background: #10b981; color: white; }
        .toast-error { background: #ef4444; color: white; }
        .toast-info { background: #3b82f6; color: white; }
    </style>
</head>
<body class="font-body text-gray-800 bg-gray-50 antialiased dark:text-gray-100 dark:bg-gray-900">
    <div id="toast-container"></div>

    <?= view('components.announcement_bar', ['announcements' => $announcements ?? []]) ?>
    <?= view('components.navbar', [
        'current_page' => $current_page ?? '/',
        'categories' => $categories ?? [],
        'cart_count' => $cart_count ?? 0,
        'wishlist_count' => $wishlist_count ?? 0,
        'customer' => $customer ?? null,
        'is_authenticated' => $is_authenticated ?? false,
        'dark' => $dark ?? false,
    ]) ?>

    <main>
        <?= $content ?? '' ?>
    </main>

    <?= view('components.footer', ['dark' => $dark ?? false]) ?>

    <script>
    function toggleDarkMode() {
        var html = document.documentElement;
        html.classList.toggle('dark');
        var isDark = html.classList.contains('dark');
        document.cookie = 'devi_theme=' + (isDark ? 'dark' : 'light') + '; path=/; max-age=' + (365 * 24 * 60 * 60);
    }

    var savedTheme = document.cookie.split('; ').find(function(r) { return r.startsWith('devi_theme='); });
    if (savedTheme) {
        var theme = savedTheme.split('=')[1];
        if (theme === 'dark') document.documentElement.classList.add('dark');
        else document.documentElement.classList.remove('dark');
    }

    function showToast(message, type) {
        type = type || 'success';
        var container = document.getElementById('toast-container');
        if (!container) return;
        var toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        var icons = { success: '✓', error: '✕', info: 'ℹ' };
        toast.innerHTML = '<span style="font-size:16px;font-weight:bold">' + (icons[type] || '') + '</span> ' + message;
        container.appendChild(toast);
        setTimeout(function() { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(function() { toast.remove(); }, 300); }, 3000);
    <?php if (!empty($_SESSION['flash_message'])): ?>
        showToast(<?= json_encode($_SESSION['flash_message']) ?>, <?= json_encode($_SESSION['flash_type'] ?? 'success') ?>);
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>
    }

    var apiBase = <?= json_encode(rtrim(env('APP_URL', ''), '/')) ?>;

    function toggleWishlist(productId) {
        fetch(apiBase + '/api/wishlist/toggle', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ product_id: productId })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                showToast(data.message || 'Updated!');
                setTimeout(function() { location.reload(); }, 500);
            } else {
                showToast(data.message || 'Please login first', 'error');
            }
        })
        .catch(function() { showToast('Error updating wishlist', 'error'); });
    }

    function addToCart(productId) {
        fetch(apiBase + '/api/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                showToast('Added to cart!');
                setTimeout(function() { location.reload(); }, 500);
            } else {
                showToast(data.message || 'Please login first', 'error');
            }
        })
        .catch(function() { showToast('Error adding to cart', 'error'); });
    }
    </script>
</body>
</html>
