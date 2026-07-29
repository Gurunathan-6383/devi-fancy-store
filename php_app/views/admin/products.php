<?php
$products = $products ?? [];
$categories = $categories ?? [];
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-heading font-bold text-gray-900 dark:text-white">Manage Products</h1>
            <p class="text-gray-500 mt-1"><?= count($products) ?> product(s) total</p>
        </div>
        <button data-open-modal="product-modal" class="btn-primary flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Add Product</span>
        </button>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Image</th>
                    <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="products-tbody" class="divide-y">
                <?php foreach ($products as $p):
                    $images = $p['images'] ?? [];
                    if (is_string($images)) $images = json_decode($images, true) ?: [];
                ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-4 py-4">
                        <img src="<?= htmlspecialchars($images[0] ?? 'https://via.placeholder.com/48') ?>" alt="<?= htmlspecialchars($p['name'] ?? '') ?>" class="w-12 h-12 rounded-lg object-cover" />
                    </td>
                    <td class="px-4 py-4">
                        <span class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($p['name'] ?? '') ?></span>
                        <?php if (!empty($p['is_featured'])): ?>
                        <svg class="inline ml-1 w-4 h-4 text-accent-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500"><?= htmlspecialchars($p['category_name'] ?? '-') ?></td>
                    <td class="px-4 py-4">
                        <span class="font-semibold">₹<?= round(($p['offer_price'] ?? 0) ?: ($p['price'] ?? 0)) ?></span>
                        <?php if (!empty($p['offer_price'])): ?><span class="text-xs text-gray-400 line-through ml-1">₹<?= round($p['price'] ?? 0) ?></span><?php endif; ?>
                    </td>
                    <td class="px-4 py-4"><?= $p['stock'] ?? 0 ?></td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= ($p['status'] ?? '') === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' ?>"><?= $p['status'] ?? 'inactive' ?></span>
                    </td>
                    <td class="px-4 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <button onclick="editProduct(<?= $p['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                            <button onclick="deleteProduct(<?= $p['id'] ?? 0 ?>)" class="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($products) === 0): ?>
                <tr><td colspan="7" class="px-6 py-12 text-center text-gray-500">No products yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="product-modal" class="modal fixed inset-0 bg-black/50 backdrop-blur-sm items-center justify-center z-50 p-4" style="display:none">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl animate-scale-in">
            <h2 id="product-modal-title" class="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">Add Product</h2>
            <form id="product-form" class="space-y-4">
                <input type="hidden" name="id" value="">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product Name</label>
                        <input type="text" name="name" class="input-field" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                        <select name="category_id" class="input-field" required>
                            <option value="">Select category</option>
                            <?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?? '' ?>"><?= htmlspecialchars($c['name'] ?? '') ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="input-field">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price (₹)</label>
                        <input type="number" step="0.01" name="price" class="input-field" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Offer Price (₹)</label>
                        <input type="number" step="0.01" name="offer_price" class="input-field" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock</label>
                        <input type="number" name="stock" class="input-field" value="0" />
                    </div>
                    <div class="flex items-center space-x-2 pt-6">
                        <input type="checkbox" name="is_featured" id="is_featured" class="w-4 h-4 text-primary-600" />
                        <label for="is_featured" class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured Product</label>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" class="input-field" rows="3"></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Specifications (one per line)</label>
                        <textarea name="specifications" class="input-field" rows="3" placeholder="Material: Gold&#10;Weight: 10g&#10;Size: Medium"></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Images</label>
                        <input type="file" name="images" multiple accept="image/*" class="input-field" />
                        <input type="hidden" name="existing_images" value="[]" />
                        <div id="existing-images" class="flex flex-wrap gap-2 mt-2"></div>
                    </div>
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
var _productsData = <?= json_encode(array_values($products)) ?>;
var existingImages = [];

function editProduct(id) {
    var prod = _productsData.find(function(p) { return p.id === id; });
    if (!prod) return;
    var form = document.getElementById('product-form');
    form.querySelector('[name="id"]').value = prod.id;
    form.querySelector('[name="name"]').value = prod.name || '';
    form.querySelector('[name="category_id"]').value = prod.category_id || '';
    form.querySelector('[name="status"]').value = prod.status || 'active';
    form.querySelector('[name="price"]').value = prod.price || '';
    form.querySelector('[name="offer_price"]').value = prod.offer_price || '';
    form.querySelector('[name="stock"]').value = prod.stock || 0;
    form.querySelector('[name="description"]').value = prod.description || '';
    form.querySelector('[name="specifications"]').value = prod.specifications || '';
    form.querySelector('[name="is_featured"]').checked = !!prod.is_featured;
    existingImages = (typeof prod.images === 'string' ? JSON.parse(prod.images || '[]') : (prod.images || []));
    form.querySelector('[name="existing_images"]').value = JSON.stringify(existingImages);
    renderExistingImages();
    document.getElementById('product-modal-title').textContent = 'Edit Product';
    form.querySelector('[type="submit"]').textContent = 'Update';
    form.querySelector('[type="submit"]').setAttribute('data-save', 'updateProduct');
    form.querySelector('[type="submit"]').removeAttribute('data-create');
    openModal('product-modal');
}

function renderExistingImages() {
    var container = document.getElementById('existing-images');
    container.innerHTML = '';
    existingImages.forEach(function(img, i) {
        var div = document.createElement('div');
        div.className = 'relative';
        div.innerHTML = '<img src="' + img + '" class="w-16 h-16 rounded object-cover" /><button type="button" onclick="removeExistingImage(' + i + ')" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">&times;</button>';
        container.appendChild(div);
    });
}

function removeExistingImage(index) {
    existingImages.splice(index, 1);
    document.getElementById('product-form').querySelector('[name="existing_images"]').value = JSON.stringify(existingImages);
    renderExistingImages();
}

document.getElementById('product-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = e.target;
    var id = form.querySelector('[name="id"]').value;
    var fd = new FormData(form);
    if (id) fd.append('_method', 'PUT');
    var url = id ? '<?= $baseUrl ?>/api/products/' + id : '<?= $baseUrl ?>/api/products';
    fetch(url, { method: 'POST', body: fd })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) { showToast(id ? 'Product updated!' : 'Product created!'); closeModal('product-modal'); location.reload(); }
        else showToast(res.message || 'Failed to save', 'error');
    })
    .catch(function() { showToast('Failed to save', 'error'); });
});

function deleteProduct(id) {
    if (!confirm('Delete this product?')) return;
    fetch('<?= $baseUrl ?>/api/products/' + id, { method: 'DELETE' })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { showToast('Product deleted!'); location.reload(); } else showToast('Failed to delete', 'error'); });
}
</script>
