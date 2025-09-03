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
        Schema::table('medis', function (Blueprint $table) {
            // Gula darah
            $table->enum('gula_darah_tipe', ['puasa', 'jpp', 'sewaktu'])->nullable()->after('tambahan');
            $table->unsignedSmallInteger('gula_darah_mg_dl')->nullable()->after('gula_darah_tipe'); // 0–65535

            // Kolesterol total
            $table->unsignedSmallInteger('kolesterol_mg_dl')->nullable();

            // Asam urat
            $table->decimal('asam_urat_mg_dl', 4, 1)->nullable(); // contoh: 3.4, 7.8

            // Antropometri
            $table->decimal('berat_kg', 5, 2)->nullable();        // contoh: 72.50
            $table->decimal('tinggi_cm', 5, 2)->nullable();       // contoh: 170.0
            $table->decimal('imt', 5, 2)->nullable()->comment('Indeks Massa Tubuh (kg/m^2)');

            // Tensi
            $table->unsignedSmallInteger('tensi_sistolik')->nullable();   // mmHg
            $table->unsignedSmallInteger('tensi_diastolik')->nullable();  // mmHg

            // Saturasi O2
            $table->unsignedTinyInteger('spo2')->nullable(); // 0–100 %
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medis', function (Blueprint $table) {
            $table->dropColumn([
                'gula_darah_tipe',
                'gula_darah_mg_dl',
                'kolesterol_mg_dl',
                'asam_urat_mg_dl',
                'berat_kg',
                'tinggi_cm',
                'imt',
                'tensi_sistolik',
                'tensi_diastolik',
                'spo2'
            ]);
        });
    }
};
