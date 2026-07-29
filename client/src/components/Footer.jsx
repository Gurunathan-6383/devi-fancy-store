import { Link } from 'react-router-dom';
import Logo from './Logo';
import { useTheme } from '../context/ThemeContext';

const footerLinks = [
  { label: 'Contact Us', slug: 'contact-us' },
  { label: 'About Us', slug: 'about-us' },
  { label: 'FAQ', slug: 'faq' },
  { label: 'Privacy Policy', slug: 'privacy-policy' },
  { label: 'Terms & Conditions', slug: 'terms-and-conditions' },
  { label: 'Return Policy', slug: 'return-policy' },
  { label: 'Shipping Policy', slug: 'shipping-policy' },
];

export default function Footer() {
  const currentYear = new Date().getFullYear();
  const { dark } = useTheme();

  return (
    <footer className={`relative overflow-hidden ${dark ? 'bg-gray-950 text-gray-400' : 'bg-gray-900 text-gray-300'}`}>
      <div className="absolute inset-0 bg-gradient-to-br from-primary-900/20 via-transparent to-secondary-900/20" />
      <div className="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-primary-500/50 to-transparent" />

      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-10">
          <div>
            <Logo size="md" />
            <p className="text-gray-400 leading-relaxed mt-4 text-sm">
              Your one-stop destination for beautiful accessories, cosmetics, and gift items. Discover elegance with every purchase.
            </p>
          </div>
          <div>
            <h4 className="text-lg font-heading font-semibold text-white mb-4">Quick Links</h4>
            <div className="space-y-2">
              {['Home', 'Categories', 'Catalogues', 'Products'].map(label => (
                <Link key={label} to={label === 'Home' ? '/' : `/${label.toLowerCase()}`} className="block text-sm text-gray-400 hover:text-primary-400 transition-colors">
                  {label}
                </Link>
              ))}
            </div>
          </div>
          <div>
            <h4 className="text-lg font-heading font-semibold text-white mb-4">Policies</h4>
            <div className="space-y-2">
              {footerLinks.map(link => (
                <Link key={link.slug} to={`/page/${link.slug}`} className="block text-sm text-gray-400 hover:text-primary-400 transition-colors">
                  {link.label}
                </Link>
              ))}
            </div>
          </div>
          <div>
            <h4 className="text-lg font-heading font-semibold text-white mb-4">Contact</h4>
            <div className="space-y-2 text-sm text-gray-400">
              <p>Phone: +91 63838 11702</p>
              <p>Email: contact@devifancystore.com</p>
              <p>Address: Thiruthangal, Sivakasi</p>
            </div>
          </div>
        </div>
        <div className="border-t border-gray-800 mt-10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
          <p className="text-sm text-gray-500">&copy; {currentYear} Devi Fancy Store. All rights reserved.</p>
          <div className="flex items-center gap-1 text-sm text-gray-500">
            Made with <span className="text-primary-500">&hearts;</span> for Devi Fancy Store
          </div>
        </div>
      </div>
    </footer>
  );
}
