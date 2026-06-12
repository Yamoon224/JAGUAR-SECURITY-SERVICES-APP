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
            if (!Schema::hasColumn('employees', 'transport_indemnity')) {
                $table->decimal('transport_indemnity', 15, 2)->default(0)->after('salary');
            }
            if (!Schema::hasColumn('employees', 'meal_allowance')) {
                $table->decimal('meal_allowance', 15, 2)->default(0)->after('transport_indemnity');
            }
            if (!Schema::hasColumn('employees', 'housing_indemnity')) {
                $table->decimal('housing_indemnity', 15, 2)->default(0)->after('meal_allowance');
            }
            if (!Schema::hasColumn('employees', 'punctuality_allowance')) {
                $table->decimal('punctuality_allowance', 15, 2)->default(0)->after('housing_indemnity');
            }
            if (!Schema::hasColumn('employees', 'responsibility_allowance')) {
                $table->decimal('responsibility_allowance', 15, 2)->default(0)->after('punctuality_allowance');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'responsibility_allowance')) {
                $table->dropColumn('responsibility_allowance');
            }
            if (Schema::hasColumn('employees', 'punctuality_allowance')) {
                $table->dropColumn('punctuality_allowance');
            }
            if (Schema::hasColumn('employees', 'housing_indemnity')) {
                $table->dropColumn('housing_indemnity');
            }
            if (Schema::hasColumn('employees', 'meal_allowance')) {
                $table->dropColumn('meal_allowance');
            }
            if (Schema::hasColumn('employees', 'transport_indemnity')) {
                $table->dropColumn('transport_indemnity');
            }
        });
    }
};
