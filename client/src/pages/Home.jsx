import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { HiArrowRight, HiSparkles, HiShieldCheck, HiTruck, HiCurrencyRupee, HiGift } from 'react-icons/hi';
import { productAPI, categoryAPI, catalogueAPI } from '../services/api';
import ProductCard from '../components/ProductCard';
import { ProductCardSkeleton } from '../components/Skeleton';
const features = [
  { icon: HiTruck, title: 'Free Shipping', desc: 'On orders above ₹500' },
  { icon: HiShieldCheck, title: 'Secure Payment', desc: '100% secure checkout' },
  { icon: HiCurrencyRupee, title: 'Best Prices', desc: 'Guaranteed lowest prices' },
  { icon: HiGift, title: 'Gift Wrapping', desc: 'Beautiful packaging' },
];
const categoryGradients = [
  'from-rose-400 to-pink-500', 'from-purple-400 to-violet-500',
  'from-amber-400 to-orange-500', 'from-emerald-400 to-teal-500',
  'from-blue-400 to-cyan-500', 'from-fuchsia-400 to-pink-500',
  'from-indigo-400 to-blue-500', 'from-red-400 to-rose-500',
  'from-lime-400 to-green-500', 'from-cyan-400 to-sky-500',
  'from-rose-400 to-red-500',
];

