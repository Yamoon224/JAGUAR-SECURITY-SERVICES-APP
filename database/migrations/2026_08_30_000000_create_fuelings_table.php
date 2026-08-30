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
        Schema::create('fuelings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fueled_at');
            $table->decimal('volume', 15, 2);
            $table->string('fuel_type');            // essence | gasoil
            $table->string('beneficiary_matricule');
            $table->string('beneficiary_function');
            $table->string('station_name');
            $table->string('vehicle_type');         // voiture | moto
            $table->string('voucher_number')->nullable();
            $table->unsignedTinyInteger('deleted')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuelings');
    }
};
