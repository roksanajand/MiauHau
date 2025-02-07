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
        Schema::create('edit_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prev_info_id');
            $table->timestamp('edited_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();

            $table->foreign('prev_info_id')->references('id')->on('prev_info')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edit_info');
    }
};
