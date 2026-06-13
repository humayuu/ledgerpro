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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')->constrained();
            $table->date('date');
            $table->enum('type', ['credit', 'cash_withdrawal', 'bank_transfer']);
            $table->string('party_name')->nullable();
            $table->decimal('amount', 15, 2);
            $table->foreignId('transfer_to_bank_id')->nullable()->constrained('banks');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
