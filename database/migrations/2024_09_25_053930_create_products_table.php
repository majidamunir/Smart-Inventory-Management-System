<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->string('name'); // Product name
            $table->text('description')->nullable(); // Product description
            $table->integer('quantity'); // Current stock quantity
            $table->decimal('price', 8, 2); // Product price
            $table->integer('reorder_level')->default(0); // Minimum quantity for reorder
            $table->foreignId('category_id')->constrained('categories'); // Foreign key referencing categories
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}

