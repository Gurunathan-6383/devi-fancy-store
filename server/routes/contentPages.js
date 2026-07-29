const express = require('express');
const router = express.Router();
const contentPageController = require('../controllers/contentPageController');
const auth = require('../middleware/auth');

router.get('/public/:slug', contentPageController.getBySlug);
router.get('/', auth, contentPageController.getAll);
router.get('/:id', auth, contentPageController.getById);
router.post('/', auth, contentPageController.create);
router.put('/:id', auth, contentPageController.update);
router.delete('/:id', auth, contentPageController.delete);

module.exports = router;
