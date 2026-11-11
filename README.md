# Kedia Builders & Colonizers — The Jaipur Blueprint
### Pure PHP & Laravel 11/10 MVC Real Estate E-Commerce Platform

A high-performance, architectural real estate e-commerce platform for **Kedia Builders & Colonizers** (Jaipur, Rajasthan — Est. 1980). Built using pure **PHP** and **Laravel MVC** (Blade templates, Eloquent Models, MySQL Migrations, Seeders, RESTful Routes, and Tailwind CSS).

---

## 🏛 Project Highlights

- **The Jaipur Blueprint Design System**: Editorial typography (`Playfair Display`, `Cormorant Garamond`, `Inter`, `JetBrains Mono`), Spaciaz architectural geometry (`#FAF8F5`, `#0E1520`, `#D4E845`, `#C8A24B`).
- **The Living Blueprint (Interactive CAD & Drone Aerial Master Plan)**: Dual-mode SVG/CSS cadastral layout with interactive plot inspection, live availability indicators, and instant cost sheet dossiers.
- **E-Commerce Real Estate Booking**: Treat every villa and plot like a live inventory product. 4-step e-commerce checkout with 5% online token advance, test payment simulation (UPI, Cards, NetBanking), 72-hour guaranteed unit holding, and instant allotment vouchers.
- **Admin E-Commerce Command Center (`/admin`)**: Real-time GMV tracking, token revenue collections, live inventory toggle (`available`, `on_hold`, `sold`), inline price editing, order management, and CSV/JSON catalog exports.
- **Patron Dashboard (`/dashboard`)**: Tracking of provisional holding vouchers, 5-stage construction-linked milestone payments, and JDA/RERA legal repositories.
- **Bilingual Localization (`EN` / `हिन्दी`)**: Instant session-based language switcher (`lang/en/messages.php` and `lang/hi/messages.php`).
- **16 Authentic Kedia Townships**: Seeders and SQL database dump for Kedia's Landmark, Kedia's Atulyam, Kedia's Tulsi, Ganpati Greens, Nikunj Villas, Kedia Aravali, etc.
- **Executive Leadership**: Authentically represents Founder & Chairman Mr. Rajendra Kedia, Director Mr. Saurabh Kedia, and CEO Mr. Gaurav Kedia.
- **Pure SQL Database (MySQL)**: Comes with ready-to-import `database/kedia_builders_db.sql` with full schema and all seeded records, plus Laravel Eloquent migrations and seeders.

---

## 📁 Architecture Overview

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php             # Spaciaz hero, ticker, stats, leadership
│   │   │   ├── ProjectController.php          # Catalog listing, dynamic filters, show
│   │   │   ├── UnitController.php             # Unit product page, live cost breakdown
│   │   │   ├── BookingController.php          # 4-step checkout, payment simulation, receipt
│   │   │   ├── AdminController.php            # E-Commerce KPI console, inventory toggle, exports
│   │   │   ├── CustomerDashboardController.php# Patron holding tracker, milestone payments
│   │   │   ├── LeadController.php             # Inbound VIP site visit & callback processing
│   │   │   ├── ToolsController.php            # Interactive EMI & Stamp Duty calculators
│   │   │   ├── PageController.php             # About, Gallery, NRI Desk, Careers, Contact, Legal
│   │   │   └── LanguageController.php         # Session-based EN <-> HI toggle
│   │   └── Middleware/
│   │       └── SetLocaleMiddleware.php        # Language interceptor
│   └── Models/
│       ├── Project.php                        # Eloquent relationship with Units
│       ├── Unit.php                           # Dynamic computePricing() breakdown
│       ├── Booking.php                        # Casts for payment schedules & cost sheets
│       └── SiteVisitLead.php                  # Inbound lead tracking
├── database/
│   ├── kedia_builders_db.sql                  # Complete MySQL database dump (Schema + seed data, UTF-8)
│   ├── migrations/
│   │   ├── 2026_01_01_000001_create_projects_table.php
│   │   ├── 2026_01_01_000002_create_units_table.php
│   │   ├── 2026_01_01_000003_create_bookings_table.php
│   │   └── 2026_01_01_000004_create_site_visit_leads_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── ProjectSeeder.php                  # All 16 authentic Kedia projects
│       ├── UnitSeeder.php                     # 120+ real inventory plots & villas
│       └── BookingSeeder.php                  # Initial seed reservations
├── lang/
│   ├── en/messages.php                        # English dictionary
│   └── hi/messages.php                        # Hindi dictionary
├── public/
│   ├── assets/                                # Official Kedia logos, CSS, JS
│   ├── media/img/                             # Authentic leadership & project archives
│   └── index.php                              # Laravel front controller
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php                  # Master Spaciaz layout, modals & toasts
│       ├── components/
│       │   ├── navbar.blade.php               # Header with language pill, admin button
│       │   ├── footer.blade.php               # Architectural footer with RERA/JDA certifications
│       │   └── living-blueprint.blade.php     # CAD & Aerial Drone interactive sector plan
│       └── pages/
│           ├── home.blade.php                 # Split hero, ticker, 3 cards, skyline stats
│           ├── projects/                      # index.blade.php & show.blade.php
│           ├── units/                         # show.blade.php
│           ├── booking/                       # checkout.blade.php & confirmation.blade.php
│           ├── admin/                         # dashboard.blade.php
│           ├── customer/                      # dashboard.blade.php
│           ├── about.blade.php
│           ├── gallery.blade.php
│           ├── tools.blade.php
│           ├── nri.blade.php
│           ├── careers.blade.php
│           ├── contact.blade.php
│           └── legal.blade.php
└── routes/
    ├── web.php                                # Complete web routes
    ├── api.php                                # RESTful AJAX unit queries
    └── console.php
