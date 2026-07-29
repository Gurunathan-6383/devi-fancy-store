# Devi Fancy Store - Digital Product Catalogue

A complete digital product catalogue website with admin panel, customer frontend, and Google Sheets order integration.

## Tech Stack

- **Frontend**: React.js (Vite), Tailwind CSS, React Router, Axios
- **Backend**: Node.js, Express.js
- **Database**: MySQL
- **Storage**: Cloudinary
- **Authentication**: JWT, bcrypt
- **Orders**: Google Sheets API

## Project Structure

```
devi/
├── server/                 # Backend (Node.js + Express)
│   ├── config/            # Database & Cloudinary config
│   ├── controllers/       # Route handlers
│   ├── middleware/        # Auth & error handling
│   ├── models/           # Database models
│   ├── routes/           # API routes
│   ├── services/         # Google Sheets integration
│   ├── utils/           # Utility classes
│   ├── server.js         # Entry point
│   ├── seed.js           # Database seeder
│   └── .env              # Environment variables
├── client/                # Frontend (React + Vite)
│   ├── src/
│   │   ├── admin/        # Admin pages
│   │   ├── components/   # Shared components
│   │   ├── context/      # React contexts
│   │   ├── pages/        # Customer pages
│   │   └── services/     # API service
│   ├── index.html
│   └── package.json
├── database.sql           # Database schema
└── README.md
```

## Setup Instructions

### Prerequisites

- Node.js 18+
- MySQL 8+
- Cloudinary account
- Google Cloud service account with Sheets API enabled

### 1. Database Setup

1. Open MySQL and run:
   ```sql
   CREATE DATABASE IF NOT EXISTS devi_fancy_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE devi_fancy_store;
   ```

2. Run the schema file:
   ```
   mysql -u root -p devi_fancy_store < database.sql
   ```

### 2. Backend Setup

```bash
cd server
npm install
```

#### Configure Environment

Edit `server/.env`:

```
PORT=5000

DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_mysql_password
DB_NAME=devi_fancy_store

JWT_SECRET=your_jwt_secret_key

CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret

GOOGLE_SHEETS_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\nYOUR_KEY_HERE\n-----END PRIVATE KEY-----\n"
GOOGLE_SHEETS_CLIENT_EMAIL=your-service-account@project.iam.gserviceaccount.com
GOOGLE_SHEETS_SPREADSHEET_ID=your_spreadsheet_id
```

#### Seed Database

```bash
cd server
node seed.js
```

This creates:
- Admin user: `admin@gmail.com` / `admin@123`
- 11 default categories
- Default settings

#### Start Backend

```bash
npm run dev
```

Server runs on `http://localhost:5000`

### 3. Frontend Setup

```bash
cd client
npm install
npm run dev
```

Frontend runs on `http://localhost:5173`

The Vite dev server proxies `/api` requests to the backend.

### 4. Google Sheets Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create a project or select existing
3. Enable Google Sheets API
4. Create a Service Account
5. Download JSON key
6. Copy the `private_key` and `client_email` to `.env`
7. Create a Google Sheet with this header row in the first sheet named `Orders`:

| name | phone | address | products | quantity | total | date |

8. Share the sheet with the service account email (Editor permissions)
9. Copy the Spreadsheet ID from the URL and paste it in `.env`

### 5. Cloudinary Setup

1. Create a [Cloudinary](https://cloudinary.com) account
2. Get your cloud name, API key, and API secret from the dashboard
3. Add them to `.env`

## Default Admin Login

- **URL**: `http://localhost:5173/admin/login`
- **Email**: `admin@gmail.com`
- **Password**: `admin@123`

## Features

### Admin Panel
- Dashboard with statistics
- Category management (CRUD, hide/show)
- Product management (CRUD, images, stock, featured)
- Catalogue management (create, publish, assign products)
- Order management (view from Google Sheets, search, sort)
- Customer list
- Settings (store name, logo, contact info, theme)

### Customer Website
- Beautiful responsive UI
- Home page with featured products, categories, catalogues
- Category-wise product browsing
- Curated catalogues
- Product details with images, specs, pricing
- Shopping cart with quantity management
- Checkout form
- Product search
- Google Sheets order integration

## API Endpoints

### Public
- `POST /api/orders` - Place order (saves to Google Sheets)
- `GET /api/products/active` - Active products
- `GET /api/products/featured` - Featured products
- `GET /api/products/search` - Search products
- `GET /api/products/slug/:slug` - Product by slug
- `GET /api/categories/active` - Active categories
- `GET /api/catalogues/published` - Published catalogues
- `GET /api/catalogues/slug/:slug` - Catalogue with products
- `GET /api/settings/public` - Public settings

### Admin (requires JWT)
- `POST /api/auth/login` - Admin login
- All CRUD for categories, products, catalogues
- `GET /api/orders` - All orders from Google Sheets
- `GET /api/settings` - All settings

## License

MIT
