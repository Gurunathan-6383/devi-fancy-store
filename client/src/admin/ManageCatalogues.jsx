import { useState, useEffect } from 'react';
import { HiPlus, HiPencil, HiTrash, HiEye, HiEyeOff, HiX } from 'react-icons/hi';
import toast from 'react-hot-toast';
import { catalogueAPI, productAPI } from '../services/api';

export default function ManageCatalogues() {
  const [catalogues, setCatalogues] = useState([]);
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [form, setForm] = useState({ title: '', description: '' });
  const [imageFile, setImageFile] = useState(null);
  const [showProductModal, setShowProductModal] = useState(false);
  const [selectedCatalogue, setSelectedCatalogue] = useState(null);
  const [catalogueProducts, setCatalogueProducts] = useState([]);

  useEffect(() => { loadData(); }, []);

  const loadData = async () => {
    try {
      const [catRes, prodRes] = await Promise.all([
        catalogueAPI.getAll(), productAPI.getAll()
      ]);
      setCatalogues(catRes.data.data);
      setProducts(prodRes.data.data);
    } catch (err) { toast.error('Failed to load data'); }
    finally { setLoading(false); }
  };

  const openCreate = () => {
    setEditItem(null); setForm({ title: '', description: '' }); setImageFile(null); setShowModal(true);
  };

  const openEdit = (cat) => {
    setEditItem(cat); setForm({ title: cat.title, description: cat.description || '' }); setImageFile(null); setShowModal(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!form.title.trim()) { toast.error('Title is required'); return; }
    const formData = new FormData();
    formData.append('title', form.title.trim());
    formData.append('description', form.description.trim());
    if (imageFile) formData.append('image', imageFile);
    try {
      if (editItem) { await catalogueAPI.update(editItem.id, formData); toast.success('Updated!'); }
      else { await catalogueAPI.create(formData); toast.success('Created!'); }
      setShowModal(false); loadData();
    } catch (err) { toast.error(err.response?.data?.message || 'Failed'); }
  };

  const handleDelete = async (id) => {
    if (!confirm('Delete this catalogue?')) return;
    try { await catalogueAPI.delete(id); toast.success('Deleted!'); loadData(); }
    catch (err) { toast.error('Failed to delete'); }
  };

  const handleTogglePublish = async (id) => {
    try { await catalogueAPI.togglePublish(id); loadData(); }
    catch (err) { toast.error('Failed'); }
  };

  const openManageProducts = async (catalogue) => {
    setSelectedCatalogue(catalogue);
    try {
      const res = await catalogueAPI.getProducts(catalogue.id);
      setCatalogueProducts(res.data.data);
    } catch { setCatalogueProducts([]); }
    setShowProductModal(true);
  };

  const addProductToCatalogue = async (productId) => {
    try {
      await catalogueAPI.addProduct(selectedCatalogue.id, productId);
      const res = await catalogueAPI.getProducts(selectedCatalogue.id);
      setCatalogueProducts(res.data.data);
      toast.success('Product added');
    } catch (err) { toast.error('Failed to add'); }
  };

  const removeProductFromCatalogue = async (productId) => {
    try {
      await catalogueAPI.removeProduct(selectedCatalogue.id, productId);
      setCatalogueProducts(prev => prev.filter(p => p.id !== productId));
      toast.success('Product removed');
    } catch (err) { toast.error('Failed to remove'); }
  };

  const availableProducts = products.filter(p => !catalogueProducts.find(cp => cp.id === p.id));

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-3xl font-heading font-bold text-gray-900 dark:text-white">Manage Catalogues</h1>
          <p className="text-gray-500 mt-1">{catalogues.length} catalogue(s) total</p>
        </div>
        <button onClick={openCreate} className="btn-primary flex items-center space-x-2">
          <HiPlus className="w-5 h-5" /><span>Add Catalogue</span>
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {catalogues.map(cat => (
          <div key={cat.id} className="card p-5">
            {cat.image && <img src={cat.image} alt={cat.title} className="w-full h-40 object-cover rounded-lg mb-4" />}
            <h3 className="text-lg font-semibold text-gray-900 dark:text-white">{cat.title}</h3>
            {cat.description && <p className="text-sm text-gray-500 mt-1 line-clamp-2">{cat.description}</p>}
            <div className="flex items-center space-x-2 mt-3">
              <span className={`px-2.5 py-0.5 rounded-full text-xs font-semibold ${cat.is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}`}>
                {cat.is_published ? 'Published' : 'Draft'}
              </span>
            </div>
            <div className="flex items-center space-x-2 mt-4 pt-3 border-t">
              <button onClick={() => handleTogglePublish(cat.id)} className="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" title={cat.is_published ? 'Unpublish' : 'Publish'}>
                {cat.is_published ? <HiEyeOff className="w-4 h-4" /> : <HiEye className="w-4 h-4" />}
              </button>
              <button onClick={() => openManageProducts(cat)} className="px-3 py-1.5 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                Manage Products
              </button>
              <div className="flex-1" />
              <button onClick={() => openEdit(cat)} className="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"><HiPencil className="w-4 h-4" /></button>
              <button onClick={() => handleDelete(cat.id)} className="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"><HiTrash className="w-4 h-4" /></button>
            </div>
          </div>
        ))}
        {catalogues.length === 0 && !loading && (
          <div className="col-span-full text-center py-12 text-gray-500">No catalogues yet.</div>
        )}
      </div>

      {showModal && (
        <div className="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
          <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-md shadow-2xl animate-scale-in">
            <h2 className="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">{editItem ? 'Edit Catalogue' : 'Add Catalogue'}</h2>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input type="text" value={form.title} onChange={e => setForm({...form, title: e.target.value})} className="input-field" required />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea value={form.description} onChange={e => setForm({...form, description: e.target.value})} className="input-field" rows="3" />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image</label>
                <input type="file" accept="image/*" onChange={e => setImageFile(e.target.files[0])} className="input-field" />
              </div>
              <div className="flex justify-end space-x-3 pt-2">
                <button type="button" onClick={() => setShowModal(false)} className="px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Cancel</button>
                <button type="submit" className="btn-primary">{editItem ? 'Update' : 'Create'}</button>
              </div>
            </form>
          </div>
        </div>
      )}

      {showProductModal && selectedCatalogue && (
        <div className="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
          <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-2xl max-h-[80vh] overflow-y-auto shadow-2xl animate-scale-in">
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-xl font-heading font-bold text-gray-900 dark:text-white">{selectedCatalogue.title} - Products</h2>
              <button onClick={() => setShowProductModal(false)} className="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"><HiX className="w-5 h-5" /></button>
            </div>

            <div className="mb-4">
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Add Product</label>
              <select className="input-field" onChange={e => { if (e.target.value) addProductToCatalogue(parseInt(e.target.value)); e.target.value = ''; }}>
                <option value="">Select a product...</option>
                {availableProducts.map(p => (
                  <option key={p.id} value={p.id}>{p.name} (₹{p.offer_price || p.price})</option>
                ))}
              </select>
            </div>

            <div className="space-y-2">
              {catalogueProducts.map(p => (
                <div key={p.id} className="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                  <div className="flex items-center space-x-3">
                    <img src={p.images?.[0] || 'https://via.placeholder.com/40'} alt={p.name} className="w-10 h-10 rounded object-cover" />
                    <div>
                      <p className="font-medium text-gray-900 dark:text-white text-sm">{p.name}</p>
                      <p className="text-xs text-gray-500">₹{p.offer_price || p.price}</p>
                    </div>
                  </div>
                  <button onClick={() => removeProductFromCatalogue(p.id)} className="p-1.5 text-red-500 hover:bg-red-50 rounded-lg"><HiTrash className="w-4 h-4" /></button>
                </div>
              ))}
              {catalogueProducts.length === 0 && <p className="text-center text-gray-500 py-8">No products in this catalogue</p>}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
