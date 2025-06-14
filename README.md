# 🔥💚 Glow Green Boiler API Project – Laravel Test 💚🔥

## By Tim Golding

This Laravel API application is a test project for Glow Green, designed to demonstrate working knowledge of Laravel backend principles, RESTful API design, and data normalization.

---

This project is a coding test submission for Glow Green Ltd, completed by Tim Golding.
It demonstrates Laravel REST API development, database normalization, and secure data handling via Sanctum.

## Features

- Fetches boiler data from Glow Green's external API.
- Normalizes data into relational tables: `boilers`, `manufacturers`, and `fuel_types`.
- Provides a REST API for CRUD operations.
- Optional authentication layer.
- Includes search filtering capabilities.
- Soft deletes with Laravel's built in support.

---

## Technologies

- Laravel 10
- MySQL
- PHP 8+
- Laravel Sanctum
- Postman Collection

---

##  Glow Green API

**Endpoint:**  
GET https://api.glowgreenltd.com/api/2025-test/boilers

**Header Auth:**  
`X-GG-API-Key: ***REMOVED***`

---

## Database Schema

- `boilers`  
  - id  
  - name  
  - manufacturer_id  
  - fuel_type_id  
  - manufacturer_part_number  
  - sku  
  - url  
  - description  
  - timestamps  
  - softDeletes  

- `manufacturers`  
  - id  
  - name  
  - timestamps  
  - softDeletes  

- `fuel_types`  
  - id  
  - name  
  - fuel_type_ref  
  - timestamps  
  - softDeletes  

All tables use foreign keys to ensure referential integrity.

---

## API Routes

| Method | Endpoint                     | Description                      |
|--------|------------------------------|----------------------------------|
| GET    | `/api/boilers`               | List all boilers (filterable)    |
| GET    | `/api/boilers/{id}`          | Get single boiler details        |
| POST   | `/api/boilers`               | Create a new boiler              |
| PUT    | `/api/boilers/{id}`          | Update a boiler                  |
| DELETE | `/api/boilers/{id}`          | Soft delete a boiler             |
| GET    | `/api/boilers/trashed`       | View soft-deleted boilers        |
| POST   | `/api/boilers/{id}/restore`  | Restore a soft-deleted boiler    |

**Filter examples:**  
`/api/boilers?manufacturer=Vaillant&fuel_type=Gas`


## Postman Collection

All API endpoints have been tested using Postman.

You can find the collection here:

`/postman/glowgreen.postman_collection.json`

> To use: Import into Postman → Set `Authorization` header (`Bearer {token}`) → Start testing!

---

## Tests

- PHPUnit
- Example tests for:
  - API CRUD actions
  - Validation
  - Soft deletion

---

## Authentication

- This API uses [Laravel Sanctum](https://laravel.com/docs/sanctum) for token-based authentication.
- Include the header: `Authorization: Bearer <token>`
- Use `/login` or `/sanctum/token` to obtain an API token (not implemented in this test)

## Authorization

Currently not implemented.

Consider adding model-level policies (Laravel `Gate` or `Policy`), or full role-based access control with `spatie/laravel-permission` for more complex user permission handling.

### 1 create user in tinker

```bash
php artisan tinker
```

### 2 here is example code to paste in to tinker

```php
$user = \App\Models\User::factory()->create([
    'name' => 'Timmy G',
    'email' => 'timmy@example.com',
    'password' => bcrypt('password123')
]);

$token = $user->createToken('PostmanClient')->plainTextToken;
echo $token;
```
Copy the output token.

### 3. Use the Bearer token you copied from above to authenticate all requests:

**Header**
```makefile
Authorization: Bearer YOUR_API_TOKEN
Accept: application/json
```

**Endpoint**
```nginx
GET http://localhost/api/boilers
```
---

## Setup Instructions

```bash
git clone https://github.com/timgolding/glow-green-ltd.git
cd glow-green-ltd
composer install
cp .env.example .env
php artisan key:generate

# Add your DB config and API Key and URL in .env
# Add the following to your .env file:
GLOW_GREEN_API_KEY=<API_KEY>
GLOW_GREEN_API_URL=https://api.glowgreenltd.com/api/2025-test/boilers
php artisan migrate
php artisan db:seed
php artisan serve
php artisan test
# Run only boiler-related tests
php artisan test --filter=BoilerApiTest
```
