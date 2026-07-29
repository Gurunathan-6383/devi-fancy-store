const Wishlist = require('../models/Wishlist');

exports.getAll = async (req, res, next) => {
  try {
    const items = await Wishlist.getByCustomer(req.customer.id);
    res.json({ success: true, data: items });
  } catch (err) { next(err); }
};

exports.getIds = async (req, res, next) => {
  try {
    const ids = await Wishlist.getIds(req.customer.id);
    res.json({ success: true, data: ids });
  } catch (err) { next(err); }
};

exports.toggle = async (req, res, next) => {
  try {
    const { product_id } = req.body;
    const exists = await Wishlist.isInWishlist(req.customer.id, product_id);
    if (exists) {
      await Wishlist.remove(req.customer.id, product_id);
      res.json({ success: true, action: 'removed' });
    } else {
      await Wishlist.add(req.customer.id, product_id);
      res.json({ success: true, action: 'added' });
    }
  } catch (err) { next(err); }
};
