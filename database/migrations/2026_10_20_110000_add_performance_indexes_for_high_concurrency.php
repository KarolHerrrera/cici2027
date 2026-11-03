<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('important_dates', function (Blueprint $table) {
            $table->index('date', 'idx_important_dates_date');
        });

        Schema::table('speakers', function (Blueprint $table) {
            $table->index('sort_order', 'idx_speakers_sort_order');
        });

        Schema::table('thematic_axes', function (Blueprint $table) {
            $table->index('sort_order', 'idx_thematic_axes_sort_order');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->index(['status', 'thematic_axis_id'], 'idx_submissions_status_axis');
        });
    }

    public function down(): void
    {
        Schema::table('important_dates', function (Blueprint $table) {
            $table->dropIndex('idx_important_dates_date');
        });

        Schema::table('speakers', function (Blueprint $table) {
            $table->dropIndex('idx_speakers_sort_order');
        });

        Schema::table('thematic_axes', function (Blueprint $table) {
            $table->dropIndex('idx_thematic_axes_sort_order');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropIndex('idx_submissions_status_axis');
        });
    }
};
