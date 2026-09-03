<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * L'unité d'un équipement n'est plus saisie ni affichée nulle part : elle
 * devient facultative en base. La table "equipment" ayant été provisionnée
 * hors migrations sur certains environnements, sa colonne peut y être en
 * NOT NULL — on la repasse en NULL sans toucher au type ni aux données.
 *
 * Passe par du SQL brut plutôt que ->change() : ce projet ne dépend pas de
 * doctrine/dbal, requis par Blueprint pour modifier une colonne existante.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('equipment') || ! Schema::hasColumn('equipment', 'unit')) {
            return;
        }

        if ($this->isNullable()) {
            return;
        }

        $type = DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'equipment')
            ->where('COLUMN_NAME', 'unit')
            ->value('COLUMN_TYPE');

        DB::statement("ALTER TABLE `equipment` MODIFY `unit` {$type} NULL DEFAULT NULL");
    }

    /**
     * Pas de retour en arrière : des lignes créées entre-temps peuvent avoir
     * une unité nulle, que le NOT NULL d'origine refuserait.
     */
    public function down(): void
    {
        //
    }

    private function isNullable(): bool
    {
        return DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'equipment')
            ->where('COLUMN_NAME', 'unit')
            ->value('IS_NULLABLE') === 'YES';
    }
};
