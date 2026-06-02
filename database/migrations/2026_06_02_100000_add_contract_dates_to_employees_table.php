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
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'contract_start_at')) {
                $table->date('contract_start_at')->nullable()->after('contract');
            }

            if (!Schema::hasColumn('employees', 'contract_end_at')) {
                $table->date('contract_end_at')->nullable()->after('contract_start_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'contract_end_at')) {
                $table->dropColumn('contract_end_at');
            }

            if (Schema::hasColumn('employees', 'contract_start_at')) {
                $table->dropColumn('contract_start_at');
            }
        });
    }
};
