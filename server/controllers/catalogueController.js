const Catalogue = require('../models/Catalogue');
const AppError = require('../utils/AppError');

exports.getAll = async (req, res, next) => {
  try {
    const catalogues = await Catalogue.getAll();
    res.json({ success: true, data: catalogues });
  } catch (err) { next(err); }
};

exports.getPublished = async (req, res, next) => {
  try {
    const catalogues = await Catalogue.getPublished();
    res.json({ success: true, data: catalogues });
  } catch (err) { next(err); }
};

exports.getById = async (req, res, next) => {
  try {
    const catalogue = await Catalogue.getById(req.params.id);
    if (!catalogue) throw new AppError('Catalogue not found', 404);
    const products = await Catalogue.getProducts(req.params.id);
    res.json({ success: true, data: { ...catalogue, products } });
  } catch (err) { next(err); }
};

exports.getBySlug = async (req, res, next) => {
  try {
    const catalogue = await Catalogue.getBySlug(req.params.slug);
    if (!catalogue) throw new AppError('Catalogue not found', 404);
    const products = await Catalogue.getProducts(catalogue.id);
    res.json({ success: true, data: { ...catalogue, products } });
  } catch (err) { next(err); }
};

exports.create = async (req, res, next) => {
  try {
    const { title, description } = req.body;
    if (!title) throw new AppError('Catalogue title is required', 400);
    const image = req.file ? req.file.path : null;
    const catalogue = await Catalogue.create({ title, description, image });
    res.status(201).json({ success: true, data: catalogue });
  } catch (err) { next(err); }
};

exports.update = async (req, res, next) => {
  try {
    const data = {};
    if (req.body.title) data.title = req.body.title;
    if (req.body.description !== undefined) data.description = req.body.description;
    if (req.file) data.image = req.file.path;
    if (req.body.is_published !== undefined) data.is_published = req.body.is_published === 'true' || req.body.is_published === true;
    const catalogue = await Catalogue.update(req.params.id, data);
    if (!catalogue) throw new AppError('Catalogue not found', 404);
    res.json({ success: true, data: catalogue });
  } catch (err) { next(err); }
};

exports.delete = async (req, res, next) => {
  try {
    await Catalogue.delete(req.params.id);
    res.json({ success: true, message: 'Catalogue deleted' });
  } catch (err) { next(err); }
};

exports.togglePublish = async (req, res, next) => {
  try {
    const catalogue = await Catalogue.togglePublish(req.params.id);
    if (!catalogue) throw new AppError('Catalogue not found', 404);
    res.json({ success: true, data: catalogue });
  } catch (err) { next(err); }
};

exports.addProduct = async (req, res, next) => {
  try {
    const { product_id } = req.body;
    if (!product_id) throw new AppError('Product ID is required', 400);
    await Catalogue.addProduct(req.params.id, product_id);
    const products = await Catalogue.getProducts(req.params.id);
    res.json({ success: true, data: products });
  } catch (err) { next(err); }
};

exports.removeProduct = async (req, res, next) => {
  try {
    await Catalogue.removeProduct(req.params.id, req.params.productId);
    const products = await Catalogue.getProducts(req.params.id);
    res.json({ success: true, data: products });
  } catch (err) { next(err); }
};

exports.getProducts = async (req, res, next) => {
  try {
    const products = await Catalogue.getProducts(req.params.id);
    res.json({ success: true, data: products });
  } catch (err) { next(err); }
};
