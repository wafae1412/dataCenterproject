# 🚀 Guide des Commandes - DataCenter Project

## 📦 Installation & Setup

### 1. Cloner et Configurer
```bash
# Aller au répertoire
cd c:\wamp64\www\DataCenter_project

# Copier le fichier d'environnement
copy .env.example .env

# Générer la clé d'application
php artisan key:generate

# Installer les dépendances Composer
composer install

# Installer les dépendances npm
npm install
```

### 2. Base de Données
```bash
# Créer les tables
php artisan migrate

# Remplir avec données test
php artisan db:seed

# Ou seeder spécifique
php artisan db:seed --class=RolesSeeder
php artisan db:seed --class=UsersSeeder
php artisan db:seed --class=CategoriesSeeder
php artisan db:seed --class=ResourcesSeeder

# Rollback dernière migration
php artisan migrate:rollback

# Rollback tout + reseed
php artisan migrate:refresh --seed
```

### 3. Lancer l'Application
```bash
# Serveur de développement
php artisan serve
# Accès: http://localhost:8000

# En arrière-plan (Windows)
start php artisan serve

# Avec port spécifique
php artisan serve --port=8001
```

### 4. Assets (CSS/JS)
```bash
# Watch mode (recompile automatiquement)
npm run watch

# Compilation production
npm run production

# Compilation développement
npm run dev
```

---

## 🔐 Authentification

### Comptes Test
```
Admin:
  Email: admin@datacenter.com
  Password: admin123

Responsable:
  Email: responsable@datacenter.com
  Password: responsable123

User:
  Email: user@datacenter.com
  Password: user123
```

### Reset Password
```bash
# CLI pour reset (Tinker)
php artisan tinker
>>> App\Models\User::where('email', 'admin@datacenter.com')->update(['password' => Hash::make('newpassword')])
>>> exit
```

---

## 🗄️ Base de Données

### Commandes Utiles
```bash
# Afficher toutes les migrations
php artisan migrate:status

# Voir les tables
php artisan tinker
>>> Schema::getConnection()->getSchemaBuilder()->getTables()

# Voir colonnes d'une table
>>> Schema::getColumnListing('users')

# Dump SQL
>>> DB::table('users')->toSql()
```

### Tinker (Shell Interactif)
```bash
php artisan tinker

# Voir tous les users
>>> App\Models\User::all()

# Créer un utilisateur
>>> App\Models\User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => Hash::make('test123'), 'role_id' => 1])

# Voir réservations
>>> App\Models\Reservation::with('user', 'resource')->get()

# Compter réservations
>>> App\Models\Reservation::count()

# Quitter
>>> exit
```

---

## 🧪 Tests

### PHPUnit
```bash
# Lancer tous les tests
vendor/bin/phpunit

# Test spécifique
vendor/bin/phpunit tests/Feature/YourTestFile.php

# Test avec verbosité
vendor/bin/phpunit -v

# Test single test method
vendor/bin/phpunit --filter testMethodName
```

### Manual Testing Checklist
```bash
# 1. Installation
php artisan serve

# 2. Login
# Aller à http://localhost:8000
# Login avec admin@datacenter.com / admin123

# 3. Tester Réservations
# Aller à /reservations
# Créer une réservation
# Vérifier statut pending
# Logout → login admin → approuver

# 4. Tester Dashboard
# Vérifier /dashboard (user)
# Vérifier /admin/dashboard (admin)
# Vérifier /responsable/dashboard (responsable)

# 5. Tester Sécurité
# Essayer /admin/* en tant que user → erreur 403
```

---

## 🛠️ Maintenance

### Cache
```bash
# Clear tous les caches
php artisan cache:clear

# Clear config cache
php artisan config:cache

# Clear route cache
php artisan route:cache

# Clear view cache
php artisan view:clear

# Clear application cache
php artisan cache:forget <key>
```

### Storage & Logs
```bash
# Voir logs
tail -f storage/logs/laravel.log

# Clear log file
echo "" > storage/logs/laravel.log

# Permissions storage
chmod -R 775 storage

# Symlink public storage
php artisan storage:link
```

### Seeders
```bash
# Créer nouveau seeder
php artisan make:seeder MySeeder

# Exécuter seeder spécifique
php artisan db:seed --class=MySeeder
```

---

## 🔍 Debugging

### Logging
```php
// Dans votre controller
\Log::info('Message', ['data' => $variable]);
\Log::error('Error', ['exception' => $e]);

// Voir logs en temps réel
tail -f storage/logs/laravel.log
```

