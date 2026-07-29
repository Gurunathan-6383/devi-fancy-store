import { Routes, Route, Outlet } from 'react-router-dom';
import Navbar from './components/Navbar';
import Footer from './components/Footer';
import AdminLayout from './components/AdminLayout';
import ProtectedRoute from './components/ProtectedRoute';
import CustomerProtectedRoute from './components/CustomerProtectedRoute';
import Home from './pages/Home';
import CategoriesPage from './pages/CategoriesPage';
import CataloguesPage from './pages/CataloguesPage';
import ProductsPage from './pages/ProductsPage';
import ProductDetails from './pages/ProductDetails';
import CartPage from './pages/CartPage';
import Checkout from './pages/Checkout';
import SearchResults from './pages/SearchResults';
import AdminLogin from './pages/AdminLogin';
import Login from './pages/Login';
import Signup from './pages/Signup';
import WishlistPage from './pages/WishlistPage';
import Dashboard from './admin/Dashboard';
import ManageCategories from './admin/ManageCategories';
import ManageProducts from './admin/ManageProducts';
import ManageCatalogues from './admin/ManageCatalogues';
import ManageOrders from './admin/ManageOrders';
import ManageCustomers from './admin/ManageCustomers';
import AdminSettings from './admin/AdminSettings';
import ManageAnnouncements from './admin/ManageAnnouncements';
import ManageContentPages from './admin/ManageContentPages';
import AnnouncementBar from './components/AnnouncementBar';
import ContentPage from './pages/ContentPage';
import ContactUs from './pages/ContactUs';
import AboutUs from './pages/AboutUs';

function PublicLayout() {
  return (
    <>
      <AnnouncementBar />
      <Navbar />
      <main className="flex-1">
        <Outlet />
      </main>
      <Footer />
    </>
  );
}

function App() {
  return (
    <div className="flex flex-col min-h-screen">
      <Routes>
        <Route path="/admin/login" element={<AdminLogin />} />
        <Route path="/login" element={<Login />} />
        <Route path="/signup" element={<Signup />} />
        <Route element={<ProtectedRoute />}>
          <Route element={<AdminLayout />}>
            <Route path="/admin" element={<Dashboard />} />
            <Route path="/admin/categories" element={<ManageCategories />} />
            <Route path="/admin/products" element={<ManageProducts />} />
            <Route path="/admin/catalogues" element={<ManageCatalogues />} />
            <Route path="/admin/orders" element={<ManageOrders />} />
            <Route path="/admin/customers" element={<ManageCustomers />} />
            <Route path="/admin/announcements" element={<ManageAnnouncements />} />
            <Route path="/admin/content-pages" element={<ManageContentPages />} />
            <Route path="/admin/settings" element={<AdminSettings />} />
          </Route>
        </Route>
        <Route element={<PublicLayout />}>
          <Route path="/" element={<Home />} />
          <Route path="/categories" element={<CategoriesPage />} />
          <Route path="/categories/:slug" element={<ProductsPage />} />
          <Route path="/catalogues" element={<CataloguesPage />} />
          <Route path="/catalogues/:slug" element={<CataloguesPage />} />
          <Route path="/products/:slug" element={<ProductDetails />} />
          <Route path="/search" element={<SearchResults />} />
          <Route path="/products" element={<ProductsPage />} />
          <Route element={<CustomerProtectedRoute />}>
            <Route path="/cart" element={<CartPage />} />
            <Route path="/checkout" element={<Checkout />} />
            <Route path="/wishlist" element={<WishlistPage />} />
          </Route>
          <Route path="/page/contact-us" element={<ContactUs />} />
          <Route path="/page/about-us" element={<AboutUs />} />
          <Route path="/page/:slug" element={<ContentPage />} />
        </Route>
      </Routes>
    </div>
  );
}

export default App;
