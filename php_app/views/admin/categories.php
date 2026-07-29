<?php
$categories = $categories ?? [];
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Manage Categories</h1>
            <p class="text-gray-500 mt-1"><?= count($categories) ?> category(ies) total</p>
        </div>
        <button data-open-modal="category-modal" class="btn-primary flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Category</span>
        </button>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="categories-tbody" class="divide-y">
                <?php foreach ($categories as $cat): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4">
                        <img src="<?= htmlspecialchars($cat['image'] ?? 'https://via.placeholder.com/48') ?>" alt="<?= htmlspecialchars($cat['name'] ?? '') ?>" class="w-12 h-12 rounded-lg object-cover" />
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($cat['name'] ?? '') ?></td>
                    <td class="px-6 py-4 text-gray-500 text-sm"><?= htmlspecialchars($cat['slug'] ?? '') ?></td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= !empty($cat['is_hidden']) ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                            <?= !empty($cat['is_hidden']) ? 'Hidden' : 'Visible' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <button onclick="toggleCategoryVisibility(<?= $cat['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Toggle visibility">
                                <?php if (!empty($cat['is_hidden'])): ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                <?php else: ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <?php endif; ?>
                            </button>
                            <button onclick="editCategory(<?= $cat['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button onclick="deleteCategory(<?= $cat['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($categories) === 0): ?>
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No categories found. Create your first category!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="category-modal" class="modal fixed inset-0 bg-black/50 backdrop-blur-sm items-center justify-center z-50 p-4" style="display:none">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-md shadow-2xl animate-scale-in">
            <h2 id="category-modal-title" class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">Add Category</h2>
            <form id="category-form" class="space-y-4">
                <input type="hidden" name="id" value="">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                    <input type="text" name="name" class="input-field" placeholder="Category name" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image (optional)</label>
                    <input type="file" name="image" accept="image/*" class="input-field" />
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" data-close-modal class="px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">Cancel</button>
                    <button type="submit" data-create="createCategory" class="btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var _categoriesData = <?= json_encode(array_values($categories)) ?>;
</script>
