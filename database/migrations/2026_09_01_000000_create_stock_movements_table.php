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
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('stock_movements')) {
            return;
        }

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->cascadeOnDelete();
            $table->string('direction');            // in | out
            $table->string('reason');               // approvisionnement, dotation, ...
            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('stock_before', 15, 2)->default(0);
            $table->decimal('stock_after', 15, 2)->default(0);
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
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
