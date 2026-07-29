const express = require('express');
const router = express.Router();
const categoryController = require('../controllers/categoryController');
const auth = require('../middleware/auth');
const { upload } = require('../config/cloudinary');

router.get('/active', categoryController.getActive);
router.get('/', auth, categoryController.getAll);
router.get('/:id', categoryController.getById);
router.post('/', auth, upload.single('image'), categoryController.create);
router.put('/:id', auth, upload.single('image'), categoryController.update);
router.delete('/:id', auth, categoryController.delete);
router.patch('/:id/toggle-visibility', auth, categoryController.toggleVisibility);

module.exports = router;
