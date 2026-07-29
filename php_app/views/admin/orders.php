<?php
$orders = $orders ?? [];
$search = $_GET['search'] ?? '';
$sortField = $_GET['sort_field'] ?? 'date';
$sortDir = $_GET['sort_dir'] ?? 'desc';
$baseUrl = rtrim(env('APP_URL', ''), '/');

$filtered = $orders;
if (!empty($search)) {
    $q = strtolower($search);
    $filtered = array_filter($orders, function($o) use ($q) {
        return strpos(strtolower($o['name'] ?? ''), $q) !== false || strpos($o['phone'] ?? '', $q) !== false || strpos(strtolower($o['products'] ?? ''), $q) !== false;
    });
}
usort($filtered, function($a, $b) use ($sortField, $sortDir) {
    $cmp = 0;
    if ($sortField === 'name') $cmp = strcmp($a['name'] ?? '', $b['name'] ?? '');
    elseif ($sortField === 'total') {
        $ta = (float) str_replace('₹', '', $a['total'] ?? '0');
        $tb = (float) str_replace('₹', '', $b['total'] ?? '0');
        $cmp = $ta - $tb;
    } else $cmp = strtotime($a['date'] ?? '') - strtotime($b['date'] ?? '');
    return $sortDir === 'asc' ? $cmp : -$cmp;
});
?>
<div>
    <div class="flex items-end justify-between mb-6">
        <div>
            <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Orders</h1>
            <p class="text-gray-500 mt-1"><?= count($filtered) ?> order(s) found</p>
        </div>
    </div>

    <div class="card p-4 mb-6">
        <form method="GET">
            <div class="relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name, phone, or products..." class="input-field pl-11" />
            </div>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" onclick="toggleSort('name')">Customer <?= $sortField === 'name' ? ($sortDir === 'asc' ? '↑' : '↓') : '' ?></th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" onclick="toggleSort('total')">Total <?= $sortField === 'total' ? ($sortDir === 'asc' ? '↑' : '↓') : '' ?></th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" onclick="toggleSort('date')">Date <?= $sortField === 'date' ? ($sortDir === 'asc' ? '↑' : '↓') : '' ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach (array_values($filtered) as $i => $order): ?>
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors" style="animation-delay: <?= $i * 0.02 ?>s">
                        <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($order['name'] ?? '') ?></td>
                        <td class="px-5 py-4 text-gray-600 dark:text-gray-400 font-mono text-sm"><?= htmlspecialchars($order['phone'] ?? '') ?></td>
                        <td class="px-5 py-4 text-gray-600 dark:text-gray-400 max-w-[200px] truncate text-sm" title="<?= htmlspecialchars($order['address'] ?? '') ?>"><?= htmlspecialchars($order['address'] ?? '') ?></td>
                        <td class="px-5 py-4 text-gray-600 dark:text-gray-400 max-w-[200px] truncate text-sm" title="<?= htmlspecialchars($order['products'] ?? '') ?>"><?= htmlspecialchars($order['products'] ?? '') ?></td>
                        <td class="px-5 py-4"><span class="bg-primary-100 text-primary-700 font-bold text-xs px-2.5 py-1 rounded-full"><?= $order['quantity'] ?? 0 ?></span></td>
                        <td class="px-5 py-4 font-extrabold text-gray-900 dark:text-white"><?= $order['total'] ?? '₹0' ?></td>
                        <td class="px-5 py-4 text-sm text-gray-500"><?= htmlspecialchars($order['date'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (count($filtered) === 0): ?>
                    <tr><td colspan="7" class="px-6 py-16 text-center text-gray-400">
                        <p class="text-lg font-medium">No orders found</p>
                        <p class="text-sm mt-1">Orders placed through the store will appear here.</p>
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleSort(field) {
    var url = new URL(window.location.href);
    var currentField = url.searchParams.get('sort_field') || 'date';
    var currentDir = url.searchParams.get('sort_dir') || 'desc';
    if (currentField === field) url.searchParams.set('sort_dir', currentDir === 'asc' ? 'desc' : 'asc');
    else { url.searchParams.set('sort_field', field); url.searchParams.set('sort_dir', 'desc'); }
    window.location.href = url.toString();
}
</script>
