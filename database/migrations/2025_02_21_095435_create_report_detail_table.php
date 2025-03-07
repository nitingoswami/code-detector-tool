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
        Schema::create('report_details', function (Blueprint $table) {
            $table->id();
            $table->string('team')->nullable()->default(null); 
            $table->unsignedBigInteger('daily_report_id')->nullable()->default(null);
            $table->string('type')->nullable()->default(null); 
            $table->longText('summary')->nullable()->default(null); 
            $table->date('date')->nullable()->default(null);
            $table->longText('old_code')->nullable()->default(null); 
            $table->longText('new_code')->nullable()->default(null); 
            $table->boolean('status')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_detail');
    }
};
