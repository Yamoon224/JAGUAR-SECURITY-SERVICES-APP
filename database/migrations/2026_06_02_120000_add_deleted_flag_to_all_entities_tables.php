<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tables metier qui doivent supporter la suppression logique via le flag `deleted`.
     *
     * @var array<int, string>
     */
    private array $tables = [
        'affectations',
        'applicants',
        'appointments',
        'bills',
        'categories',
        'customers',
        'dotations',
        'employees',
        'equipments',
        'flows',
        'galeries',
        'groups',
        'leaves',
        'licenciements',
        'logistics',
        'mails',
        'meets',
        'payments',
        'recruitments',
        'suspensions',
        'users',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table) || Schema::hasColumn($table, 'deleted')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedTinyInteger('deleted')->default(0)->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'deleted')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('deleted');
            });
        }
    }
};
