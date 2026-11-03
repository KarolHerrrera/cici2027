<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // RF-01: Configuración general e institucional editable desde el panel.
        Schema::create('congress_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('label');
            $table->string('group')->default('general')->index();
            $table->timestamps();
        });

        // RF-04: Programa y agenda académica del congreso.
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->id();
            $table->string('day_label');
            $table->date('event_date')->nullable()->index();
            $table->string('start_time', 20);
            $table->string('end_time', 20)->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('speaker_name')->nullable();
            $table->string('location')->nullable();
            $table->string('activity_type', 40)->default('conferencia')->index();
            $table->foreignId('thematic_axis_id')->nullable()->constrained('thematic_axes')->nullOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        // RF-06: Modalidades de participación y tarifas oficiales.
        Schema::create('registration_fees', function (Blueprint $table) {
            $table->id();
            $table->string('profile');
            $table->text('description')->nullable();
            $table->string('price_formatted', 40);
            $table->unsignedBigInteger('price_amount')->default(0);
            $table->string('currency', 10)->default('COP');
            $table->string('includes')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // RF-07: Documentos descargables y plantillas oficiales administrables.
        Schema::create('congress_documents', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category', 50)->default('convocatoria')->index();
            $table->text('description')->nullable();
            $table->string('external_url', 500)->nullable();
            $table->string('file_path')->nullable();
            $table->string('version', 30)->default('2027.1');
            $table->string('status', 30)->default('disponible')->index(); // disponible | proximamente | oculto
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();
        });

        // RF-08: Noticias, novedades y avisos oficiales.
        Schema::create('news_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category', 50)->default('Convocatoria')->index();
            $table->text('summary');
            $table->longText('content')->nullable();
            $table->string('image_path')->nullable();
            $table->string('external_url', 500)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        // RF-09: Comité organizador, directivo y científico visible públicamente.
        Schema::create('organizer_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->string('committee_type', 40)->default('organizador')->index(); // directivo | cientifico | organizador | logistico
            $table->string('institution')->default('Universidad Mariana');
            $table->string('program_or_unit')->nullable();
            $table->string('email')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // RF-10: Galería o histórico de ediciones anteriores del CICI.
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('edition', 60);
            $table->unsignedSmallInteger('year')->default(2025)->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('participants_count', 40)->nullable();
            $table->string('papers_count', 40)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        // RF-11: Aliados, patrocinadores y entidades participantes.
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 50)->default('aliado_academico')->index(); // organizador | aliado_academico | patrocinador | apoyo_institucional
            $table->string('country', 80)->nullable();
            $table->string('logo_path')->nullable();
            $table->string('website_url', 500)->nullable();
            $table->string('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // RF-12: Preguntas frecuentes (FAQ).
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category', 50)->default('general')->index(); // resumenes | inscripciones | modalidades | certificados | general
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        // RF-13: Mensajes de contacto y mesa de ayuda pública.
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->index();
            $table->string('phone', 50)->nullable();
            $table->string('institution')->nullable();
            $table->string('category', 50)->default('general')->index();
            $table->string('subject');
            $table->text('message');
            $table->string('status', 30)->default('nuevo')->index(); // nuevo | en_proceso | respondido
            $table->text('admin_notes')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('responded_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('sponsors');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('organizer_members');
        Schema::dropIfExists('news_posts');
        Schema::dropIfExists('congress_documents');
        Schema::dropIfExists('registration_fees');
        Schema::dropIfExists('schedule_items');
        Schema::dropIfExists('congress_settings');
    }
};
