import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { HiArrowRight } from 'react-icons/hi';
import { categoryAPI } from '../services/api';
import { useTheme } from '../context/ThemeContext';

const categoryImages = {
  'bangles': 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=600&q=80',
  'earrings': 'https://images.unsplash.com/photo-1617038220319-276d3cfab638?w=600&q=80',
  'pottu': 'https://images.unsplash.com/photo-1603561591411-07134e71a2a9?w=600&q=80',
  'chains': 'https://images.unsplash.com/photo-1610694955371-d4a3e0ce4b52?w=600&q=80',
  'hair-clips': 'https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?w=600&q=80',
  'hair-pins': 'https://images.unsplash.com/photo-1611085583191-a3b181a88401?w=600&q=80',
  'anklets': 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=600&q=80',
  'bracelets': 'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=600&q=80',
  'cosmetics': 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&q=80',
  'gift-items': 'https://images.unsplash.com/photo-1513885535751-8b9238bd345a?w=600&q=80',
  'kids-accessories': 'https://images.unsplash.com/photo-1602751584552-8ba73aad10e1?w=600&q=80',
};

const gradients = [
  'from-rose-500 via-pink-500 to-fuchsia-500',
  'from-violet-500 via-purple-500 to-indigo-500',
  'from-amber-500 via-orange-500 to-red-500',
  'from-emerald-500 via-teal-500 to-cyan-500',
  'from-blue-500 via-indigo-500 to-violet-500',
  'from-fuchsia-500 via-pink-500 to-rose-500',
  'from-indigo-500 via-blue-500 to-sky-500',
  'from-red-500 via-rose-500 to-pink-500',
  'from-lime-500 via-green-500 to-emerald-500',
  'from-cyan-500 via-teal-500 to-emerald-500',
  'from-pink-500 via-rose-500 to-red-500',
];

const emojis = ['💎', '✨', '🔮', '⛓️', '💇', '📌', '🦶', '⭕', '💄', '🎁', '👶'];

