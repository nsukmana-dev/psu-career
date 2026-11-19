<?php

use App\Enums\CompanyStatus;
use App\Enums\EmploymentType;
use App\Enums\FaqAudience;
use App\Enums\ItemStatus;
use App\Enums\LandingSettingType;
use App\Enums\RequestStatus;
use App\Enums\VerificationStatus;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Models\ContactRequestItem;
use App\Models\Faq;
use App\Models\LandingSetting;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('home page shows accurate stats', function () {
    Student::factory()->verified()->count(2)->create();
    Student::factory()->pending()->create();
    Company::factory()->create();
    Company::factory()->suspended()->create();

    $company = Company::factory()->create();
    $student = Student::factory()->verified()->create();
    $request = ContactRequest::create([
        'request_number' => 'CR-0001',
        'company_id' => $company->id,
        'position_title' => 'Backend Developer',
        'employment_type' => EmploymentType::Fulltime,
        'work_location' => 'Bekasi',
        'message' => 'Mencari kandidat.',
        'status' => RequestStatus::Completed,
    ]);
    ContactRequestItem::create([
        'contact_request_id' => $request->id,
        'student_id' => $student->id,
        'status' => ItemStatus::Approved,
    ]);

    $this->get(route('landing.home'))->assertOk();

    expect(Student::where('verification_status', VerificationStatus::Verified)->count())->toBe(3)
        ->and(Company::where('status', CompanyStatus::Active)->count())->toBe(2)
        ->and(ContactRequestItem::where('status', ItemStatus::Approved)->count())->toBe(1);
});

test('landing settings render literally on the home page', function () {
    LandingSetting::create(['key' => 'campus_name', 'value' => 'Universitas Panca Sakti Test', 'type' => LandingSettingType::Text]);

    $this->get(route('landing.home'))->assertSee('Universitas Panca Sakti Test');
});

test('faq pages only show their own audience', function () {
    Faq::create(['audience' => FaqAudience::Student, 'question' => 'Pertanyaan mahasiswa unik', 'answer' => 'Jawaban.', 'sort_order' => 1, 'is_active' => true]);
    Faq::create(['audience' => FaqAudience::Company, 'question' => 'Pertanyaan perusahaan unik', 'answer' => 'Jawaban.', 'sort_order' => 1, 'is_active' => true]);

    $this->get(route('landing.students'))
        ->assertSee('Pertanyaan mahasiswa unik')
        ->assertDontSee('Pertanyaan perusahaan unik');

    $this->get(route('landing.companies'))
        ->assertSee('Pertanyaan perusahaan unik')
        ->assertDontSee('Pertanyaan mahasiswa unik');
});

test('admin can update a landing setting value', function () {
    LandingSetting::create(['key' => 'campus_name', 'value' => '[ISI campus_name]', 'type' => LandingSettingType::Text]);
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.landing-settings.update'), ['campus_name' => 'Nama Baru'])
        ->assertRedirect();

    expect(LandingSetting::where('key', 'campus_name')->value('value'))->toBe('Nama Baru');
});

test('admin can upload a landing logo image', function () {
    Storage::fake('public');
    LandingSetting::create(['key' => 'logo', 'value' => '[ISI logo]', 'type' => LandingSettingType::Image]);
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.landing-settings.update'), ['logo' => UploadedFile::fake()->image('logo.jpg')])
        ->assertRedirect();

    $path = LandingSetting::where('key', 'logo')->value('value');
    Storage::disk('public')->assertExists($path);
});

test('uploading a non image file for a landing setting is rejected', function () {
    Storage::fake('public');
    LandingSetting::create(['key' => 'logo', 'value' => '[ISI logo]', 'type' => LandingSettingType::Image]);
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->put(route('admin.landing-settings.update'), ['logo' => UploadedFile::fake()->create('virus.exe', 100)])
        ->assertSessionHasErrors('logo');
});

test('admin can manage faqs', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.faqs.store'), [
        'audience' => FaqAudience::Student->value,
        'question' => 'Bagaimana cara daftar?',
        'answer' => 'Klik tombol daftar.',
        'sort_order' => 1,
    ])->assertRedirect();

    $faq = Faq::where('question', 'Bagaimana cara daftar?')->first();
    expect($faq)->not->toBeNull()->is_active->toBeTrue();

    $this->actingAs($admin)->post(route('admin.faqs.toggle', $faq))->assertRedirect();
    expect($faq->refresh()->is_active)->toBeFalse();

    $this->actingAs($admin)->put(route('admin.faqs.update', $faq), [
        'audience' => FaqAudience::Student->value,
        'question' => 'Bagaimana cara daftar? (edit)',
        'answer' => 'Klik tombol daftar lalu isi NIM.',
        'sort_order' => 1,
    ])->assertRedirect();
    expect($faq->refresh()->question)->toBe('Bagaimana cara daftar? (edit)');

    $this->actingAs($admin)->delete(route('admin.faqs.destroy', $faq))->assertRedirect();
    expect(Faq::find($faq->id))->toBeNull();
});

test('a non admin cannot manage landing settings or faqs', function () {
    $student = User::factory()->student()->create();

    $this->actingAs($student)->get(route('admin.landing-settings.edit'))->assertForbidden();
    $this->actingAs($student)->get(route('admin.faqs.index'))->assertForbidden();
});
