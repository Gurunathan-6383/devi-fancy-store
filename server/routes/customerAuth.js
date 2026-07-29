const express = require('express');
const router = express.Router();
const customerAuthController = require('../controllers/customerAuthController');
const customerAuth = require('../middleware/customerAuth');

router.post('/signup', customerAuthController.signup);
router.post('/login', customerAuthController.login);
router.get('/verify', customerAuth, customerAuthController.verify);

module.exports = router;
