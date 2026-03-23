<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_id')->constrained('sources')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            $table->string('author_name')->nullable();
            $table->string('external_id')->nullable();
            $table->longText('text');
            $table->unsignedTinyInteger('rating')->nullable();

            $table->enum('status', ['new', 'approved', 'rejected', 'moderation'])->default('new');
            $table->text('rejection_reason')->nullable();

            $table->string('region')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->unique(['source_id', 'external_id']);
            $table->index('product_id');
            $table->index('source_id');
            $table->index('status');
            $table->index('rating');
            $table->index('published_at');
            $table->index('region');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};