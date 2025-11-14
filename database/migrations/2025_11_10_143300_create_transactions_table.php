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
            $table->string('order_id')->unique(); // Format: ORDER-{timestamp}-{random}
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            
            // Polymorphic relation untuk mendukung berbagai tipe transaksi
            $table->string('transactable_type'); // App\Models\Booking, App\Models\Order, etc
            $table->unsignedBigInteger('transactable_id');
            
            $table->decimal('gross_amount', 15, 2);
            $table->string('payment_type')->nullable(); // credit_card, bank_transfer, gopay, etc
            $table->string('transaction_status')->default('pending'); // pending, settlement, cancel, deny, expire
            $table->string('snap_token')->nullable();
            $table->string('transaction_id')->nullable(); // Midtrans transaction ID
            
            // Midtrans response details
            $table->text('payment_details')->nullable(); // JSON
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['transactable_type', 'transactable_id']);
            $table->index('transaction_status');
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
