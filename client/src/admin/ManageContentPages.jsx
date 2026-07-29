import { useState, useEffect } from 'react';
import { HiPlus, HiPencil, HiTrash, HiEye, HiEyeOff, HiDocumentText } from 'react-icons/hi';
import toast from 'react-hot-toast';
import { contentPageAPI } from '../services/api';

const SLUG_OPTIONS = [
  { value: 'contact-us', label: 'Contact Us' },
  { value: 'about-us', label: 'About Us' },
  { value: 'faq', label: 'FAQ' },
  { value: 'privacy-policy', label: 'Privacy Policy' },
  { value: 'terms-and-conditions', label: 'Terms & Conditions' },
  { value: 'return-policy', label: 'Return Policy' },
  { value: 'shipping-policy', label: 'Shipping Policy' },
];

export default function ManageContentPages() {
  const [pages, setPages] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [form, setForm] = useState({ slug: '', title: '', content: '', meta_description: '', is_active: true });

  useEffect(() => { loadPages(); }, []);

  const loadPages = async () => {
    try {
      const res = await contentPageAPI.getAll();
      setPages(res.data.data);
    } catch {
      toast.error('Failed to load pages');
    } finally {
      setLoading(false);
    }
  };

  const openCreate = () => {
    setEditItem(null);
    setForm({ slug: '', title: '', content: '', meta_description: '', is_active: true });
    setShowModal(true);
  };

  const openEdit = (item) => {
    setEditItem(item);
    setForm({
      slug: item.slug,
      title: item.title,
      content: item.content || '',
      meta_description: item.meta_description || '',
      is_active: !!item.is_active,
    });
    setShowModal(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!form.slug || !form.title.trim() || !form.content.trim()) {
      toast.error('Slug, title, and content are required');
      return;
    }
    try {
      if (editItem) {
        await contentPageAPI.update(editItem.id, form);
        toast.success('Page updated!');
      } else {
        await contentPageAPI.create(form);
        toast.success('Page created!');
      }
      setShowModal(false);
      loadPages();
    } catch (err) {
      toast.error(err.response?.data?.message || 'Failed to save');
    }
  };

  const handleDelete = async (id) => {
    if (!confirm('Delete this page?')) return;
    try {
      await contentPageAPI.delete(id);
      toast.success('Page deleted!');
      loadPages();
    } catch {
      toast.error('Failed to delete');
    }
  };

  const handleToggleActive = async (id) => {
    const page = pages.find(p => p.id === id);
    if (!page) return;
    try {
      await contentPageAPI.update(id, { is_active: !page.is_active });
      loadPages();
    } catch {
      toast.error('Failed to toggle');
    }
  };

  if (loading) return (
    <div className="flex justify-center py-16">
      <div className="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent" />
    </div>
  );

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-3xl font-heading font-bold text-gray-900 dark:text-white">Content Pages</h1>
          <p className="text-gray-500 mt-1">{pages.length} page(s) total</p>
        </div>
        <button onClick={openCreate} className="btn-primary flex items-center space-x-2">
          <HiPlus className="w-5 h-5" />
          <span>Add Page</span>
        </button>
      </div>

      <div className="card overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
              <tr>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Page</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Updated</th>
                <th className="text-right px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100 dark:divide-gray-700">
              {pages.map((page) => (
                <tr key={page.id} className="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                  <td className="px-5 py-4">
                    <div className="flex items-center space-x-3">
                      <div className="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg">
                        <HiDocumentText className="w-5 h-5 text-primary-600" />
                      </div>
                      <div>
                        <p className="font-semibold text-gray-900 dark:text-white">{page.title}</p>
                        <p className="text-xs text-gray-500 truncate max-w-[250px]">{page.meta_description || 'No description'}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-5 py-4">
                    <code className="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-600 dark:text-gray-400">/{page.slug}</code>
                  </td>
                  <td className="px-5 py-4">
                    <span className={`px-3 py-1 rounded-full text-xs font-semibold ${page.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                      {page.is_active ? 'Active' : 'Inactive'}
                    </span>
                  </td>
                  <td className="px-5 py-4 text-sm text-gray-500">
                    {page.updated_at ? new Date(page.updated_at).toLocaleDateString() : '—'}
                  </td>
                  <td className="px-5 py-4 text-right">
                    <div className="flex items-center justify-end space-x-2">
                      <button onClick={() => handleToggleActive(page.id)}
                        className="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                        title={page.is_active ? 'Deactivate' : 'Activate'}>
                        {page.is_active ? <HiEyeOff className="w-4 h-4" /> : <HiEye className="w-4 h-4" />}
                      </button>
                      <button onClick={() => openEdit(page)}
                        className="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Edit">
                        <HiPencil className="w-4 h-4" />
                      </button>
                      <button onClick={() => handleDelete(page.id)}
                        className="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Delete">
                        <HiTrash className="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
              {pages.length === 0 && (
                <tr><td colSpan={5} className="px-6 py-16 text-center text-gray-400">
                  <HiDocumentText className="w-12 h-12 mx-auto mb-3 opacity-50" />
                  <p className="text-lg font-medium">No content pages found</p>
                </td></tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {showModal && (
        <div className="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
          <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-3xl shadow-2xl animate-scale-in max-h-[90vh] overflow-y-auto">
            <h2 className="text-xl font-heading font-bold text-gray-900 dark:text-white mb-6">
              {editItem ? 'Edit Page' : 'Add Page'}
            </h2>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Page *</label>
                  {editItem ? (
                    <input type="text" value={form.slug} readOnly className="input-field bg-gray-100 dark:bg-gray-700 cursor-not-allowed" />
                  ) : (
                    <select value={form.slug} onChange={e => setForm({ ...form, slug: e.target.value })} className="input-field" required>
                      <option value="">Select a page</option>
                      {SLUG_OPTIONS.filter(opt => !pages.find(p => p.slug === opt.value)).map(opt => (
                        <option key={opt.value} value={opt.value}>{opt.label}</option>
                      ))}
                    </select>
                  )}
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                  <input type="text" value={form.title} onChange={e => setForm({ ...form, title: e.target.value })}
                    className="input-field" placeholder="Page title" required />
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meta Description</label>
                <input type="text" value={form.meta_description} onChange={e => setForm({ ...form, meta_description: e.target.value })}
                  className="input-field" placeholder="SEO description" />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Content (HTML supported) *</label>
                <textarea value={form.content} onChange={e => setForm({ ...form, content: e.target.value })}
                  className="input-field font-mono text-sm" rows={14} required
                  placeholder="<h2>Title</h2><p>Content...</p>" />
              </div>
              <div className="flex items-center space-x-3">
                <input type="checkbox" id="is_active" checked={form.is_active}
                  onChange={e => setForm({ ...form, is_active: e.target.checked })}
                  className="w-4 h-4 text-primary-600 rounded" />
                <label htmlFor="is_active" className="text-sm font-medium text-gray-700 dark:text-gray-300">Active (visible on site)</label>
              </div>
              <div className="flex justify-end space-x-3 pt-2">
                <button type="button" onClick={() => setShowModal(false)}
                  className="px-4 py-2.5 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">Cancel</button>
                <button type="submit" className="btn-primary">{editItem ? 'Update' : 'Create'}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
