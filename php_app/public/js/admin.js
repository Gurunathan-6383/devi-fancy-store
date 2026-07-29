// ── Modal ──
function openModal(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.classList.add('show');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }
}

function closeModal(id) {
  const modal = document.getElementById(id);
  if (modal) {
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }
}

function initModalCloseButtons() {
  document.querySelectorAll('[data-close-modal]').forEach(function (el) {
    el.addEventListener('click', function () {
      const modal = el.closest('.modal');
      if (modal) closeModal(modal.id);
    });
  });

  document.querySelectorAll('.modal').forEach(function (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === modal) closeModal(modal.id);
    });
  });
}

// ── Helpers ──
function getFormData(form) {
  const fd = new FormData(form);
  const obj = {};
  fd.forEach(function (val, key) {
    obj[key] = val;
  });
  return obj;
}

function getFormFileData(form) {
  return new FormData(form);
}

function getCheckboxValue(selector, fallback) {
  const el = document.querySelector(selector);
  if (!el) return fallback;
  return el.checked;
}

function getSelectedValue(selector) {
  const el = document.querySelector(selector);
  return el ? el.value : '';
}

function setFormValues(form, data) {
  if (!form || !data) return;
  Object.keys(data).forEach(function (key) {
    const input = form.querySelector('[name="' + key + '"]');
    if (!input) return;
    if (input.type === 'checkbox' || input.type === 'radio') {
      input.checked = !!data[key];
    } else {
      input.value = data[key] != null ? data[key] : '';
    }
  });
}

function renderTableBody(tbodyId, rows, columns) {
  const tbody = document.getElementById(tbodyId);
  if (!tbody) return;
  if (!rows || rows.length === 0) {
    tbody.innerHTML = '<tr><td colspan="' + columns.length + '" class="text-center text-muted py-4">No records found.</td></tr>';
    return;
  }
  tbody.innerHTML = rows.map(function (row) {
    return '<tr>' + columns.map(function (col) {
      let val = row[col.key];
      if (col.render) {
        val = col.render(val, row);
      } else if (val == null) {
        val = '-';
      }
      return '<td>' + val + '</td>';
    }).join('') + '</tr>';
  }).join('');
}

function confirmDelete(message) {
  return confirm(message || 'Are you sure you want to delete this?');
}

