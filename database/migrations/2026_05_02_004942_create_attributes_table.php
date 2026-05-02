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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // مثلاً: Color, Size
            $table->string('display_name'); // مثلاً: اللون, المقاس
            $table->timestamps();
        });


        // جدول القيم — Color له: Red, Blue, Green
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('value');        // Red, XL, ...
            $table->string('display_value'); // أحمر, كبير جداً
            $table->timestamps();

            $table->index('attribute_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
