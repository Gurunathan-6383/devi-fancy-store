const express = require('express');
const cors = require('cors');
const path = require('path');
require('dotenv').config();

const errorHandler = require('./middleware/errorHandler');

const authRoutes = require('./routes/auth');
const customerAuthRoutes = require('./routes/customerAuth');
const categoryRoutes = require('./routes/categories');
const productRoutes = require('./routes/products');
const catalogueRoutes = require('./routes/catalogues');
const orderRoutes = require('./routes/orders');
const settingsRoutes = require('./routes/settings');
const wishlistRoutes = require('./routes/wishlist');
const reviewRoutes = require('./routes/reviews');
const announcementRoutes = require('./routes/announcements');
const contentPageRoutes = require('./routes/contentPages');

const app = express();
const PORT = process.env.PORT || 5000;

app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

app.use('/api/auth', authRoutes);
app.use('/api/customer', customerAuthRoutes);
app.use('/api/categories', categoryRoutes);
app.use('/api/products', productRoutes);
app.use('/api/catalogues', catalogueRoutes);
app.use('/api/orders', orderRoutes);
app.use('/api/settings', settingsRoutes);
app.use('/api/wishlist', wishlistRoutes);
app.use('/api/reviews', reviewRoutes);
app.use('/api/announcements', announcementRoutes);
app.use('/api/content-pages', contentPageRoutes);

app.get('/api/health', (req, res) => {
  res.json({ success: true, message: 'Devi Fancy Store API is running' });
});

app.use(errorHandler);

app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
