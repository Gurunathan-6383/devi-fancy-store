import { useState, useEffect } from 'react';
import { HiPlus, HiPencil, HiTrash, HiStar } from 'react-icons/hi';
import toast from 'react-hot-toast';
import { productAPI, categoryAPI } from '../services/api';

export default function ManageProducts() {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [form, setForm] = useState({
    name: '', category_id: '', description: '', specifications: '',
    price: '', offer_price: '', stock: '0', status: 'active', is_featured: false
  });
  const [imageFiles, setImageFiles] = useState([]);
  const [existingImages, setExistingImages] = useState([]);

  useEffect(() => {
    Promise.all([loadProducts(), loadCategories()]);
  }, []);

  const loadProducts = async () => {
    try {
      const res = await productAPI.getAll();
      setProducts(res.data.data);
    } catch (err) { toast.error('Failed to load products'); }
    finally { setLoading(false); }
  };

  const loadCategories = async () => {
    try {
      const res = await categoryAPI.getAll();
      setCategories(res.data.data);
    } catch (err) {}
  };

  const openCreate = () => {
    setEditItem(null);
    setForm({ name: '', category_id: '', description: '', specifications: '', price: '', offer_price: '', stock: '0', status: 'active', is_featured: false });
    setImageFiles([]);
    setExistingImages([]);
    setShowModal(true);
  };

  const openEdit = (product) => {
    setEditItem(product);
    setForm({
      name: product.name, category_id: product.category_id || '',
      description: product.description || '', specifications: product.specifications || '',
      price: product.price, offer_price: product.offer_price || '',
      stock: product.stock.toString(), status: product.status, is_featured: product.is_featured
    });
    setExistingImages(product.images || []);
    setImageFiles([]);
    setShowModal(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!form.name || !form.price || !form.category_id) {
      toast.error('Name, price, and category are required');
      return;
    }
    const formData = new FormData();
    formData.append('name', form.name);
    formData.append('category_id', form.category_id);
    formData.append('description', form.description || '');
    formData.append('specifications', form.specifications || '');
    formData.append('price', form.price);
    formData.append('offer_price', form.offer_price || '');
    formData.append('stock', form.stock || '0');
    formData.append('status', form.status);
    formData.append('is_featured', form.is_featured);
    formData.append('existing_images', JSON.stringify(existingImages));
    imageFiles.forEach(f => formData.append('images', f));
    try {
      if (editItem) {
        await productAPI.update(editItem.id, formData);
        toast.success('Product updated!');
      } else {
        await productAPI.create(formData);
        toast.success('Product created!');
      }
      setShowModal(false);
      loadProducts();
    } catch (err) {
      toast.error(err.response?.data?.message || 'Failed to save');
    }
  };

  const handleDelete = async (id) => {
    if (!confirm('Delete this product?')) return;
    try { await productAPI.delete(id); toast.success('Product deleted!'); loadProducts(); }
    catch (err) { toast.error('Failed to delete'); }
  };

  const removeExistingImage = (index) => {
    setExistingImages(prev => prev.filter((_, i) => i !== index));
  };

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-3xl font-heading font-bold text-gray-900 dark:text-white">Manage Products</h1>
          <p className="text-gray-500 mt-1">{products.length} product(s) total</p>
        </div>
        <button onClick={openCreate} className="btn-primary flex items-center space-x-2">
          <HiPlus className="w-5 h-5" /><span>Add Product</span>
        </button>
      </div>

      <div className="card overflow-hidden">
        <table className="w-full">
          <thead className="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
            <tr>
              <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Image</th>
              <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
              <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
              <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Price</th>
              <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Stock</th>
              <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
              <th className="text-right px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y">
            {products.map(p => (
              <tr key={p.id} className="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <td className="px-4 py-4">
                  <img src={p.images?.[0] || 'https://via.placeholder.com/48'} alt={p.name} className="w-12 h-12 rounded-lg object-cover" />
                </td>
                <td className="px-4 py-4">
                  <span className="font-medium text-gray-900 dark:text-white">{p.name}</span>
                  {p.is_featured && <HiStar className="inline ml-1 w-4 h-4 text-accent-500" />}
                </td>
                <td className="px-4 py-4 text-sm text-gray-500">{p.category_name || '-'}</td>
                <td className="px-4 py-4">
                  <span className="font-semibold">₹{p.offer_price || p.price}</span>
                  {p.offer_price && <span className="text-xs text-gray-400 line-through ml-1">₹{p.price}</span>}
                </td>
                <td className="px-4 py-4">{p.stock}</td>
                <td className="px-4 py-4">
                  <span className={`px-3 py-1 rounded-full text-xs font-semibold ${p.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'}`}>
                    {p.status}
                  </span>
                </td>
                <td className="px-4 py-4 text-right">
                  <div className="flex items-center justify-end space-x-2">
                    <button onClick={() => openEdit(p)} className="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"><HiPencil className="w-4 h-4" /></button>
                    <button onClick={() => handleDelete(p.id)} className="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"><HiTrash className="w-4 h-4" /></button>
                  </div>
                </td>
              </tr>
            ))}
            {products.length === 0 && !loading && (
              <tr><td colSpan={7} className="px-6 py-12 text-center text-gray-500">No products yet.</td></tr>
            )}
          </tbody>
        </table>
      </div>

      {showModal && (
        <div className="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
          <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl animate-scale-in">
            <h2 className="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">{editItem ? 'Edit Product' : 'Add Product'}</h2>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="col-span-2">
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product Name</label>
                  <input type="text" value={form.name} onChange={e => setForm({...form, name: e.target.value})} className="input-field" required />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                  <select value={form.category_id} onChange={e => setForm({...form, category_id: e.target.value})} className="input-field" required>
                    <option value="">Select category</option>
                    {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                  <select value={form.status} onChange={e => setForm({...form, status: e.target.value})} className="input-field">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price (₹)</label>
                  <input type="number" step="0.01" value={form.price} onChange={e => setForm({...form, price: e.target.value})} className="input-field" required />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Offer Price (₹)</label>
                  <input type="number" step="0.01" value={form.offer_price} onChange={e => setForm({...form, offer_price: e.target.value})} className="input-field" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock</label>
                  <input type="number" value={form.stock} onChange={e => setForm({...form, stock: e.target.value})} className="input-field" />
                </div>
                <div className="flex items-center space-x-2 pt-6">
                  <input type="checkbox" id="is_featured" checked={form.is_featured} onChange={e => setForm({...form, is_featured: e.target.checked})} className="w-4 h-4 text-primary-600" />
                  <label htmlFor="is_featured" className="text-sm font-medium text-gray-700 dark:text-gray-300">Featured Product</label>
                </div>
                <div className="col-span-2">
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                  <textarea value={form.description} onChange={e => setForm({...form, description: e.target.value})} className="input-field" rows="3" />
                </div>
                <div className="col-span-2">
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Specifications (one per line)</label>
                  <textarea value={form.specifications} onChange={e => setForm({...form, specifications: e.target.value})} className="input-field" rows="3" placeholder="Material: Gold&#10;Weight: 10g&#10;Size: Medium" />
                </div>
                <div className="col-span-2">
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Images</label>
                  <input type="file" multiple accept="image/*" onChange={e => setImageFiles(Array.from(e.target.files))} className="input-field" />
                  {existingImages.length > 0 && (
                    <div className="flex flex-wrap gap-2 mt-2">
                      {existingImages.map((img, i) => (
                        <div key={i} className="relative">
                          <img src={img} alt="" className="w-16 h-16 rounded object-cover" />
                          <button type="button" onClick={() => removeExistingImage(i)} className="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">&times;</button>
                        </div>
                      ))}
                    </div>
                  )}
                </div>
              </div>
              <div className="flex justify-end space-x-3 pt-2">
                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">Cancel</button>
                <button type="submit" className="btn-primary">{editItem ? 'Update' : 'Create'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