export default function Home() {
  const [featured, setFeatured] = useState([]);
  const [categories, setCategories] = useState([]);
  const [catalogues, setCatalogues] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    Promise.all([
      productAPI.getFeatured(8).then(r => r.data.data).catch(() => []),
      categoryAPI.getActive().then(r => r.data.data).catch(() => []),
      catalogueAPI.getPublished().then(r => r.data.data).catch(() => [])
    ]).then(([feat, cats, catalogs]) => {
      setFeatured(feat);
      setCategories(cats);
      setCatalogues(catalogs);
    }).finally(() => setLoading(false));
  }, []);

  return (
    <div>
      {/* Hero */}
      <section className="relative min-h-[92vh] flex items-center overflow-hidden">
        <div className="absolute inset-0">
          <img src="https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=1920&q=80" alt="" className="w-full h-full object-cover" />
          <div className="absolute inset-0 bg-gradient-to-r from-primary-900/95 via-primary-800/80 to-secondary-900/85" />
          <div className="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/20" />
        </div>

        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
          <div className="max-w-2xl animate-slide-up">
            <div className="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-5 py-2 mb-8">
              <HiSparkles className="w-4 h-4 text-accent-400" />
              <span className="text-white/90 text-sm font-medium tracking-wide">New Collection 2026</span>
            </div>
            <h1 className="text-5xl md:text-7xl lg:text-8xl font-heading font-bold text-white mb-6 leading-[1.1]">
              Discover Your
              <span className="block text-transparent bg-clip-text bg-gradient-to-r from-accent-300 via-accent-400 to-accent-500">
                Style
              </span>
            </h1>
            <p className="text-lg md:text-xl text-white/75 mb-10 leading-relaxed max-w-lg font-light">
              Explore our exclusive collection of beautiful accessories, cosmetics, and gift items crafted just for you.
            </p>
            <div className="flex flex-wrap gap-4">
              <Link to="/catalogues" className="group bg-gradient-to-r from-accent-500 to-accent-600 hover:from-accent-600 hover:to-accent-700 text-white font-bold py-4 px-10 rounded-2xl transition-all flex items-center space-x-3 shadow-xl shadow-accent-500/30 hover:shadow-accent-500/50 hover:-translate-y-0.5">
                <span className="text-lg">Explore Catalogues</span>
                <HiArrowRight className="w-5 h-5 group-hover:translate-x-1 transition-transform" />
              </Link>
              <Link to="/products" className="bg-white/10 hover:bg-white/20 text-white font-semibold py-4 px-10 rounded-2xl transition-all backdrop-blur-md border border-white/20 hover:border-white/40 hover:-translate-y-0.5">
                View All Products
              </Link>
            </div>
          </div>
        </div>

        <div className="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-float">
          <span className="text-white/50 text-xs tracking-widest uppercase">Scroll</span>
          <div className="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center pt-2">
            <div className="w-1.5 h-3 bg-white/50 rounded-full animate-pulse" />
          </div>
        </div>
      </section>

      {/* Features Strip */}
      <section className="bg-white border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100">
            {features.map((f, i) => (
              <div key={i} className="py-6 md:py-8 px-4 md:px-6 text-center group hover:bg-gray-50/50 transition-colors">
                <f.icon className="w-8 h-8 text-primary-600 mx-auto mb-2 group-hover:scale-110 transition-transform" />
                <h4 className="font-bold text-gray-900 text-sm">{f.title}</h4>
                <p className="text-gray-500 text-xs mt-0.5">{f.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Catalogues */}
      {catalogues.length > 0 && (
        <section className="py-20 bg-mesh relative">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-14">
              <span className="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Curated for you</span>
              <h2 className="text-3xl md:text-5xl font-heading font-bold text-gray-900 mt-3">Our Catalogues</h2>
              <div className="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mx-auto mt-5" />
            </div>
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
              {catalogues.map((cat, i) => (
                <Link key={cat.id} to={`/catalogues/${cat.slug}`} className="card card-hover group relative">
                  <div className="relative h-64 overflow-hidden">
                    {cat.image ? (
                      <img src={cat.image} alt={cat.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                    ) : (
                      <div className={`w-full h-full bg-gradient-to-br ${categoryGradients[i % categoryGradients.length]} flex items-center justify-center`}>
                        <span className="text-7xl font-heading font-bold text-white/90">{cat.title.charAt(0)}</span>
                      </div>
                    )}
                    <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent" />
                    <div className="absolute bottom-0 left-0 right-0 p-6">
                      <h3 className="text-2xl font-heading font-bold text-white mb-1">{cat.title}</h3>
                      {cat.description && <p className="text-white/70 text-sm line-clamp-2">{cat.description}</p>}
                      <div className="mt-3 inline-flex items-center gap-2 text-accent-400 font-semibold text-sm">
                        View Collection <HiArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                      </div>
                    </div>
                  </div>
                </Link>
              ))}
            </div>
          </div>
        </section>
      )}

      {/* Featured Products */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-end justify-between mb-12">
            <div>
              <span className="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Handpicked</span>
              <h2 className="text-3xl md:text-5xl font-heading font-bold text-gray-900 mt-2">Featured Products</h2>
              <div className="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4" />
            </div>
            <Link to="/products" className="hidden md:inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-semibold transition-colors group">
              View All
              <HiArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
            </Link>
          </div>
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            {loading
              ? Array.from({ length: 8 }).map((_, i) => <ProductCardSkeleton key={i} />)
              : featured.map(p => <ProductCard key={p.id} product={p} />)
            }
          </div>
          <div className="text-center mt-10 md:hidden">
            <Link to="/products" className="btn-primary inline-flex items-center gap-2">
              View All Products <HiArrowRight className="w-4 h-4" />
            </Link>
          </div>
        </div>
      </section>

      {/* Categories */}
      <section className="py-20 bg-mesh relative">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-14">
            <span className="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Browse by</span>
            <h2 className="text-3xl md:text-5xl font-heading font-bold text-gray-900 mt-3">Shop by Category</h2>
            <div className="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mx-auto mt-5" />
          </div>
          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-5">
            {categories.map((cat, i) => (
              <Link key={cat.id} to={`/categories/${cat.slug}`} className="card card-hover group text-center p-6 bg-white">
                <div className={`w-20 h-20 mx-auto mb-4 bg-gradient-to-br ${categoryGradients[i % categoryGradients.length]} rounded-2xl flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-lg`}>
                  <span className="text-3xl font-heading font-bold text-white">{cat.name.charAt(0)}</span>
                </div>
                <h3 className="text-sm font-bold text-gray-900 group-hover:text-primary-600 transition-colors">{cat.name}</h3>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="relative py-28 overflow-hidden">
        <div className="absolute inset-0">
          <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=1920&q=80" alt="" className="w-full h-full object-cover" />
          <div className="absolute inset-0 bg-gradient-to-r from-primary-900/95 to-secondary-900/95" />
        </div>
        <div className="relative max-w-4xl mx-auto px-4 text-center animate-slide-up">
          <HiSparkles className="w-12 h-12 text-accent-400 mx-auto mb-6 animate-float" />
          <h2 className="text-4xl md:text-6xl font-heading font-bold text-white mb-6">
            Quality & Elegance
          </h2>
          <p className="text-lg text-white/75 mb-10 max-w-2xl mx-auto leading-relaxed font-light">
            Every piece is carefully curated to bring you the finest accessories and gifts. Add a touch of sparkle to your everyday look.
          </p>
          <Link to="/categories" className="inline-flex items-center gap-3 bg-gradient-to-r from-accent-500 to-accent-600 hover:from-accent-600 hover:to-accent-700 text-white font-bold py-5 px-12 rounded-2xl transition-all shadow-xl shadow-accent-500/30 hover:shadow-accent-500/50 hover:-translate-y-0.5 text-lg">
            Start Shopping
            <HiArrowRight className="w-6 h-6" />
          </Link>
        </div>
      </section>
    </div>
  );
}
