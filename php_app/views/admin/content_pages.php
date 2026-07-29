<?php
$pages = $pages ?? [];
$baseUrl = rtrim(env('APP_URL', ''), '/');

$slugOptions = [
    'contact-us' => 'Contact Us',
    'about-us' => 'About Us',
    'faq' => 'FAQ',
    'privacy-policy' => 'Privacy Policy',
    'terms-and-conditions' => 'Terms & Conditions',
    'return-policy' => 'Return Policy',
    'shipping-policy' => 'Shipping Policy',
];

$existingSlugs = array_column($pages, 'slug');
$availableSlugs = array_diff_key($slugOptions, array_flip($existingSlugs));
?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Content Pages</h1>
            <p class="text-gray-500 mt-1"><?= count($pages) ?> page(s) total</p>
        </div>
        <button data-open-modal="page-modal" class="btn-primary flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Page</span>
        </button>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Page</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Updated</th>
                        <th class="text-right px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php foreach ($pages as $p): ?>
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($p['title'] ?? '') ?></p>
                                    <p class="text-xs text-gray-500 truncate max-w-[250px]"><?= htmlspecialchars($p['meta_description'] ?? 'No description') ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <code class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-600 dark:text-gray-400">/<?= htmlspecialchars($p['slug'] ?? '') ?></code>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= !empty($p['is_active']) ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= !empty($p['is_active']) ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500">
                            <?= !empty($p['updated_at']) ? date('M d, Y', strtotime($p['updated_at'])) : '—' ?>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button onclick="togglePageActive(<?= $p['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="<?= !empty($p['is_active']) ? 'Deactivate' : 'Activate' ?>">
                                    <?php if (!empty($p['is_active'])): ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    <?php else: ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <?php endif; ?>
                                </button>
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
                    <tr><td colspan="5" class="px-6 py-16 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-lg font-medium">No content pages found</p>
                    </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="page-modal" class="modal fixed inset-0 bg-black/50 backdrop-blur-sm items-center justify-center z-50 p-4" style="display:none">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-3xl shadow-2xl animate-scale-in max-h-[90vh] overflow-y-auto">
            <h2 id="page-modal-title" class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-6">Add Page</h2>
            <form id="page-form" class="space-y-4">
                <input type="hidden" name="id" value="">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Page *</label>
                        <select name="slug" class="input-field" required id="page-slug-select">
                            <option value="">Select a page</option>
                            <?php foreach ($availableSlugs as $val => $label): ?>
                            <option value="<?= $val ?>"><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="slug_edit" class="input-field bg-gray-100 dark:bg-gray-700 cursor-not-allowed hidden" id="page-slug-edit" readonly />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                        <input type="text" name="title" class="input-field" placeholder="Page title" required />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Description</label>
                    <input type="text" name="meta_description" class="input-field" placeholder="SEO description" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content (HTML supported) *</label>
                    <textarea name="content" class="input-field font-mono text-sm" rows="14" required placeholder="<h2>Title</h2><p>Content...</p>"></textarea>
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" name="is_active" id="is_active" class="w-4 h-4 text-primary-600 rounded" />
                    <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Active (visible on site)</label>
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" data-close-modal class="px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">Cancel</button>
                    <button type="submit" class="btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var _pagesData = <?= json_encode(array_values($pages)) ?>;
var _slugOptions = <?= json_encode($slugOptions) ?>;
var _existingSlugs = <?= json_encode($existingSlugs) ?>;

function editPage(id) {
    var page = _pagesData.find(function(p) { return p.id === id; });
    if (!page) return;
    var form = document.getElementById('page-form');
    form.querySelector('[name="id"]').value = page.id;
    form.querySelector('[name="title"]').value = page.title || '';
    form.querySelector('[name="meta_description"]').value = page.meta_description || '';
    form.querySelector('[name="content"]').value = page.content || '';
    form.querySelector('[name="is_active"]').checked = !!page.is_active;
    var slugSelect = document.getElementById('page-slug-select');
    var slugEdit = document.getElementById('page-slug-edit');
    slugSelect.classList.add('hidden');
    slugSelect.removeAttribute('required');
    slugEdit.classList.remove('hidden');
    slugEdit.value = page.slug || '';
    document.getElementById('page-modal-title').textContent = 'Edit Page';
    form.querySelector('[type="submit"]').textContent = 'Update';
    form.querySelector('[type="submit"]').setAttribute('data-save', 'updateContentPage');
    form.querySelector('[type="submit"]').removeAttribute('data-create');
    openModal('page-modal');
}

document.getElementById('page-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = e.target;
    var id = form.querySelector('[name="id"]').value;
    var slug = id ? form.querySelector('[name="slug_edit"]').value : form.querySelector('[name="slug"]').value;
    var data = {
        title: form.querySelector('[name="title"]').value.trim(),
        slug: slug,
        meta_description: form.querySelector('[name="meta_description"]').value.trim(),
        content: form.querySelector('[name="content"]').value,
        is_active: form.querySelector('[name="is_active"]').checked ? 1 : 0,
    };
    var url = id ? '<?= $baseUrl ?>/api/content-pages/' + id : '<?= $baseUrl ?>/api/content-pages';
    var method = id ? 'PUT' : 'POST';
    fetch(url, { method: method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) { showToast(id ? 'Page updated!' : 'Page created!'); closeModal('page-modal'); location.reload(); }
        else showToast(res.message || 'Failed to save', 'error');
    })
    .catch(function() { showToast('Failed to save', 'error'); });
});

function togglePageActive(id) {
    var page = _pagesData.find(function(p) { return p.id === id; });
    if (!page) return;
    fetch('<?= $baseUrl ?>/api/content-pages/' + id, {
        method: 'PUT', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ is_active: !page.is_active ? 1 : 0 })
    })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast('Status updated!'); location.reload(); } else showToast('Failed', 'error'); });
}

function deletePage(id) {
    if (!confirm('Delete this page?')) return;
    fetch('<?= $baseUrl ?>/api/content-pages/' + id, { method: 'DELETE' })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast('Page deleted!'); location.reload(); } else showToast('Failed to delete', 'error'); });
}
</script>
