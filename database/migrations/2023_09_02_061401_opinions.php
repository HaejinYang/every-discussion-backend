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
        Schema::create('opinions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('topic_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('title');
            $table->string('agree_type');
            $table->string('content');
            $table->string('summary');
            $table->integer('like');
            $table->integer('dislike');
            $table->timestamps();
            $table->softDeletes();

            $table->index('topic_id');
            $table->index('user_id');
            $table->fullText('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opinions');
    }
};
