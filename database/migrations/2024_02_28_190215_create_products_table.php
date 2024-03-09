<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('pid');
            $table->string('name', 255); // Set the length to 255 characters
            $table->text('description'); // Change the data type to text for description
            $table->string('price');
            $table->string('file_path');
            $table->string('user_id');
            $table->timestamp('expiration_time')->nullable(); // Add expiration_time field
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};