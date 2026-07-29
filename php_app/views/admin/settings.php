<?php
$settings = $settings ?? [];
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<div>
    <h1 class="text-2xl font-heading font-bold text-gray-900 dark:text-white mb-6">Settings</h1>

    <div class="card p-6 max-w-2xl">
        <form id="settings-form" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Store Name</label>
                    <input type="text" name="store_name" value="<?= htmlspecialchars($settings['store_name'] ?? '') ?>" class="input-field" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo</label>
                    <input type="file" name="logo" accept="image/*" class="input-field" />
                    <?php if (!empty($settings['logo'])): ?>
                    <div class="mt-2"><img src="<?= htmlspecialchars($settings['logo']) ?>" alt="Logo" class="h-12 rounded" /></div>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($settings['phone'] ?? '') ?>" class="input-field" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($settings['email'] ?? '') ?>" class="input-field" />
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                <textarea name="address" class="input-field" rows="3"><?= htmlspecialchars($settings['address'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Theme</label>
                <select name="theme" class="input-field">
                    <option value="light" <?= ($settings['theme'] ?? '') === 'light' ? 'selected' : '' ?>>Light</option>
                    <option value="dark" <?= ($settings['theme'] ?? '') === 'dark' ? 'selected' : '' ?>>Dark</option>
                </select>
            </div>
            <div class="pt-2">
                <button type="submit" class="btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = e.target;
    var fd = new FormData(form);
    fetch('<?= $baseUrl ?>/api/settings', { method: 'PUT', body: fd })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) { showToast('Settings updated!'); setTimeout(function() { location.reload(); }, 500); }
        else showToast('Failed to update settings', 'error');
    })
    .catch(function() { showToast('Failed to update settings', 'error'); });
});
</script>
