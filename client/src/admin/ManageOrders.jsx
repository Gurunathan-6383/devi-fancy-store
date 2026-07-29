import { useState, useEffect } from 'react';
import { HiSearch, HiDownload } from 'react-icons/hi';
import toast from 'react-hot-toast';
import { orderAPI } from '../services/api';

export default function ManageOrders() {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');
  const [sortField, setSortField] = useState('date');
  const [sortDir, setSortDir] = useState('desc');

  useEffect(() => { loadOrders(); }, []);

  const loadOrders = async () => {
    try {
      const res = await orderAPI.getAll();
      setOrders(res.data.data);
    } catch (err) {
      toast.error('Failed to load orders');
    } finally {
      setLoading(false);
    }
  };

  const filtered = orders
    .filter(o => {
      if (!search.trim()) return true;
      const q = search.toLowerCase();
      return o.name.toLowerCase().includes(q) ||
             o.phone.includes(q) ||
             o.products.toLowerCase().includes(q);
    })
    .sort((a, b) => {
      let cmp = 0;
      if (sortField === 'name') cmp = a.name.localeCompare(b.name);
      else if (sortField === 'total') {
        const ta = parseFloat(a.total.replace('₹', ''));
        const tb = parseFloat(b.total.replace('₹', ''));
        cmp = ta - tb;
      } else cmp = new Date(a.date) - new Date(b.date);
      return sortDir === 'asc' ? cmp : -cmp;
    });

  const toggleSort = (field) => {
    if (sortField === field) setSortDir(d => d === 'asc' ? 'desc' : 'asc');
    else { setSortField(field); setSortDir('desc'); }
  };

  if (loading) return (
    <div className="flex justify-center py-16">
      <div className="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent" />
    </div>
  );

  return (
    <div>
      <div className="flex items-end justify-between mb-6">
        <div>
          <h1 className="text-3xl font-heading font-bold text-gray-900 dark:text-white">Orders</h1>
          <p className="text-gray-500 mt-1">{filtered.length} order(s) found</p>
        </div>
      </div>

      <div className="card p-4 mb-6">
        <div className="relative">
          <HiSearch className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" />
          <input
            type="text"
            value={search}
            onChange={e => setSearch(e.target.value)}
            placeholder="Search by name, phone, or products..."
            className="input-field pl-11"
          />
        </div>
      </div>

      <div className="card overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50/80 dark:bg-gray-700/80 border-b border-gray-200 dark:border-gray-700">
              <tr>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" onClick={() => toggleSort('name')}>
                  Customer {sortField === 'name' && (sortDir === 'asc' ? '↑' : '↓')}
                </th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Phone</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Address</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Products</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" onClick={() => toggleSort('total')}>
                  Total {sortField === 'total' && (sortDir === 'asc' ? '↑' : '↓')}
                </th>
                <th className="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:text-primary-600 transition-colors" onClick={() => toggleSort('date')}>
                  Date {sortField === 'date' && (sortDir === 'asc' ? '↑' : '↓')}
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100 dark:divide-gray-700">
              {filtered.map((order, i) => (
                <tr key={order.id || i} className="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors" style={{ animationDelay: `${i * 0.02}s` }}>
                  <td className="px-5 py-4 font-semibold text-gray-900 dark:text-white">{order.name}</td>
                  <td className="px-5 py-4 text-gray-600 dark:text-gray-400 font-mono text-sm">{order.phone}</td>
                  <td className="px-5 py-4 text-gray-600 dark:text-gray-400 max-w-[200px] truncate text-sm" title={order.address}>{order.address}</td>
                  <td className="px-5 py-4 text-gray-600 dark:text-gray-400 max-w-[200px] truncate text-sm" title={order.products}>{order.products}</td>
                  <td className="px-5 py-4">
                    <span className="bg-primary-100 text-primary-700 font-bold text-xs px-2.5 py-1 rounded-full">{order.quantity}</span>
                  </td>
                  <td className="px-5 py-4 font-extrabold text-gray-900 dark:text-white">{order.total}</td>
                  <td className="px-5 py-4 text-sm text-gray-500">{order.date}</td>
                </tr>
              ))}
              {filtered.length === 0 && (
                <tr><td colSpan={7} className="px-6 py-16 text-center text-gray-400">
                  <p className="text-lg font-medium">No orders found</p>
                  <p className="text-sm mt-1">Orders placed through the store will appear here.</p>
                </td></tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
