<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zipcode');
            $table->string('country');
            $table->enum('insurance_property', ['yes', 'no']);
            $table->text('message')->nullable();
            $table->boolean('sms_consent')->default(false);
            $table->timestamp('registration_date')->nullable();
            $table->date('inspection_date')->nullable();
            $table->time('inspection_time')->nullable();
            $table->boolean('inspection_confirmed')->nullable();
            $table->text('notes')->nullable();
            $table->string('owner')->nullable();
            $table->text('damage_detail')->nullable();
            $table->boolean('intent_to_claim')->nullable();
            $table->string('lead_source')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->text('additional_note')->nullable();
            $table->enum('inspection_status', ['Completed', 'Pending', 'Declined'])->nullable();

            // Add latitude and longitude
            $table->double('latitude', 10, 7)->nullable();
            $table->double('longitude', 10, 7)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}; 