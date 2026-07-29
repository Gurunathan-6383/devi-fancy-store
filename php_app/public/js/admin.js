// ── Modal ──
function openModal(id) {
  var modal = document.getElementById(id);
  if (modal) {
    modal.classList.add('show');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
}

function closeModal(id) {
  var modal = document.getElementById(id);
  if (modal) {
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }
}

function confirmDelete(msg) {
  return confirm(msg || 'Are you sure you want to delete this?');
}

// ── Categories ──
async function loadCategories() {
  try {
    var res = await api.get('/categories');
    var categories = res.data || [];
    var tbody = document.getElementById('categories-tbody');
    if (!tbody) return;
    if (!categories.length) {
      tbody.innerHTML = '<tr><td colspan="5" class="text-center text-gray-400 py-12">No categories found.</td></tr>';
      return;
    }
    tbody.innerHTML = categories.map(function(c) {
      return '<tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">' +
        '<td class="px-6 py-4"><img src="' + (c.image || 'https://via.placeholder.com/48') + '" class="w-12 h-12 rounded-lg object-cover" /></td>' +
        '<td class="px-6 py-4 font-medium text-gray-900 dark:text-white">' + escapeHtml(c.name) + '</td>' +
        '<td class="px-6 py-4 text-gray-500 text-sm">' + escapeHtml(c.slug) + '</td>' +
        '<td class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold ' + (c.is_hidden ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') + '">' + (c.is_hidden ? 'Hidden' : 'Visible') + '</span></td>' +
        '<td class="px-6 py-4 text-right">' +
          '<div class="flex items-center justify-end space-x-2">' +
            '<button onclick="toggleCategoryVisibility(' + c.id + ')" class="p-2 text-gray-500 hover:text-primary-600 hover:bg-gray-100 rounded-lg" title="Toggle">' + (c.is_hidden ? '&#128065;&#8288;' : '&#128064;') + '</button>' +
            '<button onclick="editCategory(' + c.id + ')" class="p-2 text-gray-500 hover:text-secondary-600 hover:bg-gray-100 rounded-lg" title="Edit">&#9998;</button>' +
            '<button onclick="deleteCategory(' + c.id + ')" class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-lg" title="Delete">&#128465;</button>' +
          '</div>' +
        '</td></tr>';
    }).join('');
    window._categoriesData = categories;
  } catch (e) {
    showToast('Failed to load categories', 'error');
  }
}

async function saveCategory() {
  var form = document.getElementById('category-form');
  if (!form) return;
  var fd = new FormData(form);
  var editId = fd.get('id');
  try {
    if (editId) {
      fd.delete('id');
      await api.upload('/categories/' + editId + '?_method=PUT', fd);
      showToast('Category updated!');
    } else {
      await api.upload('/categories', fd);
      showToast('Category created!');
    }
    closeModal('category-modal');
    form.reset();
    loadCategories();
  } catch (e) {
    showToast(e.message || 'Failed to save category', 'error');
  }
}

function editCategory(id) {
  var cat = (window._categoriesData || []).find(function(c) { return c.id === id; });
  if (!cat) return;
  var form = document.getElementById('category-form');
  if (!form) return;
  form.querySelector('[name="name"]').value = cat.name || '';
  var hidden = form.querySelector('[name="id"]');
  if (hidden) hidden.value = cat.id;
  else { hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = 'id'; hidden.value = cat.id; form.appendChild(hidden); }
  openModal('category-modal');
}

async function deleteCategory(id) {
  if (!confirmDelete('Delete this category?')) return;
  try {
    await api.delete('/categories/' + id);
    showToast('Category deleted!');
    loadCategories();
  } catch (e) {
    showToast(e.message || 'Failed to delete', 'error');
  }
}

async function toggleCategoryVisibility(id) {
  try {
    await api.patch('/categories/' + id + '/toggle-visibility');
    showToast('Visibility toggled!');
    loadCategories();
  } catch (e) {
    showToast(e.message || 'Failed to toggle', 'error');
  }
}

// ── Products ──
async function loadProducts() {
  try {
    var res = await api.get('/products');
    var products = res.data || [];
    var tbody = document.getElementById('products-tbody');
    if (!tbody) return;
    if (!products.length) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-400 py-12">No products yet.</td></tr>';
      return;
    }
    tbody.innerHTML = products.map(function(p) {
      var img = (p.images && p.images[0]) || 'https://via.placeholder.com/48';
      var price = p.offer_price || p.price;
      return '<tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">' +
        '<td class="px-4 py-4"><img src="' + img + '" class="w-12 h-12 rounded-lg object-cover" /></td>' +
        '<td class="px-4 py-4"><span class="font-medium text-gray-900 dark:text-white">' + escapeHtml(p.name) + '</span>' + (p.is_featured ? ' &#9733;' : '') + '</td>' +
        '<td class="px-4 py-4 text-sm text-gray-500">' + escapeHtml(p.category_name || '-') + '</td>' +
        '<td class="px-4 py-4"><span class="font-semibold">&#8377;' + price + '</span>' + (p.offer_price ? '<span class="text-xs text-gray-400 line-through ml-1">&#8377;' + p.price + '</span>' : '') + '</td>' +
        '<td class="px-4 py-4">' + p.stock + '</td>' +
        '<td class="px-4 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold ' + (p.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500') + '">' + p.status + '</span></td>' +
        '<td class="px-4 py-4 text-right"><div class="flex items-center justify-end space-x-2">' +
          '<button onclick="editProduct(' + p.id + ')" class="p-2 text-gray-500 hover:text-secondary-600 hover:bg-gray-100 rounded-lg">&#9998;</button>' +
          '<button onclick="deleteProduct(' + p.id + ')" class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-lg">&#128465;</button>' +
        '</div></td></tr>';
    }).join('');
    window._productsData = products;
  } catch (e) {
    showToast('Failed to load products', 'error');
  }
}

async function saveProduct() {
  var form = document.getElementById('product-form');
  if (!form) return;
  var fd = new FormData(form);
  var editId = fd.get('id');
  try {
    if (editId) {
      fd.delete('id');
      await api.upload('/products/' + editId + '?_method=PUT', fd);
      showToast('Product updated!');
    } else {
      await api.upload('/products', fd);
      showToast('Product created!');
    }
    closeModal('product-modal');
    form.reset();
    loadProducts();
  } catch (e) {
    showToast(e.message || 'Failed to save product', 'error');
  }
}

function editProduct(id) {
  var prod = (window._productsData || []).find(function(p) { return p.id === id; });
  if (!prod) return;
  var form = document.getElementById('product-form');
  if (!form) return;
  var fields = ['name', 'category_id', 'description', 'specifications', 'price', 'offer_price', 'stock', 'status'];
  fields.forEach(function(f) {
    var el = form.querySelector('[name="' + f + '"]');
    if (el) el.value = prod[f] != null ? prod[f] : '';
  });
  var feat = form.querySelector('[name="is_featured"]');
  if (feat) feat.checked = !!prod.is_featured;
  var hidden = form.querySelector('[name="id"]');
  if (hidden) hidden.value = prod.id;
  else { hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = 'id'; hidden.value = prod.id; form.appendChild(hidden); }
  openModal('product-modal');
}

async function deleteProduct(id) {
  if (!confirmDelete('Delete this product?')) return;
  try {
    await api.delete('/products/' + id);
    showToast('Product deleted!');
    loadProducts();
  } catch (e) {
    showToast(e.message || 'Failed to delete', 'error');
  }
}

// ── Catalogues ──
async function loadCatalogues() {
  try {
    var res = await api.get('/catalogues');
    var catalogues = res.data || [];
    var container = document.getElementById('catalogues-grid');
    if (!container) return;
    if (!catalogues.length) {
      container.innerHTML = '<div class="col-span-full text-center py-12 text-gray-500">No catalogues yet.</div>';
      return;
    }
    container.innerHTML = catalogues.map(function(c) {
      return '<div class="card p-5">' +
        (c.image ? '<img src="' + c.image + '" class="w-full h-40 object-cover rounded-lg mb-4" />' : '') +
        '<h3 class="text-lg font-semibold text-gray-900 dark:text-white">' + escapeHtml(c.title) + '</h3>' +
        (c.description ? '<p class="text-sm text-gray-500 mt-1">' + escapeHtml(c.description) + '</p>' : '') +
        '<span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-xs font-semibold ' + (c.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500') + '">' + (c.is_published ? 'Published' : 'Draft') + '</span>' +
        '<div class="flex items-center space-x-2 mt-4 pt-3 border-t">' +
          '<button onclick="toggleCataloguePublish(' + c.id + ')" class="p-2 text-gray-500 hover:text-primary-600 rounded-lg hover:bg-gray-100" title="Toggle Publish">' + (c.is_published ? '&#128065;&#8288;' : '&#128064;') + '</button>' +
          '<button onclick="editCatalogue(' + c.id + ')" class="p-2 text-gray-500 hover:text-secondary-600 rounded-lg hover:bg-gray-100">&#9998;</button>' +
          '<button onclick="deleteCatalogue(' + c.id + ')" class="p-2 text-gray-500 hover:text-red-600 rounded-lg hover:bg-gray-100">&#128465;</button>' +
        '</div></div>';
    }).join('');
    window._cataloguesData = catalogues;
  } catch (e) {
    showToast('Failed to load catalogues', 'error');
  }
}

async function saveCatalogue() {
  var form = document.getElementById('catalogue-form');
  if (!form) return;
  var fd = new FormData(form);
  var editId = fd.get('id');
  try {
    if (editId) {
      fd.delete('id');
      await api.upload('/catalogues/' + editId + '?_method=PUT', fd);
      showToast('Catalogue updated!');
    } else {
      await api.upload('/catalogues', fd);
      showToast('Catalogue created!');
    }
    closeModal('catalogue-modal');
    form.reset();
    loadCatalogues();
  } catch (e) {
    showToast(e.message || 'Failed to save catalogue', 'error');
  }
}

function editCatalogue(id) {
  var cat = (window._cataloguesData || []).find(function(c) { return c.id === id; });
  if (!cat) return;
  var form = document.getElementById('catalogue-form');
  if (!form) return;
  form.querySelector('[name="title"]').value = cat.title || '';
  var desc = form.querySelector('[name="description"]');
  if (desc) desc.value = cat.description || '';
  var hidden = form.querySelector('[name="id"]');
  if (hidden) hidden.value = cat.id;
  else { hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = 'id'; hidden.value = cat.id; form.appendChild(hidden); }
  openModal('catalogue-modal');
}

async function deleteCatalogue(id) {
  if (!confirmDelete('Delete this catalogue?')) return;
  try {
    await api.delete('/catalogues/' + id);
    showToast('Catalogue deleted!');
    loadCatalogues();
  } catch (e) {
    showToast(e.message || 'Failed to delete', 'error');
  }
}

async function toggleCataloguePublish(id) {
  try {
    await api.patch('/catalogues/' + id + '/toggle-publish');
    showToast('Publish status toggled!');
    loadCatalogues();
  } catch (e) {
    showToast(e.message || 'Failed to toggle', 'error');
  }
}

// ── Orders ──
async function loadOrders() {
  try {
    var res = await api.get('/orders');
    var orders = res.data || [];
    var tbody = document.getElementById('orders-tbody');
    if (!tbody) return;
    if (!orders.length) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-400 py-12">No orders yet.</td></tr>';
      return;
    }
    tbody.innerHTML = orders.map(function(o) {
      var items = o.items;
      if (typeof items === 'string') { try { items = JSON.parse(items); } catch(e) { items = []; } }
      var productNames = Array.isArray(items) ? items.map(function(i) { return i.name || ''; }).join(', ') : '';
      var qty = Array.isArray(items) ? items.reduce(function(s, i) { return s + (i.quantity || 1); }, 0) : 0;
      return '<tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">' +
        '<td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">' + escapeHtml(o.name) + '</td>' +
        '<td class="px-5 py-4 text-gray-600 font-mono text-sm">' + escapeHtml(o.phone) + '</td>' +
        '<td class="px-5 py-4 text-gray-600 max-w-[200px] truncate text-sm" title="' + escapeHtml(o.address) + '">' + escapeHtml(o.address) + '</td>' +
        '<td class="px-5 py-4 text-gray-600 max-w-[200px] truncate text-sm" title="' + escapeHtml(productNames) + '">' + escapeHtml(productNames) + '</td>' +
        '<td class="px-5 py-4"><span class="bg-primary-100 text-primary-700 font-bold text-xs px-2.5 py-1 rounded-full">' + qty + '</span></td>' +
        '<td class="px-5 py-4 font-extrabold text-gray-900 dark:text-white">' + escapeHtml(o.total) + '</td>' +
        '<td class="px-5 py-4 text-sm text-gray-500">' + escapeHtml(o.order_date || '') + '</td></tr>';
    }).join('');
  } catch (e) {
    showToast('Failed to load orders', 'error');
  }
}

// ── Customers (derived from orders) ──
async function loadCustomers() {
  try {
    var res = await api.get('/orders');
    var orders = res.data || [];
    var map = {};
    orders.forEach(function(o) {
      if (!map[o.phone]) map[o.phone] = { name: o.name, phone: o.phone, address: o.address, orders: 0 };
      map[o.phone].orders++;
    });
    var customers = Object.values(map);
    var tbody = document.getElementById('customers-tbody');
    if (!tbody) return;
    if (!customers.length) {
      tbody.innerHTML = '<tr><td colspan="4" class="text-center text-gray-400 py-12">No customers yet.</td></tr>';
      return;
    }
    tbody.innerHTML = customers.map(function(c) {
      return '<tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">' +
        '<td class="px-6 py-4 font-medium text-gray-900 dark:text-white">' + escapeHtml(c.name) + '</td>' +
        '<td class="px-6 py-4 text-gray-600">' + escapeHtml(c.phone) + '</td>' +
        '<td class="px-6 py-4 text-gray-600 max-w-[250px] truncate">' + escapeHtml(c.address) + '</td>' +
        '<td class="px-6 py-4"><span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold">' + c.orders + '</span></td></tr>';
    }).join('');
  } catch (e) {
    showToast('Failed to load customers', 'error');
  }
}

// ── Announcements ──
async function loadAnnouncements() {
  try {
    var res = await api.get('/announcements');
    var list = res.data || [];
    var tbody = document.getElementById('announcements-tbody');
    if (!tbody) return;
    if (!list.length) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-400 py-12">No announcements yet.</td></tr>';
      return;
    }
    tbody.innerHTML = list.map(function(a) {
      return '<tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">' +
        '<td class="px-5 py-3"><div class="px-3 py-1.5 rounded-full text-xs font-semibold" style="background:' + (a.bg_color || '#e04a6f') + ';color:' + (a.text_color || '#fff') + '">' + escapeHtml(a.title) + '</div></td>' +
        '<td class="px-5 py-4 font-medium text-gray-900 dark:text-white">' + escapeHtml(a.title) + '</td>' +
        '<td class="px-5 py-4 text-sm text-gray-500">' + escapeHtml(a.type || 'general') + '</td>' +
        '<td class="px-5 py-4 text-sm">' + (a.priority || 0) + '</td>' +
        '<td class="px-5 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold ' + (a.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500') + '">' + (a.status || 'active') + '</span></td>' +
        '<td class="px-5 py-4 text-sm text-gray-500">' + (a.start_date || 'Always') + '</td>' +
        '<td class="px-5 py-4 text-right"><div class="flex items-center justify-end space-x-2">' +
          '<button onclick="editAnnouncement(' + a.id + ')" class="p-2 text-gray-500 hover:text-secondary-600 hover:bg-gray-100 rounded-lg">&#9998;</button>' +
          '<button onclick="deleteAnnouncement(' + a.id + ')" class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-lg">&#128465;</button>' +
        '</div></td></tr>';
    }).join('');
    window._announcementsData = list;
  } catch (e) {
    showToast('Failed to load announcements', 'error');
  }
}

async function saveAnnouncement() {
  var form = document.getElementById('announcement-form');
  if (!form) return;
  var data = {};
  new FormData(form).forEach(function(v, k) { data[k] = v; });
  var editId = data.id;
  delete data.id;
  try {
    if (editId) {
      await api.put('/announcements/' + editId, data);
      showToast('Announcement updated!');
    } else {
      await api.post('/announcements', data);
      showToast('Announcement created!');
    }
    closeModal('announcement-modal');
    form.reset();
    loadAnnouncements();
  } catch (e) {
    showToast(e.message || 'Failed to save announcement', 'error');
  }
}

function editAnnouncement(id) {
  var ann = (window._announcementsData || []).find(function(a) { return a.id === id; });
  if (!ann) return;
  var form = document.getElementById('announcement-form');
  if (!form) return;
  ['title', 'message', 'type', 'status', 'bg_color', 'text_color', 'priority', 'start_date', 'end_date', 'redirect_url'].forEach(function(f) {
    var el = form.querySelector('[name="' + f + '"]');
    if (el) el.value = ann[f] != null ? ann[f] : '';
  });
  var hidden = form.querySelector('[name="id"]');
  if (hidden) hidden.value = ann.id;
  else { hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = 'id'; hidden.value = ann.id; form.appendChild(hidden); }
  openModal('announcement-modal');
}

async function deleteAnnouncement(id) {
  if (!confirmDelete('Delete this announcement?')) return;
  try {
    await api.delete('/announcements/' + id);
    showToast('Announcement deleted!');
    loadAnnouncements();
  } catch (e) {
    showToast(e.message || 'Failed to delete', 'error');
  }
}

async function toggleAnnouncementStatus(id) {
  try {
    await api.patch('/announcements/' + id + '/toggle-status');
    showToast('Status toggled!');
    loadAnnouncements();
  } catch (e) {
    showToast(e.message || 'Failed to toggle', 'error');
  }
}

// ── Content Pages ──
async function loadContentPages() {
  try {
    var res = await api.get('/content-pages');
    var pages = res.data || [];
    var tbody = document.getElementById('pages-tbody');
    if (!tbody) return;
    if (!pages.length) {
      tbody.innerHTML = '<tr><td colspan="5" class="text-center text-gray-400 py-12">No pages yet.</td></tr>';
      return;
    }
    tbody.innerHTML = pages.map(function(p) {
      return '<tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">' +
        '<td class="px-6 py-4 font-medium text-gray-900 dark:text-white">' + escapeHtml(p.title) + '</td>' +
        '<td class="px-6 py-4 text-gray-500 text-sm"><code>' + escapeHtml(p.slug) + '</code></td>' +
        '<td class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-semibold ' + (p.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500') + '">' + (p.is_active ? 'Active' : 'Inactive') + '</span></td>' +
        '<td class="px-6 py-4 text-right"><div class="flex items-center justify-end space-x-2">' +
          '<button onclick="editContentPage(' + p.id + ')" class="p-2 text-gray-500 hover:text-secondary-600 hover:bg-gray-100 rounded-lg">&#9998;</button>' +
          '<button onclick="deleteContentPage(' + p.id + ')" class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-lg">&#128465;</button>' +
        '</div></td></tr>';
    }).join('');
    window._pagesData = pages;
  } catch (e) {
    showToast('Failed to load pages', 'error');
  }
}

async function saveContentPage() {
  var form = document.getElementById('page-form');
  if (!form) return;
  var data = {};
  new FormData(form).forEach(function(v, k) { data[k] = v; });
  var editId = data.id;
  delete data.id;
  try {
    if (editId) {
      await api.put('/content-pages/' + editId, data);
      showToast('Page updated!');
    } else {
      await api.post('/content-pages', data);
      showToast('Page created!');
    }
    closeModal('page-modal');
    form.reset();
    loadContentPages();
  } catch (e) {
    showToast(e.message || 'Failed to save page', 'error');
  }
}

function editContentPage(id) {
  var page = (window._pagesData || []).find(function(p) { return p.id === id; });
  if (!page) return;
  var form = document.getElementById('page-form');
  if (!form) return;
  ['slug', 'title', 'content', 'meta_description'].forEach(function(f) {
    var el = form.querySelector('[name="' + f + '"]');
    if (el) el.value = page[f] != null ? page[f] : '';
  });
  var active = form.querySelector('[name="is_active"]');
  if (active) active.checked = !!page.is_active;
  var hidden = form.querySelector('[name="id"]');
  if (hidden) hidden.value = page.id;
  else { hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = 'id'; hidden.value = page.id; form.appendChild(hidden); }
  openModal('page-modal');
}

async function deleteContentPage(id) {
  if (!confirmDelete('Delete this page?')) return;
  try {
    await api.delete('/content-pages/' + id);
    showToast('Page deleted!');
    loadContentPages();
  } catch (e) {
    showToast(e.message || 'Failed to delete', 'error');
  }
}

// ── Settings ──
async function loadSettings() {
  try {
    var res = await api.get('/settings');
    var settings = res.data || {};
    var form = document.getElementById('settings-form');
    if (!form) return;
    Object.keys(settings).forEach(function(key) {
      var input = form.querySelector('[name="' + key + '"]');
      if (input) input.value = settings[key] != null ? settings[key] : '';
    });
  } catch (e) {
    showToast('Failed to load settings', 'error');
  }
}

async function saveSettings() {
  var form = document.getElementById('settings-form');
  if (!form) return;
  var fd = new FormData(form);
  try {
    await api.upload('/settings?_method=PUT', fd);
    showToast('Settings saved!');
  } catch (e) {
    showToast(e.message || 'Failed to save settings', 'error');
  }
}

// ── Dashboard ──
async function loadDashboard() {
  try {
    var catRes = await api.get('/categories');
    var prodRes = await api.get('/products');
    var catCount = (catRes.data || []).length;
    var prodCount = (prodRes.data || []).length;
    var el;
    el = document.getElementById('stat-categories');
    if (el) el.textContent = catCount;
    el = document.getElementById('stat-products');
    if (el) el.textContent = prodCount;
  } catch (e) {}
}

// ── Helpers ──
function escapeHtml(str) {
  if (!str) return '';
  var div = document.createElement('div');
  div.textContent = String(str);
  return div.innerHTML;
}

// ── Init ──
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('[data-close-modal]').forEach(function(el) {
    el.addEventListener('click', function() {
      var modal = el.closest('.modal');
      if (modal) closeModal(modal.id);
    });
  });
  document.querySelectorAll('.modal').forEach(function(modal) {
    modal.addEventListener('click', function(e) {
      if (e.target === modal) closeModal(modal.id);
    });
  });
});

window.openModal = openModal;
window.closeModal = closeModal;
window.loadCategories = loadCategories;
window.saveCategory = saveCategory;
window.editCategory = editCategory;
window.deleteCategory = deleteCategory;
window.toggleCategoryVisibility = toggleCategoryVisibility;
window.loadProducts = loadProducts;
window.saveProduct = saveProduct;
window.editProduct = editProduct;
window.deleteProduct = deleteProduct;
window.loadCatalogues = loadCatalogues;
window.saveCatalogue = saveCatalogue;
window.editCatalogue = editCatalogue;
window.deleteCatalogue = deleteCatalogue;
window.toggleCataloguePublish = toggleCataloguePublish;
window.loadOrders = loadOrders;
window.loadCustomers = loadCustomers;
window.loadAnnouncements = loadAnnouncements;
window.saveAnnouncement = saveAnnouncement;
window.editAnnouncement = editAnnouncement;
window.deleteAnnouncement = deleteAnnouncement;
window.toggleAnnouncementStatus = toggleAnnouncementStatus;
window.loadContentPages = loadContentPages;
window.saveContentPage = saveContentPage;
window.editContentPage = editContentPage;
window.deleteContentPage = deleteContentPage;
window.loadSettings = loadSettings;
window.saveSettings = saveSettings;
window.loadDashboard = loadDashboard;
