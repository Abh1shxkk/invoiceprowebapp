# 📄 InvoicePro - Professional Invoice Management System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=flat-square)](LICENSE)

> A comprehensive, full-stack invoice management solution built with Laravel 12, featuring client management, expense tracking, financial reporting, and PDF generation. Perfect for freelancers, small businesses, and agencies.

## 🌟 GitHub Description

```
🧾 InvoicePro - Full-stack invoice management system built with Laravel 12 & TailwindCSS. Features: client management, invoice generation, expense tracking, PDF export, financial reports, REST API, role-based access, and email notifications. Deploy-ready with Docker support.
```

---

## 📋 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Architecture](#-architecture)
- [Application Flow](#-application-flow)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [API Documentation](#-api-documentation)
- [Database Schema](#-database-schema)
- [Screenshots](#-screenshots)
- [Deployment](#-deployment)
- [Testing](#-testing)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### Core Features
- 🧾 **Invoice Management** - Create, edit, send, and track invoices with line items
- 👥 **Client Management** - Comprehensive client database with contact details
- 💰 **Payment Tracking** - Record and monitor payments against invoices
- 📊 **Expense Management** - Track business expenses by category
- 📈 **Financial Reports** - Revenue, expense, and profit/loss analytics
- 📄 **PDF Generation** - Professional invoice PDFs with customizable templates

### Advanced Features
- 🔐 **Role-Based Access Control** - Admin and User roles with Spatie Permissions
- 📧 **Email Notifications** - Automated invoice emails with PDF attachments
- 🔍 **Search & Filters** - Advanced filtering across all modules
- ⚙️ **Company Settings** - Customizable company profile and invoice settings
- 🌐 **REST API** - Complete API with Sanctum authentication
- 📱 **Responsive Design** - Mobile-friendly interface with TailwindCSS

### Dashboard Insights
- Total clients, invoices, and revenue at a glance
- Monthly revenue and expense tracking
- Pending invoice amounts
- Recent invoice activity

---

## 🛠 Tech Stack

| Category | Technology |
|----------|------------|
| **Backend** | Laravel 12, PHP 8.2+ |
| **Frontend** | Blade Templates, TailwindCSS 3.x, Alpine.js |
| **Database** | MySQL / PostgreSQL / SQLite |
| **Authentication** | Laravel Breeze, Sanctum (API) |
| **Authorization** | Spatie Laravel Permission |
| **PDF Generation** | DomPDF |
| **Build Tools** | Vite, PostCSS |
| **Deployment** | Docker, Render, Nixpacks |

---

## 🏗 Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        INVOICEPRO                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐      │
│  │   Frontend   │    │   Backend    │    │   Database   │      │
│  │              │    │              │    │              │      │
│  │ • Blade      │◄──►│ • Laravel 12 │◄──►│ • MySQL/     │      │
│  │ • TailwindCSS│    │ • PHP 8.2+   │    │   PostgreSQL │      │
│  │ • Alpine.js  │    │ • Sanctum    │    │ • SQLite     │      │
│  └──────────────┘    └──────────────┘    └──────────────┘      │
│                              │                                  │
│                              ▼                                  │
│                    ┌──────────────────┐                        │
│                    │    Services      │                        │
│                    │                  │                        │
│                    │ • InvoiceService │                        │
│                    │ • PdfService     │                        │
│                    │ • ReportService  │                        │
│                    │ • SettingService │                        │
│                    └──────────────────┘                        │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### Directory Structure

```
invoicepro/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin dashboard & user management
│   │   │   ├── Api/            # REST API controllers
│   │   │   ├── Auth/           # Authentication controllers
│   │   │   └── User/           # User-facing controllers
│   │   ├── Middleware/         # Custom middleware
│   │   └── Requests/           # Form request validation
│   ├── Models/                 # Eloquent models
│   ├── Notifications/          # Email notifications
│   ├── Policies/               # Authorization policies
│   ├── Providers/              # Service providers
│   └── Services/               # Business logic services
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── css/                    # Stylesheets
│   ├── js/                     # JavaScript files
│   └── views/                  # Blade templates
├── routes/
│   ├── api.php                 # API routes
│   ├── auth.php                # Authentication routes
│   └── web.php                 # Web routes
└── tests/                      # Feature & Unit tests
```

---

## 🔄 Application Flow

### User Journey Flowchart

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           USER AUTHENTICATION                            │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                    ┌───────────────┴───────────────┐
                    ▼                               ▼
            ┌───────────────┐               ┌───────────────┐
            │   Register    │               │    Login      │
            └───────┬───────┘               └───────┬───────┘
                    │                               │
                    └───────────────┬───────────────┘
                                    ▼
                    ┌───────────────────────────────┐
                    │         DASHBOARD             │
                    │  • Revenue Overview           │
                    │  • Recent Invoices            │
                    │  • Quick Stats                │
                    └───────────────┬───────────────┘
                                    │
        ┌───────────┬───────────┬───┴───┬───────────┬───────────┐
        ▼           ▼           ▼       ▼           ▼           ▼
┌───────────┐ ┌───────────┐ ┌───────┐ ┌───────┐ ┌───────────┐ ┌────────┐
│  Clients  │ │ Invoices  │ │Expense│ │Payment│ │  Reports  │ │Settings│
└─────┬─────┘ └─────┬─────┘ └───┬───┘ └───┬───┘ └─────┬─────┘ └────────┘
      │             │           │         │           │
      ▼             ▼           ▼         ▼           ▼
┌───────────┐ ┌───────────┐ ┌───────┐ ┌───────┐ ┌───────────┐
│• Add      │ │• Create   │ │• Track│ │• Record│ │• Revenue  │
│• Edit     │ │• Edit     │ │• Cat- │ │• View  │ │• Expenses │
│• Delete   │ │• Send     │ │  egory│ │• Filter│ │• P&L      │
│• View     │ │• PDF      │ │• View │ │        │ │• Clients  │
└───────────┘ │• Mark Paid│ └───────┘ └───────┘ │• Export   │
              └───────────┘                     └───────────┘
```

### Invoice Lifecycle

```
┌──────────┐    ┌──────────┐    ┌──────────┐    ┌──────────┐
│  DRAFT   │───►│   SENT   │───►│ OVERDUE  │───►│   PAID   │
└──────────┘    └──────────┘    └──────────┘    └──────────┘
     │               │               │               │
     │               │               │               │
     ▼               ▼               ▼               ▼
  Created        Email sent      Past due        Payment
  by user        to client       date            received
```

### API Request Flow

```
┌────────────┐     ┌────────────┐     ┌────────────┐     ┌────────────┐
│   Client   │────►│   Sanctum  │────►│ Controller │────►│  Service   │
│  Request   │     │    Auth    │     │            │     │   Layer    │
└────────────┘     └────────────┘     └────────────┘     └────────────┘
                                                               │
┌────────────┐     ┌────────────┐     ┌────────────┐          │
│   JSON     │◄────│  Resource  │◄────│   Model    │◄─────────┘
│  Response  │     │ Transform  │     │            │
└────────────┘     └────────────┘     └────────────┘
```

---

## 🚀 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ & npm
- MySQL 8.0+ / PostgreSQL 13+ / SQLite

### Quick Start

```bash
# Clone the repository
git clone https://github.com/yourusername/invoicepro.git
cd invoicepro

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=invoicepro
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed the database (optional - adds demo data)
php artisan db:seed

# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

### Using Composer Scripts

```bash
# Complete setup in one command
composer setup

# Start development environment (server + queue + logs + vite)
composer dev

# Run tests
composer test
```

---

## ⚙️ Configuration

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_NAME` | Application name | InvoicePro |
| `APP_ENV` | Environment (local/production) | local |
| `DB_CONNECTION` | Database driver | mysql |
| `MAIL_MAILER` | Mail driver | log |
| `DEFAULT_TAX_RATE` | Default tax percentage | 0 |
| `DEFAULT_INVOICE_PREFIX` | Invoice number prefix | INV |

### Mail Configuration (for invoice emails)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@invoicepro.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 📡 API Documentation

### Authentication

All API endpoints (except login/register) require Bearer token authentication.

```bash
# Login
POST /api/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}

# Response
{
    "message": "Login successful",
    "user": {...},
    "token": "1|abc123..."
}
```

### Endpoints Overview

| Method | Endpoint | Description |
|--------|----------|-------------|
| **Authentication** |||
| POST | `/api/register` | Register new user |
| POST | `/api/login` | User login |
| POST | `/api/logout` | User logout |
| GET | `/api/user` | Get current user |
| **Dashboard** |||
| GET | `/api/dashboard/stats` | Get dashboard statistics |
| **Clients** |||
| GET | `/api/clients` | List all clients |
| POST | `/api/clients` | Create client |
| GET | `/api/clients/{id}` | Get client details |
| PUT | `/api/clients/{id}` | Update client |
| DELETE | `/api/clients/{id}` | Delete client |
| **Invoices** |||
| GET | `/api/invoices` | List all invoices |
| POST | `/api/invoices` | Create invoice |
| GET | `/api/invoices/{id}` | Get invoice details |
| PUT | `/api/invoices/{id}` | Update invoice |
| DELETE | `/api/invoices/{id}` | Delete invoice |
| GET | `/api/invoices/{id}/pdf` | Download invoice PDF |
| **Expenses** |||
| GET | `/api/expenses` | List all expenses |
| POST | `/api/expenses` | Create expense |
| GET | `/api/expenses/{id}` | Get expense details |
| DELETE | `/api/expenses/{id}` | Delete expense |

### Postman Collection

Import the included `InvoicePro_API.postman_collection.json` for complete API testing.

---

## 🗄 Database Schema

### Entity Relationship Diagram

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│    Users    │       │   Clients   │       │  Invoices   │
├─────────────┤       ├─────────────┤       ├─────────────┤
│ id          │◄──┐   │ id          │◄──┐   │ id          │
│ name        │   │   │ user_id     │───┘   │ user_id     │───┐
│ email       │   │   │ name        │       │ client_id   │───┤
│ password    │   │   │ email       │       │ invoice_no  │   │
│ role        │   │   │ phone       │       │ issue_date  │   │
│ profile_pic │   │   │ company     │       │ due_date    │   │
└─────────────┘   │   │ address     │       │ subtotal    │   │
       │          │   │ city/state  │       │ tax         │   │
       │          │   │ country     │       │ total       │   │
       │          │   │ tax_number  │       │ status      │   │
       │          │   └─────────────┘       │ notes       │   │
       │          │                         └─────────────┘   │
       │          │                                │          │
       │          │   ┌─────────────┐              │          │
       │          │   │Invoice Items│              │          │
       │          │   ├─────────────┤              │          │
       │          │   │ id          │              │          │
       │          │   │ invoice_id  │──────────────┘          │
       │          │   │ description │                         │
       │          │   │ quantity    │                         │
       │          │   │ price       │                         │
       │          │   │ total       │                         │
       │          │   └─────────────┘                         │
       │          │                                           │
       │          │   ┌─────────────┐       ┌─────────────┐   │
       │          │   │  Payments   │       │  Expenses   │   │
       │          │   ├─────────────┤       ├─────────────┤   │
       │          │   │ id          │       │ id          │   │
       │          │   │ invoice_id  │───────│ user_id     │───┘
       │          │   │ amount      │       │ category_id │───┐
       │          │   │ payment_date│       │ amount      │   │
       │          │   │ method      │       │ date        │   │
       │          │   │ notes       │       │ description │   │
       │          │   └─────────────┘       │ receipt     │   │
       │          │                         └─────────────┘   │
       │          │                                           │
       │          │   ┌─────────────┐       ┌─────────────┐   │
       │          │   │  Settings   │       │ Categories  │   │
       │          │   ├─────────────┤       ├─────────────┤   │
       │          └───│ user_id     │       │ id          │◄──┘
       │              │ company_name│       │ user_id     │
       │              │ logo        │       │ name        │
       │              │ address     │       │ type        │
       │              │ phone/email │       └─────────────┘
       │              │ tax_id      │
       │              │ inv_prefix  │
       │              │ tax_rate    │
       │              └─────────────┘
       │
       │          ┌─────────────┐
       │          │Activity Logs│
       │          ├─────────────┤
       └──────────│ user_id     │
                  │ action      │
                  │ model_type  │
                  │ model_id    │
                  │ changes     │
                  └─────────────┘
```

### Key Models

| Model | Description |
|-------|-------------|
| `User` | System users with roles (admin/user) |
| `Client` | Customer/client information |
| `Invoice` | Invoice header with totals and status |
| `InvoiceItem` | Line items for invoices |
| `Payment` | Payment records against invoices |
| `Expense` | Business expense tracking |
| `Category` | Expense categories |
| `Setting` | User-specific company and invoice settings |
| `ActivityLog` | Audit trail for actions |

---

## 🖼 Screenshots

### Dashboard
- Revenue overview with monthly trends
- Quick stats cards (clients, invoices, revenue)
- Recent invoice activity list

### Invoice Management
- Invoice list with status filters
- Create/Edit invoice with dynamic line items
- Professional PDF invoice generation

### Reports
- Revenue reports with date filtering
- Expense breakdown by category
- Profit/Loss analysis with charts
- Client-wise revenue analysis

---

## 🚢 Deployment

### Docker Deployment

```bash
# Build the image
docker build -t invoicepro .

# Run the container
docker run -p 80:80 \
  -e APP_KEY=your-app-key \
  -e DB_CONNECTION=pgsql \
  -e DB_HOST=your-db-host \
  -e DB_DATABASE=invoicepro \
  -e DB_USERNAME=your-username \
  -e DB_PASSWORD=your-password \
  invoicepro
```

### Render Deployment

The project includes `render.yaml` for one-click deployment to Render:

1. Connect your GitHub repository to Render
2. Create a new Web Service
3. Render will auto-detect the configuration
4. Set environment variables in Render dashboard
5. Deploy!

### Environment Setup for Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Use PostgreSQL for Render
DB_CONNECTION=pgsql

# Enable secure cookies
SESSION_SECURE_COOKIE=true
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/InvoiceManagementTest.php

# Run using composer
composer test
```

### Test Coverage

- Feature tests for client management
- Feature tests for invoice CRUD operations
- Unit tests for InvoiceService
- Authentication tests

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Use Laravel Pint for code formatting: `./vendor/bin/pint`
- Write tests for new features
- Update documentation as needed

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

Built with ❤️ for freelancers and small businesses.

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [TailwindCSS](https://tailwindcss.com) - Utility-first CSS
- [Spatie](https://spatie.be) - Laravel Permission package
- [DomPDF](https://github.com/dompdf/dompdf) - PDF generation
- [Alpine.js](https://alpinejs.dev) - Lightweight JavaScript framework

---

## 📞 Support

For support, please open an issue in the GitHub repository or contact the maintainers.

---

<p align="center">
  <strong>⭐ Star this repository if you find it helpful!</strong>
</p>
