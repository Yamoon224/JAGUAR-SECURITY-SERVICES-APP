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
        Schema::table('affectations', function (Blueprint $table) {
            if (!Schema::hasColumn('affectations', 'exoneration')) {
                // No ->after(...): this table's columns were never fully
                // tracked by migrations, don't assume a specific one exists.
                $table->decimal('exoneration', 5, 2)->default(18);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affectations', function (Blueprint $table) {
            if (Schema::hasColumn('affectations', 'exoneration')) {
                $table->dropColumn('exoneration');
            }
        });
    }
};
