function getCart() {
  const raw = getCookie('devi_cart');
  if (!raw) return [];
  try {
    return JSON.parse(raw);
  } catch (e) {
    return [];
  }
}

function saveCart(cart) {
  const d = new Date();
  d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));
  document.cookie = 'devi_cart=' + encodeURIComponent(JSON.stringify(cart)) + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
  cartCountBadge();
}

function addToCart(item) {
  const cart = getCart();
  const existing = cart.find(c => String(c.id) === String(item.id));
  if (existing) {
    existing.quantity = (existing.quantity || 1) + (item.quantity || 1);
  } else {
    cart.push({
      id: item.id,
      name: item.name || '',
      price: item.price || 0,
      offer_price: item.offer_price || item.price || 0,
      image: item.image || '',
      slug: item.slug || '',
      quantity: item.quantity || 1
    });
  }
  saveCart(cart);
  return cart;
}

function removeFromCart(productId) {
  const cart = getCart().filter(c => String(c.id) !== String(productId));
  saveCart(cart);
  return cart;
}

function updateCartQuantity(productId, delta) {
  let cart = getCart();
  const item = cart.find(c => String(c.id) === String(productId));
  if (!item) return cart;
  item.quantity = (item.quantity || 0) + delta;
  if (item.quantity <= 0) {
    cart = cart.filter(c => String(c.id) !== String(productId));
  }
  saveCart(cart);
  return cart;
}

function clearCart() {
  const d = new Date();
  d.setTime(d.getTime() - 86400000);
  document.cookie = 'devi_cart=;expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
  cartCountBadge();
}

function getCartTotal() {
  return getCart().reduce((sum, item) => {
    const price = item.offer_price && item.offer_price < item.price ? item.offer_price : item.price;
    return sum + price * (item.quantity || 1);
  }, 0);
}

function getCartItemCount() {
  return getCart().reduce((sum, item) => sum + (item.quantity || 1), 0);
}

function cartCountBadge() {
  const count = getCartItemCount();
  document.querySelectorAll('.cart-count').forEach(el => {
    el.textContent = count;
    el.style.display = count > 0 ? '' : 'none';
  });
}

function getCookie(name) {
  const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
  return v ? v.pop() : null;
}

document.addEventListener('DOMContentLoaded', cartCountBadge);
