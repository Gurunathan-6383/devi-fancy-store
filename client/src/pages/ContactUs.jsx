import { useState } from 'react';
import { Link } from 'react-router-dom';
import { HiPhone, HiMail, HiLocationMarker, HiClock, HiChatAlt, HiPaperAirplane, HiCheck } from 'react-icons/hi';
import toast from 'react-hot-toast';
import { useTheme } from '../context/ThemeContext';

const contactInfo = [
  {
    icon: HiPhone,
    title: 'Call Us',
    detail: '+91 63838 11702',
    sub: 'Mon-Sat, 9AM - 8PM',
    gradient: 'from-pink-500 to-rose-500',
    bgLight: 'bg-pink-50',
    bgDark: 'bg-pink-900/20',
  },
  {
    icon: HiMail,
    title: 'Email Us',
    detail: 'contact@devifancystore.com',
    sub: 'We reply within 24 hours',
    gradient: 'from-purple-500 to-indigo-500',
    bgLight: 'bg-purple-50',
    bgDark: 'bg-purple-900/20',
  },
  {
    icon: HiLocationMarker,
    title: 'Visit Us',
    detail: 'Thiruthangal, Sivakasi',
    sub: 'Tamil Nadu, India',
    gradient: 'from-amber-500 to-orange-500',
    bgLight: 'bg-amber-50',
    bgDark: 'bg-amber-900/20',
  },
  {
    icon: HiClock,
    title: 'Business Hours',
    detail: 'Mon - Sat: 9AM - 8PM',
    sub: 'Sunday: 10AM - 6PM',
    gradient: 'from-emerald-500 to-teal-500',
    bgLight: 'bg-emerald-50',
    bgDark: 'bg-emerald-900/20',
  },
];

