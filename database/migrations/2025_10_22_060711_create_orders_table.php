<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_code', 40)->unique();   
            $table->decimal('total', 12, 2)->default(0);
            $table->string('status', 20)->default('paid'); 
            $table->string('payment_method', 30)->nullable(); 
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->index(['user_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
