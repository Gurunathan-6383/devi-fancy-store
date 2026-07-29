const express = require('express');
const router = express.Router();
const productController = require('../controllers/productController');
const auth = require('../middleware/auth');
const { upload } = require('../config/cloudinary');

router.get('/active', productController.getActive);
router.get('/featured', productController.getFeatured);
router.get('/search', productController.search);
router.get('/', auth, productController.getAll);
router.get('/slug/:slug', productController.getBySlug);
router.get('/:id', productController.getById);
router.post('/', auth, upload.array('images', 10), productController.create);
router.put('/:id', auth, upload.array('images', 10), productController.update);
router.delete('/:id', auth, productController.delete);

module.exports = router;
