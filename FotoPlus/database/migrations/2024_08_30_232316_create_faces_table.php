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
        Schema::create('faces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_person')->nullable();
            $table->text('url_face', 200)->nullable();
            $table->timestamps();
            $table->foreign('id_person')->references('id')->on('people');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faces');
    }
};
