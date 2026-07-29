import { useState, useEffect } from 'react';
import { HiSearch } from 'react-icons/hi';
import { orderAPI } from '../services/api';

export default function ManageCustomers() {
  const [customers, setCustomers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState('');

  useEffect(() => {
    orderAPI.getAll().then(res => {
      const orders = res.data.data;
      const customerMap = {};
      orders.forEach(o => {
        if (!customerMap[o.phone]) {
          customerMap[o.phone] = { name: o.name, phone: o.phone, address: o.address, orders: 0 };
        }
        customerMap[o.phone].orders += 1;
      });
      setCustomers(Object.values(customerMap));
    }).catch(() => {}).finally(() => setLoading(false));
  }, []);

  const filtered = customers.filter(c =>
    !search.trim() || c.name.toLowerCase().includes(search.toLowerCase()) || c.phone.includes(search)
  );

  if (loading) return <div className="flex justify-center py-12"><div className="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent" /></div>;

  return (
    <div>
      <h1 className="text-2xl font-heading font-bold text-gray-900 dark:text-white mb-6">Customers</h1>

      <div className="card p-4 mb-6">
        <div className="relative">
          <HiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" />
          <input type="text" value={search} onChange={e => setSearch(e.target.value)} placeholder="Search by name or phone..." className="pl-10 pr-4 py-2.5 w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" />
        </div>
      </div>

      <div className="card overflow-hidden">
        <table className="w-full">
          <thead className="bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
            <tr>
              <th className="text-left px-6 py-4 text-sm font-semibold text-gray-600">Name</th>
              <th className="text-left px-6 py-4 text-sm font-semibold text-gray-600">Phone</th>
              <th className="text-left px-6 py-4 text-sm font-semibold text-gray-600">Address</th>
              <th className="text-left px-6 py-4 text-sm font-semibold text-gray-600">Total Orders</th>
            </tr>
          </thead>
          <tbody className="divide-y">
            {filtered.map(c => (
              <tr key={c.phone} className="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <td className="px-6 py-4 font-medium text-gray-900 dark:text-white">{c.name}</td>
                <td className="px-6 py-4 text-gray-600 dark:text-gray-400">{c.phone}</td>
                <td className="px-6 py-4 text-gray-600 dark:text-gray-400 max-w-[250px] truncate">{c.address}</td>
                <td className="px-6 py-4"><span className="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold">{c.orders}</span></td>
              </tr>
            ))}
            {filtered.length === 0 && <tr><td colSpan={4} className="px-6 py-12 text-center text-gray-500">No customers yet.</td></tr>}
          </tbody>
        </table>
      </div>
    </div>
  );
}