export default function ContactUs() {
  const { dark } = useTheme();
  const [form, setForm] = useState({ name: '', email: '', phone: '', subject: '', message: '' });
  const [sending, setSending] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!form.name.trim() || !form.email.trim() || !form.message.trim()) {
      toast.error('Please fill in all required fields');
      return;
    }
    setSending(true);
    setTimeout(() => {
      toast.success('Message sent! We will get back to you soon.');
      setForm({ name: '', email: '', phone: '', subject: '', message: '' });
      setSending(false);
    }, 1500);
  };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
      {/* Hero */}
      <div className="relative bg-gradient-to-br from-primary-600 via-primary-700 to-secondary-700 overflow-hidden">
        <div className="absolute inset-0 opacity-10">
          <div className="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl" />
          <div className="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl" />
        </div>
        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
          <Link to="/" className="text-white/60 hover:text-white text-sm mb-6 inline-flex items-center gap-1 transition-colors">
            ← Home
          </Link>
          <div className="flex flex-col md:flex-row items-center gap-10">
            <div className="flex-1">
              <h1 className="text-4xl md:text-5xl font-heading font-bold text-white mb-4">
                Let's <span className="text-yellow-300">Talk</span>
              </h1>
              <p className="text-white/80 text-lg leading-relaxed max-w-lg">
                Have a question, suggestion, or just want to say hello? We'd love to hear from you. Our team is here to help!
              </p>
              <div className="flex items-center gap-4 mt-8">
                <div className="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                  <div className="w-2 h-2 bg-green-400 rounded-full animate-pulse" />
                  <span className="text-white/90 text-sm font-medium">We typically reply within 2 hours</span>
                </div>
              </div>
            </div>
            <div className="hidden md:block flex-shrink-0">
              <div className="relative">
                <div className="w-64 h-64 bg-white/10 backdrop-blur-sm rounded-3xl rotate-6 absolute" />
                <div className="w-64 h-64 bg-white/5 backdrop-blur-sm rounded-3xl -rotate-3 absolute" />
                <div className="relative w-64 h-64 bg-gradient-to-br from-white/20 to-white/5 backdrop-blur-sm rounded-3xl flex items-center justify-center border border-white/20">
                  <div className="text-center">
                    <HiChatAlt className="w-16 h-16 text-white/80 mx-auto mb-3" />
                    <p className="text-white font-heading font-bold text-xl">Chat with Us</p>
                    <p className="text-white/60 text-sm mt-1">We're online now</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Contact Cards */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          {contactInfo.map((item, i) => (
            <div key={i} className={`rounded-2xl p-6 shadow-lg border transition-all hover:-translate-y-1 hover:shadow-xl ${dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'}`}>
              <div className={`w-14 h-14 rounded-xl bg-gradient-to-br ${item.gradient} flex items-center justify-center mb-4 shadow-lg`}>
                <item.icon className="w-7 h-7 text-white" />
              </div>
              <h3 className={`font-heading font-bold text-lg mb-1 ${dark ? 'text-white' : 'text-gray-900'}`}>{item.title}</h3>
              <p className={`font-medium text-sm ${dark ? 'text-gray-200' : 'text-gray-800'}`}>{item.detail}</p>
              <p className={`text-xs mt-1 ${dark ? 'text-gray-500' : 'text-gray-500'}`}>{item.sub}</p>
            </div>
          ))}
        </div>
      </div>

      {/* Form + Map Section */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="grid grid-cols-1 lg:grid-cols-5 gap-10">
          {/* Contact Form */}
          <div className="lg:col-span-3">
            <div className={`rounded-2xl p-8 shadow-lg border ${dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'}`}>
              <div className="flex items-center gap-3 mb-6">
                <div className="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                  <HiPaperAirplane className="w-5 h-5 text-white" />
                </div>
                <div>
                  <h2 className={`text-2xl font-heading font-bold ${dark ? 'text-white' : 'text-gray-900'}`}>Send a Message</h2>
                  <p className={`text-sm ${dark ? 'text-gray-400' : 'text-gray-500'}`}>We'll get back to you as soon as possible</p>
                </div>
              </div>

              <form onSubmit={handleSubmit} className="space-y-5">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                  <div>
                    <label className={`block text-sm font-medium mb-1.5 ${dark ? 'text-gray-300' : 'text-gray-700'}`}>Your Name *</label>
                    <input type="text" value={form.name} onChange={e => setForm({ ...form, name: e.target.value })}
                      className={`w-full px-4 py-3 rounded-xl border-2 outline-none transition-all focus:ring-2 focus:ring-primary-500 focus:border-primary-500 ${dark ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-500' : 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400'}`}
                      placeholder="John Doe" required />
                  </div>
                  <div>
                    <label className={`block text-sm font-medium mb-1.5 ${dark ? 'text-gray-300' : 'text-gray-700'}`}>Email Address *</label>
                    <input type="email" value={form.email} onChange={e => setForm({ ...form, email: e.target.value })}
                      className={`w-full px-4 py-3 rounded-xl border-2 outline-none transition-all focus:ring-2 focus:ring-primary-500 focus:border-primary-500 ${dark ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-500' : 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400'}`}
                      placeholder="you@example.com" required />
                  </div>
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                  <div>
                    <label className={`block text-sm font-medium mb-1.5 ${dark ? 'text-gray-300' : 'text-gray-700'}`}>Phone Number</label>
                    <input type="tel" value={form.phone} onChange={e => setForm({ ...form, phone: e.target.value })}
                      className={`w-full px-4 py-3 rounded-xl border-2 outline-none transition-all focus:ring-2 focus:ring-primary-500 focus:border-primary-500 ${dark ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-500' : 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400'}`}
                      placeholder="+91 98765 43210" />
                  </div>
                  <div>
                    <label className={`block text-sm font-medium mb-1.5 ${dark ? 'text-gray-300' : 'text-gray-700'}`}>Subject</label>
                    <select value={form.subject} onChange={e => setForm({ ...form, subject: e.target.value })}
                      className={`w-full px-4 py-3 rounded-xl border-2 outline-none transition-all focus:ring-2 focus:ring-primary-500 focus:border-primary-500 ${dark ? 'bg-gray-700 border-gray-600 text-white' : 'bg-gray-50 border-gray-200 text-gray-900'}`}>
                      <option value="">Select a subject</option>
                      <option value="order">Order Inquiry</option>
                      <option value="return">Return / Exchange</option>
                      <option value="product">Product Question</option>
                      <option value="feedback">Feedback</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                </div>
                <div>
                  <label className={`block text-sm font-medium mb-1.5 ${dark ? 'text-gray-300' : 'text-gray-700'}`}>Your Message *</label>
                  <textarea value={form.message} onChange={e => setForm({ ...form, message: e.target.value })} rows={5}
                    className={`w-full px-4 py-3 rounded-xl border-2 outline-none transition-all focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none ${dark ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-500' : 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400'}`}
                    placeholder="Tell us how we can help you..." required />
                </div>
                <button type="submit" disabled={sending}
                  className="w-full sm:w-auto bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3.5 px-10 rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-primary-500/25 flex items-center justify-center gap-2">
                  {sending ? (
                    <>
                      <div className="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent" />
                      Sending...
                    </>
                  ) : (
                    <>
                      <HiPaperAirplane className="w-5 h-5" />
                      Send Message
                    </>
                  )}
                </button>
              </form>
            </div>
          </div>

          {/* Side Info */}
          <div className="lg:col-span-2 space-y-6">
            {/* Map Image Placeholder */}
            <div className={`rounded-2xl overflow-hidden shadow-lg border ${dark ? 'border-gray-700' : 'border-gray-100'}`}>
              <div className="relative h-52 bg-gradient-to-br from-primary-100 to-secondary-100 dark:from-primary-900/30 dark:to-secondary-900/30">
                <img
                  src="https://images.unsplash.com/photo-1524661135-423995f22d0b?w=600&h=300&fit=crop"
                  alt="Map location"
                  className="w-full h-full object-cover opacity-80"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent" />
                <div className="absolute bottom-4 left-4 right-4">
                  <div className="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-xl px-4 py-3 flex items-center gap-3">
                    <div className="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                      <HiLocationMarker className="w-5 h-5 text-white" />
                    </div>
                    <div>
                      <p className={`font-bold text-sm ${dark ? 'text-white' : 'text-gray-900'}`}>Devi Fancy Store</p>
                      <p className={`text-xs ${dark ? 'text-gray-400' : 'text-gray-500'}`}>Thiruthangal, Sivakasi, Tamil Nadu</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* Quick Info Cards */}
            <div className={`rounded-2xl p-6 shadow-lg border ${dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'}`}>
              <h3 className={`font-heading font-bold text-lg mb-4 ${dark ? 'text-white' : 'text-gray-900'}`}>Why Contact Us?</h3>
              <div className="space-y-3">
                {[
                  { icon: '📦', text: 'Track your order status' },
                  { icon: '🔄', text: 'Easy returns & exchanges' },
                  { icon: '💬', text: 'Product recommendations' },
                  { icon: '🤝', text: 'Bulk order & wholesale inquiries' },
                  { icon: '🎁', text: 'Gift wrapping available' },
                ].map((item, i) => (
                  <div key={i} className="flex items-center gap-3">
                    <span className="text-xl">{item.icon}</span>
                    <span className={`text-sm ${dark ? 'text-gray-300' : 'text-gray-600'}`}>{item.text}</span>
                  </div>
                ))}
              </div>
            </div>

            {/* Trust Badge */}
            <div className="rounded-2xl p-6 bg-gradient-to-br from-primary-600 to-secondary-600 text-white shadow-lg">
              <div className="flex items-center gap-3 mb-3">
                <HiCheck className="w-8 h-8 text-yellow-300" />
                <h3 className="font-heading font-bold text-lg">100% Customer Satisfaction</h3>
              </div>
              <p className="text-white/80 text-sm leading-relaxed">
                Your satisfaction is our priority. We're committed to resolving any concerns quickly and ensuring you have the best shopping experience.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
