<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->dropColumn('colors');
            $table->boolean('c_black')->default(0);
            $table->boolean('c_white')->default(0);
            $table->boolean('c_ginger')->default(0);
            $table->boolean('c_tricolor')->default(0);
            $table->boolean('c_grey')->default(0);
            $table->boolean('c_brown')->default(0);
            $table->boolean('c_golden')->default(0);
            $table->boolean('c_other')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->string('colors')->nullable();
            $table->dropColumn([
                'c_black', 'c_white', 'c_ginger',
                'c_tricolor', 'c_grey', 'c_brown',
                'c_golden', 'c_other'
            ]);
        });
    }
};
