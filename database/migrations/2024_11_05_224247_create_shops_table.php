<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->bigInteger('store_type_id')->unsigned();
            $table->foreign('store_type_id')->references('id')->on('store_types');
            $table->double('max_delivery_distance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
