import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { catalogueAPI } from '../services/api';
import ProductCard from '../components/ProductCard';
import { ProductCardSkeleton } from '../components/Skeleton';

export default function CataloguesPage() {
  const { slug } = useParams();
  const [catalogues, setCatalogues] = useState([]);
  const [currentCatalogue, setCurrentCatalogue] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (slug) {
      setLoading(true);
      catalogueAPI.getBySlug(slug)
        .then(res => setCurrentCatalogue(res.data.data))
        .catch(() => setCurrentCatalogue(null))
        .finally(() => setLoading(false));
    } else {
      catalogueAPI.getPublished()
        .then(res => setCatalogues(res.data.data))
        .catch(() => {})
        .finally(() => setLoading(false));
    }
  }, [slug]);

  if (slug && currentCatalogue) {
    return (
      <div className="py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Link to="/catalogues" className="text-primary-600 hover:text-primary-700 font-medium mb-4 inline-block">&larr; Back to Catalogues</Link>
          <div className="mb-8">
            {currentCatalogue.image && (
              <img src={currentCatalogue.image} alt={currentCatalogue.title} className="w-full h-72 object-cover rounded-2xl mb-6 shadow-xl" />
            )}
            <span className="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Collection</span>
            <h1 className="text-4xl font-heading font-bold text-gray-900 dark:text-white mt-2">{currentCatalogue.title}</h1>
            <div className="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4" />
            {currentCatalogue.description && <p className="text-gray-500 mt-4 text-lg">{currentCatalogue.description}</p>}
          </div>
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            {currentCatalogue.products?.map(p => <ProductCard key={p.id} product={p} />)}
            {(!currentCatalogue.products || currentCatalogue.products.length === 0) && (
              <div className="col-span-full text-center py-16 text-gray-500">
                <p className="text-lg">No products in this catalogue yet.</p>
                <Link to="/products" className="text-primary-600 hover:text-primary-700 font-medium mt-2 inline-block">Browse all products</Link>
              </div>
            )}
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-10">
          <span className="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Curated for you</span>
          <h1 className="text-4xl md:text-5xl font-heading font-bold text-gray-900 dark:text-white mt-2">Catalogues</h1>
          <div className="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4" />
          <p className="text-gray-500 mt-4">Explore our curated collections</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {loading
            ? Array.from({ length: 6 }).map((_, i) => (
                <div key={i} className="card animate-pulse">
                  <div className="h-48 bg-gray-200 dark:bg-gray-600 rounded-t-xl" />
                  <div className="p-5 space-y-3">
                    <div className="h-6 bg-gray-200 dark:bg-gray-600 rounded w-2/3" />
                    <div className="h-4 bg-gray-200 dark:bg-gray-600 rounded w-full" />
                  </div>
                </div>
              ))
            : catalogues.map(cat => (
                <Link key={cat.id} to={`/catalogues/${cat.slug}`} className="card group overflow-hidden">
                  {cat.image ? (
                    <div className="h-48 overflow-hidden">
                      <img src={cat.image} alt={cat.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    </div>
                  ) : (
                    <div className="h-48 bg-gradient-to-br from-primary-100 to-secondary-100 flex items-center justify-center">
                      <span className="text-4xl font-heading font-bold text-primary-600">{cat.title.charAt(0)}</span>
                    </div>
                  )}
                  <div className="p-5">
                    <h3 className="text-xl font-heading font-bold text-gray-900 dark:text-white">{cat.title}</h3>
                    {cat.description && <p className="text-gray-500 mt-1 text-sm line-clamp-2">{cat.description}</p>}
                  </div>
                </Link>
              ))
          }
          {!loading && catalogues.length === 0 && (
            <div className="col-span-full text-center py-16 text-gray-500">
              <p className="text-lg">No catalogues available yet.</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
