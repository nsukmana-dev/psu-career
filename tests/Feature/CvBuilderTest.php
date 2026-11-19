<?php

use App\Models\CvTemplate;
use App\Models\Student;
use App\Services\CvDataBuilder;

beforeEach(function () {
    foreach ([
        ['name' => 'Klasik ATS-Friendly', 'slug' => 'classic-ats', 'blade_view' => 'cv.templates.classic-ats', 'sort_order' => 1],
        ['name' => 'Modern Sidebar', 'slug' => 'modern-sidebar', 'blade_view' => 'cv.templates.modern-sidebar', 'sort_order' => 2],
        ['name' => 'Kreatif', 'slug' => 'creative', 'blade_view' => 'cv.templates.creative', 'sort_order' => 3],
        ['name' => 'Portfolio', 'slug' => 'portfolio', 'blade_view' => 'cv.templates.portfolio', 'sort_order' => 4],
    ] as $template) {
        CvTemplate::create([...$template, 'is_active' => true]);
    }
});

test('cv data excludes contact fields unless explicitly included', function () {
    $student = Student::factory()->create(['phone' => '0812345678']);
    $builder = app(CvDataBuilder::class);

    $public = $builder->build($student, includeContact: false);
    $withContact = $builder->build($student, includeContact: true);

    expect($public)->not->toHaveKeys(['email', 'phone', 'linkedinUrl', 'portfolioUrl'])
        ->and($withContact)->toHaveKeys(['email', 'phone', 'linkedinUrl', 'portfolioUrl'])
        ->and($withContact['phone'])->toBe('0812345678');
});

test('cv data defaults to including contact for the owners own view', function () {
    $student = Student::factory()->create();

    expect(app(CvDataBuilder::class)->build($student))->toHaveKey('email');
});

test('student can preview every active cv template as html', function () {
    $student = Student::factory()->create();

    foreach (CvTemplate::where('is_active', true)->get() as $template) {
        $this->actingAs($student->user)
            ->get(route('student.cv.preview', $template->slug))
            ->assertOk();
    }
});

test('student can download a cv as a named pdf', function () {
    $student = Student::factory()->create();
    $template = CvTemplate::where('is_active', true)->first();

    $response = $this->actingAs($student->user)->get(route('student.cv.download', $template->slug));

    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('application/pdf');
    expect($response->headers->get('content-disposition'))->toContain("cv-{$student->nim}-{$template->slug}.pdf");
});

test('student can set a default template and accent color', function () {
    $student = Student::factory()->create();
    $template = CvTemplate::where('is_active', true)->first();

    $this->actingAs($student->user)->put(route('student.cv.settings'), [
        'default_cv_template_id' => $template->id,
        'cv_accent_color' => '#2457C5',
    ])->assertSessionHasNoErrors();

    $fresh = $student->fresh();
    expect($fresh->default_cv_template_id)->toBe($template->id)
        ->and($fresh->cv_accent_color)->toBe('#2457C5');
});

test('an invalid accent color is rejected', function () {
    $student = Student::factory()->create();
    $template = CvTemplate::where('is_active', true)->first();

    $this->actingAs($student->user)->put(route('student.cv.settings'), [
        'default_cv_template_id' => $template->id,
        'cv_accent_color' => 'not-a-color',
    ])->assertSessionHasErrors('cv_accent_color');
});
