<!DOCTYPE html>
<html lang="en" class="<?= (isset($_COOKIE['devi_theme']) && $_COOKIE['devi_theme'] === 'dark') ? 'dark' : '' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin - Devi Fancy Store') ?></title>
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
                @apply font-body text-gray-800 antialiased;
            }
            .dark body, .dark {
                @apply text-gray-100;
            }
            h1, h2, h3, h4, h5, h6 {
                @apply font-heading;
            }
            .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6 {
                @apply text-white;
            }
        }

        @layer components {
            .btn-primary {
                @apply bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3 px-7 rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40;
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
        }

        @layer utilities {
            .text-gradient {
                @apply bg-gradient-to-r from-primary-600 via-primary-500 to-secondary-600 bg-clip-text text-transparent;
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

        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .animate-slide-down { animation: slide-down 0.4s ease-out; }
        .animate-slide-up { animation: slide-up 0.5s ease-out; }
        .animate-scale-in { animation: scale-in 0.4s ease-out; }

        * { scrollbar-width: thin; scrollbar-color: #d6335e #f1f1f1; }
        .dark * { scrollbar-color: #d6335e #1f2937; }
        *::-webkit-scrollbar { width: 6px; }
        *::-webkit-scrollbar-track { background: #f1f1f1; }
        .dark *::-webkit-scrollbar-track { background: #1f2937; }
        *::-webkit-scrollbar-thumb { background: linear-gradient(to bottom, #e04a6f, #7c3aed); border-radius: 3px; }
    </style>
</head>
<body class="font-body antialiased bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
    <div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px;"></div>

    <?php
    $baseUrl = rtrim(env('APP_URL', ''), '/');
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $adminBase = rtrim(parse_url(env('APP_URL', '/devi/php_app'), PHP_URL_PATH), '/');
    $pageTitle = $title ?? 'Admin';
    ?>

    <div class="min-h-screen flex">
        <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transform -translate-x-full transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto">
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-800">
                <a href="<?= $adminBase ?>/admin" class="text-xl font-heading font-bold text-gradient">Admin Panel</a>
                <button onclick="toggleAdminSidebar()" class="lg:hidden text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <nav class="mt-6 px-3 space-y-1">
                <?php
                $navItems = [
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                        'label' => 'Dashboard',
                        'path' => '/admin'
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>',
                        'label' => 'Categories',
                        'path' => '/admin/categories'
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>',
                        'label' => 'Products',
                        'path' => '/admin/products'
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
                        'label' => 'Catalogues',
                        'path' => '/admin/catalogues'
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>',
                        'label' => 'Orders',
                        'path' => '/admin/orders'
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
                        'label' => 'Customers',
                        'path' => '/admin/customers'
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>',
                        'label' => 'Announcements',
                        'path' => '/admin/announcements'
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                        'label' => 'Content Pages',
                        'path' => '/admin/content-pages'
                    ],
                    [
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                        'label' => 'Settings',
                        'path' => '/admin/settings'
                    ],
                ];
                ?>
                <?php foreach ($navItems as $item):
                    $fullPath = $adminBase . $item['path'];
                    $isActive = $currentPath === $fullPath;
                ?>
                <a href="<?= $fullPath ?>" onclick="closeAdminSidebar()" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors <?= $isActive ? 'bg-primary-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $item['icon'] ?></svg>
                    <span class="font-medium"><?= $item['label'] ?></span>
                </a>
                <?php endforeach; ?>
            </nav>
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-800">
                <a href="<?= $baseUrl ?>/" target="_blank" class="flex items-center space-x-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span class="font-medium">View Store</span>
                </a>
                <a href="<?= $adminBase ?>/admin/logout" class="flex items-center space-x-3 px-4 py-3 text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-lg transition-colors w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span class="font-medium">Logout</span>
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-h-screen">
            <header class="bg-white dark:bg-gray-800 shadow-sm px-6 py-4 flex items-center justify-between lg:justify-end">
                <button onclick="toggleAdminSidebar()" class="lg:hidden text-gray-600 dark:text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-200 lg:hidden"><?= htmlspecialchars($pageTitle) ?></h1>
                <div class="flex items-center space-x-4">
                    <a href="<?= $baseUrl ?>/" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View Store</a>
                </div>
            </header>
            <main class="flex-1 p-6 overflow-auto">
                <?= $content ?? '' ?>
            </main>
        </div>
    </div>

    <script>
    function toggleAdminSidebar() {
        document.getElementById('admin-sidebar').classList.toggle('-translate-x-full');
    }
    function closeAdminSidebar() {
        var sidebar = document.getElementById('admin-sidebar');
        if (window.innerWidth < 1024) sidebar.classList.add('-translate-x-full');
    }
    function showToast(message, type) {
        type = type || 'success';
        var container = document.getElementById('toast-container');
        var toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        toast.style.cssText = 'display:flex;align-items:center;gap:8px;padding:12px 20px;border-radius:12px;font-size:14px;font-weight:500;box-shadow:0 10px 30px rgba(0,0,0,0.15);animation:slide-down 0.3s ease-out;max-width:380px;' + (type === 'success' ? 'background:#10b981;color:white' : type === 'error' ? 'background:#ef4444;color:white' : 'background:#3b82f6;color:white');
        var icons = { success: '✓', error: '✕', info: 'ℹ' };
        toast.innerHTML = '<span style="font-size:16px;font-weight:bold;margin-right:4px">' + (icons[type] || '') + '</span> ' + message;
        container.appendChild(toast);
        setTimeout(function() { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(function() { toast.remove(); }, 300); }, 3000);
    }

    <?php if (!empty($_SESSION['flash_message'])): ?>
    showToast(<?= json_encode($_SESSION['flash_message']) ?>, <?= json_encode($_SESSION['flash_type'] ?? 'success') ?>);
    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
    <?php endif; ?>
    </script>
    <script src="<?= $baseUrl ?>/js/api.js"></script>
    <script src="<?= $baseUrl ?>/js/app.js"></script>
    <script src="<?= $baseUrl ?>/js/admin.js"></script>
</body>
</html>
