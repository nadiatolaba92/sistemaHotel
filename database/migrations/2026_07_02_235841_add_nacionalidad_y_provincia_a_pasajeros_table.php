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
        Schema::table('pasajeros', function (Blueprint $table) {
            $table->string('nacionalidad')->nullable()->after('apellido');
            $table->string('provincia')->nullable()->after('nacionalidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasajeros', function (Blueprint $table) {
            $table->dropColumn(['nacionalidad', 'provincia']);
        });
    }
};
