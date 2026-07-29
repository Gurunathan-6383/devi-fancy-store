function clearAuthTokens() {
  localStorage.removeItem('adminToken');
  localStorage.removeItem('customerToken');
}

function getRedirectUrl(isAdmin) {
  if (isAdmin) return '/admin/dashboard';
  const params = new URLSearchParams(window.location.search);
  return params.get('redirect') || '/';
}

async function handleLogin(form, isAdmin) {
  const email = (form.querySelector('[name="email"]') || form.querySelector('#email')).value.trim();
  const password = (form.querySelector('[name="password"]') || form.querySelector('#password')).value;

  if (!email || !password) {
    showToast('Please fill in all fields', 'error');
    return false;
  }

  const btn = form.querySelector('button[type="submit"]');
  if (btn) {
    btn.disabled = true;
    btn.dataset.originalText = btn.textContent;
    btn.textContent = 'Logging in...';
  }

  try {
    const url = isAdmin ? '/auth/login' : '/customer/login';
    const res = await api.post(url, { email: email, password: password });

    if (res && res.token) {
      if (isAdmin) {
        localStorage.setItem('adminToken', res.token);
      } else {
        localStorage.setItem('customerToken', res.token);
      }
      showToast('Login successful!', 'success');
      setTimeout(function () {
        window.location.href = getRedirectUrl(isAdmin);
      }, 500);
      return true;
    } else {
      showToast(res && res.error ? res.error : 'Login failed', 'error');
      return false;
    }
  } catch (e) {
    showToast(e.message || 'Login failed', 'error');
    return false;
  } finally {
    if (btn) {
      btn.disabled = false;
      btn.textContent = btn.dataset.originalText || 'Login';
    }
  }
}

async function handleSignup(form) {
  const name = (form.querySelector('[name="name"]') || form.querySelector('#name'));
  const email = (form.querySelector('[name="email"]') || form.querySelector('#email'));
  const phone = (form.querySelector('[name="phone"]') || form.querySelector('#phone'));
  const password = (form.querySelector('[name="password"]') || form.querySelector('#password'));
  const confirmPassword = (form.querySelector('[name="confirm_password"]') || form.querySelector('#confirm_password'));

  const nameVal = name ? name.value.trim() : '';
  const emailVal = email ? email.value.trim() : '';
  const phoneVal = phone ? phone.value.trim() : '';
  const passVal = password ? password.value : '';
  const confirmVal = confirmPassword ? confirmPassword.value : '';

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

  const btn = form.querySelector('button[type="submit"]');
  if (btn) {
    btn.disabled = true;
    btn.dataset.originalText = btn.textContent;
    btn.textContent = 'Signing up...';
  }

  try {
    const body = { name: nameVal, email: emailVal, password: passVal };
    if (phoneVal) body.phone = phoneVal;

    const res = await api.post('/customer/signup', body);

    if (res && res.token) {
      localStorage.setItem('customerToken', res.token);
      showToast('Account created successfully!', 'success');
      setTimeout(function () {
        window.location.href = getRedirectUrl(false);
      }, 500);
      return true;
    } else {
      showToast(res && res.error ? res.error : 'Signup failed', 'error');
      return false;
    }
  } catch (e) {
    showToast(e.message || 'Signup failed', 'error');
    return false;
  } finally {
    if (btn) {
      btn.disabled = false;
      btn.textContent = btn.dataset.originalText || 'Sign Up';
    }
  }
}

function handleLogout(isAdmin) {
  clearAuthTokens();
  showToast('Logged out successfully', 'success');
  setTimeout(function () {
    window.location.href = isAdmin ? '/admin/login' : '/login';
  }, 300);
}

function togglePasswordVisibility(btn) {
  const wrapper = btn.closest('.input-group') || btn.parentElement;
  const input = wrapper.querySelector('input[type="password"], input[type="text"]');
  if (!input) return;

  if (input.type === 'password') {
    input.type = 'text';
    btn.innerHTML = '<i class="fa fa-eye-slash"></i>';
  } else {
    input.type = 'password';
    btn.innerHTML = '<i class="fa fa-eye"></i>';
  }
}

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-login-form]').forEach(function (form) {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      const isAdmin = form.dataset.loginForm === 'admin';
      await handleLogin(form, isAdmin);
    });
  });

  document.querySelectorAll('[data-signup-form]').forEach(function (form) {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      await handleSignup(form);
    });
  });

  document.querySelectorAll('[data-logout]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      e.preventDefault();
      const isAdmin = el.dataset.logout === 'admin';
      handleLogout(isAdmin);
    });
  });

  document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      togglePasswordVisibility(btn);
    });
  });
});

window.handleLogin = handleLogin;
window.handleSignup = handleSignup;
window.handleLogout = handleLogout;
window.togglePasswordVisibility = togglePasswordVisibility;
