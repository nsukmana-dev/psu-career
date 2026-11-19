<?php

use App\Enums\ApprovalStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partnership_applications', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->foreignId('industry_id')->nullable()->constrained('industries')->nullOnDelete();
            $table->string('pic_name');
            $table->string('pic_position');
            $table->string('email');
            $table->string('phone');
            $table->boolean('needs_internship')->default(false);
            $table->boolean('needs_fulltime')->default(false);
            $table->text('message')->nullable();
            $table->enum('status', array_column(ApprovalStatus::cases(), 'value'))
                ->default(ApprovalStatus::Pending->value)->index();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_applications');
    }
};
