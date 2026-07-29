<?php
$dark = $dark ?? false;
$baseUrl = rtrim(env('APP_URL', ''), '/');
?>
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Hero -->
    <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-secondary-700 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
            <a href="<?= $baseUrl ?>/" class="text-white/60 hover:text-white text-sm mb-6 inline-flex items-center gap-1 transition-colors">&larr; Home</a>
            <div class="flex flex-col md:flex-row items-center gap-10">
                <div class="flex-1">
                    <h1 class="text-4xl md:text-5xl font-heading font-bold text-white mb-4">Let's <span class="text-yellow-300">Talk</span></h1>
                    <p class="text-white/80 text-lg leading-relaxed max-w-lg">Have a question, suggestion, or just want to say hello? We'd love to hear from you. Our team is here to help!</p>
                    <div class="flex items-center gap-4 mt-8">
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                            <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                            <span class="text-white/90 text-sm font-medium">We typically reply within 2 hours</span>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block flex-shrink-0">
                    <div class="relative">
                        <div class="w-64 h-64 bg-white/10 backdrop-blur-sm rounded-3xl rotate-6 absolute"></div>
                        <div class="w-64 h-64 bg-white/5 backdrop-blur-sm rounded-3xl -rotate-3 absolute"></div>
                        <div class="relative w-64 h-64 bg-gradient-to-br from-white/20 to-white/5 backdrop-blur-sm rounded-3xl flex items-center justify-center border border-white/20">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-white/80 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                <p class="text-white font-heading font-bold text-xl">Chat with Us</p>
                                <p class="text-white/60 text-sm mt-1">We're online now</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Call Us -->
            <div class="rounded-2xl p-6 shadow-lg border transition-all hover:-translate-y-1 hover:shadow-xl <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-lg mb-1 <?= $dark ? 'text-white' : 'text-gray-900' ?>">Call Us</h3>
                <p class="font-medium text-sm <?= $dark ? 'text-gray-200' : 'text-gray-800' ?>">+91 63838 11702</p>
                <p class="text-xs mt-1 <?= $dark ? 'text-gray-500' : 'text-gray-500' ?>">Mon-Sat, 9AM - 8PM</p>
            </div>
            <!-- Email Us -->
            <div class="rounded-2xl p-6 shadow-lg border transition-all hover:-translate-y-1 hover:shadow-xl <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-lg mb-1 <?= $dark ? 'text-white' : 'text-gray-900' ?>">Email Us</h3>
                <p class="font-medium text-sm <?= $dark ? 'text-gray-200' : 'text-gray-800' ?>">contact@devifancystore.com</p>
                <p class="text-xs mt-1 <?= $dark ? 'text-gray-500' : 'text-gray-500' ?>">We reply within 24 hours</p>
            </div>
            <!-- Visit Us -->
            <div class="rounded-2xl p-6 shadow-lg border transition-all hover:-translate-y-1 hover:shadow-xl <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-lg mb-1 <?= $dark ? 'text-white' : 'text-gray-900' ?>">Visit Us</h3>
                <p class="font-medium text-sm <?= $dark ? 'text-gray-200' : 'text-gray-800' ?>">Thiruthangal, Sivakasi</p>
                <p class="text-xs mt-1 <?= $dark ? 'text-gray-500' : 'text-gray-500' ?>">Tamil Nadu, India</p>
            </div>
            <!-- Business Hours -->
            <div class="rounded-2xl p-6 shadow-lg border transition-all hover:-translate-y-1 hover:shadow-xl <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-lg mb-1 <?= $dark ? 'text-white' : 'text-gray-900' ?>">Business Hours</h3>
                <p class="font-medium text-sm <?= $dark ? 'text-gray-200' : 'text-gray-800' ?>">Mon - Sat: 9AM - 8PM</p>
                <p class="text-xs mt-1 <?= $dark ? 'text-gray-500' : 'text-gray-500' ?>">Sunday: 10AM - 6PM</p>
            </div>
        </div>
    </div>

    <!-- Form + Map Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
            <!-- Contact Form -->
            <div class="lg:col-span-3">
                <div class="rounded-2xl p-8 shadow-lg border <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-heading font-bold <?= $dark ? 'text-white' : 'text-gray-900' ?>">Send a Message</h2>
                            <p class="text-sm <?= $dark ? 'text-gray-400' : 'text-gray-500' ?>">We'll get back to you as soon as possible</p>
                        </div>
                    </div>
                    <form id="contact-form" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium mb-1.5 <?= $dark ? 'text-gray-300' : 'text-gray-700' ?>">Your Name *</label>
                                <input type="text" id="contact-name" class="w-full px-4 py-3 rounded-xl border-2 outline-none transition-all focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?= $dark ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-500' : 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400' ?>" placeholder="John Doe" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1.5 <?= $dark ? 'text-gray-300' : 'text-gray-700' ?>">Email Address *</label>
                                <input type="email" id="contact-email" class="w-full px-4 py-3 rounded-xl border-2 outline-none transition-all focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?= $dark ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-500' : 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400' ?>" placeholder="you@example.com" required />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium mb-1.5 <?= $dark ? 'text-gray-300' : 'text-gray-700' ?>">Phone Number</label>
                                <input type="tel" id="contact-phone" class="w-full px-4 py-3 rounded-xl border-2 outline-none transition-all focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?= $dark ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-500' : 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400' ?>" placeholder="+91 98765 43210" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1.5 <?= $dark ? 'text-gray-300' : 'text-gray-700' ?>">Subject</label>
                                <select id="contact-subject" class="w-full px-4 py-3 rounded-xl border-2 outline-none transition-all focus:ring-2 focus:ring-primary-500 focus:border-primary-500 <?= $dark ? 'bg-gray-700 border-gray-600 text-white' : 'bg-gray-50 border-gray-200 text-gray-900' ?>">
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
                            <label class="block text-sm font-medium mb-1.5 <?= $dark ? 'text-gray-300' : 'text-gray-700' ?>">Your Message *</label>
                            <textarea id="contact-message" rows="5" class="w-full px-4 py-3 rounded-xl border-2 outline-none transition-all focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none <?= $dark ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-500' : 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400' ?>" placeholder="Tell us how we can help you..." required></textarea>
                        </div>
                        <button type="submit" id="contact-submit-btn" class="w-full sm:w-auto bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold py-3.5 px-10 rounded-xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-primary-500/25 flex items-center justify-center gap-2">
                            <span id="contact-submit-text">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                Send Message
                            </span>
                            <div id="contact-submit-spinner" class="hidden animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Side Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Map Image Placeholder -->
                <div class="rounded-2xl overflow-hidden shadow-lg border <?= $dark ? 'border-gray-700' : 'border-gray-100' ?>">
                    <div class="relative h-52 bg-gradient-to-br from-primary-100 to-secondary-100 dark:from-primary-900/30 dark:to-secondary-900/30">
                        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?w=600&h=300&fit=crop" alt="Map location" class="w-full h-full object-cover opacity-80" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-xl px-4 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-sm <?= $dark ? 'text-white' : 'text-gray-900' ?>">Devi Fancy Store</p>
                                    <p class="text-xs <?= $dark ? 'text-gray-400' : 'text-gray-500' ?>">Thiruthangal, Sivakasi, Tamil Nadu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Info Cards -->
                <div class="rounded-2xl p-6 shadow-lg border <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100' ?>">
                    <h3 class="font-heading font-bold text-lg mb-4 <?= $dark ? 'text-white' : 'text-gray-900' ?>">Why Contact Us?</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3"><span class="text-xl">📦</span><span class="text-sm <?= $dark ? 'text-gray-300' : 'text-gray-600' ?>">Track your order status</span></div>
                        <div class="flex items-center gap-3"><span class="text-xl">🔄</span><span class="text-sm <?= $dark ? 'text-gray-300' : 'text-gray-600' ?>">Easy returns & exchanges</span></div>
                        <div class="flex items-center gap-3"><span class="text-xl">💬</span><span class="text-sm <?= $dark ? 'text-gray-300' : 'text-gray-600' ?>">Product recommendations</span></div>
                        <div class="flex items-center gap-3"><span class="text-xl">🤝</span><span class="text-sm <?= $dark ? 'text-gray-300' : 'text-gray-600' ?>">Bulk order & wholesale inquiries</span></div>
                        <div class="flex items-center gap-3"><span class="text-xl">🎁</span><span class="text-sm <?= $dark ? 'text-gray-300' : 'text-gray-600' ?>">Gift wrapping available</span></div>
                    </div>
                </div>

                <!-- Trust Badge -->
                <div class="rounded-2xl p-6 bg-gradient-to-br from-primary-600 to-secondary-600 text-white shadow-lg">
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-8 h-8 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="font-heading font-bold text-lg">100% Customer Satisfaction</h3>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed">Your satisfaction is our priority. We're committed to resolving any concerns quickly and ensuring you have the best shopping experience.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var btn = document.getElementById('contact-submit-btn');
    var text = document.getElementById('contact-submit-text');
    var spinner = document.getElementById('contact-submit-spinner');
    btn.disabled = true; text.classList.add('hidden'); spinner.classList.remove('hidden');
    setTimeout(function() {
        if (typeof showToast === 'function') showToast('Message sent! We will get back to you soon.');
        document.getElementById('contact-name').value = '';
        document.getElementById('contact-email').value = '';
        document.getElementById('contact-phone').value = '';
        document.getElementById('contact-subject').value = '';
        document.getElementById('contact-message').value = '';
        btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden');
    }, 1500);
});
</script>
