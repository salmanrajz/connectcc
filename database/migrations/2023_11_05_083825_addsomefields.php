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
        Schema::table('whatsapp_numbers', function (Blueprint $table) {
            //
            $table->string('status')->nullable();
            $table->string('language')->nullable();
            $table->integer('mark_soft_dnd')->nullable();
            $table->integer('mark_dnd')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_numbers', function (Blueprint $table) {
            //
        });
    }
};