### Debug Dumper
```php
// Afficher et quitter
dd($variable);

// Afficher seulement
dump($variable);
var_dump($variable);
```

### Debugging Routes
```bash
# Lister toutes les routes
php artisan route:list

# Routes spécifiques
php artisan route:list --name=reservations

# Voir URI pattern
php artisan route:list | grep "reservations"

# Middleware info
php artisan route:list --show-model
```

### Database Debugging
```bash
# Activer query logging
# Dans .env ou config/database.php
// Puis voir dans logs

# Ou en Tinker:
>>> DB::enableQueryLog()
>>> // ... vos commandes ...
>>> dd(DB::getQueryLog())
```

---

## 📝 Migrations Courantes

### Créer une Migration
```bash
# Créer seule
php artisan make:migration create_table_name_table

# Avec model
php artisan make:model ModelName -m

# Modifier table
php artisan make:migration add_column_to_table_name
```

### Migration Template
```php
// up()
Schema::create('table', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});

// down()
Schema::dropIfExists('table');
```

---

## 🔧 Configuration

### .env Fichier
```bash
# App
APP_NAME=DataCenter
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=datacenter_db
DB_USERNAME=root
DB_PASSWORD=

# Mail (optionnel)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
```

### Database Connection
```bash
# Vérifier connexion
php artisan tinker
>>> DB::connection()->getPdo()
>>> // Si OK: PDO Object

# Ou
>>> config('database.connections.mysql')
```

---

## 🐛 Problèmes Courants & Solutions

### Problème: "Class not found"
```bash
# Solution: Regénérer l'autoloader
composer dumpautoload

# Ou
composer dump-autoload -o
```

### Problème: "CSRF token mismatch"
```php
# S'assurer que dans le form:
@csrf

# Ou en Blade:
{{ csrf_field() }}
```

### Problème: "No query results"
```php
# Utiliser findOrFail() pour éviter
$model = Model::findOrFail($id); // 404 si pas trouvé

# Ou vérifier
if (!$model) {
    abort(404);
}
```

### Problème: "Middleware not found"
```bash
# Vérifier dans app/Http/Kernel.php
# Ajouter dans $routeMiddleware si manquant
'role' => \App\Http\Middleware\CheckRole::class,

# Puis redémarrer serveur
```

### Problème: Database locked
```bash
# Supprimer le fichier database si SQLite
rm database/database.sqlite

# Ou restart service MySQL
# Windows: Services → Restart MySQL
# Linux: sudo systemctl restart mysql
```

---

## 📚 Ressources Utiles

### Documentation Officielle
- Laravel: https://laravel.com/docs/8.x
- Eloquent: https://laravel.com/docs/8.x/eloquent
- Blade: https://laravel.com/docs/8.x/blade

### Commandes Artisan Courantes
```bash
php artisan              # Liste toutes les commandes
php artisan help         # Help sur une commande
php artisan list         # Lister les commandes par catégorie
php artisan make:*       # Créer différents fichiers
php artisan migrate      # Exécuter migrations
php artisan tinker       # Shell PHP interactif
```

### Fichiers Importants
- `routes/web.php` - Routes web
- `app/Http/Controllers/` - Contrôleurs
- `resources/views/` - Vues Blade
- `app/Models/` - Modèles
- `.env` - Variables d'environnement
- `config/` - Configuration
- `database/migrations/` - Migrations
- `database/seeders/` - Données test
- `public/` - Assets publics
- `storage/` - Logs et fichiers

---

## 🚀 Déploiement (Production)

### Préparation
```bash
# 1. Production .env
cp .env.production .env

# 2. Optimisation
php artisan config:cache
php artisan route:cache
php artisan optimize

# 3. Minifier assets
npm run production

# 4. Permissions
chmod -R 775 storage bootstrap/cache
chmod -R 755 public

# 5. Restart
php artisan restart
```

### Vérifications
```bash
# Vérifier version PHP
php --version

# Vérifier extensions
php -m | grep -i mysql

# Vérifier permissions
ls -la storage/
ls -la bootstrap/cache/

# Vérifier database
php artisan migrate --env=production
```

---

## 📞 Support Commands

```bash
# Infos système
php artisan about

# Version Laravel
php artisan --version

# Vérifier configuration
php artisan config:show

# Storage link
php artisan storage:link

# Optimize autoloader
composer dump-autoload -o

# Clear everything
php artisan optimize:clear
```

---

**Astuce**: Gardez ce guide sous la main pour les commandes fréquentes!

**Date**: 16 Janvier 2026
