const express = require('express');
const router = express.Router();
const orderController = require('../controllers/orderController');
const auth = require('../middleware/auth');

router.post('/', orderController.placeOrder);
router.get('/', auth, orderController.getAllOrders);

module.exports = router;
