<?php $baseUrl = rtrim(env('APP_URL', ''), '/'); ?>
<!DOCTYPE html>
<html lang="en" class="<?= (isset($_COOKIE['devi_theme']) && $_COOKIE['devi_theme'] === 'dark') ? 'dark' : '' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Devi Fancy Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: { heading: ['Playfair Display', 'serif'], body: ['Inter', 'sans-serif'] },
                colors: {
                    primary: { 50:'#fdf2f4',100:'#fce7eb',200:'#f9d0d9',300:'#f4a9b9',400:'#ec7894',500:'#e04a6f',600:'#d6335e',700:'#b8234a',800:'#9a1f40',900:'#841e3b' },
                    secondary: { 50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',800:'#5b21b6',900:'#4c1d95' },
                    accent: { 50:'#fffdf0',100:'#fff9d6',200:'#fff2a8',300:'#ffe870',400:'#ffd940',500:'#ffc800',600:'#e0a800',700:'#b38000',800:'#8a6400',900:'#664b00' },
                },
            },
        },
    };
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-mesh { background-image: radial-gradient(at 40% 20%, rgba(224,74,111,0.12) 0px, transparent 50%), radial-gradient(at 80% 0%, rgba(124,58,237,0.08) 0px, transparent 50%), radial-gradient(at 0% 50%, rgba(255,200,0,0.06) 0px, transparent 50%); }
        .input-field { width:100%; padding:0.875rem 1.25rem; border:2px solid #e5e7eb; border-radius:0.75rem; outline:none; transition:all 0.3s; background:#f9fafb; }
        .input-field:focus { ring:2px; border-color:#e04a6f; background:white; }
        @keyframes scale-in { from { opacity:0; transform:scale(0.9); } to { opacity:1; transform:scale(1); } }
        .animate-scale-in { animation: scale-in 0.4s ease-out; }
    </style>
</head>
<body class="font-body antialiased">
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-mesh">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=1920&q=80" alt="" class="w-full h-full object-cover opacity-20" />
        </div>

        <div class="relative w-full max-w-md px-4 animate-scale-in">
            <div class="text-center mb-8">
                <a href="<?= $baseUrl ?>/" class="inline-block">
                    <?= view('components.logo', ['size' => 'lg']) ?>
                </a>
                <p class="text-gray-500 mt-3">Sign in to your account</p>
            </div>

            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border border-white/40 dark:border-gray-700/40 rounded-3xl p-8 shadow-2xl">
                <h2 class="text-2xl font-heading font-bold text-gray-900 dark:text-white mb-6 text-center">Welcome Back</h2>

                <form action="<?= $baseUrl ?>/api/customer/login" method="POST" class="space-y-5" onsubmit="handleCustomerLogin(event)">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <input type="email" name="email" class="input-field pl-11" placeholder="your@email.com" required />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Password</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <input type="password" name="password" class="input-field pl-11 pr-11" placeholder="Enter password" required />
                            <button type="button" onclick="togglePasswordVisibility(this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="login-btn" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold py-3.5 px-6 rounded-xl transition-all flex items-center justify-center shadow-lg shadow-primary-200 disabled:opacity-50">
                        <span id="login-text">Sign In</span>
                        <div id="login-spinner" class="hidden animate-spin rounded-full h-6 w-6 border-2 border-white border-t-transparent"></div>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-500 text-sm">
                        Don't have an account?
                        <a href="<?= $baseUrl ?>/signup" class="text-primary-600 hover:text-primary-700 font-semibold">Sign Up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePasswordVisibility(btn) {
        var input = btn.parentElement.querySelector('input');
        var icon = btn.querySelector('.eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
        } else {
            input.type = 'password';
            if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    }

    function handleCustomerLogin(e) {
        e.preventDefault();
        var form = e.target;
        var btn = document.getElementById('login-btn');
        var text = document.getElementById('login-text');
        var spinner = document.getElementById('login-spinner');
        btn.disabled = true; text.classList.add('hidden'); spinner.classList.remove('hidden');

        var data = {};
        new FormData(form).forEach(function(v, k) { data[k] = v; });

        fetch(form.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                window.location.href = '<?= $baseUrl ?>' + (res.redirect || '/');
            } else {
                alert(res.message || 'Invalid credentials');
                btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden');
            }
        })
        .catch(function() {
            alert('Login failed. Please try again.');
            btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden');
        });
    }
    </script>
</body>
</html>
