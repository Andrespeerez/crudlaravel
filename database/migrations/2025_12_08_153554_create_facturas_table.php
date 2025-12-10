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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('cliente_id')->unsigned();
            $table->string('numero', 10);
            $table->date('fecha');
            $table->decimal('base', 19, 2)->nullable();
            $table->decimal('importeiva', 19, 2)->nullable();
            $table->decimal('importe', 19, 2)->nullable();

            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
