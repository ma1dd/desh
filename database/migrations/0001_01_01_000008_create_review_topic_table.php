<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('review_topic', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->decimal('relevance', 4, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['review_id', 'topic_id']);
            $table->index('relevance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_topic');
    }
};