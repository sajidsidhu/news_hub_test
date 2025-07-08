# News Hub Backend (Laravel)

A Laravel backend for news aggregation, supporting NewsAPI, NYT, and The Guardian. Features robust article/source models, scheduled imports, flexible search/filter API endpoints, and secure API access using Sanctum.

## Requirements
- PHP >= 8.1
- Composer
- MySQL or compatible DB
- Laravel 10+

## Setup Instructions

1. **Clone the repository**
   ```sh
   git clone https://github.com/sajidsidhu/news_hub_test.git
   cd news_hub_test
   ```

2. **Install dependencies**
   ```sh
   composer install
   ```

3. **Copy and configure environment**
   ```sh
   cp .env.example .env
   # Edit .env and set your DB credentials and API keys for NewsAPI, NYT, Guardian
   ```

4. **Generate application key**
   ```sh
   php artisan key:generate
   ```

5. **Run migrations**
   ```sh
   php artisan migrate
   ```

6. **(Optional) Seed demo data**
   ```sh
   php artisan db:seed
   ```

7. **Run the scheduler**
   - Add this cron entry to your server (for scheduled article imports):
     ```sh
     * * * * * cd /path/to/news_hub_test && php artisan schedule:run >> /dev/null 2>&1
     ```

8. **Start the server**
   ```sh
   php artisan serve
   ```

## API Authentication (Sanctum)
All API endpoints are protected with Sanctum. Authenticate using Bearer tokens.

### Register (if enabled)
```
POST /api/register
{
  "name": "User Name",
  "email": "user@example.com",
  "password": "password"
}
```

### Login
```
POST /api/login
{
  "email": "user@example.com",
  "password": "password"
}
```
**Response:**
```
{
  "user": { ... },
  "token": "<token>"
}
```
Use the `token` as a Bearer token in the `Authorization` header for all subsequent requests.

### Logout
```
POST /api/logout
Authorization: Bearer <token>
```

## API Endpoints
All endpoints require Bearer token authentication (except login/register).

- `POST /api/articles` — Search/filter articles (see ArticleController for params)
- `GET /api/categories` — List available categories
- `GET /api/sources` — List available sources
- `POST /api/logout` — Logout (invalidate token)

## Scheduled Imports
Articles are fetched hourly from NewsAPI, NYT, and The Guardian using scheduled Artisan commands. You can run them manually:
- `php artisan fetch:news`
- `php artisan fetch:nytimes-articles`
- `php artisan fetch:guardian-articles`

## Environment Variables
Set the following in your `.env`:
```
NEWSAPI_KEY=your_newsapi_key
NYT_KEY=your_nyt_key
GUARDIAN_KEY=your_guardian_key
```

## Testing
You can use Postman or curl to test the endpoints. Always include the Bearer token after login.

---

For more details, see the code in `app/Http/Controllers/Api/ArticleController.php` and the service classes in `app/Services/`.

---