// ── Categories ──
async function loadCategories() {
  try {
    const res = await api.get('/categories');
    const categories = res.categories || res || [];
    renderTableBody('categories-tbody', categories, [
      { key: 'id' },
      { key: 'name' },
      { key: 'slug' },
      { key: 'is_active', render: function (v) { return v ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Hidden</span>'; } },
      { key: 'sort_order' },
      { key: 'id', render: function (v, row) {
        return '<div class="d-flex gap-1">' +
          '<button class="btn btn-sm btn-primary" onclick="editCategory(' + v + ')"><i class="fa fa-edit"></i></button>' +
          '<button class="btn btn-sm btn-warning" onclick="toggleCategoryVisibility(' + v + ',' + (row.is_active ? 'false' : 'true') + ')"><i class="fa fa-eye' + (row.is_active ? '-slash' : '') + '"></i></button>' +
          '<button class="btn btn-sm btn-danger" onclick="deleteCategory(' + v + ')"><i class="fa fa-trash"></i></button>' +
          '</div>';
      }}
    ]);
    window._categoriesData = categories;
  } catch (e) {
    showToast('Failed to load categories', 'error');
  }
}

async function createCategory() {
  const form = document.getElementById('category-form');
  if (!form) return;
  const data = getFormData(form);

  try {
    await api.post('/categories', data);
    showToast('Category created', 'success');
    closeModal('category-modal');
    form.reset();
    loadCategories();
  } catch (e) {
    showToast(e.message || 'Failed to create category', 'error');
  }
}

async function editCategory(id) {
  const cat = (window._categoriesData || []).find(function (c) { return c.id === id; });
  if (!cat) return;
  const form = document.getElementById('category-form');
  setFormValues(form, cat);
  openModal('category-modal');
}

async function updateCategory() {
  const form = document.getElementById('category-form');
  if (!form) return;
  const data = getFormData(form);
  const id = data.id;
  if (!id) return;

  try {
    await api.put('/categories/' + id, data);
    showToast('Category updated', 'success');
    closeModal('category-modal');
    form.reset();
    loadCategories();
  } catch (e) {
    showToast(e.message || 'Failed to update category', 'error');
  }
}

async function deleteCategory(id) {
  if (!confirmDelete('Delete this category?')) return;
  try {
    await api.delete('/categories/' + id);
    showToast('Category deleted', 'success');
    loadCategories();
  } catch (e) {
    showToast(e.message || 'Failed to delete category', 'error');
  }
}

async function toggleCategoryVisibility(id, currentActive) {
  try {
    await api.put('/categories/' + id, { is_active: currentActive === 'true' || currentActive === true ? false : true });
    showToast('Visibility updated', 'success');
    loadCategories();
  } catch (e) {
    showToast(e.message || 'Failed to update visibility', 'error');
  }
}

// ── Products ──
async function loadProducts(page) {
  page = page || 1;
  try {
    const res = await api.get('/products?page=' + page + '&limit=20');
    const products = res.products || res.data || res || [];
    renderTableBody('products-tbody', products, [
      { key: 'id' },
      { key: 'name' },
      { key: 'category', render: function (v) { return v && v.name ? v.name : (v || '-'); } },
      { key: 'price', render: function (v) { return '&#8377;' + (v || 0); } },
      { key: 'stock' },
      { key: 'is_active', render: function (v) { return v ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Hidden</span>'; } },
      { key: 'id', render: function (v, row) {
        return '<div class="d-flex gap-1">' +
          '<button class="btn btn-sm btn-primary" onclick="editProduct(' + v + ')"><i class="fa fa-edit"></i></button>' +
          '<button class="btn btn-sm btn-danger" onclick="deleteProduct(' + v + ')"><i class="fa fa-trash"></i></button>' +
          '</div>';
      }}
    ]);
    window._productsData = products;
  } catch (e) {
    showToast('Failed to load products', 'error');
  }
}

async function createProduct() {
  const form = document.getElementById('product-form');
  if (!form) return;
  const fd = getFormFileData(form);

  try {
    await api.upload('/products', fd);
    showToast('Product created', 'success');
    closeModal('product-modal');
    form.reset();
    loadProducts();
  } catch (e) {
    showToast(e.message || 'Failed to create product', 'error');
  }
}

async function editProduct(id) {
  const prod = (window._productsData || []).find(function (p) { return p.id === id; });
  if (!prod) return;
  const form = document.getElementById('product-form');
  setFormValues(form, prod);
  openModal('product-modal');
}

async function updateProduct() {
  const form = document.getElementById('product-form');
  if (!form) return;
  const fd = getFormFileData(form);
  const id = fd.get('id');
  if (!id) return;
  fd.delete('id');

  try {
    await api.upload('/products/' + id + '?_method=PUT', fd);
    showToast('Product updated', 'success');
    closeModal('product-modal');
    form.reset();
    loadProducts();
  } catch (e) {
    showToast(e.message || 'Failed to update product', 'error');
  }
}

async function deleteProduct(id) {
  if (!confirmDelete('Delete this product?')) return;
  try {
    await api.delete('/products/' + id);
    showToast('Product deleted', 'success');
    loadProducts();
  } catch (e) {
    showToast(e.message || 'Failed to delete product', 'error');
  }
}

// ── Catalogues ──
async function loadCatalogues() {
  try {
    const res = await api.get('/catalogues');
    const catalogues = res.catalogues || res || [];
    renderTableBody('catalogues-tbody', catalogues, [
      { key: 'id' },
      { key: 'title' },
      { key: 'description', render: function (v) { return v ? v.substring(0, 60) + (v.length > 60 ? '...' : '') : '-'; } },
      { key: 'is_published', render: function (v) { return v ? '<span class="badge badge-success">Published</span>' : '<span class="badge badge-secondary">Draft</span>'; } },
      { key: 'id', render: function (v, row) {
        return '<div class="d-flex gap-1">' +
          '<button class="btn btn-sm btn-primary" onclick="editCatalogue(' + v + ')"><i class="fa fa-edit"></i></button>' +
          '<button class="btn btn-sm btn-warning" onclick="toggleCataloguePublish(' + v + ',' + (row.is_published ? 'false' : 'true') + ')"><i class="fa fa-eye' + (row.is_published ? '-slash' : '') + '"></i></button>' +
          '<button class="btn btn-sm btn-danger" onclick="deleteCatalogue(' + v + ')"><i class="fa fa-trash"></i></button>' +
          '</div>';
      }}
    ]);
    window._cataloguesData = catalogues;
  } catch (e) {
    showToast('Failed to load catalogues', 'error');
  }
}

async function createCatalogue() {
  const form = document.getElementById('catalogue-form');
  if (!form) return;
  const fd = getFormFileData(form);

  try {
    await api.upload('/catalogues', fd);
    showToast('Catalogue created', 'success');
    closeModal('catalogue-modal');
    form.reset();
    loadCatalogues();
  } catch (e) {
    showToast(e.message || 'Failed to create catalogue', 'error');
  }
}

async function editCatalogue(id) {
  const cat = (window._cataloguesData || []).find(function (c) { return c.id === id; });
  if (!cat) return;
  const form = document.getElementById('catalogue-form');
  setFormValues(form, cat);
  openModal('catalogue-modal');
}

async function updateCatalogue() {
  const form = document.getElementById('catalogue-form');
  if (!form) return;
  const fd = getFormFileData(form);
  const id = fd.get('id');
  if (!id) return;
  fd.delete('id');

  try {
    await api.upload('/catalogues/' + id + '?_method=PUT', fd);
    showToast('Catalogue updated', 'success');
    closeModal('catalogue-modal');
    form.reset();
    loadCatalogues();
  } catch (e) {
    showToast(e.message || 'Failed to update catalogue', 'error');
  }
}

async function deleteCatalogue(id) {
  if (!confirmDelete('Delete this catalogue?')) return;
  try {
    await api.delete('/catalogues/' + id);
    showToast('Catalogue deleted', 'success');
    loadCatalogues();
  } catch (e) {
    showToast(e.message || 'Failed to delete catalogue', 'error');
  }
}

async function toggleCataloguePublish(id, current) {
  try {
    await api.put('/catalogues/' + id, { is_published: current === 'true' || current === true ? false : true });
    showToast('Publish status updated', 'success');
    loadCatalogues();
  } catch (e) {
    showToast(e.message || 'Failed to update status', 'error');
  }
}

// ── Orders ──
async function loadOrders(filters) {
  let qs = '';
  if (filters) {
    const params = new URLSearchParams();
    if (filters.status) params.set('status', filters.status);
    if (filters.page) params.set('page', filters.page);
    qs = '?' + params.toString();
  }
  try {
    const res = await api.get('/orders' + qs);
    const orders = res.orders || res.data || res || [];
    renderTableBody('orders-tbody', orders, [
      { key: 'id', render: function (v) { return '#' + v; } },
      { key: 'customer', render: function (v) { return v && v.name ? v.name : (v && v.email ? v.email : '-'); } },
      { key: 'total', render: function (v) { return '&#8377;' + (v || 0); } },
      { key: 'status', render: function (v) {
        var cls = 'badge-secondary';
        if (v === 'delivered') cls = 'badge-success';
        else if (v === 'cancelled') cls = 'badge-danger';
        else if (v === 'shipped') cls = 'badge-info';
        else if (v === 'processing') cls = 'badge-warning';
        return '<span class="badge ' + cls + '">' + (v || '-') + '</span>';
      }},
      { key: 'created_at', render: function (v) { return v ? new Date(v).toLocaleDateString() : '-'; } },
      { key: 'id', render: function (v) {
        return '<a href="/admin/orders/' + v + '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> View</a>';
      }}
    ]);
    window._ordersData = orders;
  } catch (e) {
    showToast('Failed to load orders', 'error');
  }
}

// ── Customers ──
async function loadCustomers() {
  try {
    const res = await api.get('/customers');
    const customers = res.customers || res.data || res || [];
    renderTableBody('customers-tbody', customers, [
      { key: 'id' },
      { key: 'name' },
      { key: 'email' },
      { key: 'phone' },
      { key: 'created_at', render: function (v) { return v ? new Date(v).toLocaleDateString() : '-'; } }
    ]);
  } catch (e) {
    showToast('Failed to load customers', 'error');
  }
}

// ── Announcements ──
async function loadAnnouncements() {
  try {
    const res = await api.get('/announcements');
    const list = res.announcements || res || [];
    renderTableBody('announcements-tbody', list, [
      { key: 'id' },
      { key: 'text', render: function (v) { return v ? v.substring(0, 80) + (v.length > 80 ? '...' : '') : '-'; } },
      { key: 'is_active', render: function (v) { return v ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>'; } },
      { key: 'id', render: function (v, row) {
        return '<div class="d-flex gap-1">' +
          '<button class="btn btn-sm btn-primary" onclick="editAnnouncement(' + v + ')"><i class="fa fa-edit"></i></button>' +
          '<button class="btn btn-sm btn-warning" onclick="toggleAnnouncementStatus(' + v + ',' + (row.is_active ? 'false' : 'true') + ')"><i class="fa fa-eye' + (row.is_active ? '-slash' : '') + '"></i></button>' +
          '<button class="btn btn-sm btn-danger" onclick="deleteAnnouncement(' + v + ')"><i class="fa fa-trash"></i></button>' +
          '</div>';
      }}
    ]);
    window._announcementsData = list;
  } catch (e) {
    showToast('Failed to load announcements', 'error');
  }
}

async function createAnnouncement() {
  const form = document.getElementById('announcement-form');
  if (!form) return;
  const data = getFormData(form);
  data.is_active = form.querySelector('[name="is_active"]') ? form.querySelector('[name="is_active"]').checked : true;

  try {
    await api.post('/announcements', data);
    showToast('Announcement created', 'success');
    closeModal('announcement-modal');
    form.reset();
    loadAnnouncements();
  } catch (e) {
    showToast(e.message || 'Failed to create announcement', 'error');
  }
}

async function editAnnouncement(id) {
  const ann = (window._announcementsData || []).find(function (a) { return a.id === id; });
  if (!ann) return;
  const form = document.getElementById('announcement-form');
  setFormValues(form, ann);
  openModal('announcement-modal');
}

async function updateAnnouncement() {
  const form = document.getElementById('announcement-form');
  if (!form) return;
  const data = getFormData(form);
  const id = data.id;
  if (!id) return;
  data.is_active = form.querySelector('[name="is_active"]') ? form.querySelector('[name="is_active"]').checked : false;

  try {
    await api.put('/announcements/' + id, data);
    showToast('Announcement updated', 'success');
    closeModal('announcement-modal');
    form.reset();
    loadAnnouncements();
  } catch (e) {
    showToast(e.message || 'Failed to update announcement', 'error');
  }
}

async function deleteAnnouncement(id) {
  if (!confirmDelete('Delete this announcement?')) return;
  try {
    await api.delete('/announcements/' + id);
    showToast('Announcement deleted', 'success');
    loadAnnouncements();
  } catch (e) {
    showToast(e.message || 'Failed to delete announcement', 'error');
  }
}

async function toggleAnnouncementStatus(id, current) {
  try {
    await api.put('/announcements/' + id, { is_active: current === 'true' || current === true ? false : true });
    showToast('Status updated', 'success');
    loadAnnouncements();
  } catch (e) {
    showToast(e.message || 'Failed to update status', 'error');
  }
}

// ── Content Pages ──
async function loadContentPages() {
  try {
    const res = await api.get('/pages');
    const pages = res.pages || res.data || res || [];
    renderTableBody('pages-tbody', pages, [
      { key: 'id' },
      { key: 'title' },
      { key: 'slug' },
      { key: 'id', render: function (v) {
        return '<div class="d-flex gap-1">' +
          '<button class="btn btn-sm btn-primary" onclick="editContentPage(' + v + ')"><i class="fa fa-edit"></i></button>' +
          '<button class="btn btn-sm btn-danger" onclick="deleteContentPage(' + v + ')"><i class="fa fa-trash"></i></button>' +
          '</div>';
      }}
    ]);
    window._pagesData = pages;
  } catch (e) {
    showToast('Failed to load pages', 'error');
  }
}

async function createContentPage() {
  const form = document.getElementById('page-form');
  if (!form) return;
  const data = getFormData(form);

  try {
    await api.post('/pages', data);
    showToast('Page created', 'success');
    closeModal('page-modal');
    form.reset();
    loadContentPages();
  } catch (e) {
    showToast(e.message || 'Failed to create page', 'error');
  }
}

async function editContentPage(id) {
  const page = (window._pagesData || []).find(function (p) { return p.id === id; });
  if (!page) return;
  const form = document.getElementById('page-form');
  setFormValues(form, page);
  openModal('page-modal');
}

async function updateContentPage() {
  const form = document.getElementById('page-form');
  if (!form) return;
  const data = getFormData(form);
  const id = data.id;
  if (!id) return;

  try {
    await api.put('/pages/' + id, data);
    showToast('Page updated', 'success');
    closeModal('page-modal');
    form.reset();
    loadContentPages();
  } catch (e) {
    showToast(e.message || 'Failed to update page', 'error');
  }
}

async function deleteContentPage(id) {
  if (!confirmDelete('Delete this page?')) return;
  try {
    await api.delete('/pages/' + id);
    showToast('Page deleted', 'success');
    loadContentPages();
  } catch (e) {
    showToast(e.message || 'Failed to delete page', 'error');
  }
}

// ── Settings ──
async function loadSettings() {
  try {
    const res = await api.get('/settings');
    const settings = res.settings || res || {};
    const form = document.getElementById('settings-form');
    if (form) {
      Object.keys(settings).forEach(function (key) {
        const input = form.querySelector('[name="' + key + '"]');
        if (!input) return;
        if (input.type === 'checkbox') {
          input.checked = !!settings[key];
        } else {
          input.value = settings[key] != null ? settings[key] : '';
        }
      });
    }
  } catch (e) {
    showToast('Failed to load settings', 'error');
  }
}

async function saveSettings() {
  const form = document.getElementById('settings-form');
  if (!form) return;
  const data = getFormData(form);

  try {
    await api.put('/settings', data);
    showToast('Settings saved', 'success');
  } catch (e) {
    showToast(e.message || 'Failed to save settings', 'error');
  }
}

// ── Dashboard ──
async function loadDashboard() {
  try {
    const res = await api.get('/admin/dashboard');
    const stats = res.stats || res || {};
    document.querySelectorAll('[data-stat]').forEach(function (el) {
      const key = el.dataset.stat;
      if (stats[key] != null) {
        el.textContent = key === 'revenue' ? '&#8377;' + Number(stats[key]).toLocaleString() : stats[key];
      }
    });
  } catch (e) {
    showToast('Failed to load dashboard stats', 'error');
  }
}

// ── Init ──
document.addEventListener('DOMContentLoaded', function () {
  initModalCloseButtons();

  document.querySelectorAll('[data-open-modal]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      openModal(el.dataset.openModal);
    });
  });

  document.querySelectorAll('[data-close-modal]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      closeModal(el.dataset.closeModal || el.closest('.modal').id);
    });
  });

  document.querySelectorAll('[data-create]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      var fn = el.dataset.create;
      if (typeof window[fn] === 'function') window[fn]();
    });
  });

  document.querySelectorAll('[data-save]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      var fn = el.dataset.save;
      if (typeof window[fn] === 'function') window[fn]();
    });
  });
});

