<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sentiment_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->unique()->constrained('reviews')->cascadeOnDelete();
            $table->decimal('score', 5, 2);
            $table->enum('label', ['positive', 'neutral', 'negative']);
            $table->decimal('confidence', 4, 2)->nullable();
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();

            $table->index('label');
            $table->index('score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sentiment_analyses');
    }
};