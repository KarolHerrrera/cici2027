<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code', 30)->unique();
            $table->string('document_type', 20)->default('CC');
            $table->string('document', 30)->index();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 40)->nullable();
            $table->string('institution')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->default('Colombia');
            $table->text('coauthors')->nullable();

            $table->string('title');
            $table->foreignId('thematic_axis_id')->nullable()->constrained('thematic_axes')->nullOnDelete();
            $table->foreignId('axis_topic_id')->nullable()->constrained('axis_topics')->nullOnDelete();
            $table->string('requested_modality', 20)->default('oral'); // oral | poster
            $table->text('abstract_text')->nullable();
            $table->string('keywords', 500)->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_original_name')->nullable();
            $table->string('file_url', 500)->nullable();

            $table->string('status', 20)->default('pending')->index(); // pending | under_review | approved | rejected
            $table->string('modality', 20)->nullable();                // oral | poster (modalidad aprobada por el comité)
            $table->unsignedTinyInteger('score')->nullable();          // 0 - 100
            $table->text('review_comment')->nullable();                // visible para el postulante
            $table->text('internal_notes')->nullable();                // notas privadas del comité

            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
