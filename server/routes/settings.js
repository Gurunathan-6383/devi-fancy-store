const express = require('express');
const router = express.Router();
const settingsController = require('../controllers/settingsController');
const auth = require('../middleware/auth');
const { upload } = require('../config/cloudinary');

router.get('/public', settingsController.getPublic);
router.get('/', auth, settingsController.getAll);
router.put('/', auth, upload.single('logo'), settingsController.update);

module.exports = router;
