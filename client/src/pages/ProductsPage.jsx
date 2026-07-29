import { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { productAPI, categoryAPI } from '../services/api';
import ProductCard from '../components/ProductCard';
import { ProductCardSkeleton } from '../components/Skeleton';

export default function ProductsPage() {
  const { slug } = useParams();
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState(slug || '');
  const [loading, setLoading] = useState(true);
  const [sort, setSort] = useState('newest');

  useEffect(() => {
    categoryAPI.getActive().then(res => setCategories(res.data.data)).catch(() => {});
  }, []);

  useEffect(() => {
    setSelectedCategory(slug || '');
  }, [slug]);

  useEffect(() => {
    setLoading(true);
    const params = {};
    if (selectedCategory) params.category_slug = selectedCategory;
    if (sort) params.sort = sort;
    productAPI.getActive(params)
      .then(res => setProducts(res.data.data))
      .catch(() => {})
      .finally(() => setLoading(false));
  }, [selectedCategory, sort]);

  return (
    <div className="py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-8">
          <span className="text-primary-600 font-semibold text-sm uppercase tracking-[0.2em]">Our Collection</span>
          <h1 className="text-4xl md:text-5xl font-heading font-bold text-gray-900 dark:text-white mt-2">
            {slug ? categories.find(c => c.slug === slug)?.name || 'Products' : 'All Products'}
          </h1>
          <div className="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4" />
          <p className="text-gray-500 mt-4 max-w-md">Discover our complete collection</p>
        </div>

        <div className="flex flex-wrap items-center justify-between gap-4 mb-8">
          <div className="flex flex-wrap gap-2">
            <button onClick={() => setSelectedCategory('')} className={`px-5 py-2.5 rounded-full text-sm font-semibold transition-all ${!selectedCategory ? 'bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg shadow-primary-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'}`}>
              All
            </button>
            {categories.map(cat => (
              <button key={cat.id} onClick={() => setSelectedCategory(cat.slug)} className={`px-5 py-2.5 rounded-full text-sm font-semibold transition-all ${selectedCategory === cat.slug ? 'bg-gradient-to-r from-primary-600 to-primary-700 text-white shadow-lg shadow-primary-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'}`}>
                {cat.name}
              </button>
            ))}
          </div>
          <select value={sort} onChange={e => setSort(e.target.value)} className="input-field w-auto">
            <option value="newest">Newest First</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
            <option value="name">Name: A-Z</option>
          </select>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
          {loading
            ? Array.from({ length: 12 }).map((_, i) => <ProductCardSkeleton key={i} />)
            : products.map(p => <ProductCard key={p.id} product={p} />)
          }
          {!loading && products.length === 0 && (
            <div className="col-span-full text-center py-16 text-gray-500">
              <p className="text-lg mb-2">No products found.</p>
              <p>Try adjusting your filters or check back later.</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
