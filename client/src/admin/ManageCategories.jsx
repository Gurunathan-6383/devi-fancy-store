import { useState, useEffect } from 'react';
import { HiPlus, HiPencil, HiTrash, HiEye, HiEyeOff } from 'react-icons/hi';
import toast from 'react-hot-toast';
import { categoryAPI } from '../services/api';

export default function ManageCategories() {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [form, setForm] = useState({ name: '' });
  const [imageFile, setImageFile] = useState(null);

  useEffect(() => { loadCategories(); }, []);

  const loadCategories = async () => {
    try {
      const res = await categoryAPI.getAll();
      setCategories(res.data.data);
    } catch (err) {
      toast.error('Failed to load categories');
    } finally {
      setLoading(false);
    }
  };

  const openCreate = () => {
    setEditItem(null);
    setForm({ name: '' });
    setImageFile(null);
    setShowModal(true);
  };

  const openEdit = (cat) => {
    setEditItem(cat);
    setForm({ name: cat.name });
    setImageFile(null);
    setShowModal(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!form.name.trim()) { toast.error('Category name is required'); return; }
    const formData = new FormData();
    formData.append('name', form.name.trim());
    if (imageFile) formData.append('image', imageFile);
    try {
      if (editItem) {
        await categoryAPI.update(editItem.id, formData);
        toast.success('Category updated!');
      } else {
        await categoryAPI.create(formData);
        toast.success('Category created!');
      }
      setShowModal(false);
      loadCategories();
    } catch (err) {
      toast.error(err.response?.data?.message || 'Failed to save');
    }
  };

  const handleDelete = async (id) => {
    if (!confirm('Are you sure you want to delete this category?')) return;
    try {
      await categoryAPI.delete(id);
      toast.success('Category deleted!');
      loadCategories();
    } catch (err) {
      toast.error('Failed to delete');
    }
  };

  const handleToggleVisibility = async (id) => {
    try {
      await categoryAPI.toggleVisibility(id);
      loadCategories();
    } catch (err) {
      toast.error('Failed to toggle visibility');
    }
  };

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-3xl font-heading font-bold text-gray-900 dark:text-white">Manage Categories</h1>
          <p className="text-gray-500 mt-1">{categories.length} category(ies) total</p>
        </div>
        <button onClick={openCreate} className="btn-primary flex items-center space-x-2">
          <HiPlus className="w-5 h-5" />
          <span>Add Category</span>
        </button>
      </div>

      <div className="card overflow-hidden">
        <table className="w-full">
          <thead className="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
            <tr>
              <th className="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Image</th>
              <th className="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
              <th className="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
              <th className="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
              <th className="text-right px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y">
            {categories.map(cat => (
              <tr key={cat.id} className="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <td className="px-6 py-4">
                  <img src={cat.image || 'https://via.placeholder.com/48'} alt={cat.name} className="w-12 h-12 rounded-lg object-cover" />
                </td>
                <td className="px-6 py-4 font-medium text-gray-900 dark:text-white">{cat.name}</td>
                <td className="px-6 py-4 text-gray-500 text-sm">{cat.slug}</td>
                <td className="px-6 py-4">
                  <span className={`px-3 py-1 rounded-full text-xs font-semibold ${cat.is_hidden ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}`}>
                    {cat.is_hidden ? 'Hidden' : 'Visible'}
                  </span>
                </td>
                <td className="px-6 py-4 text-right">
                  <div className="flex items-center justify-end space-x-2">
                    <button onClick={() => handleToggleVisibility(cat.id)} className="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Toggle visibility">
                      {cat.is_hidden ? <HiEyeOff className="w-4 h-4" /> : <HiEye className="w-4 h-4" />}
                    </button>
                    <button onClick={() => openEdit(cat)} className="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Edit">
                      <HiPencil className="w-4 h-4" />
                    </button>
                    <button onClick={() => handleDelete(cat.id)} className="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Delete">
                      <HiTrash className="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
            {categories.length === 0 && !loading && (
              <tr><td colSpan={5} className="px-6 py-12 text-center text-gray-500">No categories found. Create your first category!</td></tr>
            )}
          </tbody>
        </table>
      </div>

      {showModal && (
        <div className="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
          <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-md shadow-2xl animate-scale-in">
            <h2 className="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">{editItem ? 'Edit Category' : 'Add Category'}</h2>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input type="text" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} className="input-field" placeholder="Category name" required />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image (optional)</label>
                <input type="file" accept="image/*" onChange={(e) => setImageFile(e.target.files[0])} className="input-field" />
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
