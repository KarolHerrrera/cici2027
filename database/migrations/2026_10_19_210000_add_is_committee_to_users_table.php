<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_committee')->default(false)->after('is_admin');
            $table->boolean('is_committee_leader')->default(false)->after('is_committee');
            $table->string('specialty')->nullable()->after('is_committee_leader');
            $table->foreignId('thematic_axis_id')->nullable()->after('specialty')->constrained('thematic_axes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('thematic_axis_id');
            $table->dropColumn(['is_committee', 'is_committee_leader', 'specialty']);
        });
    }
};
