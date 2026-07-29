import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { HiTrash, HiHeart, HiShoppingBag, HiArrowLeft } from 'react-icons/hi';
import { wishlistAPI } from '../services/api';
import { useTheme } from '../context/ThemeContext';
import toast from 'react-hot-toast';

function parseImages(images) {
  if (!images) return [];
  if (Array.isArray(images)) return images;
  try { return JSON.parse(images); } catch { return []; }
}

export default function WishlistPage() {
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const { dark } = useTheme();

  useEffect(() => { loadWishlist(); }, []);

  const loadWishlist = async () => {
    try {
      const res = await wishlistAPI.getAll();
      setItems(res.data.data || []);
    } catch { }
    finally { setLoading(false); }
  };

  const handleRemove = async (productId) => {
    try {
      await wishlistAPI.toggle(productId);
      setItems(prev => prev.filter(item => item.product_id !== productId));
      toast.success('Removed from wishlist');
    } catch { toast.error('Failed to remove'); }
  };

  if (loading) {
    return (
      <div className="py-12 max-w-7xl mx-auto px-4">
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          {Array.from({ length: 4 }).map((_, i) => (
            <div key={i} className={`card animate-pulse ${dark ? 'bg-gray-800' : ''}`}>
              <div className={`aspect-square ${dark ? 'bg-gray-700' : 'bg-gray-200'}`} />
              <div className="p-4 space-y-2">
                <div className={`h-4 rounded w-3/4 ${dark ? 'bg-gray-700' : 'bg-gray-200'}`} />
                <div className={`h-6 rounded w-1/2 ${dark ? 'bg-gray-700' : 'bg-gray-200'}`} />
              </div>
            </div>
          ))}
        </div>
      </div>
    );
  }

  if (items.length === 0) {
    return (
      <div className="py-20 text-center min-h-[60vh] flex items-center justify-center">
        <div className="max-w-md mx-auto px-4 animate-scale-in">
          <div className={`w-28 h-28 mx-auto mb-6 rounded-full flex items-center justify-center ${dark ? 'bg-red-900/30' : 'bg-gradient-to-br from-red-100 to-pink-100'}`}>
            <HiHeart className="w-14 h-14 text-red-400" />
          </div>
          <h2 className={`text-3xl font-heading font-bold mb-3 ${dark ? 'text-white' : 'text-gray-900'}`}>My Wishlist is Empty</h2>
          <p className={`mb-8 leading-relaxed ${dark ? 'text-gray-400' : 'text-gray-500'}`}>Save your favorite items here for later.</p>
          <Link to="/products" className="btn-primary inline-flex items-center gap-2">
            <HiShoppingBag className="w-5 h-5" /> Start Shopping
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="py-10 min-h-[80vh]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-8">
          <Link to="/products" className="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-3 transition-colors">
            <HiArrowLeft className="w-4 h-4" /> Continue Shopping
          </Link>
          <h1 className={`text-4xl font-heading font-bold ${dark ? 'text-white' : 'text-gray-900'}`}>My Wishlist</h1>
          <p className={`mt-1 ${dark ? 'text-gray-400' : 'text-gray-500'}`}>{items.length} item(s)</p>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
          {items.map(item => {
            const imgs = parseImages(item.images);
            return (
              <div key={item.id} className="relative group">
                <Link to={`/products/${item.slug}`}>
                  <div className={`card card-hover ${dark ? 'bg-gray-800 border-gray-700' : ''}`}>
                    <div className="relative aspect-square overflow-hidden">
                      <img
                        src={imgs[0] || 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=400&q=80'}
                        alt={item.name}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                      />
                    </div>
                    <div className="p-4">
                      <p className={`text-[11px] font-semibold uppercase tracking-wider mb-1 ${dark ? 'text-primary-400' : 'text-primary-600'}`}>{item.category_name}</p>
                      <h3 className={`font-bold text-sm line-clamp-1 ${dark ? 'text-white' : 'text-gray-900'}`}>{item.name}</h3>
                      <div className="flex items-center gap-2 mt-2">
                        <span className={`text-lg font-extrabold ${dark ? 'text-white' : 'text-gray-900'}`}>₹{Math.round(item.offer_price || item.price)}</span>
                        {item.offer_price && <span className={`text-sm line-through ${dark ? 'text-gray-500' : 'text-gray-400'}`}>₹{item.price}</span>}
                      </div>
                    </div>
                  </div>
                </Link>
                <button
                  onClick={() => handleRemove(item.product_id)}
                  className="absolute top-3 right-3 w-9 h-9 bg-white/90 hover:bg-red-50 rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:scale-110 z-10"
                >
                  <HiTrash className="w-4 h-4 text-red-500" />
                </button>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}
