<?php

namespace Tests\Feature\Auth;

use App\Models\StudyProgram;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_students_can_register(): void
    {
        $program = StudyProgram::factory()->create();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'nim' => '2026000123',
            'study_program_id' => $program->id,
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'consent' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('student.dashboard', absolute: false));
    }

    public function test_registration_is_rate_limited(): void
    {
        $program = StudyProgram::factory()->create();

        for ($i = 0; $i < 10; $i++) {
            $this->post('/register', ['nim' => "202600{$i}"]);
        }

        $response = $this->post('/register', [
            'name' => 'Test User',
            'nim' => '2026009999',
            'study_program_id' => $program->id,
            'email' => 'throttled@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'consent' => '1',
        ]);

        $response->assertStatus(429);
    }
}
