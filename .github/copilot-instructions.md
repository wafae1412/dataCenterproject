# DataCenter Project - AI Coding Agent Guide

## Project Overview
**DataCenter** is a Laravel 8 application for managing datacenter resources, reservations, and maintenance. It uses role-based access control (Admin, Responsable, User) with a MySQL database, custom CSS (no Bootstrap/Tailwind/jQuery), and Blade templating.

## Architecture & Key Components

### MVC Structure
- **Models**: User, Role, Resource, Category, Reservation, Maintenance, Notification (in [app/Models/](../app/Models/))
- **Controllers**: ResourceController, ReservationController, CategoryController, MaintenanceController, NotificationController + Admin & Responsable subdirectories (in [app/Http/Controllers/](../app/Http/Controllers/))
- **Views**: Organized by feature in blade templates (in [resources/views/](../resources/views/))

### Database & Relationships
**Key Models & Relations:**
- `User`: belongsTo Role → hasMany Reservations, Notifications
- `Resource`: belongsTo Category → hasMany Reservations, Maintenances
- `Reservation`: belongsTo User, Resource (fields: user_id, resource_id, date_start, date_end, status, justification)
- `Role`: hasMany Users (roles: Admin, Responsable, User)

**Schema Notes:**
- All tables use timestamps (created_at, updated_at)
- Reservation.status: pending, approved, rejected, active, finished
- Resource.status: available, occupied, maintenance

### Authentication & Authorization
- **Guard**: Session-based (web) using Laravel Sanctum
- **Middleware**: CheckRole in [app/Http/Middleware/CheckRole.php](../app/Http/Middleware/CheckRole.php) validates role-based access
- **Route Pattern**: Protected routes use `middleware(['auth', 'role:RoleName'])` (see [routes/web.php](../routes/web.php) for examples)
- **Role Checks**: User model has `isAdmin()`, `isResponsable()` methods

### Controllers Pattern
Controllers follow standard Laravel conventions:
- **index()**: List records with eager loading (e.g., `Resource::with('category')`)
- **store()**: Validate with `$request->validate()`, create model, redirect with success message
- **Middleware-based authorization**: Role checks handled at route level, not in controller methods

## Development Workflows

### Running the Application
```bash
# Install dependencies
composer install
npm install

# Database setup
php artisan migrate
php artisan db:seed

# Development server
php artisan serve

# Watch assets
npm run watch
```

### Testing
```bash
# Run tests
vendor/bin/phpunit

# Test a specific file
vendor/bin/phpunit tests/Feature/YourTest.php
```

### Common Artisan Commands
```bash
php artisan make:model ModelName -m  # Create model + migration
php artisan make:migration table_name
php artisan make:controller NameController
php artisan tinker  # Interactive shell
```

## Project-Specific Conventions

### Frontend - Custom CSS (No External Frameworks)
- **CSS Location**: [public/css/app.css](../public/css/app.css)
- **No Bootstrap/Tailwind/jQuery** - pure CSS3 with Flexbox/Grid
- **Classes**: Use semantic, short names (e.g., `.btn-primary`, `.table-striped`, `.card`)
- **Responsive**: Mobile-first approach with media queries

### Blade Templating
- **Layout**: [resources/views/layouts/app.blade.php](../resources/views/layouts/app.blade.php) - base template with navigation
- **Auth Check**: Use `@auth` / `@guest` directives and `Auth::user()`
- **Role Check**: Use `@if(Auth::user()->isAdmin())` or `Auth::user()->role->name === 'Admin'`
- **Error Display**: `@if($errors->any())` with `{{ $errors->first() }}`

### Form Validation
```php
$request->validate([
    'field' => 'required|string|max:255|unique:table_name',
    'email' => 'required|email',
    'foreign_key' => 'required|exists:related_table,id'
]);
```

### Model Queries
- Use **eager loading** to prevent N+1 queries: `Resource::with('category')->get()`
- Use **Eloquent scopes** for reusable query filters
- Order by `created_at` descending for most lists

### Form Submission Pattern
1. Validate input with `$request->validate()`
2. Create/update model: `Model::create($request->all())` or model->update()
3. Redirect with flash message: `redirect()->route('route.name')->with('success', 'Message')`
4. Display flash messages in Blade: `@if(session('success')) <div>{{ session('success') }}</div>`

## Middleware & Authorization

### CheckRole Middleware
- Located: [app/Http/Middleware/CheckRole.php](../app/Http/Middleware/CheckRole.php)
- Usage: `Route::middleware('role:Admin,Responsable')->group(...)`
- Redirects unauthenticated users to /login, returns 403 for unauthorized roles

### When Adding New Features
1. Add middleware check in **routes** (not controllers) for role-based access
2. Use **eager loading** in controllers to avoid database queries in views
3. Validate input **before** creating/updating models
4. Generate **notifications** automatically when relevant (e.g., on reservation approval)

## Integration Points

### Database Seeders
- [database/seeders/](../database/seeders/): RolesSeeder, UsersSeeder, CategoriesSeeder, ResourcesSeeder
- Run: `php artisan db:seed` or `php artisan db:seed --class=RolesSeeder`

### Routes Organization
- **Web Routes** ([routes/web.php](../routes/web.php)): Public & authenticated routes, admin/responsable/user pages
- **API Routes** ([routes/api.php](../routes/api.php)): Sanctum-protected endpoints (minimal currently)

### Common File Locations
- Config: [config/](../config/) - app.php, auth.php, database.php
- Migrations: [database/migrations/](../database/migrations/)
- Database: MySQL (configured in .env)

## Code Examples from This Project

### Creating a Resource (Controller Pattern)
See [ResourceController::store()](../app/Http/Controllers/ResourceController.php#L33) - validates fields, sets status, creates model.

### Role-Based Routes
See [routes/web.php](../routes/web.php#L38) - Admin dashboard protected with `middleware(['auth','role:Admin'])`.

### Model Relationships
See [Reservation model](../app/Models/Reservation.php) - belongsTo User and Resource with nullable timestamps.

## Critical Do's and Don'ts

✅ **DO:**
- Use eager loading (`.with('relation')`) in controllers
- Validate ALL user input with `$request->validate()`
- Add role checks at route level, not in controllers
- Use Laravel's built-in helpers (Auth::user(), redirect(), session())
- Order list views by created_at DESC for reverse chronological display

❌ **DON'T:**
- Add Bootstrap, Tailwind, or jQuery - use custom CSS only
- Query models in Blade templates (do it in controllers)
- Skip input validation
- Mix API and web route logic
- Use direct SQL queries when Eloquent is available

## When Extending the Project
1. Follow **existing naming conventions** (ResourceController, not ResourceMgr)
2. Update migrations with foreign key constraints: `$table->foreignId('resource_id')->constrained()`
3. Add relationships to models immediately after field changes
4. Test role-based access with `Auth::user()->role->name` checks
5. Keep custom CSS in [public/css/app.css](../public/css/app.css) with semantic class names
