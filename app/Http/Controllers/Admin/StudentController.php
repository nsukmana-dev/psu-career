<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::query()
            ->with(['user', 'studyProgram'])
            ->when($request->status, fn ($q, $status) => $q->where('verification_status', $status))
            ->when($request->study_program_id, fn ($q, $id) => $q->where('study_program_id', $id))
            ->when($request->entry_year, fn ($q, $year) => $q->where('entry_year', $year))
            ->when($request->search, fn ($q, $term) => $q->where(fn ($q2) => $q2
                ->where('nim', 'like', "%{$term}%")
                ->orWhereHas('user', fn ($q3) => $q3->where('name', 'like', "%{$term}%"))
            ))
            ->orderBy('nim')
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index', [
            'students' => $students,
            'studyPrograms' => StudyProgram::orderBy('name')->get(),
            'statuses' => VerificationStatus::cases(),
            'verifiedCount' => Student::where('verification_status', VerificationStatus::Verified)->count(),
            'inactiveCount' => Student::whereHas('user', fn ($q) => $q->where('is_active', false))->count(),
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $student = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::password(32)),
                'role' => UserRole::Student,
                'must_change_password' => true,
                'email_verified_at' => now(),
            ]);

            return Student::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'study_program_id' => $request->study_program_id,
                'entry_year' => $request->entry_year ?? now()->year,
            ]);
        });

        Password::sendResetLink(['email' => $student->user->email]);
        ActivityLog::record('student.created', $student);

        return redirect()->route('admin.students.index')
            ->with('status', "Akun mahasiswa {$student->user->name} dibuat, email atur password terkirim.");
    }

    public function toggleActive(Student $student): RedirectResponse
    {
        $student->user->update(['is_active' => ! $student->user->is_active]);

        ActivityLog::record($student->user->is_active ? 'student.activated' : 'student.deactivated', $student);

        return back()->with('status', $student->user->is_active ? 'Akun mahasiswa diaktifkan.' : 'Akun mahasiswa dinonaktifkan.');
    }

    public function resetPassword(Student $student): RedirectResponse
    {
        Password::sendResetLink(['email' => $student->user->email]);
        ActivityLog::record('student.password-reset-sent', $student);

        return back()->with('status', 'Email atur ulang password terkirim ke mahasiswa.');
    }
}
