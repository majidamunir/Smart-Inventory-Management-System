<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id(); // Primary key for each line item
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade'); // Links to the transactions table
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Links to the products table
            $table->integer('quantity'); // Quantity of product sold
            $table->decimal('price_at_sale', 8, 2); // Product price at the time of sale
            $table->decimal('subtotal', 10, 2); // Subtotal for the product (quantity * price_at_sale)
            $table->timestamps(); // Created at and updated at timestamps (when the line item was added)
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
}
