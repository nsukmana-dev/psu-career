<?php

use App\Enums\CompanyStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('industry_id')->nullable()->constrained('industries')->nullOnDelete();
            $table->string('address')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('description')->nullable();
            $table->string('pic_name');
            $table->string('pic_position');
            $table->string('pic_phone');
            $table->enum('status', array_column(CompanyStatus::cases(), 'value'))->default(CompanyStatus::Active->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