window.openModal = openModal;
window.closeModal = closeModal;
window.loadCategories = loadCategories;
window.createCategory = createCategory;
window.editCategory = editCategory;
window.updateCategory = updateCategory;
window.deleteCategory = deleteCategory;
window.toggleCategoryVisibility = toggleCategoryVisibility;
window.loadProducts = loadProducts;
window.createProduct = createProduct;
window.editProduct = editProduct;
window.updateProduct = updateProduct;
window.deleteProduct = deleteProduct;
window.loadCatalogues = loadCatalogues;
window.createCatalogue = createCatalogue;
window.editCatalogue = editCatalogue;
window.updateCatalogue = updateCatalogue;
window.deleteCatalogue = deleteCatalogue;
window.toggleCataloguePublish = toggleCataloguePublish;
window.loadOrders = loadOrders;
window.loadCustomers = loadCustomers;
window.loadAnnouncements = loadAnnouncements;
window.createAnnouncement = createAnnouncement;
window.editAnnouncement = editAnnouncement;
window.updateAnnouncement = updateAnnouncement;
window.deleteAnnouncement = deleteAnnouncement;
window.toggleAnnouncementStatus = toggleAnnouncementStatus;
window.loadContentPages = loadContentPages;
window.createContentPage = createContentPage;
window.editContentPage = editContentPage;
window.updateContentPage = updateContentPage;
window.deleteContentPage = deleteContentPage;
window.loadSettings = loadSettings;
window.saveSettings = saveSettings;
window.loadDashboard = loadDashboard;
