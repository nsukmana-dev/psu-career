<?php

use App\Models\Company;
use App\Models\SavedSearch;
use App\Models\Shortlist;
use App\Models\Student;

test('a company can create a shortlist and add a candidate with a note', function () {
    $company = Company::factory()->create();
    $student = Student::factory()->create();

    $this->actingAs($company->user)->post(route('company.shortlists.store'), ['name' => 'Posisi Backend'])
        ->assertRedirect();

    $shortlist = Shortlist::where('company_id', $company->id)->first();
    expect($shortlist->name)->toBe('Posisi Backend');

    $this->actingAs($company->user)->post(route('company.shortlists.items.quick-add'), [
        'shortlist_id' => $shortlist->id,
        'student_id' => $student->id,
        'note' => 'Kandidat kuat',
    ])->assertRedirect();

    expect($shortlist->items()->where('student_id', $student->id)->first()->note)->toBe('Kandidat kuat');
});

test('a company cannot view, modify, or add items to another companys shortlist', function () {
    $owner = Company::factory()->create();
    $intruder = Company::factory()->create();
    $shortlist = Shortlist::create(['company_id' => $owner->id, 'name' => 'Rahasia']);
    $student = Student::factory()->create();

    $this->actingAs($intruder->user)->get(route('company.shortlists.show', $shortlist))->assertForbidden();
    $this->actingAs($intruder->user)->delete(route('company.shortlists.destroy', $shortlist))->assertForbidden();
    $this->actingAs($intruder->user)->post(route('company.shortlists.items.quick-add'), [
        'shortlist_id' => $shortlist->id,
        'student_id' => $student->id,
    ])->assertForbidden();
});

test('a company cannot delete another companys saved search', function () {
    $owner = Company::factory()->create();
    $intruder = Company::factory()->create();
    $saved = SavedSearch::create(['company_id' => $owner->id, 'name' => 'Filter A', 'filters' => ['min_gpa' => 3]]);

    $this->actingAs($intruder->user)->delete(route('company.saved-searches.destroy', $saved))->assertForbidden();
});

test('a saved search stores filters as json and can be reapplied', function () {
    $company = Company::factory()->create();

    $this->actingAs($company->user)->post(route('company.saved-searches.store'), [
        'name' => 'IPK Tinggi',
        'filters' => ['min_gpa' => '3.5', 'sort' => 'gpa_desc'],
    ])->assertRedirect();

    $saved = SavedSearch::where('company_id', $company->id)->first();
    expect($saved->filters)->toBe(['min_gpa' => '3.5', 'sort' => 'gpa_desc']);

    $this->actingAs($company->user)->get(route('company.saved-searches.apply', $saved))
        ->assertRedirect(route('company.candidates.index', $saved->filters));
});