```

---

## 🚀 Installation & MySQL Setup

### Requirements
- **PHP** >= 8.2 with `pdo_mysql` extension
- **MySQL** / MariaDB >= 8.0 (e.g. XAMPP, Laragon, Docker, or native MySQL server)
- **Composer** (PHP Package Manager)

### Step 1: Clone Repository
```bash
git clone https://github.com/ayush4002/kedia-web-2.git
cd kedia-web-2
```

### Step 2: Install Composer Dependencies
```bash
composer install
```

### Step 3: Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```
Ensure your MySQL credentials match in `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kedia_builders_db
DB_USERNAME=root
DB_PASSWORD=
```

Set the admin console login (the admin console at `/admin` stays closed while the password is blank):
```ini
ADMIN_USER=admin
ADMIN_PASSWORD=choose-a-strong-password
```

### Step 4: Setup Database (Choose Option A or Option B)

#### Option A: Direct SQL Dump Import (Instant)
Import the provided UTF-8 MySQL dump (built from the migrations and seeders) via command line or phpMyAdmin:
```bash
mysql -u root -p --default-character-set=utf8mb4 kedia_builders_db < database/kedia_builders_db.sql
```

#### Option B: Laravel Artisan Migrations & Seeders
```bash
# Creates tables and seeds all 16 projects and 120 inventory units
php artisan migrate --seed
```

#### Database imported from the old (pre-November 2026) dump?
That dump was UTF-16 and turned characters like `·`, `₹` and `’` into `??`, and it had no migrations table. Repair it in place (statuses, prices and bookings are untouched), then apply new migrations:
```bash
php artisan kedia:repair-db --baseline
php artisan migrate
```

### Step 5: Start Development Server
```bash
php artisan serve
```
Open **`http://127.0.0.1:8000`** in your browser.

---

## 🔑 Key Routes & URLs

| Route | Name | Description |
|---|---|---|
| `/` | `home` | Spaciaz Split Hero, 3 Signature Cards, Ticker, Stats & Living Blueprint |
| `/projects` | `projects.index` | Filterable catalog by type (villa/plot/apartment) and status |
| `/projects/{slug}` | `projects.show` | Township dossier, RERA details, sector blueprint & available units |
| `/projects/{slug}/units/{code}` | `units.show` | Unit product page, 2D floorplan blueprint, live price breakup (unit codes are unique only within a project) |
| `/booking/{unitId}` | `booking.checkout` | 4-step checkout & 5% test token reservation (held as *Pending Verification* until confirmed in admin) |
| `/booking/confirmation/{id}`| `booking.confirmation` | Official allotment voucher & printable payment receipt |
| `/dashboard` | `customer.dashboard`| Patron holding tracker & milestone payment schedules |
| `/admin` | `admin.dashboard` | E-Commerce Command Center (GMV, unit status toggle, price updater, CSV exports) |
| `/language/{locale}` | `language.switch` | Toggle between English (`en`) and Hindi (`hi`) |
| `/about` | `about` | 4+ decades legacy, leadership bios, vision & mission |
| `/gallery` | `gallery` | Project photos, Bhoomi Pujan archives & press coverage |
| `/tools` | `tools` | Interactive EMI loan calculator & Rajasthan stamp duty estimator |
| `/nri` | `nri` | Dedicated NRI investment desk, FEMA & repatriation support |
| `/contact` | `contact` | Jaipur Headquarters address, phone, WhatsApp & inquiry desk |
| `/legal` | `legal` | RAJ-RERA sanction certificates & terms of holding |

---

## 📞 Corporate Information

- **Company**: Kedia Builders & Colonizers
- **Corporate Office**: Sikar Road & Bani Park, Jaipur, Rajasthan 302016
- **Direct Desk**: `+91 8302 500 500`
- **WhatsApp**: `+91 98291 84872`
- **Email**: `kediabuildersandcolonizers@gmail.com`
- **Lead RERA Sanction**: `RAJ/P/2021/1480`
