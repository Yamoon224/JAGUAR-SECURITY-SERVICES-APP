<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Le modèle Equipment écrit dans la table "equipment" (nom indénombrable,
 * fixé explicitement sur le modèle). Certaines bases — dont la production —
 * traînent en plus une table héritée "equipments" créée hors migrations,
 * et leurs clés étrangères "equipment_id" pointent dessus : une première
 * version de la migration des achats utilisait `constrained()` sans
 * argument, qui devine "equipments" à partir du nom de colonne.
 *
 * Conséquence : tout équipement créé par l'application atterrit dans
 * "equipment" mais reste absent de "equipments", et l'insertion de l'achat
 * qui le référence casse sur la contrainte (SQLSTATE 23000 / errno 1452).
 *
 * Cette migration repointe ces contraintes vers "equipment". Elle est
 * idempotente et ne touche que ce qui est réellement mal ciblé.
 */
return new class extends Migration
{
    /** Tables dont la colonne "equipment_id" doit référencer "equipment". */
    private array $tables = ['purchases', 'dotations'];

    public function up(): void
    {
        if (! Schema::hasTable('equipment')) {
            return;
        }

        // "equipment.id" est un INT sur le schéma d'origine, là où
        // foreignId() crée des BIGINT : MySQL refuse la contrainte tant que
        // les deux colonnes n'ont pas exactement le même type.
        $target = $this->columnType('equipment', 'id');

        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'equipment_id')) {
                continue;
            }

            foreach ($this->wrongForeignKeys($table) as $constraint) {
                DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraint}`");
            }

            if ($this->columnType($table, 'equipment_id') !== $target) {
                $nullable = $this->isNullable($table, 'equipment_id') ? 'NULL' : 'NOT NULL';
                DB::statement("ALTER TABLE `{$table}` MODIFY `equipment_id` {$target} {$nullable}");
            }

            if ($this->hasForeignKeyOnEquipment($table)) {
                continue;
            }

            // Des lignes créées du temps de la contrainte fautive peuvent
            // pointer sur un id qui n'existe que dans "equipments" : la
            // contrainte serait refusée. On laisse alors la colonne
            // simplement indexée (comme stock_movements) et on le signale
            // plutôt que d'échouer ou de supprimer des données métier.
            $orphans = $this->orphanCount($table);

            if ($orphans > 0) {
                echo "  ! {$table} : {$orphans} ligne(s) référencent un équipement absent de `equipment` — "
                    . "clé étrangère non rétablie, à arbitrer manuellement." . PHP_EOL;
                $this->ensureIndex($table);
                continue;
            }

            DB::statement(
                "ALTER TABLE `{$table}` ADD CONSTRAINT `{$table}_equipment_id_foreign` "
                . "FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE"
            );
        }
    }

    /** Lignes dont l'équipement référencé n'existe pas dans "equipment". */
    private function orphanCount(string $table): int
    {
        return DB::table($table)
            ->whereNotNull('equipment_id')
            ->whereNotIn('equipment_id', DB::table('equipment')->select('id'))
            ->count();
    }

    private function ensureIndex(string $table): void
    {
        $exists = DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', 'equipment_id')
            ->exists();

        if (! $exists) {
            DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$table}_equipment_id_index` (`equipment_id`)");
        }
    }

    /**
     * Pas de retour en arrière : remettre une contrainte vers "equipments"
     * recréerait exactement le bug que cette migration corrige.
     */
    public function down(): void
    {
        //
    }

    /** Contraintes de la table qui référencent autre chose que "equipment". */
    private function wrongForeignKeys(string $table): array
    {
        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', 'equipment_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->where('REFERENCED_TABLE_NAME', '!=', 'equipment')
            ->pluck('CONSTRAINT_NAME')
            ->all();
    }

    private function hasForeignKeyOnEquipment(string $table): bool
    {
        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', 'equipment_id')
            ->where('REFERENCED_TABLE_NAME', 'equipment')
            ->exists();
    }

    private function columnType(string $table, string $column): string
    {
        return (string) DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->value('COLUMN_TYPE');
    }

    private function isNullable(string $table, string $column): bool
    {
        return DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->value('IS_NULLABLE') === 'YES';
    }
};
