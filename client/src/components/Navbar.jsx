import { useState, useEffect, useRef } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { HiMenu, HiX, HiShoppingCart, HiSearch, HiUser, HiLogout, HiChevronDown, HiHeart, HiSun, HiMoon } from 'react-icons/hi';
import { useCart } from '../context/CartContext';
import { useCustomerAuth } from '../context/CustomerAuthContext';
import { useWishlist } from '../context/WishlistContext';
import { useTheme } from '../context/ThemeContext';
import { categoryAPI } from '../services/api';
import Logo from './Logo';

export default function Navbar() {
  const [isOpen, setIsOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [categories, setCategories] = useState([]);
  const [showSearch, setShowSearch] = useState(false);
  const [showUserMenu, setShowUserMenu] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const { getItemCount } = useCart();
  const { customer, logout, isAuthenticated } = useCustomerAuth();
  const { wishlistIds } = useWishlist();
  const { dark, toggleTheme } = useTheme();
  const navigate = useNavigate();
  const userMenuRef = useRef(null);

  useEffect(() => {
    categoryAPI.getActive().then(res => setCategories(res.data.data)).catch(() => {});
    const handleScroll = () => setScrolled(window.scrollY > 10);
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    const handleClickOutside = (e) => {
      if (userMenuRef.current && !userMenuRef.current.contains(e.target)) {
        setShowUserMenu(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const handleSearch = (e) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      navigate(`/search?q=${encodeURIComponent(searchQuery.trim())}`);
      setSearchQuery('');
      setShowSearch(false);
    }
  };

  const handleLogout = () => {
    logout();
    setShowUserMenu(false);
    navigate('/');
  };

  const navLinks = [
    { label: 'Home', path: '/' },
    { label: 'Categories', path: '/categories' },
    { label: 'Catalogues', path: '/catalogues' },
    { label: 'Products', path: '/products' },
    { label: 'Contact Us', path: '/page/contact-us' },
    { label: 'About Us', path: '/page/about-us' },
  ];

  return (
    <nav className={`sticky top-0 z-50 transition-all duration-300 ${scrolled ? (dark ? 'bg-gray-900/95 backdrop-blur-md shadow-lg' : 'bg-white/95 backdrop-blur-md shadow-lg') : (dark ? 'bg-gray-900 shadow-md' : 'bg-white shadow-md')}`}>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16 md:h-20 items-center">
          <Link to="/" className="flex-shrink-0">
            <Logo size="sm" />
          </Link>

          <div className="hidden lg:flex items-center space-x-1">
            {navLinks.map(item => (
              <Link key={item.path} to={item.path} className={`relative px-4 py-2 font-medium transition-colors group ${dark ? 'text-gray-300 hover:text-primary-400' : 'text-gray-700 hover:text-primary-600'}`}>
                {item.label}
                <span className="absolute bottom-0 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full group-hover:w-3/4 transition-all duration-300" />
              </Link>
            ))}
          </div>

          <div className="flex items-center space-x-1.5">
            <button onClick={toggleTheme} className={`p-2.5 rounded-full transition-all ${dark ? 'text-yellow-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50'}`}>
              {dark ? <HiSun className="w-5 h-5" /> : <HiMoon className="w-5 h-5" />}
            </button>

            <button onClick={() => setShowSearch(!showSearch)} className={`p-2.5 rounded-full transition-all ${dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50'}`}>
              <HiSearch className="w-5 h-5" />
            </button>

            {isAuthenticated && (
              <Link to="/wishlist" className={`relative p-2.5 rounded-full transition-all ${dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50'}`}>
                <HiHeart className="w-5 h-5" />
                {wishlistIds.length > 0 && (
                  <span className="absolute -top-0.5 -right-0.5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-md">
                    {wishlistIds.length}
                  </span>
                )}
              </Link>
            )}

            <Link to="/cart" className={`relative p-2.5 rounded-full transition-all ${dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50'}`}>
              <HiShoppingCart className="w-5 h-5" />
              {getItemCount() > 0 && (
                <span className="absolute -top-0.5 -right-0.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-[10px] rounded-full w-5 h-5 flex items-center justify-center font-bold shadow-md animate-bounce">
                  {getItemCount()}
                </span>
              )}
            </Link>

            <div className="relative" ref={userMenuRef}>
              {isAuthenticated ? (
                <button
                  onClick={() => setShowUserMenu(!showUserMenu)}
                  className={`flex items-center gap-2 p-2 rounded-full transition-all ${dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50'}`}
                >
                  <div className="w-8 h-8 bg-gradient-to-br from-primary-400 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
                    {customer?.name?.charAt(0)?.toUpperCase() || 'U'}
                  </div>
                  <span className={`hidden md:block text-sm font-medium max-w-[100px] truncate ${dark ? 'text-gray-300' : ''}`}>{customer?.name}</span>
                  <HiChevronDown className="w-4 h-4 hidden md:block" />
                </button>
              ) : (
                <Link
                  to="/login"
                  className={`flex items-center gap-2 px-3 py-2 rounded-full transition-all ${dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-600 hover:text-primary-600 hover:bg-primary-50'}`}
                >
                  <HiUser className="w-5 h-5" />
                  <span className="hidden md:block text-sm font-medium">Login</span>
                </Link>
              )}

              {showUserMenu && isAuthenticated && (
                <div className={`absolute right-0 top-full mt-2 w-56 rounded-2xl shadow-xl border py-2 animate-scale-in z-50 ${dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'}`}>
                  <div className={`px-4 py-3 border-b ${dark ? 'border-gray-700' : 'border-gray-100'}`}>
                    <p className={`font-bold text-sm ${dark ? 'text-white' : 'text-gray-900'}`}>{customer?.name}</p>
                    <p className={`text-xs mt-0.5 ${dark ? 'text-gray-400' : 'text-gray-500'}`}>{customer?.email}</p>
                  </div>
                  <button
                    onClick={handleLogout}
                    className="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                  >
                    <HiLogout className="w-4 h-4" />
                    Sign Out
                  </button>
                </div>
              )}
            </div>

            <button onClick={() => setIsOpen(!isOpen)} className={`lg:hidden p-2.5 rounded-full transition-all ${dark ? 'text-gray-300 hover:bg-gray-800' : 'text-gray-600 hover:bg-gray-100'}`}>
              {isOpen ? <HiX className="w-5 h-5" /> : <HiMenu className="w-5 h-5" />}
            </button>
          </div>
        </div>

        {showSearch && (
          <div className="pb-4 animate-slide-down">
            <form onSubmit={handleSearch} className="flex max-w-xl mx-auto">
              <div className="relative flex-1">
                <HiSearch className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" />
                <input
                  type="text"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  placeholder="Search for bangles, earrings, chains..."
                  className={`w-full pl-10 pr-4 py-3 border rounded-l-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none ${dark ? 'bg-gray-800 border-gray-700 text-white' : 'bg-gray-50 border-gray-200'}`}
                  autoFocus
                />
              </div>
              <button type="submit" className="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-8 rounded-r-xl transition-all font-medium">
                Search
              </button>
            </form>
          </div>
        )}
      </div>

      {isOpen && (
        <div className={`lg:hidden border-t shadow-lg animate-slide-down ${dark ? 'bg-gray-900 border-gray-700' : 'bg-white'}`}>
          <div className="px-4 py-4 space-y-1">
            {navLinks.map(item => (
              <Link key={item.path} to={item.path} onClick={() => setIsOpen(false)} className={`block px-4 py-3 font-medium rounded-lg transition-colors ${dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50'}`}>
                {item.label}
              </Link>
            ))}
            {isAuthenticated && (
              <Link to="/wishlist" onClick={() => setIsOpen(false)} className={`block px-4 py-3 font-medium rounded-lg transition-colors ${dark ? 'text-gray-300 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50'}`}>
                My Wishlist
              </Link>
            )}
            <div className={`border-t pt-3 mt-3 ${dark ? 'border-gray-700' : 'border-gray-200'}`}>
              {isAuthenticated ? (
                <>
                  <div className="px-4 py-2 flex items-center gap-3">
                    <div className="w-10 h-10 bg-gradient-to-br from-primary-400 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                      {customer?.name?.charAt(0)?.toUpperCase() || 'U'}
                    </div>
                    <div>
                      <p className={`font-bold text-sm ${dark ? 'text-white' : 'text-gray-900'}`}>{customer?.name}</p>
                      <p className={`text-xs ${dark ? 'text-gray-400' : 'text-gray-500'}`}>{customer?.email}</p>
                    </div>
                  </div>
                  <button onClick={() => { handleLogout(); setIsOpen(false); }} className="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 font-medium rounded-lg transition-colors">
                    Sign Out
                  </button>
                </>
              ) : (
                <>
                  <Link to="/login" onClick={() => setIsOpen(false)} className="block px-4 py-3 text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 font-medium rounded-lg transition-colors">
                    Sign In
                  </Link>
                  <Link to="/signup" onClick={() => setIsOpen(false)} className={`block px-4 py-3 font-medium rounded-lg transition-colors ${dark ? 'text-gray-300 hover:bg-gray-800' : 'text-gray-700 hover:bg-gray-50'}`}>
                    Create Account
                  </Link>
                </>
              )}
            </div>
            <div className={`border-t pt-3 mt-3 ${dark ? 'border-gray-700' : 'border-gray-200'}`}>
              <p className={`px-4 text-xs uppercase tracking-wider font-medium mb-2 ${dark ? 'text-gray-500' : 'text-gray-400'}`}>Categories</p>
              {categories.slice(0, 6).map(cat => (
                <Link key={cat.id} to={`/categories/${cat.slug}`} onClick={() => setIsOpen(false)} className={`block px-4 py-2 text-sm rounded-lg transition-colors ${dark ? 'text-gray-400 hover:text-primary-400 hover:bg-gray-800' : 'text-gray-500 hover:text-primary-600 hover:bg-gray-50'}`}>
                  {cat.name}
                </Link>
              ))}
            </div>
          </div>
        </div>
      )}
    </nav>
  );
}
