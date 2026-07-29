<!DOCTYPE html>
<html lang="en" class="<?= (isset($_COOKIE['devi_theme']) && $_COOKIE['devi_theme'] === 'dark') ? 'dark' : '' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Devi Fancy Store') ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta_description ?? 'Your one-stop destination for beautiful accessories, cosmetics, and gift items. Discover elegance with every purchase.') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: {
                    heading: ['Playfair Display', 'serif'],
                    body: ['Inter', 'sans-serif']
                },
                colors: {
                    primary: {
                        50: '#fdf2f4',
                        100: '#fce7eb',
                        200: '#f9d0d9',
                        300: '#f4a9b9',
                        400: '#ec7894',
                        500: '#e04a6f',
                        600: '#d6335e',
                        700: '#b8234a',
                        800: '#9a1f40',
                        900: '#841e3b',
                    },
                    secondary: {
                        50: '#f5f3ff',
                        100: '#ede9fe',
                        200: '#ddd6fe',
                        300: '#c4b5fd',
                        400: '#a78bfa',
                        500: '#8b5cf6',
                        600: '#7c3aed',
                        700: '#6d28d9',
                        800: '#5b21b6',
                        900: '#4c1d95',
                    },
                    accent: {
                        50: '#fffdf0',
                        100: '#fff9d6',
                        200: '#fff2a8',
                        300: '#ffe870',
                        400: '#ffd940',
                        500: '#ffc800',
                        600: '#e0a800',
                        700: '#b38000',
                        800: '#8a6400',
                        900: '#664b00',
                    },
                },
            },
        },
    };
    </script>
    <style>
        @layer base {
            body {
                @apply font-body text-gray-800 bg-gray-50 antialiased;
            }
            .dark body, .dark {
                @apply text-gray-100 bg-gray-900;
            }
            h1, h2, h3, h4, h5, h6 {
                @apply font-heading;
            }
            .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6 {
                @apply text-white;
            }
            label {
                @apply text-gray-700;
            }
            .dark label {
                @apply text-gray-300;
            }
            p, span, li, td, th, div {
                @apply transition-colors duration-200;
            }
            ::selection {
                @apply bg-primary-500/30 text-primary-900;
            }
            .dark ::selection {
                @apply bg-primary-500/40 text-white;
            }
            table {
                @apply text-gray-900;
            }
            .dark table {
                @apply text-gray-100;
            }
        }

        @layer components {
            .btn-primary {
                @apply bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3 px-7 rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40;
            }
            .btn-secondary {
                @apply bg-gradient-to-r from-secondary-600 to-secondary-700 hover:from-secondary-700 hover:to-secondary-800 text-white font-semibold py-3 px-7 rounded-xl transition-all duration-300 shadow-lg shadow-secondary-500/25 hover:shadow-secondary-500/40;
            }
            .btn-accent {
                @apply bg-gradient-to-r from-accent-500 to-accent-600 hover:from-accent-600 hover:to-accent-700 text-white font-semibold py-3 px-7 rounded-xl transition-all duration-300 shadow-lg shadow-accent-500/25 hover:shadow-accent-500/40;
            }
            .btn-outline {
                @apply border-2 border-primary-500 text-primary-600 hover:bg-primary-600 hover:text-white font-semibold py-3 px-7 rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-primary-500/25;
            }
            .btn-ghost {
                @apply text-gray-600 hover:text-primary-600 hover:bg-primary-50 font-medium py-2 px-4 rounded-xl transition-all duration-200;
            }
            .dark .btn-ghost {
                @apply text-gray-400 hover:text-primary-400 hover:bg-primary-900/20;
            }
            .card {
                @apply bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100/80;
            }
            .dark .card {
                @apply bg-gray-800 border-gray-700/80 text-gray-100;
            }
            .card-hover {
                @apply hover:-translate-y-1.5 transition-all duration-500;
            }
            .input-field {
                @apply w-full px-5 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all duration-300 bg-gray-50/50 focus:bg-white text-gray-900 placeholder-gray-400;
            }
            .dark .input-field {
                @apply bg-gray-700/50 border-gray-600 text-white focus:bg-gray-700 placeholder-gray-500;
            }
            .section-title {
                @apply text-3xl md:text-5xl font-heading font-bold text-gray-900 relative inline-block;
            }
            .dark .section-title {
                @apply text-white;
            }
            .section-title::after {
                content: '';
                @apply absolute -bottom-3 left-0 w-16 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full;
            }
            .glass-card {
                @apply bg-white/70 backdrop-blur-xl border border-white/30 rounded-2xl shadow-xl;
            }
            .dark .glass-card {
                @apply bg-gray-800/70 border-gray-700/30;
            }
        }

        @layer utilities {
            .text-gradient {
                @apply bg-gradient-to-r from-primary-600 via-primary-500 to-secondary-600 bg-clip-text text-transparent;
            }
            .text-gradient-gold {
                @apply bg-gradient-to-r from-accent-400 via-accent-300 to-accent-500 bg-clip-text text-transparent;
            }
            .bg-mesh {
                background-image: radial-gradient(at 40% 20%, rgba(224, 74, 111, 0.12) 0px, transparent 50%),
                                  radial-gradient(at 80% 0%, rgba(124, 58, 237, 0.08) 0px, transparent 50%),
                                  radial-gradient(at 0% 50%, rgba(255, 200, 0, 0.06) 0px, transparent 50%);
            }
        }

        @keyframes slide-down {
            from { opacity: 0; transform: translateY(-12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(224, 74, 111, 0.4); }
            50% { box-shadow: 0 0 20px 10px rgba(224, 74, 111, 0); }
        }

        .animate-slide-down { animation: slide-down 0.4s ease-out; }
        .animate-slide-up { animation: slide-up 0.5s ease-out; }
        .animate-fade-in { animation: fade-in 0.6s ease-out; }
        .animate-scale-in { animation: scale-in 0.4s ease-out; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulse-glow 2s ease-in-out infinite; }
        .animate-shimmer {
            background: linear-gradient(90deg, transparent 25%, rgba(255,255,255,0.3) 50%, transparent 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        .line-clamp-1 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1; }
        .line-clamp-2 { overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }

        * { scrollbar-width: thin; scrollbar-color: #d6335e #f1f1f1; }
        .dark * { scrollbar-color: #d6335e #1f2937; }
        *::-webkit-scrollbar { width: 6px; }
        *::-webkit-scrollbar-track { background: #f1f1f1; }
        .dark *::-webkit-scrollbar-track { background: #1f2937; }
        *::-webkit-scrollbar-thumb { background: linear-gradient(to bottom, #e04a6f, #7c3aed); border-radius: 3px; }
    </style>
</head>
<body class="font-body text-gray-800 bg-gray-50 antialiased dark:text-gray-100 dark:bg-gray-900">
    <div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;"></div>

    <?php
    $baseUrl = rtrim(env('APP_URL', ''), '/');
    $announcements = $announcements ?? [];
    $categories = $categories ?? [];
    $cart_count = $cart_count ?? 0;
    $wishlist_count = $wishlist_count ?? 0;
    $customer = $customer ?? null;
    $is_authenticated = $is_authenticated ?? false;
    $dark = (isset($_COOKIE['devi_theme']) && $_COOKIE['devi_theme'] === 'dark');
    ?>

    <?= view('components.announcement_bar', ['announcements' => $announcements]) ?>
    <?= view('components.navbar', [
        'current_page' => $current_page ?? '/',
        'categories' => $categories,
        'cart_count' => $cart_count,
        'wishlist_count' => $wishlist_count,
        'customer' => $customer,
        'is_authenticated' => $is_authenticated,
        'dark' => $dark,
    ]) ?>

    <main>
        <?= $content ?? '' ?>
    </main>

    <?= view('components.footer', ['dark' => $dark]) ?>

    <script src="<?= $baseUrl ?>/js/api.js"></script>
    <script src="<?= $baseUrl ?>/js/app.js"></script>
    <script src="<?= $baseUrl ?>/js/cart.js"></script>
    <script src="<?= $baseUrl ?>/js/wishlist.js"></script>
    <script src="<?= $baseUrl ?>/js/auth.js"></script>
</body>
</html>
