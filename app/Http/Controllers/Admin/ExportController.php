<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ItemStatus;
use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use App\Models\Student;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function students(): StreamedResponse
    {
        $students = Student::query()
            ->where('verification_status', VerificationStatus::Verified)
            ->with(['user', 'studyProgram'])
            ->orderBy('nim')
            ->get();

        return $this->csv('mahasiswa-terverifikasi.csv', [
            'NIM', 'Nama', 'Email', 'Program Studi', 'Angkatan', 'Semester', 'IPK', 'Kota', 'No HP', 'Tanggal Verifikasi',
        ], $students->map(fn (Student $student) => [
            $student->nim,
            $student->user->name,
            $student->user->email,
            $student->studyProgram->name,
            $student->entry_year,
            $student->current_semester,
            $student->gpa,
            $student->city,
            $student->phone,
            $student->verified_at?->format('Y-m-d'),
        ]));
    }

    public function contactRequests(): StreamedResponse
    {
        $requests = ContactRequest::query()
            ->with('company')
            ->withCount([
                'items as approved_count' => fn ($q) => $q->where('status', ItemStatus::Approved),
                'items as rejected_count' => fn ($q) => $q->where('status', ItemStatus::Rejected),
                'items as pending_count' => fn ($q) => $q->where('status', ItemStatus::Pending),
            ])
            ->orderByDesc('created_at')
            ->get();

        return $this->csv('rekap-request-kontak.csv', [
            'No Request', 'Perusahaan', 'Posisi', 'Tipe', 'Lokasi', 'Status', 'Disetujui', 'Ditolak', 'Menunggu', 'Tanggal Dibuat',
        ], $requests->map(fn (ContactRequest $request) => [
            $request->request_number,
            $request->company->name,
            $request->position_title,
            $request->employment_type->label(),
            $request->work_location,
            $request->status->label(),
            $request->approved_count,
            $request->rejected_count,
            $request->pending_count,
            $request->created_at->format('Y-m-d'),
        ]));
    }

    private function csv(string $filename, array $header, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($header, $rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $header);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
