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
        Schema::create('savings_targets', function (Blueprint $table) {
            $table->id();
            $table->decimal('target_amount', 10, 2);
            $table->text('description')->nullable();
            $table->date('target_date')->nullable();
            $table->boolean('is_achieved')->default(false);
            $table->timestamps();
        });

        Schema::create('savings_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('savings_target_id')->constrained('savings_targets')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_targets');
        Schema::dropIfExists('savings_payments');
    }
};
