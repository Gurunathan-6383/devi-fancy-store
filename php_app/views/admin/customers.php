<?php
$customers = $customers ?? [];
$search = $_GET['search'] ?? '';
$baseUrl = rtrim(env('APP_URL', ''), '/');

$filtered = $customers;
if (!empty($search)) {
    $q = strtolower($search);
    $filtered = array_filter($customers, function($c) use ($q) {
        return strpos(strtolower($c['name'] ?? ''), $q) !== false || strpos($c['phone'] ?? '', $q) !== false;
    });
}
?>
<div>
    <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white mb-6">Customers</h1>

    <div class="card p-4 mb-6">
        <form method="GET">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name or phone..." class="pl-10 pr-4 py-2.5 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" />
            </div>
        </form>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                <tr>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Name</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Phone</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Address</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Total Orders</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($filtered as $c): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($c['name'] ?? '') ?></td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400"><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                    <td class="px-6 py-4 text-gray-600 dark:text-gray-400 max-w-[250px] truncate"><?= htmlspecialchars($c['address'] ?? '') ?></td>
                    <td class="px-6 py-4"><span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold"><?= $c['orders'] ?? 0 ?></span></td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($filtered) === 0): ?>
                <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">No customers yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
