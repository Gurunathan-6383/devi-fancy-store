import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { announcementAPI } from '../services/api';
import api from '../services/api';

const PAGE_MAP = {
  'contact-us': { icon: '📞', accent: 'from-pink-500 to-rose-500' },
  'about-us': { icon: '💡', accent: 'from-purple-500 to-indigo-500' },
  'faq': { icon: '❓', accent: 'from-amber-500 to-orange-500' },
  'privacy-policy': { icon: '🔒', accent: 'from-emerald-500 to-teal-500' },
  'terms-and-conditions': { icon: '📜', accent: 'from-blue-500 to-cyan-500' },
  'return-policy': { icon: '🔄', accent: 'from-red-500 to-pink-500' },
  'shipping-policy': { icon: '🚚', accent: 'from-violet-500 to-purple-500' },
};

export default function ContentPage() {
  const { slug } = useParams();
  const [page, setPage] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    setLoading(true);
    setError(null);
    api.get(`/content-pages/public/${slug}`)
      .then(res => {
        if (res.data.data.is_active) setPage(res.data.data);
        else setError('Page not available');
      })
      .catch(() => setError('Page not found'))
      .finally(() => setLoading(false));
  }, [slug]);

  const meta = PAGE_MAP[slug] || { icon: '📄', accent: 'from-gray-500 to-gray-600' };

  if (loading) return (
    <div className="flex justify-center py-20">
      <div className="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent" />
    </div>
  );

  if (error) return (
    <div className="max-w-4xl mx-auto px-4 py-20 text-center">
      <p className="text-6xl mb-4">📭</p>
      <h2 className="text-2xl font-heading font-bold text-gray-900 dark:text-white mb-2">{error}</h2>
      <Link to="/" className="text-primary-600 hover:text-primary-700 font-medium mt-4 inline-block">← Back to Home</Link>
    </div>
  );

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      <div className={`bg-gradient-to-r ${meta.accent} py-12 md:py-16`}>
        <div className="max-w-4xl mx-auto px-4 sm:px-6">
          <Link to="/" className="text-white/70 hover:text-white text-sm mb-4 inline-block transition-colors">← Home</Link>
          <h1 className="text-3xl md:text-4xl font-heading font-bold text-white flex items-center gap-3">
            <span className="text-4xl">{meta.icon}</span>
            {page.title}
          </h1>
          {page.meta_description && (
            <p className="text-white/80 mt-2 text-sm">{page.meta_description}</p>
          )}
        </div>
      </div>

      <div className="max-w-4xl mx-auto px-4 sm:px-6 py-10 md:py-14">
        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 md:p-10">
          <div
            className="prose prose-lg max-w-none prose-headings:font-heading prose-headings:text-gray-900 dark:prose-headings:text-white prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-li:text-gray-700 dark:prose-li:text-gray-300 prose-a:text-primary-600 hover:prose-a:text-primary-700 prose-strong:text-gray-900 dark:prose-strong:text-white"
            dangerouslySetInnerHTML={{ __html: page.content }}
          />
        </div>

        <div className="mt-8 text-center">
          <p className="text-gray-500 dark:text-gray-400 text-sm">
            Last updated: {new Date(page.updated_at).toLocaleDateString('en-IN', { year: 'numeric', month: 'long', day: 'numeric' })}
          </p>
        </div>
      </div>
    </div>
  );
}
