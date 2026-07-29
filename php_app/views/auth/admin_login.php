<?php $baseUrl = rtrim(env('APP_URL', ''), '/'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Devi Fancy Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
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
        @keyframes scale-in { from { opacity:0; transform:scale(0.9); } to { opacity:1; transform:scale(1); } }
        @keyframes float { 0%, 100% { transform:translateY(0px); } 50% { transform:translateY(-8px); } }
        .animate-scale-in { animation: scale-in 0.4s ease-out; }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="font-body antialiased">
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=1920&q=80" alt="Background" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-br from-primary-900/90 via-primary-800/80 to-secondary-900/90"></div>
        </div>

        <div class="relative w-full max-w-md px-4 animate-scale-in">
            <div class="text-center mb-8">
                <div class="inline-block animate-float">
                    <?= view('components.logo', ['size' => 'lg']) ?>
                </div>
                <p class="text-white/60 mt-4 text-sm tracking-wide">Admin Panel Login</p>
            </div>
            <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl hover:shadow-white/10 transition-shadow duration-500">
                <h2 class="text-2xl font-heading font-bold text-white mb-6 text-center">Sign In</h2>
                <form action="<?= $baseUrl ?>/api/auth/login" method="POST" class="space-y-5" onsubmit="handleAdminLogin(event)">
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-1">Email</label>
                        <input type="email" name="email" value="admin@gmail.com" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:ring-2 focus:ring-accent-500 focus:border-accent-500 outline-none transition-all" placeholder="admin@gmail.com" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/80 mb-1">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:ring-2 focus:ring-accent-500 focus:border-accent-500 outline-none transition-all" placeholder="Enter password" required />
                    </div>
                    <button type="submit" id="admin-login-btn" class="w-full bg-gradient-to-r from-accent-500 to-accent-600 hover:from-accent-600 hover:to-accent-700 text-white font-semibold py-3 px-6 rounded-xl transition-all flex items-center justify-center shadow-lg shadow-accent-500/30 disabled:opacity-50">
                        <span id="admin-login-text">Sign In</span>
                        <div id="admin-login-spinner" class="hidden animate-spin rounded-full h-6 w-6 border-2 border-white border-t-transparent"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function handleAdminLogin(e) {
        e.preventDefault();
        var form = e.target;
        var btn = document.getElementById('admin-login-btn');
        var text = document.getElementById('admin-login-text');
        var spinner = document.getElementById('admin-login-spinner');
        btn.disabled = true; text.classList.add('hidden'); spinner.classList.remove('hidden');

        var formData = new FormData(form);
        var data = {};
        formData.forEach(function(v, k) { data[k] = v; });

        fetch(form.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success && res.data && res.data.token) {
                localStorage.setItem('adminToken', res.data.token);
                window.location.href = '<?= $baseUrl ?>/admin';
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
