<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lead follow-up tracker for the admin console: next follow-up date and internal notes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_visit_leads', function (Blueprint $table) {
            $table->date('follow_up_at')->nullable()->after('assigned_advisor');
            $table->text('notes')->nullable()->after('follow_up_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('site_visit_leads', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['follow_up_at', 'notes']);
        });
    }
};
