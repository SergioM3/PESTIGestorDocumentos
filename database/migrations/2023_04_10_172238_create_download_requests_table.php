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
        Schema::create('download_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('document_id');
            $table->string('request_state');
            $table->dateTime('accept_date')->nullable();
            $table->dateTime('reject_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('download_requests');
    }
};
