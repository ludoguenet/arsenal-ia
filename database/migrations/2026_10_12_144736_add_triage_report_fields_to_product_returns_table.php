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
        Schema::table('product_returns', function (Blueprint $table) {
            $table->string('priority')->nullable()->after('category');
            $table->string('recommended_action')->nullable()->after('triage_summary');
            $table->boolean('requires_human_review')->nullable()->after('recommended_action');
            $table->timestamp('triaged_at')->nullable()->after('requires_human_review');

            $table->index('category');
            $table->index('priority');
            $table->index('requires_human_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_returns', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['requires_human_review']);

            $table->dropColumn(['priority', 'recommended_action', 'requires_human_review', 'triaged_at']);
        });
    }
};
