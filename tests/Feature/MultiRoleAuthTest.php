<?php

use App\Enums\CompanyStatus;
use App\Models\Company;
use App\Models\Student;
use App\Models\User;

test('admin lands on the admin dashboard after login', function () {
    $admin = User::factory()->admin()->create();

    $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect(route('admin.dashboard', absolute: false));
});

test('student lands on the student dashboard after login', function () {
    $student = Student::factory()->create()->user;

    $this->post('/login', ['email' => $student->email, 'password' => 'password'])
        ->assertRedirect(route('student.dashboard', absolute: false));
});

test('company lands on the company dashboard after login', function () {
    $company = Company::factory()->create()->user;

    $this->post('/login', ['email' => $company->email, 'password' => 'password'])
        ->assertRedirect(route('company.dashboard', absolute: false));
});

test('a role cannot access another roles dashboard', function () {
    $student = Student::factory()->create()->user;

    $this->actingAs($student)->get('/admin')->assertForbidden();
    $this->actingAs($student)->get('/company')->assertForbidden();
});

test('an inactive user cannot log in', function () {
    $user = User::factory()->student()->inactive()->create();

    $response = $this->post('/login', ['email' => $user->email, 'password' => 'password']);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('a suspended company cannot log in', function () {
    $company = Company::factory()->suspended()->create();

    $response = $this->post('/login', ['email' => $company->user->email, 'password' => 'password']);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('a suspended company is blocked even with an existing session', function () {
    $company = Company::factory()->create();

    $this->actingAs($company->user)->get('/company')->assertOk();

    $company->update(['status' => CompanyStatus::Suspended]);

    $this->actingAs($company->user->fresh())->get('/company')->assertForbidden();
});

test('a user forced to change password is redirected away from the dashboard', function () {
    $user = User::factory()->student()->create(['must_change_password' => true]);
    Student::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)->get('/student')->assertRedirect(route('password.change'));

    $this->actingAs($user)
        ->post('/password/change', ['password' => 'new-password', 'password_confirmation' => 'new-password'])
        ->assertRedirect(route('student.dashboard', absolute: false));

    expect($user->refresh()->must_change_password)->toBeFalse();
});
