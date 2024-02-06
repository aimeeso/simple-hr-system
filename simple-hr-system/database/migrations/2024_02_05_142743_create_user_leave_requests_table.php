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
        Schema::create('user_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cacadesOnDelete();
            $table->foreignId('leave_type_id')->nullable()->constrained()->nullOnDelete();
            $table->date('start_date');
            $table->enum('start_type', ['AM', 'PM'])->default('AM');
            $table->date('end_date');
            $table->enum('end_type', ['AM', 'PM'])->default('PM');
            $table->unsignedDecimal('number_of_leave_day', 4, 1)->default(0);
            $table->enum('status', ['DRAFT', 'PENDING', 'APPROVED', 'REJECTED', 'CANCELED'])->default('DRAFT');
            $table->tinyInteger('count_annual_leave')->default(1);
            $table->string('updated_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_leave_requests');
    }
};
