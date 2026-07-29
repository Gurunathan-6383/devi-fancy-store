let _wishlistIds = [];

async function toggleWishlist(productId) {
  const token = localStorage.getItem('customerToken');
  if (!token) { window.location.href = '/login'; return null; }
  try {
    const res = await api.post('/wishlist/toggle', { product_id: productId });
    const data = res.data || res;
    if (data && data.action) {
      if (data.action === 'added') _wishlistIds.push(Number(productId));
      else _wishlistIds = _wishlistIds.filter(id => id !== Number(productId));
      refreshWishlistBadge();
      return data.action;
    }
  } catch (e) {
    if (e.status === 401) { window.location.href = '/login'; }
    return null;
  }
  return null;
}

async function getWishlistIds() {
  const token = localStorage.getItem('customerToken');
  if (!token) return [];
  try {
    const res = await api.get('/wishlist/ids');
    const data = res.data || res;
    if (data && Array.isArray(data.ids)) {
      _wishlistIds = data.ids.map(Number);
    } else if (Array.isArray(data)) {
      _wishlistIds = data.map(Number);
    }
    return _wishlistIds;
  } catch (e) {
    return [];
  }
}

function isWishlisted(productId) {
  return _wishlistIds.includes(Number(productId));
}

function refreshWishlistBadge() {
  document.querySelectorAll('.wishlist-count').forEach(el => {
    el.textContent = _wishlistIds.length;
    el.style.display = _wishlistIds.length > 0 ? '' : 'none';
  });
}

document.addEventListener('DOMContentLoaded', async function() {
  const token = localStorage.getItem('customerToken');
  if (token) {
    await getWishlistIds();
    refreshWishlistBadge();
  }
});

window.toggleWishlist = toggleWishlist;
window.isWishlisted = isWishlisted;
window.getWishlistIds = getWishlistIds;
