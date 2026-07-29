import { Link, useNavigate, useLocation } from 'react-router-dom';
import { HiShoppingCart, HiHeart, HiEye } from 'react-icons/hi';
import { useCart } from '../context/CartContext';
import { useCustomerAuth } from '../context/CustomerAuthContext';
import { useWishlist } from '../context/WishlistContext';
import { useTheme } from '../context/ThemeContext';
import toast from 'react-hot-toast';

export default function ProductCard({ product }) {
  const { addItem, cart } = useCart();
  const { isAuthenticated } = useCustomerAuth();
  const { toggleWishlist, isWishlisted } = useWishlist();
  const { dark } = useTheme();
  const navigate = useNavigate();
  const location = useLocation();
  const inCart = cart.find(item => item.id === product.id);
  const wishlisted = isWishlisted(product.id);
  const price = product.offer_price || product.price;
  const hasOffer = !!product.offer_price;
  const discount = hasOffer ? Math.round((1 - product.offer_price / product.price) * 100) : 0;

  const handleAddToCart = (e) => {
    e.preventDefault();
    if (!isAuthenticated) {
      toast.error('Please login to add products to your cart.');
      navigate('/login', { state: { from: location.pathname } });
      return;
    }
    if (product.stock <= 0) { toast.error(t('outOfStock')); return; }
    addItem({
      id: product.id, name: product.name, price: product.price,
      offer_price: product.offer_price, image: product.images?.[0] || '/placeholder.jpg',
      stock: product.stock, slug: product.slug
    });
    toast.success('Added to cart!');
  };

  const handleWishlist = async (e) => {
    e.preventDefault();
    if (!isAuthenticated) {
      toast.error('Please login to add products to your cart.');
      navigate('/login', { state: { from: location.pathname } });
      return;
    }
    const action = await toggleWishlist(product.id);
    toast.success(action === 'added' ? 'Added to wishlist!' : 'Removed from wishlist!');
  };

  return (
    <Link to={`/products/${product.slug}`} className={`card card-hover group ${dark ? 'bg-gray-800 border-gray-700' : ''}`}>
      <div className="relative overflow-hidden aspect-square">
        <img
          src={product.images?.[0] || 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=400&q=80'}
          alt={product.name}
          className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out"
        />

        <div className="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500" />

        {hasOffer && (
          <div className="absolute top-3 left-3 z-10">
            <div className="bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg shadow-red-500/30">
              -{discount}% OFF
            </div>
          </div>
        )}

        {product.stock <= 0 && (
          <div className="absolute inset-0 bg-black/60 flex items-center justify-center z-10 backdrop-blur-[1px]">
            <span className="text-white font-bold text-lg bg-black/50 px-4 py-2 rounded-full">{t('outOfStock')}</span>
          </div>
        )}

        <div className="absolute top-3 right-3 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0 z-10">
          <button
            onClick={handleWishlist}
            className={`w-9 h-9 rounded-full flex items-center justify-center shadow-lg transition-all hover:scale-110 ${wishlisted ? 'bg-red-500 text-white' : 'bg-white/90 hover:bg-white text-gray-600 hover:text-red-500'}`}
          >
            <HiHeart className={`w-4 h-4 ${wishlisted ? 'fill-white' : ''}`} />
          </button>
          <button
            onClick={handleAddToCart}
            disabled={product.stock <= 0}
            className="w-9 h-9 bg-primary-500 hover:bg-primary-600 rounded-full flex items-center justify-center shadow-lg shadow-primary-500/30 transition-all hover:scale-110 disabled:opacity-50"
          >
            <HiShoppingCart className="w-4 h-4 text-white" />
          </button>
        </div>

        <div className="absolute bottom-3 left-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 z-10">
          <Link to={`/products/${product.slug}`} className={`flex items-center justify-center gap-2 font-semibold py-2.5 rounded-xl shadow-xl transition-all text-sm ${dark ? 'bg-gray-800/95 hover:bg-gray-800 text-white' : 'bg-white/95 hover:bg-white text-gray-900'}`}>
            <HiEye className="w-4 h-4" />
            Quick View
          </Link>
        </div>
      </div>

      <div className="p-4">
        <p className={`text-[11px] font-semibold uppercase tracking-wider mb-1.5 ${dark ? 'text-primary-400' : 'text-primary-600'}`}>
          {product.category_name || 'General'}
        </p>
        <h3 className={`font-bold text-[15px] mb-2 line-clamp-1 transition-colors ${dark ? 'text-white group-hover:text-primary-400' : 'text-gray-900 group-hover:text-primary-600'}`}>
          {product.name}
        </h3>
        <div className="flex items-center gap-2.5">
          <span className={`text-xl font-extrabold ${dark ? 'text-white' : 'text-gray-900'}`}>
            ₹{Math.round(price)}
          </span>
          {hasOffer && (
            <span className={`text-sm line-through font-medium ${dark ? 'text-gray-500' : 'text-gray-400'}`}>
              ₹{Math.round(product.price)}
            </span>
          )}
        </div>
        {inCart && (
          <div className="mt-2 flex items-center gap-1 text-xs text-primary-600 font-semibold bg-primary-50 dark:bg-primary-900/30 dark:text-primary-400 px-2.5 py-1 rounded-full w-fit">
            <HiShoppingCart className="w-3 h-3" />
            {inCart.quantity} in cart
          </div>
        )}
      </div>
    </Link>
  );
}
