<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The "categories" and "equipments" tables (plus the relational columns on
 * "dotations") were never created through a Laravel migration in this
 * project's history — they were provisioned directly in the database at
 * some point outside of source control. Some environments (e.g. a fresh
 * production database) may therefore be missing them entirely, which
 * breaks the whole Logistique module (Catégorie, Équipement, Dotation,
 * Achat). This migration is fully idempotent: it only creates what is
 * actually missing and never touches existing data.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedTinyInteger('deleted')->default(0)->index();
                $table->timestamps();
            });
        }

        // Eloquent treats "equipment" as an uncountable noun, so the
        // Equipment model (which has no explicit $table) resolves to the
        // singular table name "equipment", not "equipments".
        if (!Schema::hasTable('equipment')) {
            Schema::create('equipment', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('categories');
                $table->string('name');
                $table->decimal('price', 15, 2)->default(0);
                $table->decimal('qty', 15, 2)->default(0);
                $table->string('unit')->nullable();
                $table->unsignedTinyInteger('deleted')->default(0)->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('dotations')) {
            Schema::create('dotations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees');
                $table->foreignId('equipment_id')->constrained('equipment');
                $table->decimal('qty', 15, 2)->default(0);
                $table->unsignedTinyInteger('deleted')->default(0)->index();
                $table->timestamps();
            });
        } else {
            Schema::table('dotations', function (Blueprint $table) {
                if (!Schema::hasColumn('dotations', 'employee_id')) {
                    $table->foreignId('employee_id')->nullable()->constrained('employees');
                }
                if (!Schema::hasColumn('dotations', 'equipment_id')) {
                    $table->foreignId('equipment_id')->nullable()->constrained('equipment');
                }
                if (!Schema::hasColumn('dotations', 'qty')) {
                    $table->decimal('qty', 15, 2)->default(0);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * Left empty on purpose: this migration only fills in gaps in an
     * inconsistent schema, so rolling it back could drop tables/columns
     * that other, untracked parts of the database now depend on.
     */
    public function down(): void
    {
        //
    }
};
