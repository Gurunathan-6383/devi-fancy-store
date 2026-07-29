<?php
$type = $type ?? 'product_card';
$rows = $rows ?? 5;
?>

<?php if ($type === 'product_card'): ?>
<div class="card animate-pulse">
    <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-t-xl"></div>
    <div class="p-4 space-y-3">
        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
        <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
        <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
    </div>
</div>
<?php elseif ($type === 'table'): ?>
<div class="animate-pulse space-y-3">
    <?php for ($i = 0; $i < $rows; $i++): ?>
    <div class="flex space-x-4">
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded flex-1"></div>
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded flex-1"></div>
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded flex-1"></div>
        <div class="h-10 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
    </div>
    <?php endfor; ?>
</div>
<?php elseif ($type === 'page'): ?>
<div class="animate-pulse space-y-6 p-8">
    <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php for ($i = 0; $i < 8; $i++): ?>
        <div class="space-y-3">
            <div class="aspect-square bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
        </div>
        <?php endfor; ?>
    </div>
</div>
<?php endif; ?>
