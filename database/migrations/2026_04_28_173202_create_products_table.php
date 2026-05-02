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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->decimal('price',10,2);
            $table->decimal('compare_price',10,2)->nullable();
            $table->decimal('cost_price',10,2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('sku')->unique()->nullable();
            $table->decimal('weight',8,2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('track_quantity')->default(true);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->softDeletes();
            $table->timestamps();

            //  Indexes
            $table->index('category_id');
            $table->index('slug');
            $table->index('price');
            $table->index('is_active');
            $table->index('is_featured');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
