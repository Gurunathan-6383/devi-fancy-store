<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Models\Category;
use App\Models\Product;
use App\Models\Catalogue;
use App\Models\Review;

class HomeController
{
    public function adminLogin(Request $request)
    {
        echo layout('admin', view('auth.admin_login'), ['title' => 'Admin Login']);
    }

    public function login(Request $request)
    {
        echo layout('main', view('auth.login'), ['title' => 'Login']);
    }

    public function signup(Request $request)
    {
        echo layout('main', view('auth.signup'), ['title' => 'Sign Up']);
    }

    public function dashboard(Request $request)
    {
        $db = Database::getInstance();
        $stats = [
            'products' => $db->fetch("SELECT COUNT(*) as count FROM products")['count'],
            'categories' => $db->fetch("SELECT COUNT(*) as count FROM categories")['count'],
            'catalogues' => $db->fetch("SELECT COUNT(*) as count FROM catalogues")['count'],
            'orders' => $db->fetch("SELECT COUNT(*) as count FROM orders")['count'] ?? 0,
        ];
        echo layout('admin', view('admin.dashboard', ['title' => 'Dashboard', 'page' => 'dashboard', 'stats' => $stats]), ['title' => 'Dashboard']);
    }

    public function manageCategories(Request $request)
    {
        $categories = Category::findAll();
        echo layout('admin', view('admin.categories', ['title' => 'Categories', 'page' => 'categories', 'categories' => $categories]), ['title' => 'Categories']);
    }

    public function manageProducts(Request $request)
    {
        $products = Product::findAll();
        $categories = Category::findAll();
        echo layout('admin', view('admin.products', ['title' => 'Products', 'page' => 'products', 'products' => $products, 'categories' => $categories]), ['title' => 'Products']);
    }

    public function manageCatalogues(Request $request)
    {
        $catalogues = Catalogue::findAll();
        echo layout('admin', view('admin.catalogues', ['title' => 'Catalogues', 'page' => 'catalogues', 'catalogues' => $catalogues]), ['title' => 'Catalogues']);
    }

    public function manageOrders(Request $request)
    {
        echo layout('admin', view('admin.orders', ['title' => 'Orders', 'page' => 'orders']), ['title' => 'Orders']);
    }

    public function manageCustomers(Request $request)
    {
        $db = Database::getInstance();
        $customers = $db->fetchAll("SELECT * FROM customers ORDER BY created_at DESC");
        echo layout('admin', view('admin.customers', ['title' => 'Customers', 'page' => 'customers', 'customers' => $customers]), ['title' => 'Customers']);
    }

    public function manageAnnouncements(Request $request)
    {
        $db = Database::getInstance();
        $announcements = $db->fetchAll("SELECT * FROM announcements ORDER BY created_at DESC");
        echo layout('admin', view('admin.announcements', ['title' => 'Announcements', 'page' => 'announcements', 'announcements' => $announcements]), ['title' => 'Announcements']);
    }

    public function manageContentPages(Request $request)
    {
        $db = Database::getInstance();
        $pages = $db->fetchAll("SELECT * FROM content_pages ORDER BY created_at DESC");
        echo layout('admin', view('admin.content_pages', ['title' => 'Content Pages', 'page' => 'content_pages', 'pages' => $pages]), ['title' => 'Content Pages']);
    }

    public function adminSettings(Request $request)
    {
        $db = Database::getInstance();
        $rows = $db->fetchAll("SELECT setting_key, setting_value FROM settings");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        echo layout('admin', view('admin.settings', ['title' => 'Settings', 'page' => 'settings', 'settings' => $settings]), ['title' => 'Settings']);
    }

    public function home(Request $request)
    {
        $featured = Product::getFeatured();
        $categories = Category::getActive();
        $catalogues = Catalogue::getPublished();
        echo layout('main', view('pages.home', [
            'title' => 'Home',
            'featured_products' => $featured,
            'categories' => $categories,
            'catalogues' => $catalogues,
        ]), ['title' => setting('store_name', 'Devi Fancy Store')]);
    }

    public function categories(Request $request)
    {
        $categories = Category::getActive();
        echo layout('main', view('pages.categories', ['title' => 'Categories', 'categories' => $categories]), ['title' => 'Categories']);
    }

