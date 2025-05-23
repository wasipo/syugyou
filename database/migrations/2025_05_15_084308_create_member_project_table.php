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
        Schema::create('member_project', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('assigned_by')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->string('role')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->primary(['project_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_project');
    }
};
