const express = require('express');
const router = express.Router();
const reviewController = require('../controllers/reviewController');
const customerAuth = require('../middleware/customerAuth');

router.get('/product/:productId', reviewController.getByProduct);
router.post('/', customerAuth, reviewController.create);

module.exports = router;
