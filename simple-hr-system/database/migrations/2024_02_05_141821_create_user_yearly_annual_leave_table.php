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
        Schema::create('user_yearly_annual_leave', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cacadesOnDelete();
            $table->smallInteger('year');
            $table->unsignedDecimal('number_of_day', 4, 1)->default(0);
            $table->unsignedDecimal('additional_number_of_day', 4, 1)->default(0);
            $table->unsignedDecimal('used_number_of_day', 4, 1)->default(0);
            $table->timestamps();

            $table->unique(['user_id','year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_yearly_annual_leave');
    }
};
