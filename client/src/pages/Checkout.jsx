import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { HiArrowLeft, HiCheckCircle } from 'react-icons/hi';
import toast from 'react-hot-toast';
import { useCart } from '../context/CartContext';
import { orderAPI } from '../services/api';
import Logo from '../components/Logo';

export default function Checkout() {
  const { cart, getTotal, clearCart, getItemCount } = useCart();
  const navigate = useNavigate();
  const [form, setForm] = useState({ name: '', phone: '', address: '' });
  const [submitting, setSubmitting] = useState(false);

  if (cart.length === 0) {
    return (
      <div className="py-20 text-center min-h-[60vh] flex items-center justify-center">
        <div className="max-w-md mx-auto px-4">
          <h2 className="text-2xl font-heading font-bold text-gray-900 dark:text-white mb-3">No items to checkout</h2>
          <Link to="/products" className="btn-primary inline-flex items-center gap-2">Start Shopping</Link>
        </div>
      </div>
    );
  }

  const subtotal = getTotal();
  const shipping = subtotal >= 500 ? 0 : 49;
  const total = subtotal + shipping;

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!form.name.trim() || !form.phone.trim() || !form.address.trim()) {
      toast.error('Please fill all fields');
      return;
    }
    setSubmitting(true);
    try {
      const items = cart.map(item => ({
        id: item.id,
        name: item.name,
        quantity: item.quantity,
        price: item.offer_price || item.price
      }));
      await orderAPI.placeOrder({ name: form.name.trim(), phone: form.phone.trim(), address: form.address.trim(), items, total: Math.round(total) });
      toast.success('Order placed successfully!');
      clearCart();
      navigate('/');
    } catch (err) {
      toast.error(err.response?.data?.message || 'Failed to place order. Please try again.');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="py-10 min-h-[80vh]">
      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <Link to="/cart" className="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium mb-3 transition-colors">
          <HiArrowLeft className="w-4 h-4" /> Back to Cart
        </Link>
        <h1 className="text-4xl font-heading font-bold text-gray-900 dark:text-white mb-8">Checkout</h1>

        <div className="grid grid-cols-1 lg:grid-cols-5 gap-8">
          <div className="lg:col-span-3">
            <div className="card p-8">
              <h2 className="text-xl font-heading font-bold text-gray-900 dark:text-white mb-6">Shipping Details</h2>
              <form onSubmit={handleSubmit} className="space-y-5">
                <div>
                  <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                  <input type="text" value={form.name} onChange={e => setForm({...form, name: e.target.value})} className="input-field" placeholder="Enter your full name" required />
                </div>
                <div>
                  <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                  <input type="tel" value={form.phone} onChange={e => setForm({...form, phone: e.target.value})} className="input-field" placeholder="Enter your phone number" required />
                </div>
                <div>
                  <label className="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Delivery Address</label>
                  <textarea value={form.address} onChange={e => setForm({...form, address: e.target.value})} className="input-field" rows="4" placeholder="Enter your full address" required />
                </div>
                <button type="submit" disabled={submitting} className="btn-primary w-full py-4 text-lg flex items-center justify-center gap-2">
                  {submitting ? (
                    <div className="animate-spin rounded-full h-6 w-6 border-2 border-white border-t-transparent" />
                  ) : (
                    <>
                      <HiCheckCircle className="w-5 h-5" />
                      Place Order &bull; ₹{Math.round(total)}
                    </>
                  )}
                </button>
              </form>
            </div>
          </div>

          <div className="lg:col-span-2">
            <div className="card p-6 sticky top-24">
              <div className="mb-4"><Logo size="sm" /></div>
              <h3 className="text-xl font-heading font-bold text-gray-900 dark:text-white mb-4">Order Summary</h3>
              <p className="text-sm text-gray-500 mb-4">{getItemCount()} item(s)</p>
              <div className="space-y-3 mb-5 max-h-60 overflow-y-auto">
                {cart.map(item => {
                  const price = item.offer_price || item.price;
                  return (
                    <div key={item.id} className="flex items-center gap-3">
                      <img src={item.image || 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=100&q=80'} alt={item.name} className="w-12 h-12 rounded-lg object-cover flex-shrink-0" />
                      <div className="flex-1 min-w-0">
                        <p className="text-sm font-semibold text-gray-900 dark:text-white truncate">{item.name}</p>
                        <p className="text-xs text-gray-500">Qty: {item.quantity}</p>
                      </div>
                      <p className="text-sm font-bold text-gray-900 dark:text-white">₹{Math.round(price * item.quantity)}</p>
                    </div>
                  );
                })}
              </div>
              <div className="border-t pt-4 space-y-2">
                <div className="flex justify-between text-gray-600 dark:text-gray-400 text-sm">
                  <span>Subtotal</span>
                  <span className="font-semibold">₹{Math.round(subtotal)}</span>
                </div>
                <div className="flex justify-between text-gray-600 dark:text-gray-400 text-sm">
                  <span>Shipping</span>
                  <span className={`font-semibold ${shipping === 0 ? 'text-green-600' : ''}`}>
                    {shipping === 0 ? 'Free' : `₹${shipping}`}
                  </span>
                </div>
                <div className="border-t pt-3 flex justify-between">
                  <span className="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                   <span className="text-2xl font-extrabold text-gray-900 dark:text-white">₹{Math.round(total)}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
