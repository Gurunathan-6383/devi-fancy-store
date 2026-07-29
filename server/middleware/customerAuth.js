const jwt = require('jsonwebtoken');
const Customer = require('../models/Customer');
const AppError = require('../utils/AppError');

const customerAuth = async (req, res, next) => {
  try {
    const header = req.headers.authorization;
    if (!header || !header.startsWith('Bearer ')) throw new AppError('No token provided', 401);

    const token = header.split(' ')[1];
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    if (decoded.type !== 'customer') throw new AppError('Invalid token type', 401);

    const customer = await Customer.findById(decoded.id);
    if (!customer) throw new AppError('Customer not found', 401);

    req.customer = customer;
    next();
  } catch (err) {
    if (err.name === 'JsonWebTokenError' || err.name === 'TokenExpiredError') {
      return next(new AppError('Invalid or expired token', 401));
    }
    next(err);
  }
};

module.exports = customerAuth;
