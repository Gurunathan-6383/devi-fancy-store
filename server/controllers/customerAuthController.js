const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const Customer = require('../models/Customer');
const AppError = require('../utils/AppError');

exports.signup = async (req, res, next) => {
  try {
    const { name, email, phone, password } = req.body;
    if (!name || !email || !password) throw new AppError('Name, email and password required', 400);
    if (password.length < 6) throw new AppError('Password must be at least 6 characters', 400);

    const existing = await Customer.findByEmail(email);
    if (existing) throw new AppError('Email already registered', 409);

    const hashed = await bcrypt.hash(password, 10);
    const customer = await Customer.create({ name, email, phone, password: hashed });

    const token = jwt.sign({ id: customer.id, email: customer.email, type: 'customer' }, process.env.JWT_SECRET, { expiresIn: '7d' });

    res.status(201).json({
      success: true,
      token,
      customer: { id: customer.id, name: customer.name, email: customer.email, phone: customer.phone }
    });
  } catch (err) {
    next(err);
  }
};

exports.login = async (req, res, next) => {
  try {
    const { email, password } = req.body;
    if (!email || !password) throw new AppError('Email and password required', 400);

    const customer = await Customer.findByEmail(email);
    if (!customer) throw new AppError('Invalid credentials', 401);

    const isMatch = await bcrypt.compare(password, customer.password);
    if (!isMatch) throw new AppError('Invalid credentials', 401);

    const token = jwt.sign({ id: customer.id, email: customer.email, type: 'customer' }, process.env.JWT_SECRET, { expiresIn: '7d' });

    res.json({
      success: true,
      token,
      customer: { id: customer.id, name: customer.name, email: customer.email, phone: customer.phone }
    });
  } catch (err) {
    next(err);
  }
};

exports.verify = async (req, res) => {
  res.json({ success: true, customer: req.customer });
};
