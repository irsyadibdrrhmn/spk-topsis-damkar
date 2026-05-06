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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Tambahkan kolom criteria_id dengan foreign key
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');

            $table->decimal('score', 12, 4);
            $table->string('period'); // Format: YYYY-MM
            $table->timestamps();

            $table->unique(['user_id', 'criteria_id', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
