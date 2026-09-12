<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('payment_code', 30)->unique();
            $table->enum('method', ['cod', 'transfer', 'midtrans']);
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'confirmed', 'failed'])->default('pending');
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number', 30)->nullable();
            $table->string('proof_image')->nullable();
            $table->string('gateway_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};