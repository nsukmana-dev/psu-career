<?php

use App\Enums\EmploymentType;
use App\Enums\RequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('position_title');
            $table->enum('employment_type', array_column(EmploymentType::cases(), 'value'));
            $table->string('work_location')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', array_column(RequestStatus::cases(), 'value'))
                ->default(RequestStatus::Pending->value)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_requests');
    }
};
