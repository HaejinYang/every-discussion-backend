<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('opinions_reference', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('opinion_id')->unsigned();
            $table->bigInteger('refer_to_id')->unsigned();

            $table->index('opinion_id');
            $table->index('refer_to_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opinions_reference');
    }
};
