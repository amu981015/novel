<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // 2. novels migration
public function up()
{
    Schema::create('novels', function (Blueprint $table) {
        $table->id();
        $table->string('title', 100)->unique();
        $table->string('author', 50);
        $table->string('cover', 255)->nullable();
        $table->text('intro')->nullable(); // 改用 text，適合長簡介
        $table->unsignedBigInteger('category_id');
        $table->tinyInteger('status')->default(0); // 0=連載, 1=完結, 2=停更

        // 外鍵約束 + 刪分類時，書自動刪（或設 null）
        $table->foreign('category_id')
              ->references('id')
              ->on('categories')
              ->onDelete('cascade');

        $table->timestamps(); // 自動 created_at + updated_at
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
