<?php
$pages = $pages ?? [];
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Manage Content Pages</h1>
            <p class="text-gray-500 mt-1"><?= count($pages) ?> page(s) total</p>
        </div>
        <button onclick="openPageModal()" class="btn-primary flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Page</span>
        </button>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Updated</th>
                    <th class="text-right px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($pages as $p): ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($p['title'] ?? '') ?></span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($p['slug'] ?? '') ?></td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= !empty($p['is_active']) ? 'bg-green-100 text-green-700' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' ?>">
                            <?= !empty($p['is_active']) ? 'Active' : 'Inactive' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?= !empty($p['updated_at']) ? date('M d, Y', strtotime($p['updated_at'])) : '-' ?></td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="<?= $baseUrl ?>/page/<?= htmlspecialchars($p['slug'] ?? '') ?>" target="_blank" class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Preview">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <button onclick="editPage(<?= $p['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button onclick="deletePage(<?= $p['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($pages) === 0): ?>
                <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">No content pages yet. Create your first page!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="page-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl animate-scale-in">
            <h2 id="page-modal-title" class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">Add Page</h2>
            <form id="page-form" class="space-y-4">
                <input type="hidden" id="page-id" value="">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                    <input type="text" id="page-title" class="input-field" placeholder="Page title" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                    <input type="text" id="page-slug" class="input-field" placeholder="page-slug" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content (HTML)</label>
                    <textarea id="page-content" class="input-field" rows="12" placeholder="Enter page content in HTML..."></textarea>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="page-active" class="w-4 h-4 text-primary-600" checked />
                    <label for="page-active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</label>
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="closePageModal()" class="px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">Cancel</button>
                    <button type="submit" id="page-submit-btn" class="btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var pageEditId = null;

function openPageModal() {
    pageEditId = null;
    document.getElementById('page-id').value = '';
    document.getElementById('page-title').value = '';
    document.getElementById('page-slug').value = '';
    document.getElementById('page-content').value = '';
    document.getElementById('page-active').checked = true;
    document.getElementById('page-modal-title').textContent = 'Add Page';
    document.getElementById('page-submit-btn').textContent = 'Create';
    document.getElementById('page-modal').classList.remove('hidden');
}
function closePageModal() { document.getElementById('page-modal').classList.add('hidden'); }

function editPage(id) {
    pageEditId = id;
    fetch('<?= $baseUrl ?>/api/content-pages/' + id).then(function(r) { return r.json(); }).then(function(res) {
        if (res.success) {
            var p = res.data;
            document.getElementById('page-title').value = p.title || '';
            document.getElementById('page-slug').value = p.slug || '';
            document.getElementById('page-content').value = p.content || '';
            document.getElementById('page-active').checked = !!p.is_active;
            document.getElementById('page-modal-title').textContent = 'Edit Page';
            document.getElementById('page-submit-btn').textContent = 'Update';
            document.getElementById('page-modal').classList.remove('hidden');
        } else { showToast('Failed to load page', 'error'); }
    });
}

document.getElementById('page-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var data = {
        title: document.getElementById('page-title').value.trim(),
        slug: document.getElementById('page-slug').value.trim(),
        content: document.getElementById('page-content').value,
        is_active: document.getElementById('page-active').checked ? 1 : 0,
    };
    var url = pageEditId ? '<?= $baseUrl ?>/api/content-pages/' + pageEditId : '<?= $baseUrl ?>/api/content-pages';
    var method = pageEditId ? 'PUT' : 'POST';
    fetch(url, { method: method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast(pageEditId ? 'Page updated!' : 'Page created!'); closePageModal(); location.reload(); } else showToast(res.message || 'Failed to save', 'error'); })
    .catch(function() { showToast('Failed to save', 'error'); });
});

function deletePage(id) {
    if (!confirm('Are you sure you want to delete this page?')) return;
    fetch('<?= $baseUrl ?>/api/content-pages/' + id, { method: 'DELETE' })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast('Page deleted!'); location.reload(); } else showToast('Failed to delete', 'error'); });
}
</script>
