<?php

use App\Enums\CompanyStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\User;
use App\Notifications\CompanyInvitation;
use Illuminate\Support\Facades\Notification;

test('admin can manually create a company which invites a user', function () {
    Notification::fake();
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.companies.store'), [
        'name' => 'PT Teknologi Nusantara',
        'pic_name' => 'Siti Aminah',
        'pic_position' => 'Talent Acquisition',
        'pic_phone' => '021555000',
        'email' => 'siti@teknologinusantara.test',
    ])->assertRedirect(route('admin.companies.index'));

    $company = Company::where('name', 'PT Teknologi Nusantara')->first();
    expect($company)->not->toBeNull()
        ->status->toBe(CompanyStatus::Active);

    $user = User::where('email', 'siti@teknologinusantara.test')->first();
    expect($user)->not->toBeNull()
        ->role->toBe(UserRole::Company)
        ->must_change_password->toBeTrue();

    expect($company->user_id)->toBe($user->id);
    Notification::assertSentTo($user, CompanyInvitation::class);
});

test('admin can suspend and reactivate a company', function () {
    $admin = User::factory()->admin()->create();
    $company = Company::factory()->create();

    $this->actingAs($admin)->post(route('admin.companies.suspend', $company))
        ->assertRedirect();
    expect($company->fresh()->status)->toBe(CompanyStatus::Suspended);

    $this->actingAs($admin)->post(route('admin.companies.activate', $company))
        ->assertRedirect();
    expect($company->fresh()->status)->toBe(CompanyStatus::Active);
});

test('a non admin cannot manage companies', function () {
    $company = Company::factory()->create();

    $this->actingAs($company->user)->get(route('admin.companies.index'))->assertForbidden();
});
