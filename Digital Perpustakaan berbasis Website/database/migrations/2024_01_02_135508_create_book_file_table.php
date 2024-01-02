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
        Schema::create('book_files', function (Blueprint $table) {
            $table->id();
            $table->string('cover_path')->nullable();
            $table->string('cover_mime')->nullable();
            $table->binary('cover_image')->nullable();
            $table->string('pdf_path')->nullable(); 
            $table->string('pdf_mime')->nullable();
            $table->binary('pdf_file')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_files');
    }
};
