import { useState, useEffect } from 'react';
import toast from 'react-hot-toast';
import { settingsAPI } from '../services/api';

export default function AdminSettings() {
  const [form, setForm] = useState({
    store_name: '', phone: '', email: '', address: '', theme: 'light'
  });
  const [logoFile, setLogoFile] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    settingsAPI.getAll().then(res => {
      const s = res.data.data;
      setForm({
        store_name: s.store_name || '',
        phone: s.phone || '',
        email: s.email || '',
        address: s.address || '',
        theme: s.theme || 'light'
      });
    }).catch(() => {}).finally(() => setLoading(false));
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData();
    Object.entries(form).forEach(([k, v]) => formData.append(k, v));
    if (logoFile) formData.append('logo', logoFile);
    try {
      await settingsAPI.update(formData);
      toast.success('Settings updated!');
    } catch (err) {
      toast.error('Failed to update settings');
    }
  };

  if (loading) return <div className="flex justify-center py-12"><div className="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent" /></div>;

  return (
    <div>
      <h1 className="text-2xl font-heading font-bold text-gray-900 dark:text-white mb-6">Settings</h1>

      <div className="card p-6 max-w-2xl">
        <form onSubmit={handleSubmit} className="space-y-5">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Store Name</label>
              <input type="text" value={form.store_name} onChange={e => setForm({...form, store_name: e.target.value})} className="input-field" />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo</label>
              <input type="file" accept="image/*" onChange={e => setLogoFile(e.target.files[0])} className="input-field" />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
              <input type="text" value={form.phone} onChange={e => setForm({...form, phone: e.target.value})} className="input-field" />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
              <input type="email" value={form.email} onChange={e => setForm({...form, email: e.target.value})} className="input-field" />
            </div>
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
            <textarea value={form.address} onChange={e => setForm({...form, address: e.target.value})} className="input-field" rows="3" />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Theme</label>
            <select value={form.theme} onChange={e => setForm({...form, theme: e.target.value})} className="input-field">
              <option value="light">Light</option>
              <option value="dark">Dark</option>
            </select>
          </div>
          <div className="pt-2">
            <button type="submit" className="btn-primary">Save Settings</button>
          </div>
        </form>
      </div>
    </div>
  );
}
