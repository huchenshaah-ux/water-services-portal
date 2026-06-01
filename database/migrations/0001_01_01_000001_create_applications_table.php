<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('entry_no')->unique();
            $table->date('application_date');
            $table->string('applicant_name');
            $table->string('id_number', 50);
            $table->string('contact_number', 30);
            $table->text('address');
            $table->text('service_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('service_category');
            $table->enum('status', ['pending', 'approved', 'connected', 'rejected'])->default('pending');
            $table->foreignId('supervised_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('fenaka_id')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('application_date');
            $table->index('service_category');
            $table->index(['id_number', 'entry_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
