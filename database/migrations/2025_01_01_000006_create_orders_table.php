<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 30)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('receiver_name');
            $table->string('receiver_phone', 25);
            $table->text('shipping_address');
            $table->string('city');
            $table->string('province');
            $table->unsignedInteger('total_items')->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->enum('status', [
                'pending', 'processing', 'shipped', 'completed', 'cancelled',
            ])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};