    public function products(Request $request)
    {
        $slug = $request->param('slug');
        $category = null;
        $products = [];

        if ($slug) {
            $db = Database::getInstance();
            $category = $db->fetch("SELECT * FROM categories WHERE slug = ?", [$slug]);
            if ($category) {
                $products = $db->fetchAll("SELECT * FROM products WHERE category_id = ? AND status = 'active' ORDER BY created_at DESC", [$category['id']]);
            }
        } else {
            $products = Product::getActive();
        }

        $categories = Category::getActive();
        echo layout('main', view('pages.products', [
            'title' => $category ? $category['name'] : 'Products',
            'products' => $products,
            'categories' => $categories,
            'current_category' => $category,
        ]), ['title' => $category ? $category['name'] : 'Products']);
    }

    public function catalogueDetail(Request $request)
    {
        $slug = $request->param('slug');
        $catalogue = Catalogue::getBySlug($slug);
        if (!$catalogue) {
            http_response_code(404);
            echo layout('main', view('pages.404'), ['title' => 'Not Found']);
            return;
        }
        $catalogue['products'] = Catalogue::getProducts($catalogue['id']);
        echo layout('main', view('pages.catalogues', [
            'title' => $catalogue['title'],
            'catalogue' => $catalogue,
        ]), ['title' => $catalogue['title']]);
    }

    public function catalogues(Request $request)
    {
        $catalogues = Catalogue::getPublished();
        echo layout('main', view('pages.catalogues', ['title' => 'Catalogues', 'catalogues' => $catalogues]), ['title' => 'Catalogues']);
    }

    public function productDetails(Request $request)
    {
        $slug = $request->param('slug');
        $product = Product::getBySlug($slug);
        if (!$product) {
            http_response_code(404);
            echo layout('main', view('pages.404'), ['title' => 'Not Found']);
            return;
        }
        $reviews = Review::getByProduct($product['id']);
        $stats = Review::getStats($product['id']);
        $related = [];
        if ($product['category_id']) {
            $db = Database::getInstance();
            $related = $db->fetchAll("SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 'active' ORDER BY RAND() LIMIT 4", [$product['category_id'], $product['id']]);
        }
        echo layout('main', view('pages.product_details', [
            'title' => $product['name'],
            'product' => $product,
            'reviews' => $reviews,
            'review_stats' => $stats,
            'related_products' => $related,
        ]), ['title' => $product['name']]);
    }

    public function searchResults(Request $request)
    {
        $query = $request->input('q', '');
        $products = [];
        if ($query) {
            $db = Database::getInstance();
            $like = '%' . $query . '%';
            $products = $db->fetchAll(
                "SELECT * FROM products WHERE status = 'active' AND (name LIKE ? OR description LIKE ?) ORDER BY created_at DESC",
                [$like, $like]
            );
        }
        echo layout('main', view('pages.search_results', ['title' => 'Search Results', 'query' => $query, 'products' => $products]), ['title' => 'Search Results']);
    }

    public function cart(Request $request)
    {
        if (!isset($_SESSION['customer_user'])) {
            header('Location: /login');
            exit;
        }
        echo layout('main', view('pages.cart', ['title' => 'Cart']), ['title' => 'Cart']);
    }

    public function checkout(Request $request)
    {
        if (!isset($_SESSION['customer_user'])) {
            header('Location: /login');
            exit;
        }
        echo layout('main', view('pages.checkout', ['title' => 'Checkout']), ['title' => 'Checkout']);
    }

    public function wishlist(Request $request)
    {
        if (!isset($_SESSION['customer_user'])) {
            header('Location: /login');
            exit;
        }
        $items = [];
        $customerId = $_SESSION['customer_user']['id'] ?? null;
        if ($customerId) {
            $db = Database::getInstance();
            $items = $db->fetchAll(
                "SELECT p.* FROM products p INNER JOIN wishlists w ON p.id = w.product_id WHERE w.customer_id = ? ORDER BY w.created_at DESC",
                [$customerId]
            );
        }
        echo layout('main', view('pages.wishlist', ['title' => 'Wishlist', 'items' => $items]), ['title' => 'Wishlist']);
    }

    public function contentPage(Request $request)
    {
        $slug = $request->param('slug');
        $db = Database::getInstance();
        $page = $db->fetch("SELECT * FROM content_pages WHERE slug = ? AND is_active = 1", [$slug]);
        if (!$page) {
            http_response_code(404);
            echo layout('main', view('pages.404'), ['title' => 'Not Found']);
            return;
        }
        echo layout('main', view('pages.content_page', ['title' => $page['title'], 'page' => $page]), ['title' => $page['title']]);
    }

    public function contactUs(Request $request)
    {
        echo layout('main', view('pages.contact_us', ['title' => 'Contact Us']), ['title' => 'Contact Us']);
    }

    public function aboutUs(Request $request)
    {
        echo layout('main', view('pages.about_us', ['title' => 'About Us']), ['title' => 'About Us']);
    }
}
