<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Створення таблиці centralcorp_options
        if (!Schema::hasTable('centralcorp_options')) {
            Schema::create('centralcorp_options', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // Створення таблиці centralcorp_whitelist
        if (!Schema::hasTable('centralcorp_whitelist')) {
            Schema::create('centralcorp_whitelist', function (Blueprint $table) {
                $table->id();
                $table->string('users')->unique();
                $table->timestamps();
            });
        }

        // Створення таблиці centralcorp_whitelist_roles
        if (!Schema::hasTable('centralcorp_whitelist_roles')) {
            Schema::create('centralcorp_whitelist_roles', function (Blueprint $table) {
                $table->id();
                $table->string('role')->unique();
                $table->timestamps();
            });
        }

        // Створення таблиці centralcorp_bg_roles (без дефолтного значення для поля role_background)
        if (!Schema::hasTable('centralcorp_bg_roles')) {
            Schema::create('centralcorp_bg_roles', function (Blueprint $table) {
                $table->id();
                $table->string('role_name')->unique();
                $table->text('role_background'); // Без значення за замовчуванням
                $table->timestamps();
            });
        }

        // Створення таблиці centralcorp_ignored_folders
        if (!Schema::hasTable('centralcorp_ignored_folders')) {
            Schema::create('centralcorp_ignored_folders', function (Blueprint $table) {
                $table->id();
                $table->string('folder_name')->unique();
                $table->timestamps();
            });
        }

        // Створення таблиці centralcorp_mods
        if (!Schema::hasTable('centralcorp_mods')) {
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

        // Додавання стовпця icon до таблиці servers
        Schema::table('servers', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('port');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Видалення таблиць
        Schema::dropIfExists('centralcorp_options');
        Schema::dropIfExists('centralcorp_whitelist');
        Schema::dropIfExists('centralcorp_whitelist_roles');
        Schema::dropIfExists('centralcorp_bg_roles');
        Schema::dropIfExists('centralcorp_ignored_folders');
        Schema::dropIfExists('centralcorp_mods');

        // Видалення стовпця icon з таблиці servers
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
