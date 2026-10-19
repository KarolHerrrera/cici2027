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
    Schema::create('speakers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('thematic_axis_id')->nullable()->constrained()->nullOnDelete();
        $table->string('name');
        $table->string('photo')->nullable();
        $table->string('job_title')->nullable();
        $table->string('specialty')->nullable();
        $table->string('institution');
        $table->string('city')->nullable();
        $table->string('country');
        $table->text('bio')->nullable();
        $table->string('talk_title')->nullable();
        $table->unsignedSmallInteger('sort_order')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speakers');
    }
};
