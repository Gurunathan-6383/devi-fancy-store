let _customerData = null;
let _adminData = null;

function getCookie(name) {
  const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
  return v ? v.pop() : null;
}

function setCookie(name, value, days) {
  const d = new Date();
  d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
  document.cookie = name + '=' + encodeURIComponent(value) + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
}

function initTheme() {
  const theme = getCookie('devi_theme');
  if (theme === 'dark') {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
}

function toggleTheme() {
  const isDark = document.documentElement.classList.toggle('dark');
  setCookie('devi_theme', isDark ? 'dark' : 'light', 365);
}

function showToast(message, type) {
  type = type || 'success';
  const existing = document.querySelector('.devi-toast');
  if (existing) existing.remove();

  const toast = document.createElement('div');
  toast.className = 'devi-toast devi-toast-' + type;
  toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:10000;padding:12px 24px;border-radius:8px;color:#fff;font-size:14px;max-width:400px;box-shadow:0 4px 12px rgba(0,0,0,0.15);transition:opacity 0.3s,transform 0.3s;opacity:0;transform:translateY(-10px);';

  if (type === 'success') {
    toast.style.background = '#10b981';
  } else if (type === 'error') {
    toast.style.background = '#ef4444';
  } else if (type === 'warning') {
    toast.style.background = '#f59e0b';
  } else if (type === 'info') {
    toast.style.background = '#3b82f6';
  } else {
    toast.style.background = '#6b7280';
  }

  toast.textContent = message;
  document.body.appendChild(toast);

  requestAnimationFrame(function () {
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
  });

  setTimeout(function () {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(-10px)';
    setTimeout(function () {
      toast.remove();
    }, 300);
  }, 3000);
}

async function verifyCustomer() {
  const token = localStorage.getItem('customerToken');
  if (!token) {
    _customerData = null;
    updateCustomerUI();
    return;
  }
  try {
    const res = await api.get('/customer/verify');
    if (res && res.customer) {
      _customerData = res.customer;
    } else {
      _customerData = null;
      localStorage.removeItem('customerToken');
    }
  } catch (e) {
    _customerData = null;
    if (e.status === 401) {
      localStorage.removeItem('customerToken');
    }
  }
  updateCustomerUI();
}

async function verifyAdmin() {
  const token = localStorage.getItem('adminToken');
  if (!token) {
    _adminData = null;
    updateAdminUI();
    return;
  }
  try {
    const res = await api.get('/auth/verify');
    if (res && (res.user || res.admin)) {
      _adminData = res.user || res.admin;
    } else {
      _adminData = null;
      localStorage.removeItem('adminToken');
    }
  } catch (e) {
    _adminData = null;
    if (e.status === 401) {
      localStorage.removeItem('adminToken');
    }
  }
  updateAdminUI();
}

function updateCustomerUI() {
  document.querySelectorAll('.customer-name').forEach(function (el) {
    el.textContent = _customerData ? (_customerData.name || _customerData.email) : '';
  });
  document.querySelectorAll('.customer-greeting').forEach(function (el) {
    el.style.display = _customerData ? '' : 'none';
  });
  document.querySelectorAll('.guest-only').forEach(function (el) {
    el.style.display = _customerData ? 'none' : '';
  });
  document.querySelectorAll('.customer-only').forEach(function (el) {
    el.style.display = _customerData ? '' : 'none';
  });
}

function updateAdminUI() {
  document.querySelectorAll('.admin-name').forEach(function (el) {
    el.textContent = _adminData ? (_adminData.name || _adminData.email) : '';
  });
}

function getCustomer() {
  return _customerData;
}

function getAdmin() {
  return _adminData;
}

function isLoggedIn() {
  return !!_customerData;
}

async function loadAnnouncement() {
  try {
    const res = await api.get('/announcements/active');
    if (res && res.announcement) {
      renderAnnouncement(res.announcement);
    } else if (res && res.announcements && res.announcements.length > 0) {
      renderAnnouncement(res.announcements[0]);
    }
  } catch (e) {
    // silently ignore
  }
}

function renderAnnouncement(ann) {
  const bar = document.querySelector('.announcement-bar');
  if (!bar) return;
  const text = ann.text || ann.message || '';
  if (!text) {
    bar.style.display = 'none';
    return;
  }
  bar.style.display = '';
  bar.innerHTML =
    '<div class="announcement-marquee">' +
    '<span class="announcement-text">' + escapeHtml(text) + '</span>' +
    '<span class="announcement-text">' + escapeHtml(text) + '</span>' +
    '</div>';
}

function escapeHtml(str) {
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

document.addEventListener('DOMContentLoaded', function () {
  initTheme();
  verifyCustomer();
  verifyAdmin();
  loadAnnouncement();

  document.querySelectorAll('[data-toggle-theme]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      toggleTheme();
    });
  });
});

window.toggleTheme = toggleTheme;
window.showToast = showToast;
window.getCustomer = getCustomer;
window.getAdmin = getAdmin;
window.isLoggedIn = isLoggedIn;
