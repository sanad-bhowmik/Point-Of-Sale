<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lc_id');
            $table->string('name', 255);
            $table->string('number', 100);
            $table->date('shipping_date')->nullable();
            $table->date('arriving_date')->nullable();
            $table->string('status', 50)->nullable();
            $table->timestamps();

            // Optional: if lc_id references another table (example: letters_of_credit)
            // $table->foreign('lc_id')->references('id')->on('letters_of_credit')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
