<?php if (isset($type) && $type === 'product_card'): ?>
<div class="card animate-pulse">
    <div class="aspect-square bg-gray-200 rounded-t-xl dark:bg-gray-700"></div>
    <div class="p-4 space-y-3">
        <div class="h-3 bg-gray-200 rounded w-1/3 dark:bg-gray-700"></div>
        <div class="h-5 bg-gray-200 rounded w-3/4 dark:bg-gray-700"></div>
        <div class="h-6 bg-gray-200 rounded w-1/2 dark:bg-gray-700"></div>
    </div>
</div>
<?php elseif (isset($type) && $type === 'table'): $rows = $rows ?? 5; ?>
<div class="animate-pulse space-y-3">
    <?php for ($i = 0; $i < $rows; $i++): ?>
    <div class="flex space-x-4">
        <div class="h-10 bg-gray-200 rounded flex-1 dark:bg-gray-700"></div>
        <div class="h-10 bg-gray-200 rounded flex-1 dark:bg-gray-700"></div>
        <div class="h-10 bg-gray-200 rounded flex-1 dark:bg-gray-700"></div>
        <div class="h-10 bg-gray-200 rounded w-20 dark:bg-gray-700"></div>
    </div>
    <?php endfor; ?>
</div>
<?php elseif (isset($type) && $type === 'page'): ?>
<div class="animate-pulse space-y-6 p-8">
    <div class="h-8 bg-gray-200 rounded w-1/3 dark:bg-gray-700"></div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php for ($i = 0; $i < 8; $i++): ?>
        <div class="space-y-3">
            <div class="aspect-square bg-gray-200 rounded-xl dark:bg-gray-700"></div>
            <div class="h-4 bg-gray-200 rounded w-2/3 dark:bg-gray-700"></div>
            <div class="h-6 bg-gray-200 rounded w-1/2 dark:bg-gray-700"></div>
        </div>
        <?php endfor; ?>
    </div>
</div>
<?php endif; ?>
