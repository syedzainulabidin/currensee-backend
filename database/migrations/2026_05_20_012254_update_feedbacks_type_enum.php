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
        // MySQL requires re-declaring the full enum to add values
        \DB::statement("ALTER TABLE feedbacks MODIFY COLUMN type ENUM('bug', 'suggestion', 'general', 'feature', 'other') NOT NULL DEFAULT 'general'");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE feedbacks MODIFY COLUMN type ENUM('bug', 'suggestion', 'general') NOT NULL DEFAULT 'general'");
    }
};
