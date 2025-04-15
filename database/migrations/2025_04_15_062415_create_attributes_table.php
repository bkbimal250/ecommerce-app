<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['select', 'radio', 'checkbox', 'text'])->default('select');
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attributes');
    }
}