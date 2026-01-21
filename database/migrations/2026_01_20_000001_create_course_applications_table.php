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
        Schema::create('course_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Наименование курса
            $table->string('course_name');
            // Желаемая дата начала обучения
            $table->date('start_date')->nullable();
            // Способ оплаты: 'cash' | 'phone'
            $table->string('payment_method');
            // Статус заявки: Новая / Идет обучение / Обучение завершено
            $table->string('status')->default('Новая');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_applications');
    }
};


