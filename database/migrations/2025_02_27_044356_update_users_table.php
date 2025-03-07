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
        Schema::table('users', function (Blueprint $table) {
            $table->string('team_code')->nullable()->change();
            $table->string('slack_name')->nullable()->change();
            $table->string('user_role')->default('client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->string('team_code')->change();
        $table->string('slack_name')->change();
        $table->dropColumn('user_role');
        });
    }
};
