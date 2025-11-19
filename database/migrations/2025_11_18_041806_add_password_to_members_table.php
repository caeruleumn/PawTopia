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
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'password')) {
                $table->string('password')->after('email');
            }
            if (!Schema::hasColumn('members', 'role')) {
                $table->string('role')->default('member')->after('password');
            }
            if (!Schema::hasColumn('members', 'status')) {
                $table->string('status')->default('active')->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['password', 'role', 'status']);
        });
    }
};
