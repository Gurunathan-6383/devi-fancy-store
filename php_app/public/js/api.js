const API_BASE = window.location.origin + '/api';

function getAdminToken() {
  return localStorage.getItem('adminToken');
}

function getCustomerToken() {
  return localStorage.getItem('customerToken');
}

function isAdminPage() {
  return window.location.pathname.startsWith('/admin');
}

function isCustomerPage() {
  const p = window.location.pathname;
  return p.startsWith('/account') || p.startsWith('/wishlist') || p.startsWith('/orders') || p.startsWith('/reviews');
}

function clearTokens() {
  localStorage.removeItem('adminToken');
  localStorage.removeItem('customerToken');
}

function buildHeaders(isUpload) {
  const headers = {};
  if (!isUpload) {
    headers['Content-Type'] = 'application/json';
  }

  if (isAdminPage() && getAdminToken()) {
    headers['Authorization'] = 'Bearer ' + getAdminToken();
  } else if (isCustomerPage() && getCustomerToken()) {
    headers['Authorization'] = 'Bearer ' + getCustomerToken();
  }

  return headers;
}

function handleAuthError(status) {
  if (status === 401) {
    clearTokens();
    if (isAdminPage()) {
      window.location.href = '/admin/login';
    }
  }
}

async function request(method, url, body, isUpload) {
  const fullUrl = url.startsWith('http') ? url : API_BASE + url;
  const opts = {
    method: method,
    headers: buildHeaders(isUpload),
    credentials: 'same-origin'
  };

  if (body !== undefined && body !== null) {
    if (isUpload) {
      opts.body = body;
    } else {
      opts.body = JSON.stringify(body);
    }
  }

  const res = await fetch(fullUrl, opts);

  if (res.status === 204 || res.status === 205) {
    return null;
  }

  const text = await res.text();
  let data;
  try {
    data = text ? JSON.parse(text) : null;
  } catch (e) {
    data = text;
  }

  if (!res.ok) {
    handleAuthError(res.status);
    const err = new Error(data && data.error ? data.error : (data && data.message ? data.message : 'Request failed'));
    err.status = res.status;
    err.data = data;
    throw err;
  }

  return data;
}

function apiGet(url) {
  return request('GET', url);
}

function apiPost(url, data) {
  return request('POST', url, data);
}

function apiPut(url, data) {
  return request('PUT', url, data);
}

function apiDelete(url) {
  return request('DELETE', url);
}

function apiPatch(url, data) {
  return request('PATCH', url, data);
}

function apiUpload(url, formData) {
  return request('POST', url, formData, true);
}

window.api = {
  get: apiGet,
  post: apiPost,
  put: apiPut,
  delete: apiDelete,
  patch: apiPatch,
  upload: apiUpload
};
