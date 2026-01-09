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
        Schema::create('exam_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_group_id')->constrained()->cascadeOnDelete();
            $table->string('violation_type');
            $table->integer('violation_count')->default(1);
            $table->text('description')->nullable();
            $table->timestamp('violated_at');
            $table->enum('status', ['active', 'forgiven', 'ended'])->default('active');
            $table->foreignId('forgiven_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('forgiven_at')->nullable();
            $table->text('forgiven_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_violations');
    }
};
