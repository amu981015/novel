<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // 3. chapters migration
public function up()
{
    Schema::create('chapters', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('novel_id');
        $table->integer('chapter_num');
        $table->string('title', 100);
        $table->longText('content');

        // 外鍵：刪書時，章節自動跟著刪
        $table->foreign('novel_id')
              ->references('id')
              ->on('novels')
              ->onDelete('cascade');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
