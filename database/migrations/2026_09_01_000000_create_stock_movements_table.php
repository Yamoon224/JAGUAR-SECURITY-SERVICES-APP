<?php

use App\Models\Equipment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Archive logistique : journal automatisé de tous les mouvements de stock
 * d'équipement (approvisionnement, dotation, restitution, détérioration,
 * réparation, ajustement, épuisement). Chaque ligne est immuable et porte
 * le stock disponible avant / après le mouvement.
 *
 * NB : la table "equipment" a été provisionnée hors migrations et sa
 * colonne "id" est un INT (pas un BIGINT). "stock_movements.equipment_id"
 * est donc typé en INT UNSIGNED pour correspondre. On n'ajoute PAS de
 * contrainte de clé étrangère au niveau base (les relations Eloquent
 * suffisent) afin d'éviter tout conflit de type sur ce schéma d'origine
 * mixte : on se contente de colonnes indexées.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Repart d'une table propre même si un essai précédent a laissé
        // une table partielle (échec sur l'ajout des clés étrangères).
        Schema::dropIfExists('stock_movements');

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('equipment_id')->index();     // equipment.id est un INT
            $table->string('direction');            // in | out
            $table->string('reason');               // approvisionnement, dotation, ...
            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('stock_before', 15, 2)->default(0);
            $table->decimal('stock_after', 15, 2)->default(0);
            $table->unsignedBigInteger('employee_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('note')->nullable();
            $table->unsignedTinyInteger('deleted')->default(0)->index();
            $table->timestamps();

            $table->index(['equipment_id', 'created_at']);
            $table->index('reason');
        });

        // Solde d'ouverture : une ligne par équipement avec le disponible actuel.
        if (Schema::hasTable('equipment')) {
            foreach (Equipment::all() as $equipment) {
                $available = (float) $equipment->available_qty;
                DB::table('stock_movements')->insert([
                    'equipment_id' => $equipment->id,
                    'direction' => 'in',
                    'reason' => 'ouverture',
                    'quantity' => $available,
                    'stock_before' => 0,
                    'stock_after' => $available,
                    'note' => "Solde d'ouverture de l'archive logistique",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
