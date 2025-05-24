<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('options_loaders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('server_id');
            $table->string('minecraft_version');
            $table->boolean('loader_activation');
            $table->string('loader_type');
            $table->string('loader_forge_version')->nullable();
            $table->string('loader_fabric_version')->nullable();
            $table->string('loader_build_version')->nullable();
            $table->timestamps();
            $table->foreign('server_id')
                ->references('id')
                ->on('servers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centralcorp_loader_options');
    }
};
