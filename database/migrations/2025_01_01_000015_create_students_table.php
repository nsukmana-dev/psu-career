<?php

use App\Enums\Gender;
use App\Enums\JobInterest;
use App\Enums\VerificationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nim')->unique();
            $table->foreignId('study_program_id')->constrained('study_programs')->restrictOnDelete();
            $table->unsignedSmallInteger('entry_year')->nullable()->index();
            $table->unsignedTinyInteger('current_semester')->nullable()->index();
            $table->decimal('gpa', 3, 2)->nullable()->index();
            $table->enum('gender', array_column(Gender::cases(), 'value'))->nullable();
            $table->date('birth_date')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->text('summary')->nullable();
            $table->string('photo_path')->nullable();

            $table->enum('job_interest', array_column(JobInterest::cases(), 'value'))->nullable();
            $table->boolean('willing_to_relocate')->default(false);
            $table->boolean('open_to_contact')->default(false)->index();

            $table->enum('verification_status', array_column(VerificationStatus::cases(), 'value'))
                ->default(VerificationStatus::Draft->value)->index();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();

            $table->foreignId('default_cv_template_id')->nullable()->constrained('cv_templates')->nullOnDelete();
            $table->string('cv_accent_color', 7)->nullable();
            $table->unsignedTinyInteger('profile_completion')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
