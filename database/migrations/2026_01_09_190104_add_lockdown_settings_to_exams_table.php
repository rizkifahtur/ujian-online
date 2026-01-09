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
        Schema::table('exams', function (Blueprint $table) {
            $table->integer('lockdown_duration')->default(30)->after('show_answer');
            $table->integer('max_violations')->default(3)->after('lockdown_duration');
            $table->enum('enable_lockdown', ['Y', 'N'])->default('Y')->after('max_violations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['lockdown_duration', 'max_violations', 'enable_lockdown']);
        });
    }
};
