import { Link } from 'react-router-dom';
import { HiTrash, HiMinus, HiPlus, HiShoppingBag, HiArrowLeft } from 'react-icons/hi';
import { useCart } from '../context/CartContext';
import toast from 'react-hot-toast';

export default function CartPage() {
  const { cart, removeItem, updateQuantity, getTotal, getItemCount } = useCart();

  const handleRemove = (id, name) => {
    removeItem(id);
    toast.success(`${name} removed from cart`);
  };

  if (cart.length === 0) {
    return (
      <div className="py-20 text-center min-h-[60vh] flex items-center justify-center">
        <div className="max-w-md mx-auto px-4 animate-scale-in">
          <div className="w-28 h-28 mx-auto mb-6 bg-gradient-to-br from-primary-100 to-secondary-100 rounded-full flex items-center justify-center">
            <HiShoppingBag className="w-14 h-14 text-primary-400" />
          </div>
          <h2 className="text-3xl font-heading font-bold text-gray-900 dark:text-white mb-3">Your Cart is Empty</h2>
          <p className="text-gray-500 mb-8 leading-relaxed">Looks like you haven't added anything yet. Start exploring our collection!</p>
          <Link to="/products" className="btn-primary inline-flex items-center gap-2">
            <HiShoppingBag className="w-5 h-5" /> Start Shopping
          </Link>
        </div>
      </div>
    );
  }

  const subtotal = getTotal();
  const shipping = subtotal >= 500 ? 0 : 49;
  const total = subtotal + shipping;

  return (
    <div className="py-10 min-h-[80vh]">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-8">
          <Link to="/products" className="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-3 transition-colors">
            <HiArrowLeft className="w-4 h-4" /> Continue Shopping
          </Link>
          <h1 className="text-4xl font-heading font-bold text-gray-900 dark:text-white">Shopping Cart</h1>
          <p className="text-gray-500 mt-1">{getItemCount()} item(s) in your cart</p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div className="lg:col-span-2 space-y-4">
            {cart.map((item, i) => {
              const price = item.offer_price || item.price;
              return (
                <div key={item.id} className="card p-5 flex items-center gap-5 animate-slide-up" style={{ animationDelay: `${i * 0.05}s` }}>
                  <Link to={`/products/${item.slug}`} className="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700 shadow-inner">
                    <img src={item.image || 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=200&q=80'} alt={item.name} className="w-full h-full object-cover hover:scale-110 transition-transform duration-500" />
                  </Link>
                  <div className="flex-1 min-w-0">
                    <Link to={`/products/${item.slug}`} className="font-bold text-gray-900 dark:text-white hover:text-primary-600 transition-colors line-clamp-1 text-lg">{item.name}</Link>
                    <p className="text-primary-600 font-bold text-lg mt-1">₹{Math.round(price)}</p>
                  </div>
                  <div className="flex items-center border-2 border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <button onClick={() => updateQuantity(item.id, item.quantity - 1)} className="p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                      <HiMinus className="w-4 h-4" />
                    </button>
                    <span className="px-5 py-3 font-bold text-lg min-w-[3rem] text-center bg-gray-50 dark:bg-gray-700">{item.quantity}</span>
                    <button onClick={() => updateQuantity(item.id, item.quantity + 1)} className="p-3 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                      <HiPlus className="w-4 h-4" />
                    </button>
                  </div>
                  <p className="font-extrabold text-gray-900 dark:text-white w-20 text-right text-lg">₹{Math.round(price * item.quantity)}</p>
                  <button onClick={() => handleRemove(item.id, item.name)} className="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                    <HiTrash className="w-5 h-5" />
                  </button>
                </div>
              );
            })}
          </div>

          <div className="lg:col-span-1">
            <div className="card p-6 sticky top-24">
              <h3 className="text-xl font-heading font-bold text-gray-900 dark:text-white mb-5">Order Summary</h3>
              <div className="space-y-3 mb-6">
                <div className="flex justify-between text-gray-600 dark:text-gray-400">
                  <span>Subtotal ({getItemCount()} items)</span>
                  <span className="font-semibold">₹{Math.round(subtotal)}</span>
                </div>
                <div className="flex justify-between text-gray-600 dark:text-gray-400">
                  <span>Shipping</span>
                  <span className={`font-semibold ${shipping === 0 ? 'text-green-600' : ''}`}>
                    {shipping === 0 ? 'Free' : `₹${shipping}`}
                  </span>
                </div>
                {shipping > 0 && (
                  <p className="text-xs text-green-600 bg-green-50 px-3 py-2 rounded-lg">
                    Add ₹{500 - Math.round(subtotal)} more for free shipping!
                  </p>
                )}
              </div>
              <div className="border-t pt-4 mb-6">
                <div className="flex justify-between">
                  <span className="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                   <span className="text-2xl font-extrabold text-gray-900 dark:text-white">₹{Math.round(total)}</span>
                </div>
              </div>
              <Link to="/checkout" className="btn-primary w-full text-center flex items-center justify-center gap-2 py-4 text-lg">
                Proceed to Checkout
              </Link>
              <Link to="/products" className="block text-center text-primary-600 hover:text-primary-700 font-medium mt-4 transition-colors">
                Continue Shopping
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
