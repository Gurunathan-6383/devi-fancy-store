<?php
$product = $product ?? null;
$related_products = $related_products ?? [];
$reviews = $reviews ?? [];
$review_stats = $review_stats ?? ['count' => 0, 'avg_rating' => 0];
$dark = $dark ?? false;
$baseUrl = rtrim(env('APP_URL', ''), '/');

if (!$product): ?>
<div class="py-20 text-center min-h-[60vh] flex items-center justify-center">
    <div class="max-w-md mx-auto px-4">
        <h2 class="text-3xl font-heading font-bold mb-3 <?= $dark ? 'text-white' : 'text-gray-900' ?>">No products found.</h2>
        <a href="<?= $baseUrl ?>/products" class="btn-primary inline-flex items-center gap-2">Back to Products</a>
    </div>
</div>
<?php return; endif;

$images = $product['images'] ?? [];
if (is_string($images)) $images = json_decode($images, true) ?: [];
$price = ($product['offer_price'] ?? 0) ?: ($product['price'] ?? 0);
$hasOffer = !empty($product['offer_price']);
$specs = !empty($product['specifications']) ? array_filter(explode("\n", $product['specifications'])) : [];
$discount = $hasOffer ? round((1 - ($product['offer_price'] ?? 0) / ($product['price'] ?? 1)) * 100) : 0;
$inCart = $in_cart ?? false;
$inCartQty = $in_cart_qty ?? 0;
$is_authenticated = $is_authenticated ?? false;
?>
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex items-center text-sm mb-8 overflow-x-auto">
            <a href="<?= $baseUrl ?>/" class="transition-colors whitespace-nowrap <?= $dark ? 'text-gray-500 hover:text-primary-400' : 'text-gray-400 hover:text-primary-600' ?>">Home</a>
            <svg class="w-3.5 h-3.5 mx-2 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="<?= $baseUrl ?>/products" class="transition-colors whitespace-nowrap <?= $dark ? 'text-gray-500 hover:text-primary-400' : 'text-gray-400 hover:text-primary-600' ?>">Products</a>
            <?php if (!empty($product['category_name'])): ?>
            <svg class="w-3.5 h-3.5 mx-2 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="<?= $baseUrl ?>/categories/<?= htmlspecialchars($product['category_slug'] ?? '') ?>" class="transition-colors whitespace-nowrap <?= $dark ? 'text-gray-500 hover:text-primary-400' : 'text-gray-400 hover:text-primary-600' ?>"><?= htmlspecialchars($product['category_name']) ?></a>
            <?php endif; ?>
            <svg class="w-3.5 h-3.5 mx-2 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="font-medium truncate <?= $dark ? 'text-white' : 'text-gray-900' ?>"><?= htmlspecialchars($product['name'] ?? '') ?></span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-14">
            <!-- Image Gallery -->
            <div class="animate-slide-up">
                <div class="relative aspect-square rounded-2xl overflow-hidden mb-4 shadow-xl group <?= $dark ? 'bg-gray-800' : 'bg-gray-100' ?>">
                    <img id="main-product-image" src="<?= htmlspecialchars($images[0] ?? 'https://via.placeholder.com/600x600?text=No+Image') ?>" alt="<?= htmlspecialchars($product['name'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                    <?php if ($hasOffer): ?><div class="absolute top-4 left-4 bg-red-500 text-white font-bold px-4 py-2 rounded-xl text-sm shadow-lg animate-pulse-glow"><?= $discount ?>% OFF</div><?php endif; ?>
                </div>
                <?php if (count($images) > 1): ?>
                <div class="flex gap-3 overflow-x-auto pb-2">
                    <?php foreach ($images as $i => $img): ?>
                    <button onclick="selectImage(<?= $i ?>, '<?= htmlspecialchars($img) ?>')" class="thumb-btn w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 transition-all duration-300 border-2 <?= $i === 0 ? 'border-primary-500 shadow-lg ring-2 ring-primary-200' : ($dark ? 'border-gray-600 hover:border-gray-500' : 'border-gray-200 hover:border-gray-300') ?> opacity-70 hover:opacity-100">
                        <img src="<?= htmlspecialchars($img) ?>" alt="" class="w-full h-full object-cover" />
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Product Info -->
            <div class="animate-slide-up" style="animation-delay: 0.1s">
                <p class="font-semibold text-sm uppercase tracking-[0.15em] mb-2 <?= $dark ? 'text-primary-400' : 'text-primary-600' ?>"><?= htmlspecialchars($product['category_name'] ?? 'General') ?></p>
                <h1 class="text-3xl md:text-4xl font-heading font-bold mb-5 leading-tight <?= $dark ? 'text-white' : 'text-gray-900' ?>"><?= htmlspecialchars($product['name'] ?? '') ?></h1>

                <div class="flex items-center gap-4 mb-4">
                    <span class="text-4xl font-extrabold <?= $dark ? 'text-white' : 'text-gray-900' ?>">₹<?= round($price) ?></span>
                    <?php if ($hasOffer): ?>
                        <span class="text-xl line-through <?= $dark ? 'text-gray-500' : 'text-gray-400' ?>">₹<?= round($product['price'] ?? 0) ?></span>
                        <span class="bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-bold px-4 py-1.5 rounded-full shadow-md">Save <?= $discount ?>%</span>
                    <?php endif; ?>
                </div>

                <?php if (($review_stats['count'] ?? 0) > 0): ?>
                <div class="flex items-center gap-2 mb-6">
                    <div class="flex gap-0.5">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <svg class="w-5 h-5 <?= $i <= round($review_stats['avg_rating'] ?? 0) ? 'text-accent-400 fill-accent-400' : 'text-gray-300 dark:text-gray-600' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <span class="text-sm font-medium <?= $dark ? 'text-gray-400' : 'text-gray-600' ?>"><?= number_format($review_stats['avg_rating'] ?? 0, 1) ?> (<?= $review_stats['count'] ?? 0 ?> Reviews)</span>
                </div>
                <?php endif; ?>

                <div class="mb-6 flex items-center gap-3">
                    <?php if (($product['stock'] ?? 0) > 0): ?>
                    <span class="inline-flex items-center gap-2 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-semibold px-4 py-2 rounded-xl border border-green-200 dark:border-green-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        In Stock (<?= $product['stock'] ?? 0 ?> available)
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center gap-2 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-semibold px-4 py-2 rounded-xl border border-red-200 dark:border-red-800">Out of Stock</span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($product['description'])): ?>
                <div class="mb-6">
                    <h3 class="text-base font-bold mb-2 uppercase tracking-wide <?= $dark ? 'text-white' : 'text-gray-900' ?>">Description</h3>
                    <p class="leading-relaxed <?= $dark ? 'text-gray-400' : 'text-gray-600' ?>"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($specs)): ?>
                <div class="mb-8">
                    <h3 class="text-base font-bold mb-3 uppercase tracking-wide <?= $dark ? 'text-white' : 'text-gray-900' ?>">Specifications</h3>
                    <div class="rounded-xl p-4 space-y-2 border <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-gray-50 border-gray-100' ?>">
                        <?php foreach ($specs as $i => $spec): $parts = explode(':', $spec, 2); $key = trim($parts[0] ?? ''); $val = trim($parts[1] ?? $spec); ?>
                        <div class="flex py-1.5 <?= $i < count($specs) - 1 ? 'border-b border-gray-200 dark:border-gray-700' : '' ?>">
                            <span class="w-36 flex-shrink-0 font-medium text-sm <?= $dark ? 'text-gray-400' : 'text-gray-500' ?>"><?= htmlspecialchars($key) ?>:</span>
                            <span class="font-semibold text-sm <?= $dark ? 'text-white' : 'text-gray-900' ?>"><?= htmlspecialchars($val) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (($product['stock'] ?? 0) > 0): ?>
                <div class="mb-6">
                    <label class="block text-sm font-bold mb-2 uppercase tracking-wide <?= $dark ? 'text-gray-300' : 'text-gray-700' ?>">Quantity</label>
                    <div class="inline-flex items-center border-2 rounded-xl overflow-hidden <?= $dark ? 'border-gray-600' : 'border-gray-200' ?>">
                        <button onclick="updateQty(-1)" class="p-3.5 transition-colors <?= $dark ? 'hover:bg-gray-700' : 'hover:bg-gray-100' ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <span id="qty-display" class="px-8 py-3.5 font-bold text-lg min-w-[4rem] text-center <?= $dark ? 'bg-gray-800 text-white' : 'bg-gray-50' ?>">1</span>
                        <button onclick="updateQty(1)" class="p-3.5 transition-colors <?= $dark ? 'hover:bg-gray-700' : 'hover:bg-gray-100' ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <div class="flex flex-wrap gap-4 mb-6">
                    <button onclick="handleAddToCart(<?= $product['id'] ?? 0 ?>)" <?= (($product['stock'] ?? 0) <= 0) ? 'disabled' : '' ?> class="btn-primary flex-1 flex items-center justify-center gap-2 py-4 text-lg disabled:opacity-40 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        Add to Cart
                    </button>
                    <button onclick="handleBuyNow(<?= $product['id'] ?? 0 ?>)" <?= (($product['stock'] ?? 0) <= 0) ? 'disabled' : '' ?> class="flex-1 font-bold py-4 px-6 rounded-2xl transition-all flex items-center justify-center gap-2 text-lg shadow-lg hover:-translate-y-0.5 disabled:opacity-40 disabled:cursor-not-allowed <?= $dark ? 'bg-gray-700 hover:bg-gray-600 text-white' : 'bg-gray-900 hover:bg-gray-800 text-white' ?>">Buy Now</button>
                </div>
                <?php if ($inCart): ?>
                <p class="text-sm font-medium px-4 py-2.5 rounded-xl mb-6 <?= $dark ? 'text-primary-400 bg-primary-900/30 border border-primary-800' : 'text-primary-600 bg-primary-50 border border-primary-100' ?>"><?= $inCartQty ?> item(s) already in your cart</p>
                <?php endif; ?>

                <div class="grid grid-cols-3 gap-3 mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <div class="text-center p-3 rounded-xl border <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-gray-50 border-gray-100' ?>">
                        <svg class="w-6 h-6 text-primary-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        <p class="text-xs font-bold <?= $dark ? 'text-white' : 'text-gray-900' ?>">Free Shipping</p>
                        <p class="text-[10px] <?= $dark ? 'text-gray-500' : 'text-gray-500' ?>">On orders above ₹500</p>
                    </div>
                    <div class="text-center p-3 rounded-xl border <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-gray-50 border-gray-100' ?>">
                        <svg class="w-6 h-6 text-primary-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <p class="text-xs font-bold <?= $dark ? 'text-white' : 'text-gray-900' ?>">Secure Payment</p>
                        <p class="text-[10px] <?= $dark ? 'text-gray-500' : 'text-gray-500' ?>">100% secure checkout</p>
                    </div>
                    <div class="text-center p-3 rounded-xl border <?= $dark ? 'bg-gray-800 border-gray-700' : 'bg-gray-50 border-gray-100' ?>">
                        <svg class="w-6 h-6 text-primary-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <p class="text-xs font-bold <?= $dark ? 'text-white' : 'text-gray-900' ?>">Easy Returns</p>
                        <p class="text-[10px] <?= $dark ? 'text-gray-500' : 'text-gray-500' ?>">7 Days</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-20">
            <div class="mb-10">
                <span class="font-semibold text-sm uppercase tracking-[0.2em] <?= $dark ? 'text-primary-400' : 'text-primary-600' ?>">Reviews</span>
                <h2 class="text-3xl font-heading font-bold mt-2 <?= $dark ? 'text-white' : 'text-gray-900' ?>">Reviews (<?= $review_stats['count'] ?? 0 ?>)</h2>
                <div class="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Review Form -->
                <div class="card p-6 <?= $dark ? 'bg-gray-800 border-gray-700' : '' ?>">
                    <h3 class="text-lg font-heading font-bold mb-4 <?= $dark ? 'text-white' : 'text-gray-900' ?>">Write a Review</h3>
                    <?php if ($is_authenticated): ?>
                    <form action="<?= $baseUrl ?>/api/reviews" method="POST" class="space-y-4" onsubmit="handleReviewSubmit(event)">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?? 0 ?>">
                        <div>
                            <label class="block text-sm font-bold mb-2 <?= $dark ? 'text-gray-300' : 'text-gray-700' ?>">Your Rating</label>
                            <div class="flex gap-1" id="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <button type="button" onclick="setRating(<?= $i ?>)" class="star-btn text-accent-400 fill-accent-400">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-2 <?= $dark ? 'text-gray-300' : 'text-gray-700' ?>">Your Review</label>
                            <textarea name="comment" class="input-field <?= $dark ? 'bg-gray-700 border-gray-600 text-white' : '' ?>" rows="4" placeholder="Tell others about your experience..."></textarea>
                        </div>
                        <button type="submit" id="review-submit-btn" class="btn-primary w-full">
                            <span id="review-submit-text">Submit Review</span>
                            <div id="review-submit-spinner" class="hidden animate-spin rounded-full h-6 w-6 border-2 border-white border-t-transparent mx-auto"></div>
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="text-center py-8">
                        <p class="mb-4 <?= $dark ? 'text-gray-400' : 'text-gray-500' ?>">Login to review</p>
                        <a href="<?= $baseUrl ?>/login" class="btn-primary inline-block">Sign In</a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Reviews List -->
                <div class="lg:col-span-2 space-y-4">
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                        <div class="card p-5 <?= $dark ? 'bg-gray-800 border-gray-700' : '' ?>">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gradient-to-br from-primary-400 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            <?= strtoupper(substr($review['customer_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm <?= $dark ? 'text-white' : 'text-gray-900' ?>"><?= htmlspecialchars($review['customer_name'] ?? '') ?></p>
                                            <div class="flex gap-0.5">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <svg class="w-4 h-4 <?= $i <= ($review['rating'] ?? 0) ? 'text-accent-400 fill-accent-400' : 'text-gray-300 dark:text-gray-600' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-xs <?= $dark ? 'text-gray-500' : 'text-gray-400' ?>"><?= !empty($review['created_at']) ? date('M d, Y', strtotime($review['created_at'])) : '' ?></span>
                            </div>
                            <?php if (!empty($review['comment'])): ?><p class="text-sm leading-relaxed <?= $dark ? 'text-gray-400' : 'text-gray-600' ?>"><?= htmlspecialchars($review['comment']) ?></p><?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <div class="card p-8 text-center <?= $dark ? 'bg-gray-800 border-gray-700' : '' ?>">
                        <svg class="w-12 h-12 mx-auto mb-3 <?= $dark ? 'text-gray-600' : 'text-gray-300' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        <p class="<?= $dark ? 'text-gray-400' : 'text-gray-500' ?>">No reviews yet. Be the first to review!</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($related_products)): ?>
        <div class="mt-20">
            <div class="mb-10">
                <span class="font-semibold text-sm uppercase tracking-[0.2em] <?= $dark ? 'text-primary-400' : 'text-primary-600' ?>">You may also like</span>
                <h2 class="text-3xl font-heading font-bold mt-2 <?= $dark ? 'text-white' : 'text-gray-900' ?>">Related Products</h2>
                <div class="w-20 h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-secondary-500 rounded-full mt-4"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <?php foreach ($related_products as $p): ?>
                    <?= view('components.product_card', ['product' => $p, 'dark' => $dark]) ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
var currentQty = 1;
var maxStock = <?= $product['stock'] ?? 0 ?>;
var currentRating = 5;

function selectImage(index, src) {
    document.getElementById('main-product-image').src = src;
    document.querySelectorAll('.thumb-btn').forEach(function(b, i) {
        if (i === index) {
            b.classList.add('border-primary-500', 'ring-2', 'ring-primary-200');
        } else {
            b.classList.remove('border-primary-500', 'ring-2', 'ring-primary-200');
        }
    });
}

function setRating(r) {
    currentRating = r;
    document.querySelectorAll('.star-btn').forEach(function(b, i) {
        var svg = b.querySelector('svg');
        if (i < r) {
            svg.classList.add('text-accent-400', 'fill-accent-400');
            svg.classList.remove('text-gray-300', 'dark:text-gray-600');
        } else {
            svg.classList.remove('text-accent-400', 'fill-accent-400');
            svg.classList.add('text-gray-300', 'dark:text-gray-600');
        }
    });
}

function updateQty(delta) {
    currentQty = Math.max(1, Math.min(maxStock, currentQty + delta));
    document.getElementById('qty-display').textContent = currentQty;
}

function handleAddToCart(productId) {
    var token = localStorage.getItem('customerToken');
    if (!token) {
        window.location.href = '/login';
        return;
    }
    var data = JSON.stringify({ product_id: productId, quantity: currentQty });
    fetch('<?= $baseUrl ?>/api/cart/add', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: data })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) { if (typeof showToast === 'function') showToast('Added to cart!'); setTimeout(function() { location.reload(); }, 500); } else { if (typeof showToast === 'function') showToast(res.message || 'Please login first', 'error'); } })
    .catch(function() { if (typeof showToast === 'function') showToast('Error', 'error'); });
}

function handleBuyNow(productId) {
    handleAddToCart(productId);
    setTimeout(function() { window.location.href = '<?= $baseUrl ?>/checkout'; }, 600);
}

function handleReviewSubmit(e) {
    e.preventDefault();
    var token = localStorage.getItem('customerToken');
    if (!token) {
        window.location.href = '/login';
        return;
    }
    var form = e.target;
    var btn = document.getElementById('review-submit-btn');
    var text = document.getElementById('review-submit-text');
    var spinner = document.getElementById('review-submit-spinner');
    btn.disabled = true; text.classList.add('hidden'); spinner.classList.remove('hidden');

    var data = { product_id: form.product_id.value, rating: currentRating, comment: form.comment.value };
    fetch(form.action, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) })
    .then(function(r) { return r.json(); })
    .then(function(res) {
        if (res.success) { if (typeof showToast === 'function') showToast('Review submitted!'); setTimeout(function() { location.reload(); }, 500); }
        else { if (typeof showToast === 'function') showToast(res.message || 'Failed', 'error'); btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden'); }
    })
    .catch(function() { if (typeof showToast === 'function') showToast('Error', 'error'); btn.disabled = false; text.classList.remove('hidden'); spinner.classList.add('hidden'); });
}
</script>
