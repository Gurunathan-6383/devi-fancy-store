import axios from 'axios';

const API_URL = '/api';

const api = axios.create({
  baseURL: API_URL,
  headers: { 'Content-Type': 'application/json' }
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('adminToken');
  const customerToken = localStorage.getItem('customerToken');
  const needsCustomer = config.url?.startsWith('/customer') || config.url?.startsWith('/wishlist') || config.url?.startsWith('/reviews');
  if (needsCustomer && customerToken) {
    config.headers.Authorization = `Bearer ${customerToken}`;
  } else if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('adminToken');
      if (window.location.pathname.startsWith('/admin') && !window.location.pathname.includes('/admin/login')) {
        window.location.href = '/admin/login';
      }
    }
    return Promise.reject(error);
  }
);

export const authAPI = {
  login: (data) => api.post('/auth/login', data),
  verify: () => api.get('/auth/verify'),
};

export const customerAPI = {
  signup: (data) => api.post('/customer/signup', data),
  login: (data) => api.post('/customer/login', data),
  verify: () => api.get('/customer/verify'),
};

export const categoryAPI = {
  getAll: () => api.get('/categories'),
  getActive: () => api.get('/categories/active'),
  getById: (id) => api.get(`/categories/${id}`),
  create: (data) => api.post('/categories', data, { headers: { 'Content-Type': 'multipart/form-data' } }),
  update: (id, data) => api.put(`/categories/${id}`, data, { headers: { 'Content-Type': 'multipart/form-data' } }),
  delete: (id) => api.delete(`/categories/${id}`),
  toggleVisibility: (id) => api.patch(`/categories/${id}/toggle-visibility`),
};

export const productAPI = {
  getAll: () => api.get('/products'),
  getActive: (params) => api.get('/products/active', { params }),
  getById: (id) => api.get(`/products/${id}`),
  getBySlug: (slug) => api.get(`/products/slug/${slug}`),
  getFeatured: (limit) => api.get('/products/featured', { params: { limit } }),
  search: (params) => api.get('/products/search', { params }),
  create: (data) => api.post('/products', data, { headers: { 'Content-Type': 'multipart/form-data' } }),
  update: (id, data) => api.put(`/products/${id}`, data, { headers: { 'Content-Type': 'multipart/form-data' } }),
  delete: (id) => api.delete(`/products/${id}`),
};

export const catalogueAPI = {
  getAll: () => api.get('/catalogues'),
  getPublished: () => api.get('/catalogues/published'),
  getById: (id) => api.get(`/catalogues/${id}`),
  getBySlug: (slug) => api.get(`/catalogues/slug/${slug}`),
  create: (data) => api.post('/catalogues', data, { headers: { 'Content-Type': 'multipart/form-data' } }),
  update: (id, data) => api.put(`/catalogues/${id}`, data, { headers: { 'Content-Type': 'multipart/form-data' } }),
  delete: (id) => api.delete(`/catalogues/${id}`),
  togglePublish: (id) => api.patch(`/catalogues/${id}/toggle-publish`),
  getProducts: (id) => api.get(`/catalogues/${id}/products`),
  addProduct: (id, productId) => api.post(`/catalogues/${id}/products`, { product_id: productId }),
  removeProduct: (id, productId) => api.delete(`/catalogues/${id}/products/${productId}`),
};

export const orderAPI = {
  placeOrder: (data) => api.post('/orders', data),
  getAll: () => api.get('/orders'),
};

export const settingsAPI = {
  getAll: () => api.get('/settings'),
  getPublic: () => api.get('/settings/public'),
  update: (data) => api.put('/settings', data, { headers: { 'Content-Type': 'multipart/form-data' } }),
};

export const wishlistAPI = {
  getAll: () => api.get('/wishlist'),
  getIds: () => api.get('/wishlist/ids'),
  toggle: (productId) => api.post('/wishlist/toggle', { product_id: productId }),
};

export const reviewAPI = {
  getByProduct: (productId) => api.get(`/reviews/product/${productId}`),
  create: (data) => api.post('/reviews', data),
};

export const announcementAPI = {
  getActive: () => api.get('/announcements/active'),
  getAll: () => api.get('/announcements'),
  getById: (id) => api.get(`/announcements/${id}`),
  create: (data) => api.post('/announcements', data),
  update: (id, data) => api.put(`/announcements/${id}`, data),
  delete: (id) => api.delete(`/announcements/${id}`),
  toggleStatus: (id) => api.patch(`/announcements/${id}/toggle-status`),
};

export const contentPageAPI = {
  getAll: () => api.get('/content-pages'),
  getBySlug: (slug) => api.get(`/content-pages/public/${slug}`),
  getById: (id) => api.get(`/content-pages/${id}`),
  create: (data) => api.post('/content-pages', data),
  update: (id, data) => api.put(`/content-pages/${id}`, data),
  delete: (id) => api.delete(`/content-pages/${id}`),
};

export default api;
