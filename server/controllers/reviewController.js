const Review = require('../models/Review');

exports.getByProduct = async (req, res, next) => {
  try {
    const reviews = await Review.getByProduct(req.params.productId);
    const stats = await Review.getStats(req.params.productId);
    res.json({ success: true, data: { reviews, stats } });
  } catch (err) { next(err); }
};

exports.create = async (req, res, next) => {
  try {
    const { product_id, rating, comment } = req.body;
    if (!product_id || !rating) {
      return res.status(400).json({ success: false, message: 'Product ID and rating are required' });
    }
    if (rating < 1 || rating > 5) {
      return res.status(400).json({ success: false, message: 'Rating must be between 1 and 5' });
    }
    const existing = await Review.hasReviewed(req.customer.id, product_id);
    if (existing) {
      return res.status(409).json({ success: false, message: 'You have already reviewed this product' });
    }
    const review = await Review.create(req.customer.id, product_id, rating, comment);
    res.status(201).json({ success: true, data: review });
  } catch (err) { next(err); }
};
