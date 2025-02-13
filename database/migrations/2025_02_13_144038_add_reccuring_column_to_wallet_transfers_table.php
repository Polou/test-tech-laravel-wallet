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
        Schema::table('wallet_transfers', function (Blueprint $table) {
            $table->boolean('is_reccuring')->default(false)->after('amount');
            $table->date('start_date')->after('is_reccuring')->nullable();
            $table->date('end_date')->after('start_date')->nullable();
            $table->integer('frequency')->after('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallet_transfers', function (Blueprint $table) {
            $table->dropColumn(['is_reccuring', 'start_date', 'end_date', 'frequency']);
        });
    }
};
