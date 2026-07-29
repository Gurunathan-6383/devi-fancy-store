const Settings = require('../models/Settings');
const AppError = require('../utils/AppError');

exports.getAll = async (req, res, next) => {
  try {
    const settings = await Settings.getAll();
    res.json({ success: true, data: settings });
  } catch (err) { next(err); }
};

exports.update = async (req, res, next) => {
  try {
    const allowed = ['store_name', 'logo', 'phone', 'email', 'address', 'theme'];
    const updates = {};
    for (const key of allowed) {
      if (req.body[key] !== undefined) {
        updates[key] = req.body[key];
      }
    }
    if (req.file) updates.logo = req.file.path;
    const settings = await Settings.updateMultiple(updates);
    res.json({ success: true, data: settings });
  } catch (err) { next(err); }
};

exports.getPublic = async (req, res, next) => {
  try {
    const settings = await Settings.getAll();
    const publicSettings = {};
    const allowed = ['store_name', 'logo', 'phone', 'email', 'address', 'theme'];
    for (const key of allowed) {
      if (settings[key]) publicSettings[key] = settings[key];
    }
    res.json({ success: true, data: publicSettings });
  } catch (err) { next(err); }
};
