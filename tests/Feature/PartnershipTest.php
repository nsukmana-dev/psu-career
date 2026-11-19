<?php

use App\Enums\ApprovalStatus;
use App\Enums\UserRole;
use App\Models\PartnershipApplication;
use App\Models\User;
use App\Notifications\CompanyInvitation;
use App\Notifications\PartnershipApplicationRejected;
use Illuminate\Support\Facades\Notification;

function partnershipPayload(array $overrides = []): array
{
    return array_merge([
        'company_name' => 'PT Maju Jaya',
        'pic_name' => 'Budi Santoso',
        'pic_position' => 'HR Manager',
        'email' => 'hr@majujaya.test',
        'phone' => '0812345678',
        'needs_internship' => true,
        'needs_fulltime' => false,
        'message' => 'Kami tertarik bermitra.',
    ], $overrides);
}

test('a valid partnership application is stored as pending', function () {
    $this->post(route('partnership.store'), partnershipPayload())
        ->assertSessionHasNoErrors();

    expect(PartnershipApplication::where('company_name', 'PT Maju Jaya')->first())
        ->not->toBeNull()
        ->status->toBe(ApprovalStatus::Pending);
});

test('filling the honeypot field rejects the submission', function () {
    $this->post(route('partnership.store'), partnershipPayload(['website' => 'https://spambot.test']))
        ->assertSessionHasErrors('website');

    expect(PartnershipApplication::count())->toBe(0);
});

test('the public form is rate limited', function () {
    for ($i = 0; $i < 5; $i++) {
        $this->post(route('partnership.store'), partnershipPayload(['email' => "hr{$i}@majujaya.test"]));
    }

    $this->post(route('partnership.store'), partnershipPayload(['email' => 'hr6@majujaya.test']))
        ->assertStatus(429);
});

test('admin approving an application creates a company and user and sends an invitation', function () {
    Notification::fake();
    $admin = User::factory()->admin()->create();
    $application = PartnershipApplication::create(partnershipPayload());

    $this->actingAs($admin)->post(route('admin.partnership-applications.approve', $application))
        ->assertRedirect(route('admin.partnership-applications.index'));

    $application->refresh();
    expect($application->status)->toBe(ApprovalStatus::Approved)
        ->and($application->company_id)->not->toBeNull();

    $user = User::where('email', 'hr@majujaya.test')->first();
    expect($user)->not->toBeNull()
        ->role->toBe(UserRole::Company)
        ->must_change_password->toBeTrue();

    Notification::assertSentTo($user, CompanyInvitation::class);
    expect(\DB::table('password_reset_tokens')->where('email', $user->email)->exists())->toBeTrue();
});

test('admin rejecting an application requires a reason and notifies the applicant', function () {
    Notification::fake();
    $admin = User::factory()->admin()->create();
    $application = PartnershipApplication::create(partnershipPayload());

    $this->actingAs($admin)->post(route('admin.partnership-applications.reject', $application))
        ->assertSessionHasErrors('rejection_reason');

    $this->actingAs($admin)->post(route('admin.partnership-applications.reject', $application), [
        'rejection_reason' => 'Data tidak lengkap.',
    ])->assertRedirect(route('admin.partnership-applications.index'));

    $application->refresh();
    expect($application->status)->toBe(ApprovalStatus::Rejected)
        ->and($application->company_id)->toBeNull();

    expect(User::where('email', 'hr@majujaya.test')->exists())->toBeFalse();
    Notification::assertSentOnDemand(PartnershipApplicationRejected::class);
});
