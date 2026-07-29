import { useState } from 'react';
import { Link, Outlet, useLocation, useNavigate } from 'react-router-dom';
import {
  HiHome, HiCollection, HiCube, HiBookOpen, HiShoppingBag,
  HiUsers, HiCog, HiLogout, HiMenu, HiX, HiBell, HiSpeakerphone, HiDocumentText
} from 'react-icons/hi';
import { useAuth } from '../context/AuthContext';

const navItems = [
  { icon: HiHome, label: 'Dashboard', path: '/admin' },
  { icon: HiCollection, label: 'Categories', path: '/admin/categories' },
  { icon: HiCube, label: 'Products', path: '/admin/products' },
  { icon: HiBookOpen, label: 'Catalogues', path: '/admin/catalogues' },
  { icon: HiShoppingBag, label: 'Orders', path: '/admin/orders' },
  { icon: HiUsers, label: 'Customers', path: '/admin/customers' },
  { icon: HiSpeakerphone, label: 'Announcements', path: '/admin/announcements' },
  { icon: HiDocumentText, label: 'Content Pages', path: '/admin/content-pages' },
  { icon: HiCog, label: 'Settings', path: '/admin/settings' },
];

export default function AdminLayout() {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const location = useLocation();
  const navigate = useNavigate();
  const { logout } = useAuth();

  const handleLogout = () => {
    logout();
    navigate('/admin/login');
  };

  return (
    <div className="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
      <aside className={`fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transform transition-transform duration-300 ${sidebarOpen ? 'translate-x-0' : '-translate-x-full'} lg:translate-x-0 lg:static lg:inset-auto`}>
        <div className="flex items-center justify-between px-6 py-5 border-b border-gray-800">
          <Link to="/admin" className="text-xl font-heading font-bold text-gradient">Admin Panel</Link>
          <button onClick={() => setSidebarOpen(false)} className="lg:hidden text-gray-400">
            <HiX className="w-6 h-6" />
          </button>
        </div>
        <nav className="mt-6 px-3 space-y-1">
          {navItems.map((item) => {
            const isActive = location.pathname === item.path;
            return (
              <Link
                key={item.path}
                to={item.path}
                onClick={() => setSidebarOpen(false)}
                className={`flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors ${
                  isActive ? 'bg-primary-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800'
                }`}
              >
                <item.icon className="w-5 h-5" />
                <span className="font-medium">{item.label}</span>
              </Link>
            );
          })}
        </nav>
        <div className="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-800">
          <Link to="/" target="_blank" className="flex items-center space-x-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors mb-2">
            <HiBell className="w-5 h-5" />
            <span className="font-medium">View Store</span>
          </Link>
          <button onClick={handleLogout} className="flex items-center space-x-3 px-4 py-3 text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-lg transition-colors w-full">
            <HiLogout className="w-5 h-5" />
            <span className="font-medium">Logout</span>
          </button>
        </div>
      </aside>

      <div className="flex-1 flex flex-col min-h-screen">
        <header className="bg-white dark:bg-gray-800 shadow-sm px-6 py-4 flex items-center justify-between lg:justify-end">
          <button onClick={() => setSidebarOpen(true)} className="lg:hidden text-gray-600 dark:text-gray-400">
            <HiMenu className="w-6 h-6" />
          </button>
          <h1 className="text-lg font-semibold text-gray-800 dark:text-gray-200 lg:hidden">
            {navItems.find(i => i.path === location.pathname)?.label || 'Admin'}
          </h1>
          <div className="flex items-center space-x-4">
            <Link to="/" className="text-sm text-primary-600 hover:text-primary-700 font-medium">View Store</Link>
          </div>
        </header>
        <main className="flex-1 p-6 overflow-auto">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
