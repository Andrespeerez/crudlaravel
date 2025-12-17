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
        Schema::create('facturalineas', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('factura_id')->unsigned();
            $table->bigInteger('articulo_id')->unsigned()->nullable();

            $table->integer('codigo');
            $table->decimal('cantidad', 10, 2);
            $table->string('descripcion', 50);
            $table->decimal('precio', 10, 2);
            $table->decimal('base', 19, 2);
            $table->decimal('iva', 5, 2);
            $table->decimal('importeiva', 19, 2);
            $table->decimal('importe', 19, 2);

            $table->timestamps();

            $table->foreign('factura_id')->references('id')->on('facturas')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('articulo_id')->references('id')->on('articulos')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturalineas');
    }
};
