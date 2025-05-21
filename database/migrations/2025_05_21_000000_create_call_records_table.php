<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('call_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('call_id')->unique();
            $table->string('agent_id');
            $table->string('from_number');
            $table->string('to_number');
            $table->enum('direction', ['inbound', 'outbound']);
            $table->string('call_status');
            $table->timestamp('start_timestamp')->nullable();
            $table->timestamp('end_timestamp')->nullable();
            $table->integer('duration_ms')->nullable();
            $table->text('transcript')->nullable();
            $table->string('recording_url')->nullable();
            $table->text('call_summary')->nullable();
            $table->string('user_sentiment')->nullable();
            $table->boolean('call_successful')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('call_records');
    }
}; 