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
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->boolean('featured')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->json('meta_data')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}