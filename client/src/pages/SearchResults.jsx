import { useState, useEffect } from 'react';
import { useSearchParams, Link } from 'react-router-dom';
import { productAPI } from '../services/api';
import ProductCard from '../components/ProductCard';
import { ProductCardSkeleton } from '../components/Skeleton';

export default function SearchResults() {
  const [searchParams] = useSearchParams();
  const query = searchParams.get('q') || '';
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [sort, setSort] = useState('newest');

  useEffect(() => {
    if (!query) { setProducts([]); setLoading(false); return; }
    setLoading(true);
    productAPI.search({ q: query, sort })
      .then(res => setProducts(res.data.data))
      .catch(() => {})
      .finally(() => setLoading(false));
  }, [query, sort]);

  return (
    <div className="py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-8">
          <span className="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Discover</span>
          <h1 className="text-3xl md:text-4xl font-heading font-bold text-gray-900 dark:text-white mt-2">
            Search Results
          </h1>
          <div className="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4" />
          <p className="text-gray-500 mt-4">
            {query ? `Showing results for "${query}"` : 'Enter a search term to find products'}
          </p>
        </div>

        {products.length > 0 && (
          <div className="flex justify-end mb-6">
            <select value={sort} onChange={e => setSort(e.target.value)} className="input-field w-auto">
              <option value="newest">Newest First</option>
              <option value="price_low">Price: Low to High</option>
              <option value="price_high">Price: High to Low</option>
              <option value="name">Name: A-Z</option>
            </select>
          </div>
        )}

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
          {loading
            ? Array.from({ length: 8 }).map((_, i) => <ProductCardSkeleton key={i} />)
            : products.map(p => <ProductCard key={p.id} product={p} />)
          }
          {!loading && products.length === 0 && query && (
            <div className="col-span-full text-center py-16">
              <p className="text-lg text-gray-500 mb-2">No products found for "{query}"</p>
              <p className="text-gray-400 mb-6">Try different keywords or browse our categories.</p>
              <Link to="/products" className="btn-primary inline-block">Browse All Products</Link>
            </div>
          )}
          {!query && (
            <div className="col-span-full text-center py-16">
              <p className="text-gray-500 mb-6">Use the search bar above to find products.</p>
              <Link to="/products" className="btn-primary inline-block">Browse All Products</Link>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