export default function CategoriesPage() {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const { dark } = useTheme();

  useEffect(() => {
    categoryAPI.getActive()
      .then(res => setCategories(res.data.data))
      .catch(() => {})
      .finally(() => setLoading(false));
  }, []);

  const getBentoSize = (i) => {
    if (i === 0) return 'sm:col-span-2 sm:row-span-2';
    if (i === 1) return 'sm:col-span-1 sm:row-span-1';
    if (i === 2) return 'sm:col-span-1 sm:row-span-1';
    if (i === 3) return 'sm:col-span-1 sm:row-span-2';
    if (i === 4) return 'sm:col-span-1 sm:row-span-1';
    return 'sm:col-span-1 sm:row-span-1';
  };

  const getHeight = (i) => {
    if (i === 0) return 'h-72 sm:h-full';
    if (i === 3) return 'h-64 sm:h-full';
    return 'h-56 sm:h-full';
  };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      {/* Hero */}
      <div className="relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-br from-gray-900 via-primary-900 to-secondary-900" />
        <div className="absolute inset-0" style={{ backgroundImage: 'radial-gradient(circle at 20% 50%, rgba(224, 74, 111, 0.15) 0%, transparent 50%), radial-gradient(circle at 80% 50%, rgba(124, 58, 237, 0.15) 0%, transparent 50%)' }} />
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
          <div className="flex flex-col md:flex-row items-center gap-8">
            <div className="flex-1 text-center md:text-left">
              <div className="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-4">
                <span className="w-2 h-2 bg-green-400 rounded-full animate-pulse" />
                <span className="text-white/80 text-xs font-medium uppercase tracking-wider">{categories.length} Categories Available</span>
              </div>
              <h1 className="text-4xl md:text-6xl font-heading font-bold text-white mb-4 leading-tight">
                Find Your <span className="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-pink-400 to-primary-400">Perfect Style</span>
              </h1>
              <p className="text-white/60 text-lg max-w-md leading-relaxed">
                Curated collections for every taste, every occasion, every you.
              </p>
            </div>
            <div className="hidden lg:flex items-center gap-3">
              {categories.slice(0, 4).map((cat, i) => {
                const imgUrl = cat.image || categoryImages[cat.slug];
                return (
                  <div key={cat.id} className="relative w-16 h-16 rounded-2xl overflow-hidden border-2 border-white/20 shadow-lg" style={{ transform: `rotate(${(i - 1.5) * 5}deg)` }}>
                    {imgUrl ? (
                      <img src={imgUrl} alt="" className="w-full h-full object-cover" />
                    ) : (
                      <div className={`w-full h-full bg-gradient-to-br ${gradients[i % gradients.length]} flex items-center justify-center`}>
                        <span className="text-2xl">{emojis[i % emojis.length]}</span>
                      </div>
                    )}
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      </div>

      {/* Bento Grid */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10 pb-16">
        {loading ? (
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 auto-rows-[220px]">
            {Array.from({ length: 6 }).map((_, i) => (
              <div key={i} className={`rounded-3xl animate-pulse ${dark ? 'bg-gray-800' : 'bg-gray-200'} ${i === 0 ? 'sm:col-span-2 sm:row-span-2' : ''}`} />
            ))}
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 auto-rows-[200px]">
            {categories.map((cat, i) => {
              const hasImage = !!cat.image;
              const gradient = gradients[i % gradients.length];
              const emoji = emojis[i % emojis.length];
              const imgUrl = hasImage ? cat.image : categoryImages[cat.slug] || null;
              const bentoClass = getBentoSize(i);
              const heightClass = getHeight(i);

              return (
                <Link
                  key={cat.id}
                  to={`/categories/${cat.slug}`}
                  className={`group relative rounded-3xl overflow-hidden ${bentoClass} ${heightClass} shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-1`}
                >
                  {/* Background */}
                  {imgUrl ? (
                    <img
                      src={imgUrl}
                      alt={cat.name}
                      className="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                    />
                  ) : (
                    <div className={`absolute inset-0 bg-gradient-to-br ${gradient}`}>
                      <div className="absolute inset-0 flex items-center justify-center">
                        <span className="text-7xl opacity-30 group-hover:opacity-50 group-hover:scale-125 transition-all duration-500">{emoji}</span>
                      </div>
                    </div>
                  )}

                  {/* Dark overlay */}
                  <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity" />

                  {/* Decorative corner */}
                  <div className="absolute top-0 right-0 w-24 h-24 bg-white/5 backdrop-blur-sm rounded-bl-[3rem] flex items-center justify-center">
                    <span className="text-3xl opacity-60 group-hover:opacity-100 group-hover:rotate-12 transition-all duration-300">{emoji}</span>
                  </div>

                  {/* Content */}
                  <div className="absolute bottom-0 left-0 right-0 p-5 sm:p-6">
                    <div className="transform group-hover:-translate-y-1 transition-transform duration-300">
                      <h3 className="font-heading font-bold text-white text-xl sm:text-2xl mb-1">
                        {cat.name}
                      </h3>
                      <div className="flex items-center gap-2 text-white/60 group-hover:text-yellow-300 text-sm font-medium transition-colors">
                        <span>Explore</span>
                        <HiArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                      </div>
                    </div>
                  </div>

                  {/* Hover ring */}
                  <div className="absolute inset-0 rounded-3xl ring-0 group-hover:ring-2 ring-white/30 ring-inset transition-all duration-300" />
                </Link>
              );
            })}
          </div>
        )}

        {!loading && categories.length === 0 && (
          <div className="text-center py-20">
            <p className="text-6xl mb-4">📂</p>
            <h2 className={`text-2xl font-heading font-bold mb-2 ${dark ? 'text-white' : 'text-gray-900'}`}>No Categories Yet</h2>
            <p className={`${dark ? 'text-gray-400' : 'text-gray-500'}`}>Categories will appear here once added by the admin.</p>
          </div>
        )}
      </div>
    </div>
  );
}
