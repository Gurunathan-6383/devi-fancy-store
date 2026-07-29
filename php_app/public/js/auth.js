function clearAuthTokens() {
  localStorage.removeItem('adminToken');
  localStorage.removeItem('customerToken');
}

function getRedirectUrl(isAdmin) {
  if (isAdmin) return '/admin';
  var params = new URLSearchParams(window.location.search);
  return params.get('redirect') || '/';
}

async function handleLogin(form, isAdmin) {
  var emailEl = form.querySelector('[name="email"]') || form.querySelector('#email');
  var passEl = form.querySelector('[name="password"]') || form.querySelector('#password');
  var email = emailEl ? emailEl.value.trim() : '';
  var password = passEl ? passEl.value : '';

  if (!email || !password) {
    showToast('Please fill in all fields', 'error');
    return false;
  }

  var btn = form.querySelector('button[type="submit"]');
  if (btn) { btn.disabled = true; btn.dataset.origText = btn.textContent; btn.textContent = 'Logging in...'; }

  try {
    var url = isAdmin ? '/auth/login' : '/customer/login';
    var res = await api.post(url, { email: email, password: password });
    var data = res.data || res;
    var token = data.token;
    var user = data.user || data.customer;

    if (token) {
      if (isAdmin) {
        localStorage.setItem('adminToken', token);
      } else {
        localStorage.setItem('customerToken', token);
      }
      showToast('Login successful!', 'success');
      setTimeout(function() { window.location.href = getRedirectUrl(isAdmin); }, 500);
      return true;
    } else {
      showToast(res.message || 'Login failed', 'error');
      return false;
    }
  } catch (e) {
    showToast(e.message || 'Login failed', 'error');
    return false;
  } finally {
    if (btn) { btn.disabled = false; btn.textContent = btn.dataset.origText || 'Login'; }
  }
}

async function handleSignup(form) {
  var nameEl = form.querySelector('[name="name"]');
  var emailEl = form.querySelector('[name="email"]');
  var phoneEl = form.querySelector('[name="phone"]');
  var passEl = form.querySelector('[name="password"]');
  var confirmEl = form.querySelector('[name="confirm_password"]');

  var nameVal = nameEl ? nameEl.value.trim() : '';
  var emailVal = emailEl ? emailEl.value.trim() : '';
  var phoneVal = phoneEl ? phoneEl.value.trim() : '';
  var passVal = passEl ? passEl.value : '';
  var confirmVal = confirmEl ? confirmEl.value : '';

  if (!nameVal || !emailVal || !passVal) {
    showToast('Please fill in all required fields', 'error');
    return false;
  }
  if (passVal.length < 6) {
    showToast('Password must be at least 6 characters', 'error');
    return false;
  }
  if (confirmVal && passVal !== confirmVal) {
    showToast('Passwords do not match', 'error');
    return false;
  }

  var btn = form.querySelector('button[type="submit"]');
  if (btn) { btn.disabled = true; btn.dataset.origText = btn.textContent; btn.textContent = 'Signing up...'; }

  try {
    var body = { name: nameVal, email: emailVal, password: passVal };
    if (phoneVal) body.phone = phoneVal;

    var res = await api.post('/customer/signup', body);
    var data = res.data || res;
    var token = data.token;

    if (token) {
      localStorage.setItem('customerToken', token);
      showToast('Account created successfully!', 'success');
      setTimeout(function() { window.location.href = getRedirectUrl(false); }, 500);
      return true;
    } else {
      showToast(res.message || 'Signup failed', 'error');
      return false;
    }
  } catch (e) {
    showToast(e.message || 'Signup failed', 'error');
    return false;
  } finally {
    if (btn) { btn.disabled = false; btn.textContent = btn.dataset.origText || 'Sign Up'; }
  }
}

function handleLogout(isAdmin) {
  clearAuthTokens();
  showToast('Logged out successfully', 'success');
  setTimeout(function() { window.location.href = isAdmin ? '/admin/login' : '/login'; }, 300);
}

function togglePasswordVisibility(btn) {
  var wrapper = btn.closest('.input-group') || btn.parentElement;
  var input = wrapper.querySelector('input[type="password"], input[type="text"]');
  if (!input) return;
  if (input.type === 'password') {
    input.type = 'text';
    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>';
  } else {
    input.type = 'password';
    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
  }
}

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('[data-login-form]').forEach(function(form) {
    form.addEventListener('submit', async function(e) {
      e.preventDefault();
      var isAdmin = form.dataset.loginForm === 'admin';
      await handleLogin(form, isAdmin);
    });
  });
  document.querySelectorAll('[data-signup-form]').forEach(function(form) {
    form.addEventListener('submit', async function(e) {
      e.preventDefault();
      await handleSignup(form);
    });
  });
  document.querySelectorAll('[data-logout]').forEach(function(el) {
    el.addEventListener('click', function(e) {
      e.preventDefault();
      handleLogout(el.dataset.logout === 'admin');
    });
  });
  document.querySelectorAll('[data-toggle-password]').forEach(function(btn) {
    btn.addEventListener('click', function() { togglePasswordVisibility(btn); });
  });
});

window.handleLogin = handleLogin;
window.handleSignup = handleSignup;
window.handleLogout = handleLogout;
window.togglePasswordVisibility = togglePasswordVisibility;
