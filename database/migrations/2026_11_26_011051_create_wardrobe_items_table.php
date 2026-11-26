<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wardrobe_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('outfit_id')->constrained('outfits')->cascadeOnDelete();
            $table->string('title'); // Nama item
            $table->text('notes')->nullable(); // Catatan
            $table->decimal('price', 12, 2)->default(0); // Harga
            $table->string('color')->nullable(); // Warna
            $table->string('image_url')->nullable(); // Opsional
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wardrobe_items');
    }
};