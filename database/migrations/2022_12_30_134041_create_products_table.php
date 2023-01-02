<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('title', 150);
            $table->string('keywords')->nullable();
            $table->longText('content')->nullable();
            $table->string('image', 75)->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('price')->nullable();
            $table->string('quantity')->nullable()->default(1);
            $table->decimal('sale_price')->nullable();
            $table->text('detail')->nullable();
            $table->string('slug', 100)->nullable();
            $table->string('status', 5)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
