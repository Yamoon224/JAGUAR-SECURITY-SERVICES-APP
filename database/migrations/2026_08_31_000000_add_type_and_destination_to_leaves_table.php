<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Une demande de congé doit désormais être formalisée par une lettre
 * d'acceptation. On ajoute :
 *  - "type"        : nature du congé (annuel, maladie, sanitaire, touristique...)
 *  - "destination" : pays ou ville, obligatoire pour les congés sanitaires
 *                    ou touristiques (contrôlé côté application).
 *
 * La table "leaves" n'ayant jamais été créée via une migration Laravel,
 * cette migration est idempotente.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('leaves')) {
            return;
        }

        Schema::table('leaves', function (Blueprint $table) {
            if (! Schema::hasColumn('leaves', 'type')) {
                $table->string('type')->default('annuel')->after('end');
            }
            if (! Schema::hasColumn('leaves', 'destination')) {
                $table->string('destination')->nullable()->after('type');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('leaves')) {
            return;
        }

        Schema::table('leaves', function (Blueprint $table) {
            foreach (['type', 'destination'] as $column) {
                if (Schema::hasColumn('leaves', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
