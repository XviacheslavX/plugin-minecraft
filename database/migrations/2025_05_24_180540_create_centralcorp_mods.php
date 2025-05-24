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
        Schema::create('centralcorp_mods', function (Blueprint $table) {
                $table->id();
                $table->string('file')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('icon')->nullable();
                $table->boolean('optional')->default(false);
                $table->boolean('recommended')->default(false);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centralcorp_mods');
    }
};
