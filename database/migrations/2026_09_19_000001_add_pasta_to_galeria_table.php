<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galeria', function (Blueprint $table) {
            $table->string('pasta', 100)->nullable()->index()->after('destaque');
        });
    }

    public function down(): void
    {
        Schema::table('galeria', function (Blueprint $table) {
            $table->dropColumn('pasta');
        });
    }
};
