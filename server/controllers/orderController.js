const { appendToSheet, getOrdersFromSheet } = require('../services/googleSheets');
const AppError = require('../utils/AppError');
const Settings = require('../models/Settings');

exports.placeOrder = async (req, res, next) => {
  try {
    const { name, phone, address, items, total } = req.body;

    if (!name || !phone || !address || !items || !items.length || !total) {
      throw new AppError('All fields are required', 400);
    }

    const productNames = items.map(item => item.name).join(', ');
    const quantities = items.map(item => item.quantity).join(', ');

    const date = new Date().toLocaleString('en-IN', { timeZone: 'Asia/Kolkata' });

    const orderData = [[name, phone, address, productNames, quantities, `₹${total}`, date]];

    await appendToSheet(orderData);

    res.status(201).json({
      success: true,
      message: 'Order placed successfully!'
    });
  } catch (err) {
    next(err);
  }
};

exports.getAllOrders = async (req, res, next) => {
  try {
    const orders = await getOrdersFromSheet();
    const formatted = orders.map((row, index) => ({
      id: index + 1,
      name: row[0] || '',
      phone: row[1] || '',
      address: row[2] || '',
      products: row[3] || '',
      quantity: row[4] || '',
      total: row[5] || '',
      date: row[6] || ''
    }));
    res.json({ success: true, data: formatted });
  } catch (err) {
    next(err);
  }
};
