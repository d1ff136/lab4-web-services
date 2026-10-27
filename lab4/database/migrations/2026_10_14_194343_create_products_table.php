<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('operation', 30);
            $table->decimal('price', 12, 2);
            $table->string('currency', 8);
            $table->string('short_description');
            $table->text('description');
            $table->string('image');
            $table->string('contact_name');
            $table->string('contact_phone', 40);
            $table->string('contact_email');
            $table->string('status', 20);
            $table->date('published_at');
            $table->json('attributes');
            $table->timestamps();

            $table->index('operation');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
