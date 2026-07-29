<?php
$catalogues = $catalogues ?? [];
$products = $products ?? [];
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Manage Catalogues</h1>
            <p class="text-gray-500 mt-1"><?= count($catalogues) ?> catalogue(s) total</p>
        </div>
        <button onclick="openCatalogueModal()" class="btn-primary flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Catalogue</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($catalogues as $cat): ?>
        <div class="card p-5">
            <?php if (!empty($cat['image'])): ?><img src="<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['title'] ?? '') ?>" class="w-full h-40 object-cover rounded-lg mb-4" /><?php endif; ?>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($cat['title'] ?? '') ?></h3>
            <?php if (!empty($cat['description'])): ?><p class="text-sm text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($cat['description']) ?></p><?php endif; ?>
            <div class="flex items-center space-x-2 mt-3">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold <?= !empty($cat['is_published']) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                    <?= !empty($cat['is_published']) ? 'Published' : 'Draft' ?>
                </span>
            </div>
            <div class="flex items-center space-x-2 mt-4 pt-3 border-t">
                <button onclick="toggleCataloguePublish(<?= $cat['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" title="<?= !empty($cat['is_published']) ? 'Unpublish' : 'Publish' ?>">
                    <?php if (!empty($cat['is_published'])): ?>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    <?php else: ?>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <?php endif; ?>
                </button>
                <button onclick="openManageProducts(<?= $cat['id'] ?? 0 ?>, '<?= htmlspecialchars($cat['title'] ?? '') ?>')" class="px-3 py-1.5 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">Manage Products</button>
                <div class="flex-1"></div>
                <button onclick="editCatalogue(<?= $cat['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                <button onclick="deleteCatalogue(<?= $cat['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (count($catalogues) === 0): ?>
        <div class="col-span-full text-center py-12 text-gray-500">No catalogues yet.</div>
        <?php endif; ?>
    </div>

    <!-- Catalogue Modal -->
    <div id="catalogue-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-md shadow-2xl animate-scale-in">
            <h2 id="catalogue-modal-title" class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">Add Catalogue</h2>
            <form id="catalogue-form" class="space-y-4">
                <input type="hidden" id="catalogue-id" value="">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                    <input type="text" id="catalogue-title" class="input-field" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea id="catalogue-description" class="input-field" rows="3"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image</label>
                    <input type="file" id="catalogue-image" accept="image/*" class="input-field" />
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="closeCatalogueModal()" class="px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">Cancel</button>
                    <button type="submit" id="catalogue-submit-btn" class="btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage Products Modal -->
    <div id="product-modal-panel" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-2xl max-h-[80vh] overflow-y-auto shadow-2xl animate-scale-in">
            <div class="flex items-center justify-between mb-4">
                <h2 id="product-modal-panel-title" class="text-xl font-heading font-bold text-gray-900 dark:text-white">Catalogue - Products</h2>
                <button onclick="closeProductPanel()" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Add Product</label>
                <select id="add-product-select" class="input-field" onchange="addProductToCatalogue(this.value); this.value='';">
                    <option value="">Select a product...</option>
                    <?php foreach ($products as $p): ?>
                    <option value="<?= $p['id'] ?? '' ?>"><?= htmlspecialchars(($p['name'] ?? '') . ' (₹' . round(($p['offer_price'] ?? 0) ?: ($p['price'] ?? 0)) . ')') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="catalogue-products-list" class="space-y-2"></div>
        </div>
    </div>
</div>

<script>
var catalogueEditId = null;
var selectedCatalogueId = null;

function openCatalogueModal() { catalogueEditId = null; document.getElementById('catalogue-id').value = ''; document.getElementById('catalogue-title').value = ''; document.getElementById('catalogue-description').value = ''; document.getElementById('catalogue-image').value = ''; document.getElementById('catalogue-modal-title').textContent = 'Add Catalogue'; document.getElementById('catalogue-submit-btn').textContent = 'Create'; document.getElementById('catalogue-modal').classList.remove('hidden'); }
function closeCatalogueModal() { document.getElementById('catalogue-modal').classList.add('hidden'); }

function editCatalogue(id) {
    catalogueEditId = id;
    fetch('<?= $baseUrl ?>/api/catalogues/' + id).then(function(r) { return r.json(); }).then(function(res) {
        if (res.success) {
            var cat = res.data;
            document.getElementById('catalogue-title').value = cat.title;
            document.getElementById('catalogue-description').value = cat.description || '';
            document.getElementById('catalogue-modal-title').textContent = 'Edit Catalogue';
            document.getElementById('catalogue-submit-btn').textContent = 'Update';
            document.getElementById('catalogue-modal').classList.remove('hidden');
        } else showToast('Failed to load', 'error');
    });
}

document.getElementById('catalogue-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData();
    formData.append('title', document.getElementById('catalogue-title').value.trim());
    formData.append('description', document.getElementById('catalogue-description').value.trim());
    var fileInput = document.getElementById('catalogue-image');
    if (fileInput.files[0]) formData.append('image', fileInput.files[0]);
    var url = catalogueEditId ? '<?= $baseUrl ?>/api/catalogues/' + catalogueEditId : '<?= $baseUrl ?>/api/catalogues';
    fetch(url, { method: 'POST', body: formData })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast(catalogueEditId ? 'Updated!' : 'Created!'); closeCatalogueModal(); location.reload(); } else showToast(res.message || 'Failed', 'error'); });
});

