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
        Schema::create('novels', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->unique(); // Novel title must be unique
            $table->string('author', 50); // Author name
            $table->string("cover", 255)->nullable(); // Cover image URL
            $table->string("intro")->nullable(); // Introduction
            $table->unsignedBigInteger('category_id'); // Foreign key to categories table
            $table->tinyInteger('status')->default(0); // Status: 0 - ongoing, 1 - completed, 2 - hiatus
            $table->timestamps("created_at", "updated_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novels');
    }
};
