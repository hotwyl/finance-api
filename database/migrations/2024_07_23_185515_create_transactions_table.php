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
            $table->uuid('id')->primary();
            $table->uuid('wallet_id');
            $table->enum('type', ['entrada', 'saida'])->default('saida');
            $table->enum('description', ['salário','investimento','extra','financiamento','emprestimo','aluguel','luz','agua','internet','alimentação','transporte','educação','lazer','vestuario','poupança','outros']);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pendente', 'pago', 'cancelado'])->default('pendente');
            $table->boolean('recurrence')->nullable();
            $table->enum('period', ['diario', 'semanal', 'quinzenal', 'mensal', 'bimestral', 'trimestral', 'semestral', 'anual'])->nullable();
            $table->integer('installments')->nullable();
            $table->date('due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->text('annotation')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');
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
