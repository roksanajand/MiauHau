<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAnimalsFunctions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Usuwanie istniejących procedur przed ich tworzeniem
        DB::unprepared('DROP PROCEDURE IF EXISTS animalsColors;');
        DB::unprepared('DROP PROCEDURE IF EXISTS animalCrudential;');

        // Tworzenie procedury animalsColors
        DB::unprepared('
            CREATE PROCEDURE animalsColors()
            BEGIN
                SELECT
                    id,
                    name,
                    c_black,
                    c_white,
                    c_ginger,
                    c_grey,
                    c_brown,
                    c_golden,
                    c_other
                FROM animals;
            END
        ');

        // Tworzenie procedury animalCrudential
        DB::unprepared('
            CREATE PROCEDURE animalCrudential()
            BEGIN
                SELECT
                    id,
                    name,
                    owner_id
                FROM animals;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Usuwanie procedury animalsColors
        DB::unprepared('DROP PROCEDURE IF EXISTS animalsColors;');

        // Usuwanie procedury animalCrudential
        DB::unprepared('DROP PROCEDURE IF EXISTS animalCrudential;');
    }
}
