# 🥤 Soft Drink Web Organizer (Iași Edition)

## 📌 Descriere

Aplicația este orientată către gestionarea băuturilor non-alcoolice disponibile în **localuri, baruri, restaurante și marketuri din Iași**. Utilizatorii pot descoperi noi băuturi în funcţie de preferinţe, crea liste de băuturi pe care le-au încercat sau pe care şi-ar dori să le încerce, iar furnizorii (business-uri locale) pot adăuga și administra băuturile disponibile.

Sistemul gestionează informații precum: ingrediente, preț, alergeni, disponibilitate sezonieră, locație (local din Iași), și oferă statistici exportabile (CSV, SVG) și un clasament al produselor populare disponibil și ca RSS feed.

---

# 📁 Structura proiectului

```id="struct1"
soft-drink-organizer/
│
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   ├── php/
│   │   └── Dockerfile
│   ├── postgres/
│   │   └── init.sql
│   └── docker-compose.yml
│
├── backend/
│   ├── config/
│   │   ├── database.php
│   │   └── config.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   ├── Drink.php
│   │   ├── Category.php
│   │   ├── Ingredient.php
│   │   ├── ShoppingList.php
│   │   └── Statistics.php
│   │
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── DrinkController.php
│   │   ├── UserController.php
│   │   ├── ShoppingListController.php
│   │   └── StatisticsController.php
│   │
│   ├── routes/
│   │   └── api.php
│   │
│   ├── services/
│   │   ├── ExportService.php
│   │   ├── RSSService.php
│   │   └── OpenFoodFactsService.php
│   │
│   └── utils/
│       ├── Validator.php
│       └── Response.php
│
├── frontend/
│   ├── assets/
│   │   ├── css/
│   │   │   └── styles.css
│   │   ├── js/
│   │   │   ├── app.js
│   │   │   ├── api.js
│   │   │   └── charts.js
│   │   └── images/
│   │
│   ├── pages/
│   │   ├── index.html
│   │   ├── login.html
│   │   ├── dashboard.html
│   │   ├── drinks.html
│   │   ├── shopping-list.html
│   │   └── statistics.html
│   │
│   └── components/
│       ├── navbar.html
│       ├── footer.html
│       └── drink-card.html
│
├── database/
│   ├── schema.sql
│   └── seeds.sql
│
├── exports/
│   ├── csv/
│   └── svg/
│
├── rss/
│   └── feed.php
│
├── public/
│   └── index.php
│
└── README.md
```

---

# 🐳 1. Docker

## `docker/docker-compose.yml`

* Definește serviciile aplicației:

  * nginx (server web)
  * php (backend API)
  * postgres (baza de date)
* Configurează rețeaua dintre servicii

## `docker/nginx/default.conf`

* Servește frontend-ul
* Redirecționează request-urile `/api` către backend

## `docker/php/Dockerfile`

* Configurează mediul PHP
* Instalează extensii necesare (PDO PostgreSQL)

## `docker/postgres/init.sql`

* Inițializare bază de date

---

# ⚙️ 2. Backend (PHP API)

## 🔹 Config

### `backend/config/database.php`

* Conexiune la PostgreSQL

### `backend/config/config.php`

* Setări globale (URL, constante, mediu)

---

## 📦 Models

### `User.php`

* gestionare utilizatori normali
* autentificare

### `Provider.php`

* gestionare conturi furnizori (localuri din Iași)
* include informații despre locație

### `Drink.php`

* gestionare băuturi
* asociere cu furnizor (local)

### `Category.php`

* categorii (ceaiuri, lactate, sucuri etc.)

### `Ingredient.php`

* ingrediente

### `ShoppingList.php`

* liste cumpărături

### `Statistics.php`

* calcul statistici (popularitate, consum)

---

## 🎮 Controllers

### `AuthController.php`

* login/register (user + furnizor)

### `DrinkController.php`

* CRUD băuturi
* filtrare (categorie, locație, sezon)

### `ProviderController.php`

* gestionare furnizori
* adăugare localuri din Iași

### `ShoppingListController.php`

* liste cumpărături

### `StatisticsController.php`

* generare statistici

---

## 🛣️ Routes

### `routes/api.php`

* endpoint-uri API:

```id="routes1"
GET /api/drinks
GET /api/drinks?location=iasi
POST /api/drinks (furnizor)
POST /api/login
GET /api/providers
GET /api/statistics
```

---

## 🔧 Services

### `ExportService.php`

* export CSV
* generare grafice SVG

### `RSSService.php`

* feed RSS cu cele mai populare băuturi din Iași

### `OpenFoodFactsService.php`

* integrare API extern (bonus)

---

## 🧰 Utils

### `Validator.php`

* validare input

### `Response.php`

* formatare răspuns JSON

---

# 🌐 3. Frontend

## 📄 Pages

### `index.html`

* pagină principală (prezentare + locații din Iași)

### `login.html`

* autentificare (user / furnizor)

### `dashboard.html`

* overview aplicație

### `drinks.html`

* listă băuturi disponibile în Iași

### `providers.html`

* listă localuri / furnizori

### `shopping-list.html`

* liste cumpărături

### `statistics.html`

* statistici și grafice

---

## 🎨 Assets

### `css/styles.css`

* stilizare UI

### `js/app.js`

* logică aplicație

### `js/api.js`

* comunicare cu backend

### `js/charts.js`

* generare grafice SVG

### `images/`

* imagini produse

---

## 🧩 Components

### `navbar.html`

* navigare

### `footer.html`

* subsol

### `drink-card.html`

* afișare produs

---

# 🗄️ 4. Database

## `database/schema.sql`

Tabele principale:

* users
* providers (localuri din Iași)
* drinks
* categories
* ingredients
* drink_ingredients
* shopping_lists
* shopping_list_items

## `database/seeds.sql`

* date demo (localuri din Iași + produse)

---

# 📤 5. Exporturi

## `exports/csv/`

* statistici exportate

## `exports/svg/`

* grafice generate

---

# 📡 6. RSS

## `rss/feed.php`

* top băuturi populare din Iași

---

# 🌍 7. Public

## `public/index.php`

* entry point pentru API

---

# 👥 Tipuri de utilizatori

## 👤 User

* vizualizează băuturi
* creează liste
* vede statistici

## 🏪 Furnizor (local/bar/market)

* își creează cont
* adaugă băuturi
* gestionează disponibilitatea
* vede popularitatea produselor

---

# 📅 Planificare pe 5 săptămâni

## 🗓️ Săptămâna 1 – Setup & DB

* configurare Docker
* creare DB
* definire tabele
* test conexiune

---

## 🗓️ Săptămâna 2 – Backend API

* modele (User, Provider, Drink)
* autentificare
* CRUD băuturi (pentru furnizori)

---

## 🗓️ Săptămâna 3 – Frontend

* UI pagini principale
* listare băuturi din Iași
* login/register

---

## 🗓️ Săptămâna 4 – Funcționalități

* liste cumpărături
* filtrare după locație (localuri)
* afișare furnizori

---

## 🗓️ Săptămâna 5 – Finalizare

* statistici
* export CSV + SVG
* RSS feed
* integrare Open Food Facts (bonus)
* testare finală

---

# ✅ Funcționalități finale

* gestionare băuturi pe locații din Iași
* conturi furnizori
* liste cumpărături
* statistici
* export CSV & SVG
* RSS feed
* integrare API extern (bonus)

---

