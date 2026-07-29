<?php
$current_page = $current_page ?? '/';
$categories = $categories ?? [];
$cart_count = $cart_count ?? 0;
$wishlist_count = $wishlist_count ?? 0;
$customer = $customer ?? null;
$is_authenticated = $is_authenticated ?? false;
$dark = $dark ?? false;

$navLinks = [
    ['label' => 'Home', 'path' => '/'],
    ['label' => 'Categories', 'path' => '/categories'],
    ['label' => 'Catalogues', 'path' => '/catalogues'],
    ['label' => 'Products', 'path' => '/products'],
    ['label' => 'Contact Us', 'path' => '/page/contact-us'],
    ['label' => 'About Us', 'path' => '/page/about-us'],
];
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<nav id="main-navbar" class="sticky top-0 z-50 transition-all duration-300 bg-white shadow-md dark:bg-gray-900" data-scrolled="false">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 md:h-20 items-center">
            <a href="<?= $baseUrl ?>/" class="flex-shrink-0">
                <?= view('components.logo', ['size' => 'sm']) ?>
            </a>

            <div class="hidden lg:flex items-center space-x-1">
                <?php foreach ($navLinks as $item): ?>
                    <a href="<?= $baseUrl . $item['path'] ?>" class="relative px-4 py-2 font-medium transition-colors group <?= $dark ? 'text-gray-300 hover:text-primary-400' : 'text-gray-700 hover:text-primary-600' ?>">
                        <?= $item['label'] ?>
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full group-hover:w-3/4 transition-all duration-300"></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="flex items-center space-x-1.5">
                <button onclick="toggleTheme()" class="p-2.5 rounded-full transition-all <?= $dark ? 'text-yellow-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' ?>">
                    <?php if ($dark): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <?php else: ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <?php endif; ?>
                </button>

                <button onclick="toggleSearch()" class="p-2.5 rounded-full transition-all <?= $dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <?php if ($is_authenticated): ?>
                <a href="<?= $baseUrl ?>/wishlist" class="relative p-2.5 rounded-full transition-all <?= $dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <?php if ($wishlist_count > 0): ?>
                    <span class="absolute -top-0.5 -right-0.5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-md"><?= $wishlist_count ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>

                <a href="<?= $baseUrl ?>/cart" class="relative p-2.5 rounded-full transition-all <?= $dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    <?php if ($cart_count > 0): ?>
                    <span class="absolute -top-0.5 -right-0.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-md animate-bounce"><?= $cart_count ?></span>
                    <?php endif; ?>
                </a>

                <div class="relative" id="user-menu-container">
                    <?php if ($is_authenticated && $customer): ?>
                    <button onclick="toggleUserMenu()" class="flex items-center gap-2 p-2 rounded-full transition-all <?= $dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' ?>">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
                            <?= strtoupper(substr($customer['name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <span class="hidden md:block text-sm font-medium max-w-[100px] truncate <?= $dark ? 'text-gray-300' : '' ?>"><?= htmlspecialchars($customer['name'] ?? '') ?></span>
                        <svg class="w-4 h-4 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="user-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-56 rounded-2xl shadow-xl border py-2 animate-scale-in z-50 <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                        <div class="px-4 py-3 border-b <?= $dark ? 'border-gray-700' : 'border-gray-100' ?>">
                            <p class="font-bold text-sm <?= $dark ? 'text-white' : 'text-gray-900' ?>"><?= htmlspecialchars($customer['name'] ?? '') ?></p>
                            <p class="text-xs mt-0.5 <?= $dark ? 'text-gray-400' : 'text-gray-500' ?>"><?= htmlspecialchars($customer['email'] ?? '') ?></p>
                        </div>
                        <button
                            onclick="handleSignOut()"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Sign Out
                        </button>
                    </div>
                    <?php else: ?>
                    <a href="<?= $baseUrl ?>/login" class="flex items-center gap-2 px-3 py-2 rounded-full transition-all <?= $dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="hidden md:block text-sm font-medium">Login</span>
                    </a>
                    <?php endif; ?>
                </div>

                <button onclick="toggleMobileMenu()" class="lg:hidden p-2.5 rounded-full transition-all <?= $dark ? 'text-gray-300 hover:bg-gray-800' : 'text-gray-600 hover:bg-gray-100' ?>">
                    <svg id="menu-open-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg id="menu-close-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div id="search-bar" class="hidden pb-4 animate-slide-down">
            <form action="<?= $baseUrl ?>/search" method="GET" class="flex max-w-xl mx-auto">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" placeholder="Search for bangles, earrings, chains..." class="w-full pl-10 pr-4 py-3 border rounded-l-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none <?= $dark ? 'bg-gray-800 border-gray-700 text-white' : 'bg-gray-50 border-gray-200' ?>" autofocus />
                </div>
                <button type="submit" class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-8 rounded-r-xl transition-all font-medium">Search</button>
            </form>
        </div>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden border-t shadow-lg animate-slide-down <?= $dark ? 'bg-gray-900 border-gray-700' : 'bg-white' ?>">
        <div class="px-4 py-4 space-y-1">
            <?php foreach ($navLinks as $item): ?>
                <a href="<?= $baseUrl . $item['path'] ?>" onclick="closeMobileMenu()" class="block px-4 py-3 font-medium rounded-lg transition-colors <?= $dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50' ?>"><?= $item['label'] ?></a>
            <?php endforeach; ?>
            <?php if ($is_authenticated): ?>
                <a href="<?= $baseUrl ?>/wishlist" onclick="closeMobileMenu()" class="block px-4 py-3 font-medium rounded-lg transition-colors <?= $dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50' ?>">My Wishlist</a>
            <?php endif; ?>
            <div class="border-t pt-3 mt-3 <?= $dark ? 'border-gray-700' : 'border-gray-200' ?>">
                <?php if ($is_authenticated && $customer): ?>
                    <div class="px-4 py-2 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold shadow-md"><?= strtoupper(substr($customer['name'] ?? 'U', 0, 1)) ?></div>
                        <div>
                            <p class="font-bold text-sm <?= $dark ? 'text-white' : 'text-gray-900' ?>"><?= htmlspecialchars($customer['name'] ?? '') ?></p>
                            <p class="text-xs <?= $dark ? 'text-gray-400' : 'text-gray-500' ?>"><?= htmlspecialchars($customer['email'] ?? '') ?></p>
                        </div>
                    </div>
                    <button onclick="handleSignOut()" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 font-medium rounded-lg transition-colors">Sign Out</button>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>/login" onclick="closeMobileMenu()" class="block px-4 py-3 text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 font-medium rounded-lg transition-colors">Sign In</a>
                    <a href="<?= $baseUrl ?>/signup" onclick="closeMobileMenu()" class="block px-4 py-3 font-medium rounded-lg transition-colors <?= $dark ? 'text-gray-300 hover:bg-gray-800' : 'text-gray-700 hover:bg-gray-50' ?>">Create Account</a>
                <?php endif; ?>
            </div>
            <div class="border-t pt-3 mt-3 <?= $dark ? 'border-gray-700' : 'border-gray-200' ?>">
                <p class="px-4 text-xs uppercase tracking-wider font-medium mb-2 <?= $dark ? 'text-gray-500' : 'text-gray-400' ?>">Categories</p>
                <?php foreach (array_slice($categories, 0, 6) as $cat): ?>
                    <a href="<?= $baseUrl ?>/categories/<?= htmlspecialchars($cat['slug'] ?? '') ?>" onclick="closeMobileMenu()" class="block px-4 py-2 text-sm rounded-lg transition-colors <?= $dark ? 'text-gray-400 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-500 hover:text-primary-600 hover:bg-gray-50' ?>"><?= htmlspecialchars($cat['name'] ?? '') ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var navbar = document.getElementById('main-navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 10) {
            navbar.setAttribute('data-scrolled', 'true');
            navbar.classList.remove('bg-white', 'shadow-md', 'dark:bg-gray-900');
            navbar.classList.add('bg-white/95', 'backdrop-blur-md', 'shadow-lg', 'dark:bg-gray-900/95');
        } else {
            navbar.setAttribute('data-scrolled', 'false');
            navbar.classList.add('bg-white', 'shadow-md', 'dark:bg-gray-900');
            navbar.classList.remove('bg-white/95', 'backdrop-blur-md', 'shadow-lg', 'dark:bg-gray-900/95');
        }
    });
});

function toggleSearch() {
    var el = document.getElementById('search-bar');
    el.classList.toggle('hidden');
}

function toggleMobileMenu() {
    var el = document.getElementById('mobile-menu');
    var openIcon = document.getElementById('menu-open-icon');
    var closeIcon = document.getElementById('menu-close-icon');
    el.classList.toggle('hidden');
    openIcon.classList.toggle('hidden');
    closeIcon.classList.toggle('hidden');
}

function closeMobileMenu() {
    var el = document.getElementById('mobile-menu');
    var openIcon = document.getElementById('menu-open-icon');
    var closeIcon = document.getElementById('menu-close-icon');
    if (el) el.classList.add('hidden');
    if (openIcon) openIcon.classList.remove('hidden');
    if (closeIcon) closeIcon.classList.add('hidden');
}

function toggleUserMenu() {
    var el = document.getElementById('user-menu-dropdown');
    if (el) el.classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    var container = document.getElementById('user-menu-container');
    var dropdown = document.getElementById('user-menu-dropdown');
    if (container && dropdown && !container.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});

function handleSignOut() {
    localStorage.removeItem('customerToken');
    window.location.href = '/';
}
</script>
