const express = require('express');
const router = express.Router();
const catalogueController = require('../controllers/catalogueController');
const auth = require('../middleware/auth');
const { upload } = require('../config/cloudinary');

router.get('/published', catalogueController.getPublished);
router.get('/', auth, catalogueController.getAll);
router.get('/slug/:slug', catalogueController.getBySlug);
router.get('/:id', catalogueController.getById);
router.get('/:id/products', catalogueController.getProducts);
router.post('/', auth, upload.single('image'), catalogueController.create);
router.put('/:id', auth, upload.single('image'), catalogueController.update);
router.delete('/:id', auth, catalogueController.delete);
router.patch('/:id/toggle-publish', auth, catalogueController.togglePublish);
router.post('/:id/products', auth, catalogueController.addProduct);
router.delete('/:id/products/:productId', auth, catalogueController.removeProduct);

module.exports = router;
