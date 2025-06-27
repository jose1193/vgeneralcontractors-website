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
        Schema::create('alliance_companies', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('alliance_company_name'); 
            $table->string('phone')->nullable();
            $table->string('email')->nullable(); 
            $table->string('address')->nullable(); 
            $table->string('website')->nullable(); 
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alliance_companies');
    }
};
