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
        Schema::create('user_balance_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
              ->constrained()
              ->cascadeOnDelete();

            $table->string('type', 20); //UserBalanceTransactionTypeEnum

            $table->decimal('amount', 15)->comment('Сумма операции'); // сумма операции
            $table->decimal('balance_after', 15);

            $table->string('description')->nullable(); // комментарий к операции
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_balance_transactions');
    }
};
