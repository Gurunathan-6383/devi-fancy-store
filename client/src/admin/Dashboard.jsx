import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { HiCollection, HiCube, HiBookOpen, HiShoppingBag, HiPlus, HiCog } from 'react-icons/hi';
import { categoryAPI, productAPI, catalogueAPI, orderAPI } from '../services/api';

export default function Dashboard() {
  const [stats, setStats] = useState({ categories: 0, products: 0, catalogues: 0, orders: 0 });

  useEffect(() => {
    Promise.all([
      categoryAPI.getAll().then(r => r.data.data || []).catch(() => []),
      productAPI.getAll().then(r => r.data.data || []).catch(() => []),
      catalogueAPI.getAll().then(r => r.data.data || []).catch(() => []),
      orderAPI.getAll().then(r => r.data.data || []).catch(() => [])
    ]).then(([categories, products, catalogues, orders]) => {
      setStats({
        categories: categories.length,
        products: products.length,
        catalogues: catalogues.length,
        orders: orders.length
      });
    });
  }, []);

  const cards = [
    { label: 'Categories', value: stats.categories, icon: HiCollection, color: 'from-secondary-500 to-secondary-600', shadow: 'shadow-secondary-200', link: '/admin/categories' },
    { label: 'Products', value: stats.products, icon: HiCube, color: 'from-primary-500 to-primary-600', shadow: 'shadow-primary-200', link: '/admin/products' },
    { label: 'Catalogues', value: stats.catalogues, icon: HiBookOpen, color: 'from-accent-500 to-accent-600', shadow: 'shadow-accent-200', link: '/admin/catalogues' },
    { label: 'Orders', value: stats.orders, icon: HiShoppingBag, color: 'from-emerald-500 to-emerald-600', shadow: 'shadow-emerald-200', link: '/admin/orders' },
  ];

  const actions = [
    { label: 'Add Product', icon: HiPlus, link: '/admin/products', color: 'bg-primary-600 hover:bg-primary-700 shadow-primary-200' },
    { label: 'Categories', icon: HiCollection, link: '/admin/categories', color: 'bg-secondary-600 hover:bg-secondary-700 shadow-secondary-200' },
    { label: 'Catalogues', icon: HiBookOpen, link: '/admin/catalogues', color: 'bg-accent-600 hover:bg-accent-700 shadow-accent-200' },
    { label: 'Settings', icon: HiCog, link: '/admin/settings', color: 'bg-gray-800 hover:bg-gray-900 shadow-gray-200' },
  ];

  return (
    <div>
      <div className="mb-8">
        <h1 className="text-3xl font-heading font-bold text-gray-900 dark:text-white">Dashboard</h1>
        <p className="text-gray-500 mt-1">Welcome back! Here's your store overview.</p>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {cards.map((card, i) => (
          <Link key={card.label} to={card.link} className="card card-hover p-6 group animate-slide-up" style={{ animationDelay: `${i * 0.05}s` }}>
            <div className="flex items-center justify-between">
              <div>
                <p className="text-gray-500 text-sm font-medium">{card.label}</p>
                <p className="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{card.value}</p>
              </div>
              <div className={`bg-gradient-to-br ${card.color} p-3.5 rounded-xl shadow-lg ${card.shadow} group-hover:scale-110 transition-transform duration-300`}>
                <card.icon className="w-6 h-6 text-white" />
              </div>
            </div>
          </Link>
        ))}
      </div>

      <div className="card p-6">
        <h2 className="text-lg font-heading font-bold text-gray-900 dark:text-white mb-5">Quick Actions</h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {actions.map((a, i) => (
            <Link key={a.label} to={a.link} className={`${a.color} text-white font-semibold py-3 px-6 rounded-xl transition-all text-center flex items-center justify-center gap-2 shadow-lg hover:-translate-y-0.5`}>
              <a.icon className="w-4 h-4" />
              {a.label}
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
}
