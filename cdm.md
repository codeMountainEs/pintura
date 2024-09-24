

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



