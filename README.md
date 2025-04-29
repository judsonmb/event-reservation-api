# Event Reservation API

An API for managing events, reservations, and reviews using Laravel and Sanctum for authentication.

## 🚀 Requirements

- PHP >= 8.1  
- Composer  
- Git  

> 🛑 No database server required – uses SQLite.

---

## 🛠️ Getting Started

Clone the repository and set up the project:

```bash
git clone https://github.com/judsonmb/event-reservation-api.git
cd event-reservation-api
composer install
cp .env.example .env
```

## ⚙️ Configure Environment

Ensure your `.env` file has the following for SQLite:

```env
DB_CONNECTION=sqlite
DB_DATABASE=${PWD}/database/database.sqlite
```

Then create the database file:

```bash
touch database/database.sqlite
```

Generate the application key:

```bash
php artisan key:generate
```

Run the migrations and seeders (optional):

```bash
php artisan migrate --seed
```

## 🧪 Run the Development Server

```bash
php artisan serve
```

The API will be available at: [http://localhost:8000](http://localhost:8000)

---

## 🔐 Authentication

This project uses Laravel Sanctum for token-based authentication.

- `POST /api/register` — Create an account  
- `POST /api/login` — Get a token  
- Use `Authorization: Bearer {token}` header for protected routes

---

## 🧪 Run Tests

```bash
php artisan test
```

---

## 📁 Project Structure

- `app/Services` — Business logic  
- `app/Http/Requests` — Form validation  
- `app/Http/Controllers` — API endpoints  

---