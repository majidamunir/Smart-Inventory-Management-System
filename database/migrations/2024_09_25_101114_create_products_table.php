<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key for the product
            $table->string('sku')->unique(); // Unique stock-keeping unit
            $table->string('name'); // Product name
            $table->text('description')->nullable(); // Product description
            $table->integer('quantity'); // Current stock quantity
            $table->decimal('price', 8, 2); // Product price
            $table->integer('reorder_level')->default(0); // Minimum quantity for reorder
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Foreign key to categories
            $table->timestamps(); // Created at, updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
