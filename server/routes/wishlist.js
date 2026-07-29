const express = require('express');
const router = express.Router();
const wishlistController = require('../controllers/wishlistController');
const customerAuth = require('../middleware/customerAuth');

router.get('/', customerAuth, wishlistController.getAll);
router.get('/ids', customerAuth, wishlistController.getIds);
router.post('/toggle', customerAuth, wishlistController.toggle);

module.exports = router;
