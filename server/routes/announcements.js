const express = require('express');
const router = express.Router();
const announcementController = require('../controllers/announcementController');
const auth = require('../middleware/auth');

router.get('/active', announcementController.getActive);
router.get('/', auth, announcementController.getAll);
router.get('/:id', announcementController.getById);
router.post('/', auth, announcementController.create);
router.put('/:id', auth, announcementController.update);
router.delete('/:id', auth, announcementController.delete);
router.patch('/:id/toggle-status', auth, announcementController.toggleStatus);

module.exports = router;
