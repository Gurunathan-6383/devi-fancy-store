import { useState, useEffect } from 'react';
import { HiPlus, HiPencil, HiTrash, HiEye, HiEyeOff, HiSpeakerphone } from 'react-icons/hi';
import toast from 'react-hot-toast';
import { announcementAPI } from '../services/api';

const TYPES = [
  { value: 'discount', label: 'Discount Offer' },
  { value: 'festival', label: 'Festival Offer' },
  { value: 'flash_sale', label: 'Flash Sale' },
  { value: 'new_arrival', label: 'New Arrival' },
  { value: 'free_shipping', label: 'Free Shipping' },
  { value: 'general', label: 'General Announcement' },
];

const defaultForm = {
  title: '',
  message: '',
  type: 'general',
  status: 'active',
  bg_color: '#e04a6f',
  text_color: '#ffffff',
  priority: 0,
  start_date: '',
  end_date: '',
  redirect_url: '',
};

export default function ManageAnnouncements() {
  const [announcements, setAnnouncements] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showModal, setShowModal] = useState(false);
  const [showPreview, setShowPreview] = useState(false);
  const [editItem, setEditItem] = useState(null);
  const [form, setForm] = useState(defaultForm);
  const [filter, setFilter] = useState('all');

  useEffect(() => { loadAnnouncements(); }, []);

  const loadAnnouncements = async () => {
    try {
      const res = await announcementAPI.getAll();
      setAnnouncements(res.data.data);
    } catch {
      toast.error('Failed to load announcements');
    } finally {
      setLoading(false);
    }
  };

  const openCreate = () => {
    setEditItem(null);
    setForm(defaultForm);
    setShowModal(true);
  };

  const openEdit = (item) => {
    setEditItem(item);
    setForm({
      title: item.title,
      message: item.message,
      type: item.type,
      status: item.status,
      bg_color: item.bg_color || '#e04a6f',
      text_color: item.text_color || '#ffffff',
      priority: item.priority || 0,
      start_date: item.start_date ? item.start_date.slice(0, 16) : '',
      end_date: item.end_date ? item.end_date.slice(0, 16) : '',
      redirect_url: item.redirect_url || '',
    });
    setShowModal(true);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!form.title.trim() || !form.message.trim()) {
      toast.error('Title and message are required');
      return;
    }
    const payload = { ...form };
    if (payload.start_date) payload.start_date = payload.start_date.replace('T', ' ') + ':00';
    else payload.start_date = null;
    if (payload.end_date) payload.end_date = payload.end_date.replace('T', ' ') + ':00';
    else payload.end_date = null;
    if (!payload.redirect_url) payload.redirect_url = null;
    try {
      if (editItem) {
        await announcementAPI.update(editItem.id, payload);
        toast.success('Announcement updated!');
      } else {
        await announcementAPI.create(payload);
        toast.success('Announcement created!');
      }
      setShowModal(false);
      loadAnnouncements();
    } catch (err) {
      toast.error(err.response?.data?.message || 'Failed to save');
    }
  };

  const handleDelete = async (id) => {
    if (!confirm('Delete this announcement?')) return;
    try {
      await announcementAPI.delete(id);
      toast.success('Announcement deleted!');
      loadAnnouncements();
    } catch {
      toast.error('Failed to delete');
    }
  };

  const handleToggleStatus = async (id) => {
    try {
      await announcementAPI.toggleStatus(id);
      loadAnnouncements();
    } catch {
      toast.error('Failed to toggle status');
    }
  };

  const filtered = filter === 'all' ? announcements : announcements.filter(a => a.status === filter);

  if (loading) return (
    <div className="flex justify-center py-16">
      <div className="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent" />
    </div>
  );

  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-3xl font-heading font-bold text-gray-900 dark:text-white">Announcements</h1>
          <p className="text-gray-500 mt-1">{announcements.length} announcement(s) total</p>
        </div>
        <button onClick={openCreate} className="btn-primary flex items-center space-x-2">
          <HiPlus className="w-5 h-5" />
          <span>Add Announcement</span>
        </button>
      </div>

      <div className="flex items-center space-x-2 mb-6">
        {['all', 'active', 'inactive'].map(f => (
          <button key={f} onClick={() => setFilter(f)}
            className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${filter === f ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600'}`}>
            {f.charAt(0).toUpperCase() + f.slice(1)}
          </button>
        ))}
      </div>

      <div className="card overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
              <tr>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Preview</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Title</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Priority</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Schedule</th>
                <th className="text-right px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100 dark:divide-gray-700">
              {filtered.map((item) => (
                <tr key={item.id} className="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                  <td className="px-5 py-3">
                    <div className="flex items-center space-x-2">
                      <div className="px-3 py-1.5 rounded-full text-xs font-semibold max-w-[220px] truncate"
                        style={{ backgroundColor: item.bg_color, color: item.text_color }}>
                        {item.title}
                      </div>
                    </div>
                  </td>
                  <td className="px-5 py-3">
                    <p className="font-semibold text-gray-900 dark:text-white">{item.title}</p>
                    <p className="text-sm text-gray-500 truncate max-w-[200px]">{item.message}</p>
                  </td>
                  <td className="px-5 py-3">
                    <span className="text-xs font-medium text-gray-600 dark:text-gray-400 capitalize">{item.type.replace('_', ' ')}</span>
                  </td>
                  <td className="px-5 py-3">
                    <span className="text-sm font-bold text-gray-900 dark:text-white">{item.priority}</span>
                  </td>
                  <td className="px-5 py-3">
                    <span className={`px-3 py-1 rounded-full text-xs font-semibold ${item.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                      {item.status === 'active' ? 'Active' : 'Inactive'}
                    </span>
                  </td>
                  <td className="px-5 py-3 text-sm text-gray-500">
                    {item.start_date ? (
                      <div>
                        <p>From: {new Date(item.start_date).toLocaleDateString()}</p>
                        {item.end_date && <p>To: {new Date(item.end_date).toLocaleDateString()}</p>}
                      </div>
                    ) : (
                      <span className="text-gray-400">Always</span>
                    )}
                  </td>
                  <td className="px-5 py-3 text-right">
                    <div className="flex items-center justify-end space-x-2">
                      <button onClick={() => handleToggleStatus(item.id)}
                        className="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                        title={item.status === 'active' ? 'Deactivate' : 'Activate'}>
                        {item.status === 'active' ? <HiEyeOff className="w-4 h-4" /> : <HiEye className="w-4 h-4" />}
                      </button>
                      <button onClick={() => openEdit(item)}
                        className="p-2 text-gray-500 dark:text-gray-400 hover:text-secondary-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Edit">
                        <HiPencil className="w-4 h-4" />
                      </button>
                      <button onClick={() => handleDelete(item.id)}
                        className="p-2 text-gray-500 dark:text-gray-400 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Delete">
                        <HiTrash className="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
              {filtered.length === 0 && (
                <tr><td colSpan={7} className="px-6 py-16 text-center text-gray-400">
                  <HiSpeakerphone className="w-12 h-12 mx-auto mb-3 opacity-50" />
                  <p className="text-lg font-medium">No announcements found</p>
                  <p className="text-sm mt-1">Create your first announcement to get started.</p>
                </td></tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {showModal && (
        <div className="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
          <div className="bg-white dark:bg-gray-800 rounded-3xl p-8 w-full max-w-lg shadow-2xl animate-scale-in max-h-[90vh] overflow-y-auto">
            <h2 className="text-xl font-heading font-bold text-gray-900 dark:text-white mb-6">
              {editItem ? 'Edit Announcement' : 'Add Announcement'}
            </h2>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                <input type="text" value={form.title} onChange={e => setForm({ ...form, title: e.target.value })}
                  className="input-field" placeholder="e.g. 50% Off This Weekend!" required />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message *</label>
                <textarea value={form.message} onChange={e => setForm({ ...form, message: e.target.value })}
                  className="input-field" rows={3} placeholder="Announcement details..." required />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                  <select value={form.type} onChange={e => setForm({ ...form, type: e.target.value })} className="input-field">
                    {TYPES.map(t => <option key={t.value} value={t.value}>{t.label}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                  <input type="number" value={form.priority} onChange={e => setForm({ ...form, priority: parseInt(e.target.value) || 0 })}
                    className="input-field" min="0" />
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Background Color</label>
                  <div className="flex items-center space-x-2">
                    <input type="color" value={form.bg_color} onChange={e => setForm({ ...form, bg_color: e.target.value })}
                      className="w-10 h-10 rounded-lg border-0 cursor-pointer" />
                    <input type="text" value={form.bg_color} onChange={e => setForm({ ...form, bg_color: e.target.value })}
                      className="input-field flex-1" />
                  </div>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Text Color</label>
                  <div className="flex items-center space-x-2">
                    <input type="color" value={form.text_color} onChange={e => setForm({ ...form, text_color: e.target.value })}
                      className="w-10 h-10 rounded-lg border-0 cursor-pointer" />
                    <input type="text" value={form.text_color} onChange={e => setForm({ ...form, text_color: e.target.value })}
                      className="input-field flex-1" />
                  </div>
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date (optional)</label>
                  <input type="datetime-local" value={form.start_date} onChange={e => setForm({ ...form, start_date: e.target.value })}
                    className="input-field" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date (optional)</label>
                  <input type="datetime-local" value={form.end_date} onChange={e => setForm({ ...form, end_date: e.target.value })}
                    className="input-field" />
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Redirect URL (optional)</label>
                <input type="url" value={form.redirect_url} onChange={e => setForm({ ...form, redirect_url: e.target.value })}
                  className="input-field" placeholder="https://example.com" />
              </div>

              <div className="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <p className="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Preview</p>
                <div className="rounded-xl overflow-hidden" style={{ backgroundColor: form.bg_color, color: form.text_color }}>
                  <div className="flex items-center h-10 px-4 space-x-2 text-sm">
                    <span className="font-semibold">{form.title || 'Announcement Title'}</span>
                    <span className="opacity-80">—</span>
                    <span>{form.message || 'Announcement message'}</span>
                  </div>
                </div>
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
