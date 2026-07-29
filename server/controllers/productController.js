const Product = require('../models/Product');
const AppError = require('../utils/AppError');

exports.getAll = async (req, res, next) => {
  try {
    const products = await Product.getAll();
    res.json({ success: true, data: products });
  } catch (err) { next(err); }
};

exports.getActive = async (req, res, next) => {
  try {
    const filters = {};
    if (req.query.category_id) filters.category_id = req.query.category_id;
    if (req.query.category_slug) filters.category_slug = req.query.category_slug;
    if (req.query.search) filters.search = req.query.search;
    if (req.query.featured) filters.featured = req.query.featured === 'true';
    if (req.query.limit) filters.limit = parseInt(req.query.limit);
    if (req.query.min_price) filters.min_price = req.query.min_price;
    if (req.query.max_price) filters.max_price = req.query.max_price;
    const products = await Product.getActive(filters);
    res.json({ success: true, data: products });
  } catch (err) { next(err); }
};

exports.getById = async (req, res, next) => {
  try {
    const product = await Product.getById(req.params.id);
    if (!product) throw new AppError('Product not found', 404);
    res.json({ success: true, data: product });
  } catch (err) { next(err); }
};

exports.getBySlug = async (req, res, next) => {
  try {
    const product = await Product.getBySlug(req.params.slug);
    if (!product) throw new AppError('Product not found', 404);
    res.json({ success: true, data: product });
  } catch (err) { next(err); }
};

exports.create = async (req, res, next) => {
  try {
    const { name, category_id, description, specifications, price, offer_price, stock, status, is_featured } = req.body;
    if (!name || !price || !category_id) throw new AppError('Name, price and category are required', 400);

    const images = [];
    if (req.files && req.files.length > 0) {
      req.files.forEach(f => images.push(f.path));
    }

    const product = await Product.create({
      name, category_id, description, specifications, price,
      offer_price: offer_price || null, stock: stock || 0,
      status: status || 'active', is_featured: is_featured === 'true' || is_featured === true,
      images
    });
    res.status(201).json({ success: true, data: product });
  } catch (err) { next(err); }
};

exports.update = async (req, res, next) => {
  try {
    const data = {};
    ['name', 'category_id', 'description', 'specifications', 'price', 'offer_price', 'stock', 'status', 'is_featured'].forEach(field => {
      if (req.body[field] !== undefined) data[field] = req.body[field];
    });
    if (req.files && req.files.length > 0) {
      const existing = await Product.getById(req.params.id);
      const images = existing ? existing.images : [];
      req.files.forEach(f => images.push(f.path));
      data.images = images;
    }
    if (req.body.existing_images) {
      const existingImages = typeof req.body.existing_images === 'string' ? JSON.parse(req.body.existing_images) : req.body.existing_images;
      if (req.files && req.files.length > 0) {
        req.files.forEach(f => existingImages.push(f.path));
      }
      data.images = existingImages;
    }
    const product = await Product.update(req.params.id, data);
    if (!product) throw new AppError('Product not found', 404);
    res.json({ success: true, data: product });
  } catch (err) { next(err); }
};

exports.delete = async (req, res, next) => {
  try {
    await Product.delete(req.params.id);
    res.json({ success: true, message: 'Product deleted' });
  } catch (err) { next(err); }
};

exports.getFeatured = async (req, res, next) => {
  try {
    const limit = req.query.limit ? parseInt(req.query.limit) : 8;
    const products = await Product.getFeatured(limit);
    res.json({ success: true, data: products });
  } catch (err) { next(err); }
};

exports.search = async (req, res, next) => {
  try {
    const { q, category_id, min_price, max_price, sort } = req.query;
    const results = await Product.search(q || '', { category_id, min_price, max_price, sort });
    res.json({ success: true, data: results });
  } catch (err) { next(err); }
};
