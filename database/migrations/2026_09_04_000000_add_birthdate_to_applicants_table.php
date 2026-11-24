<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('applicants') && ! Schema::hasColumn('applicants', 'birthdate')) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->date('birthdate')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('applicants', 'birthdate')) {
            Schema::table('applicants', function (Blueprint $table) {
                $table->dropColumn('birthdate');
            });
        }
    }
};
