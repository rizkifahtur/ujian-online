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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('question_image')->nullable()->after('question');
            $table->string('option_1_image')->nullable()->after('option_1');
            $table->string('option_2_image')->nullable()->after('option_2');
            $table->string('option_3_image')->nullable()->after('option_3');
            $table->string('option_4_image')->nullable()->after('option_4');
            $table->string('option_5_image')->nullable()->after('option_5');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['question_image', 'option_1_image', 'option_2_image', 'option_3_image', 'option_4_image', 'option_5_image']);
        });
    }
};
