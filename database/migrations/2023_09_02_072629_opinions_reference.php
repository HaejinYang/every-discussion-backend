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
            $table->bigInteger('reference_id')->unsigned();
            $table->string('agree_type');

            $table->index('opinion_id');
            $table->index('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
