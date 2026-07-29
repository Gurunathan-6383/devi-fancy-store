import { useState, useEffect } from 'react';
import { useParams, Link, useNavigate, useLocation } from 'react-router-dom';
import { HiShoppingCart, HiMinus, HiPlus, HiCheck, HiChevronRight, HiShieldCheck, HiTruck, HiRefresh, HiStar } from 'react-icons/hi';
import toast from 'react-hot-toast';
import { productAPI, reviewAPI } from '../services/api';
import { useCart } from '../context/CartContext';
import { useCustomerAuth } from '../context/CustomerAuthContext';
import { useTheme } from '../context/ThemeContext';
import { ProductCardSkeleton } from '../components/Skeleton';
import ProductCard from '../components/ProductCard';

function StarRating({ rating, size = 'w-5 h-5' }) {
  return (
    <div className="flex gap-0.5">
      {[1, 2, 3, 4, 5].map(i => (
        <HiStar key={i} className={`${size} ${i <= rating ? 'text-accent-400 fill-accent-400' : 'text-gray-300 dark:text-gray-600'}`} />
      ))}
    </div>
  );
}

export default function ProductDetails() {
  const { slug } = useParams();
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [selectedImage, setSelectedImage] = useState(0);
  const [quantity, setQuantity] = useState(1);
  const [related, setRelated] = useState([]);
  const [reviews, setReviews] = useState([]);
  const [reviewStats, setReviewStats] = useState({ count: 0, avg_rating: 0 });
  const [reviewForm, setReviewForm] = useState({ rating: 5, comment: '' });
  const [submittingReview, setSubmittingReview] = useState(false);
  const { addItem, cart } = useCart();
  const { isAuthenticated } = useCustomerAuth();
  const { dark } = useTheme();
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    setLoading(true);
    window.scrollTo(0, 0);
    productAPI.getBySlug(slug)
      .then(res => {
        setProduct(res.data.data);
        if (res.data.data?.id) {
          productAPI.getRelated(res.data.data.id, 4).then(r => setRelated(r.data.data || [])).catch(() => {});
          loadReviews(res.data.data.id);
        }
      })
      .catch(() => {})
      .finally(() => setLoading(false));
  }, [slug]);

  const loadReviews = (productId) => {
    reviewAPI.getByProduct(productId).then(res => {
      setReviews(res.data.data?.reviews || []);
      setReviewStats(res.data.data?.stats || { count: 0, avg_rating: 0 });
    }).catch(() => {});
  };

  if (loading) {
    return (
      <div className="py-12 max-w-7xl mx-auto px-4">
        <div className="animate-pulse grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-14">
          <div>
            <div className={`aspect-square rounded-2xl mb-4 ${dark ? 'bg-gray-700' : 'bg-gray-200'}`} />
            <div className="flex gap-3">{Array.from({ length: 4 }).map((_, i) => <div key={i} className={`w-20 h-20 rounded-xl ${dark ? 'bg-gray-700' : 'bg-gray-200'}`} />)}</div>
          </div>
          <div className="space-y-4 py-4">
            {[1/4, 3/4, 1/3, 1, 1, 2/3, 1/2].map((w, i) => <div key={i} className={`h-4 rounded w-${w === 1 ? 'full' : w === 1/4 ? '1/4' : w === 1/3 ? '1/3' : w === 2/3 ? '2/3' : w === 1/2 ? '1/2' : '3/4'} ${dark ? 'bg-gray-700' : 'bg-gray-200'}`} />)}
          </div>
        </div>
      </div>
    );
  }

  if (!product) {
    return (
      <div className="py-20 text-center min-h-[60vh] flex items-center justify-center">
        <div className="max-w-md mx-auto px-4">
          <h2 className={`text-3xl font-heading font-bold mb-3 ${dark ? 'text-white' : 'text-gray-900'}`}>{'No products found.'}</h2>
          <Link to="/products" className="btn-primary inline-flex items-center gap-2">{'Back to Products'}</Link>
        </div>
      </div>
    );
  }

  const inCart = cart.find(item => item.id === product.id);
  const price = product.offer_price || product.price;
  const hasOffer = !!product.offer_price;
  const specs = product.specifications ? product.specifications.split('\n').filter(s => s.trim()) : [];
  const discount = hasOffer ? Math.round((1 - product.offer_price / product.price) * 100) : 0;

  const requireAuth = (msg) => {
    if (!isAuthenticated) { toast.error(msg); navigate('/login', { state: { from: location.pathname } }); return false; }
    return true;
  };

  const handleAddToCart = () => {
    if (!requireAuth('Please login to add products to your cart.')) return;
    if (product.stock <= 0) { toast.error('Out of Stock'); return; }
    for (let i = 0; i < quantity; i++) {
      addItem({ id: product.id, name: product.name, price: product.price, offer_price: product.offer_price, image: product.images?.[0] || '/placeholder.jpg', stock: product.stock, slug: product.slug });
    }
    setQuantity(1);
    toast.success(`Added ${quantity} item(s) to cart!`);
  };

  const handleBuyNow = () => {
    if (!requireAuth('Please login to continue your purchase.')) return;
    handleAddToCart();
    navigate('/checkout');
  };

  const handleSubmitReview = async (e) => {
    e.preventDefault();
    if (!requireAuth('Login to review')) return;
    setSubmittingReview(true);
    try {
      await reviewAPI.create({ product_id: product.id, rating: reviewForm.rating, comment: reviewForm.comment });
      toast.success('Review submitted!');
      setReviewForm({ rating: 5, comment: '' });
      loadReviews(product.id);
    } catch (err) {
      toast.error(err.response?.data?.message || 'Failed to submit review');
    } finally { setSubmittingReview(false); }
  };

  return (
    <div className="py-10">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav className="flex items-center text-sm mb-8 overflow-x-auto">
          <Link to="/" className={`transition-colors whitespace-nowrap ${dark ? 'text-gray-500 hover:text-primary-400' : 'text-gray-400 hover:text-primary-600'}`}>{'Home'}</Link>
          <HiChevronRight className="w-3.5 h-3.5 mx-2 flex-shrink-0 text-gray-400" />
          <Link to="/products" className={`transition-colors whitespace-nowrap ${dark ? 'text-gray-500 hover:text-primary-400' : 'text-gray-400 hover:text-primary-600'}`}>{'Products'}</Link>
          {product.category_name && (<><HiChevronRight className="w-3.5 h-3.5 mx-2 flex-shrink-0 text-gray-400" /><Link to={`/categories/${product.category_slug}`} className={`transition-colors whitespace-nowrap ${dark ? 'text-gray-500 hover:text-primary-400' : 'text-gray-400 hover:text-primary-600'}`}>{product.category_name}</Link></>)}
          <HiChevronRight className="w-3.5 h-3.5 mx-2 flex-shrink-0 text-gray-400" />
          <span className={`font-medium truncate ${dark ? 'text-white' : 'text-gray-900'}`}>{product.name}</span>
        </nav>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-14">
          <div className="animate-slide-up">
            <div className={`relative aspect-square rounded-2xl overflow-hidden mb-4 shadow-xl group ${dark ? 'bg-gray-800' : 'bg-gray-100'}`}>
              <img src={product.images?.[selectedImage] || product.images?.[0] || 'https://via.placeholder.com/600x600?text=No+Image'} alt={product.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
              {hasOffer && <div className="absolute top-4 left-4 bg-red-500 text-white font-bold px-4 py-2 rounded-xl text-sm shadow-lg animate-pulse-glow">{discount}% OFF</div>}
            </div>
            {product.images?.length > 1 && (
              <div className="flex gap-3 overflow-x-auto pb-2">
                {product.images.map((img, i) => (
                  <button key={i} onClick={() => setSelectedImage(i)} className={`w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 transition-all duration-300 ${selectedImage === i ? 'border-2 border-primary-500 shadow-lg ring-2 ring-primary-200' : `border-2 ${dark ? 'border-gray-600 hover:border-gray-500' : 'border-gray-200 hover:border-gray-300'} opacity-70 hover:opacity-100`}`}>
                    <img src={img} alt="" className="w-full h-full object-cover" />
                  </button>
                ))}
              </div>
            )}
          </div>

          <div className="animate-slide-up" style={{ animationDelay: '0.1s' }}>
            <p className={`font-semibold text-sm uppercase tracking-[0.15em] mb-2 ${dark ? 'text-primary-400' : 'text-primary-600'}`}>{product.category_name || 'General'}</p>
            <h1 className={`text-3xl md:text-4xl font-heading font-bold mb-5 leading-tight ${dark ? 'text-white' : 'text-gray-900'}`}>{product.name}</h1>

            <div className="flex items-center gap-4 mb-4">
              <span className={`text-4xl font-extrabold ${dark ? 'text-white' : 'text-gray-900'}`}>₹{Math.round(price)}</span>
              {hasOffer && (<><span className={`text-xl line-through ${dark ? 'text-gray-500' : 'text-gray-400'}`}>₹{product.price}</span><span className="bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-bold px-4 py-1.5 rounded-full shadow-md">Save {discount}%</span></>)}
            </div>

            {reviewStats.count > 0 && (
              <div className="flex items-center gap-2 mb-6">
                <StarRating rating={Math.round(reviewStats.avg_rating)} />
                <span className={`text-sm font-medium ${dark ? 'text-gray-400' : 'text-gray-600'}`}>{Number(reviewStats.avg_rating).toFixed(1)} ({reviewStats.count} {'Reviews'})</span>
              </div>
            )}

            <div className="mb-6 flex items-center gap-3">
              {product.stock > 0 ? (
                <span className="inline-flex items-center gap-2 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-semibold px-4 py-2 rounded-xl border border-green-200 dark:border-green-800">
                  <HiCheck className="w-5 h-5" /> {'In Stock'} ({product.stock} {'available'})
                </span>
              ) : (
                <span className="inline-flex items-center gap-2 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-semibold px-4 py-2 rounded-xl border border-red-200 dark:border-red-800">{'Out of Stock'}</span>
              )}
            </div>

            {product.description && (
              <div className="mb-6">
                <h3 className={`text-base font-bold mb-2 uppercase tracking-wide ${dark ? 'text-white' : 'text-gray-900'}`}>{'Description'}</h3>
                <p className={`leading-relaxed ${dark ? 'text-gray-400' : 'text-gray-600'}`}>{product.description}</p>
              </div>
            )}

            {specs.length > 0 && (
              <div className="mb-8">
                <h3 className={`text-base font-bold mb-3 uppercase tracking-wide ${dark ? 'text-white' : 'text-gray-900'}`}>{'Specifications'}</h3>
                <div className={`rounded-xl p-4 space-y-2 border ${dark ? 'bg-gray-800 border-gray-700' : 'bg-gray-50 border-gray-100'}`}>
                  {specs.map((spec, i) => {
                    const [key, ...val] = spec.split(':');
                    return (
                      <div key={i} className={`flex py-1.5 ${i < specs.length - 1 ? 'border-b border-gray-200 dark:border-gray-700' : ''}`}>
                        <span className={`w-36 flex-shrink-0 font-medium text-sm ${dark ? 'text-gray-400' : 'text-gray-500'}`}>{key?.trim()}:</span>
                        <span className={`font-semibold text-sm ${dark ? 'text-white' : 'text-gray-900'}`}>{val.join(':')?.trim()}</span>
                      </div>
                    );
                  })}
                </div>
              </div>
            )}

            {product.stock > 0 && (
              <div className="mb-6">
                <label className={`block text-sm font-bold mb-2 uppercase tracking-wide ${dark ? 'text-gray-300' : 'text-gray-700'}`}>{'Quantity'}</label>
                <div className={`inline-flex items-center border-2 rounded-xl overflow-hidden ${dark ? 'border-gray-600' : 'border-gray-200'}`}>
                  <button onClick={() => setQuantity(Math.max(1, quantity - 1))} className={`p-3.5 transition-colors ${dark ? 'hover:bg-gray-700' : 'hover:bg-gray-100'}`}><HiMinus className="w-4 h-4" /></button>
                  <span className={`px-8 py-3.5 font-bold text-lg min-w-[4rem] text-center ${dark ? 'bg-gray-800 text-white' : 'bg-gray-50'}`}>{quantity}</span>
                  <button onClick={() => setQuantity(Math.min(product.stock, quantity + 1))} className={`p-3.5 transition-colors ${dark ? 'hover:bg-gray-700' : 'hover:bg-gray-100'}`}><HiPlus className="w-4 h-4" /></button>
                </div>
              </div>
            )}

            <div className="flex flex-wrap gap-4 mb-6">
              <button onClick={handleAddToCart} disabled={product.stock <= 0} className="btn-primary flex-1 flex items-center justify-center gap-2 py-4 text-lg disabled:opacity-40 disabled:cursor-not-allowed">
                <HiShoppingCart className="w-5 h-5" />{'Add to Cart'}
              </button>
              <button onClick={handleBuyNow} disabled={product.stock <= 0} className={`flex-1 font-bold py-4 px-6 rounded-2xl transition-all flex items-center justify-center gap-2 text-lg shadow-lg hover:-translate-y-0.5 disabled:opacity-40 disabled:cursor-not-allowed ${dark ? 'bg-gray-700 hover:bg-gray-600 text-white' : 'bg-gray-900 hover:bg-gray-800 text-white'}`}>
                {'Buy Now'}
              </button>
            </div>
            {inCart && (
              <p className={`text-sm font-medium px-4 py-2.5 rounded-xl mb-6 ${dark ? 'text-primary-400 bg-primary-900/30 border border-primary-800' : 'text-primary-600 bg-primary-50 border border-primary-100'}`}>
                {inCart.quantity} item(s) already in your cart
              </p>
            )}

            <div className="grid grid-cols-3 gap-3 mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
              {[{ icon: HiTruck, text: 'Free Shipping', sub: 'On orders above ?500' }, { icon: HiShieldCheck, text: 'Secure Payment', sub: '100% secure checkout' }, { icon: HiRefresh, text: 'Easy Returns', sub: '7 Days' }].map((b, i) => (
                <div key={i} className={`text-center p-3 rounded-xl border ${dark ? 'bg-gray-800 border-gray-700' : 'bg-gray-50 border-gray-100'}`}>
                  <b.icon className="w-6 h-6 text-primary-600 mx-auto mb-1" />
                  <p className={`text-xs font-bold ${dark ? 'text-white' : 'text-gray-900'}`}>{b.text}</p>
                  <p className={`text-[10px] ${dark ? 'text-gray-500' : 'text-gray-500'}`}>{b.sub}</p>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Reviews Section */}
        <div className="mt-20">
          <div className="mb-10">
            <span className={`font-semibold text-sm uppercase tracking-[0.2em] ${dark ? 'text-primary-400' : 'text-primary-600'}`}>{'Reviews'}</span>
            <h2 className={`text-3xl font-heading font-bold mt-2 ${dark ? 'text-white' : 'text-gray-900'}`}>{'Reviews'} ({reviewStats.count})</h2>
            <div className="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4" />
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Review Form */}
            <div className={`card p-6 ${dark ? 'bg-gray-800 border-gray-700' : ''}`}>
              <h3 className={`text-lg font-heading font-bold mb-4 ${dark ? 'text-white' : 'text-gray-900'}`}>{'Write a Review'}</h3>
              {isAuthenticated ? (
                <form onSubmit={handleSubmitReview} className="space-y-4">
                  <div>
                    <label className={`block text-sm font-bold mb-2 ${dark ? 'text-gray-300' : 'text-gray-700'}`}>{'Your Rating'}</label>
                    <div className="flex gap-1">
                      {[1, 2, 3, 4, 5].map(i => (
                        <button key={i} type="button" onClick={() => setReviewForm(prev => ({ ...prev, rating: i }))}>
                          <HiStar className={`w-8 h-8 transition-colors ${i <= reviewForm.rating ? 'text-accent-400 fill-accent-400' : 'text-gray-300 dark:text-gray-600'}`} />
                        </button>
                      ))}
                    </div>
                  </div>
                  <div>
                    <label className={`block text-sm font-bold mb-2 ${dark ? 'text-gray-300' : 'text-gray-700'}`}>{'Your Review'}</label>
                    <textarea value={reviewForm.comment} onChange={e => setReviewForm(prev => ({ ...prev, comment: e.target.value }))} className={`input-field ${dark ? 'bg-gray-700 border-gray-600 text-white' : ''}`} rows="4" placeholder="Tell others about your experience..." />
                  </div>
                  <button type="submit" disabled={submittingReview} className="btn-primary w-full">
                    {submittingReview ? <div className="animate-spin rounded-full h-6 w-6 border-2 border-white border-t-transparent mx-auto" /> : 'Submit Review'}
                  </button>
                </form>
              ) : (
                <div className="text-center py-8">
                  <p className={`mb-4 ${dark ? 'text-gray-400' : 'text-gray-500'}`}>{'Login to review'}</p>
                  <Link to="/login" className="btn-primary inline-block">{'Sign In'}</Link>
                </div>
              )}
            </div>

            {/* Reviews List */}
            <div className="lg:col-span-2 space-y-4">
              {reviews.length === 0 ? (
                <div className={`card p-8 text-center ${dark ? 'bg-gray-800 border-gray-700' : ''}`}>
                  <HiStar className={`w-12 h-12 mx-auto mb-3 ${dark ? 'text-gray-600' : 'text-gray-300'}`} />
                  <p className={`${dark ? 'text-gray-400' : 'text-gray-500'}`}>{'No reviews yet. Be the first to review!'}</p>
                </div>
              ) : (
                reviews.map(review => (
                  <div key={review.id} className={`card p-5 ${dark ? 'bg-gray-800 border-gray-700' : ''}`}>
                    <div className="flex items-start justify-between mb-3">
                      <div>
                        <div className="flex items-center gap-3">
                          <div className="w-9 h-9 bg-gradient-to-br from-primary-400 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            {review.customer_name?.charAt(0)?.toUpperCase()}
                          </div>
                          <div>
                            <p className={`font-bold text-sm ${dark ? 'text-white' : 'text-gray-900'}`}>{review.customer_name}</p>
                            <StarRating rating={review.rating} size="w-4 h-4" />
                          </div>
                        </div>
                      </div>
                      <span className={`text-xs ${dark ? 'text-gray-500' : 'text-gray-400'}`}>{new Date(review.created_at).toLocaleDateString()}</span>
                    </div>
                    {review.comment && <p className={`text-sm leading-relaxed ${dark ? 'text-gray-400' : 'text-gray-600'}`}>{review.comment}</p>}
                  </div>
                ))
              )}
            </div>
          </div>
        </div>

        {/* Related Products */}
        {related.length > 0 && (
          <div className="mt-20">
            <div className="mb-10">
              <span className={`font-semibold text-sm uppercase tracking-[0.2em] ${dark ? 'text-primary-400' : 'text-primary-600'}`}>{'You may also like'}</span>
              <h2 className={`text-3xl font-heading font-bold mt-2 ${dark ? 'text-white' : 'text-gray-900'}`}>{'Related Products'}</h2>
              <div className="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4" />
            </div>
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
              {related.map(p => <ProductCard key={p.id} product={p} />)}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
