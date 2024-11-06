<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::statement("INSERT INTO `store_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
                                (1,	'Takeaway',	now(),	NULL),
                                (2,	'Shop',	now(),	NULL),
                                (3,	'Restaurant',	now(),	NULL);");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_types');
    }
};
