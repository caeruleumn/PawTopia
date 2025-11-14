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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('pet_id')->nullable()->after('member_id')->constrained('pets')->onDelete('cascade');
            $table->enum('drop_off_type', ['owner', 'daycare'])->default('owner')->after('booking_time');
            $table->enum('pick_up_type', ['owner', 'daycare'])->default('owner')->after('drop_off_type');
            $table->decimal('distance_km', 8, 2)->nullable()->after('pick_up_type');
            $table->decimal('base_price', 10, 2)->default(50000)->after('distance_km');
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('base_price');
            $table->integer('duration_days')->default(1)->after('booking_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['pet_id']);
            $table->dropColumn([
                'pet_id',
                'drop_off_type',
                'pick_up_type',
                'distance_km',
                'base_price',
                'delivery_fee',
                'duration_days'
            ]);
        });
    }
};
