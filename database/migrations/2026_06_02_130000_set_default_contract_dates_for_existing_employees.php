<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $today = Carbon::today()->toDateString();
        $endOfYear = Carbon::today()->endOfYear()->toDateString();

        DB::table('employees')->update([
            'contract_start_at' => $today,
            'contract_end_at' => $endOfYear,
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('employees')->update([
            'contract_start_at' => null,
            'contract_end_at' => null,
            'updated_at' => now(),
        ]);
    }
};