function toggleCataloguePublish(id) { fetch('<?= $baseUrl ?>/api/catalogues/' + id + '/toggle-publish', { method: 'PATCH' }).then(function(r) { return r.json(); }).then(function(res) { if (res.success) { showToast('Toggled!'); location.reload(); } else showToast('Failed', 'error'); }); }
function deleteCatalogue(id) { if (!confirm('Delete this catalogue?')) return; fetch('<?= $baseUrl ?>/api/catalogues/' + id, { method: 'DELETE' }).then(function(r) { return r.json(); }).then(function(res) { if (res.success) { showToast('Deleted!'); location.reload(); } else showToast('Failed', 'error'); }); }

function openManageProducts(id, title) {
    selectedCatalogueId = id;
    document.getElementById('product-modal-panel-title').textContent = title + ' - Products';
    loadCatalogueProducts(id);
    document.getElementById('product-modal-panel').classList.remove('hidden');
}
function closeProductPanel() { document.getElementById('product-modal-panel').classList.add('hidden'); }

function loadCatalogueProducts(id) {
    fetch('<?= $baseUrl ?>/api/catalogues/' + id + '/products').then(function(r) { return r.json(); }).then(function(res) {
        var list = document.getElementById('catalogue-products-list');
        list.innerHTML = '';
        if (res.success && res.data.length > 0) {
            res.data.forEach(function(p) {
                var imgs = p.images || []; if (typeof imgs === 'string') try { imgs = JSON.parse(imgs); } catch(e) { imgs = []; }
                var div = document.createElement('div');
                div.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg';
                div.innerHTML = '<div class="flex items-center space-x-3"><img src="' + (imgs[0] || 'https://via.placeholder.com/40') + '" class="w-10 h-10 rounded object-cover" /><div><p class="font-medium text-gray-900 dark:text-white text-sm">' + p.name + '</p><p class="text-xs text-gray-500">₹' + Math.round(p.offer_price || p.price) + '</p></div></div><button onclick="removeCatalogueProduct(' + p.id + ')" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>';
                list.appendChild(div);
            });
        } else { list.innerHTML = '<p class="text-center text-gray-500 py-8">No products in this catalogue</p>'; }
    });
}

function addProductToCatalogue(productId) { if (!productId) return; fetch('<?= $baseUrl ?>/api/catalogues/' + selectedCatalogueId + '/products', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ product_id: parseInt(productId) }) }).then(function(r) { return r.json(); }).then(function(res) { if (res.success) { showToast('Product added'); loadCatalogueProducts(selectedCatalogueId); } else showToast('Failed', 'error'); }); }
function removeCatalogueProduct(productId) { fetch('<?= $baseUrl ?>/api/catalogues/' + selectedCatalogueId + '/products/' + productId, { method: 'DELETE' }).then(function(r) { return r.json(); }).then(function(res) { if (res.success) { showToast('Product removed'); loadCatalogueProducts(selectedCatalogueId); } else showToast('Failed', 'error'); }); }
</script>
