let _wishlistIds = [];

async function toggleWishlist(productId) {
  const token = localStorage.getItem('customerToken');
  if (!token) {
    window.location.href = '/login';
    return null;
  }
  const res = await api.post('/wishlist/toggle', { product_id: productId });
  if (res && res.action) {
    if (res.action === 'added') {
      _wishlistIds.push(Number(productId));
    } else {
      _wishlistIds = _wishlistIds.filter(id => id !== Number(productId));
    }
    refreshWishlistBadge();
    return res.action;
  }
  return null;
}

async function getWishlistIds() {
  const token = localStorage.getItem('customerToken');
  if (!token) return [];
  try {
    const res = await api.get('/wishlist/ids');
    if (res && Array.isArray(res.ids)) {
      _wishlistIds = res.ids.map(Number);
      return _wishlistIds;
    }
    if (res && Array.isArray(res)) {
      _wishlistIds = res.map(Number);
      return _wishlistIds;
    }
    return [];
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

document.addEventListener('DOMContentLoaded', async function () {
  const token = localStorage.getItem('customerToken');
  if (token) {
    await getWishlistIds();
    refreshWishlistBadge();
  }
});
