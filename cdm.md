

# install laravel
bootcamp

# install breeze 
composer require laravel/breeze --dev

php artisan breeze:install livewire

npm run dev

# modelos 

php artisan make:model -mc  category 

$table->string('name');
$table->string('slug')->unique();
$table->string('image')->nullable();
$table->boolean('is_active')->default(true);

php artisan make:model -mc  brand

$table->string('name');
$table->string('slug')->unique();
$table->string('image')->nullable();
$table->boolean('is_active')->default(true);

php artisan make:model -mc  product

$table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
$table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
$table->string('name');
$table->string('slug')->unique();
$table->string('image')->nullable();

            $table->json('images')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10,2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('in_stock')->default(true);
            $table->boolean('on_sale')->default(false);
            $table->string('medida')->nullable();

php artisan make:model -mc movimiento

# seeders 

php artisan make:seeder BrandsSeeder
php artisan make:seeder CategoriesSeeder
php artisan make:seeder ProductsSeeder

# filament



composer require filament/filament:"^3.2" -W

php artisan filament:install --panels

php artisan make:filament-user

php artisan storage:link

php artisan make:filament-resource User

# translations
 php artisan vendor:publish --tag=filament-panels-translations
 php artisan vendor:publish --tag=filament-actions-translations

php artisan vendor:publish --tag=filament-forms-translations

php artisan vendor:publish --tag=filament-infolists-translations

php artisan vendor:publish --tag=filament-notifications-translations

php artisan vendor:publish --tag=filament-tables-translations

php artisan vendor:publish --tag=filament-translations


# resources

php artisan make:filament-resource Category --generate
php artisan make:filament-resource Brand --generate

php artisan make:filament-resource Product --generate

php artisan make:filament-resource Movimiento --generate

** 
php artisan make:filament-resource User --generate
ojo password de filament

php artisan make:filament-resource Acceso --generate



# install preline 

npm install preline // or yarn add preline


# BOOTCAMP--LIVEWIRE


php artisan make:model -mc Acceso
route  -> AccesoController index 
Controller -> view   /resources/views/accesos.blade.php 



php artisan make:volt chirps/create --class
/resources/views/livewire/accesos/create.blade.php


php artisan make:policy TagPolicy --model=Tag


# Añadir teclado_id a user

php artisan composer make:migration add_teclado_id_to_users

# Notificaciones 

php artisan make:notification NuevoAcceso


# FRONTEND - DCODEMANIA
* HOMEPAGE
php artisan make:livewire HomePage


php artisan livewire:layout

php artisan make:livewire partials.navbar
php artisan make:livewire partials.footer


# filtros de laravel daily 

# export csd EXPORT ACTION filamentphp
# Laravel 11 and higher
php artisan make:queue-batches-table
php artisan make:notifications-table

->headerActions([
ExportAction::make()
->exporter(ProductExporter::class)
]);

php artisan make:filament-exporter Product

php artisan make:filament-exporter Acceso --generate
Filament exporter [app/Filament/Exports/AccesoExporter.php] created successfully


# FILTROS LIVEWIRE3 CDW
php artisan ma



# rama main 
php artisan  make:migration add_rendimiento2_to_products
php artisan  make:migration add_rendimiento2_to_movimientos

# Roles y Permisos 

php artisan make:model Role -m 
php artisan make:migration add_role_to_users 

user role_id 
    role()
    admin()

php artisan make:policy MovimientoPolicy --model=Movimiento
php artisan make:policy UserPolicy --model=User
php artisan make:policy AccesoPolicy --model=Acceso
php artisan make:policy CategoryPolicy --model=Category
php artisan make:policy BrandPolicy --model=Brand
php artisan make:policy CategoryPolicy --model=Category
php artisan make:policy ProductPolicy --model=Product



# notification 

php artisan make:notifications-table


php artian queue:work

## activar en servidor 
