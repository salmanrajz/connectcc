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
        //
        Schema::create('dnd_aashirs', function (Blueprint $table) {
            $table->id();
            $table->string('system_dnd')->nullable();
            $table->string('vicidial_dnd')->nullable();
            $table->string('yeastar_dnd')->nullable();
            $table->string('old_yeastar_dnd')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
