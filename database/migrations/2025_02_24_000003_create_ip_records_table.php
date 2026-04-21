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
        Schema::create('ip_records', function (Blueprint $table) {
            $table->string('record_id')->primary();
            $table->string('registration_number')->nullable();
            $table->string('ip_title');
            $table->string('category')->nullable();
            $table->string('class_of_work')->nullable();
            $table->date('date_creation')->nullable();
            $table->date('date_registered_deposited')->nullable();
            $table->string('campus')->nullable();
            $table->string('college')->nullable();
            $table->string('program')->nullable();
            $table->string('owner_inventor_summary')->nullable();
            $table->text('gdrive_link')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->nullable();
            $table->string('ipophl_id')->nullable();
            $table->timestamps();
            $table->timestamp('archived_at')->nullable();
            
            $table->index('category');
            $table->index('status');
            $table->index('campus');
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('archived_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_records');
    }
};
