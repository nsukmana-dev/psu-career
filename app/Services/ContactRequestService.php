<?php

namespace App\Services;

use App\Enums\ItemStatus;
use App\Enums\RequestStatus;
use App\Enums\UserRole;
use App\Models\ActivityLog;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Models\ContactRequestItem;
use App\Models\User;
use App\Notifications\ContactApproved;
use App\Notifications\ContactRequestDecided;
use App\Notifications\NewContactRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use InvalidArgumentException;

class ContactRequestService
{
    public const MAX_STUDENTS_PER_REQUEST = 20;

    /**
     * @param  int[]  $studentIds
     */
    public function create(Company $company, array $data, array $studentIds): ContactRequest
    {
        if (count($studentIds) > self::MAX_STUDENTS_PER_REQUEST) {
            throw new InvalidArgumentException('Maksimal 20 kandidat per request.');
        }

        $alreadyApproved = ContactRequestItem::whereIn('student_id', $studentIds)
            ->where('status', ItemStatus::Approved)
            ->whereHas('contactRequest', fn ($q) => $q->where('company_id', $company->id))
            ->pluck('student_id');

        $studentIds = array_values(array_diff($studentIds, $alreadyApproved->all()));

        if ($studentIds === []) {
            throw new InvalidArgumentException('Semua kandidat yang dipilih sudah disetujui sebelumnya.');
        }

        return DB::transaction(function () use ($company, $data, $studentIds) {
            $request = ContactRequest::create([
                ...$data,
                'request_number' => $this->generateRequestNumber(),
                'company_id' => $company->id,
                'status' => RequestStatus::Pending,
            ]);

            foreach ($studentIds as $studentId) {
                $request->items()->create(['student_id' => $studentId, 'status' => ItemStatus::Pending]);
            }

            Notification::send(User::where('role', UserRole::Admin)->get(), new NewContactRequest($request));

            return $request;
        });
    }

    public function decideItem(ContactRequestItem $item, User $admin, ItemStatus $status, ?string $reason = null): void
    {
        if (! $item->contactRequest->company->isActive()) {
            throw new InvalidArgumentException('Perusahaan ini sedang ditangguhkan, request tidak bisa diproses.');
        }

        $item->update([
            'status' => $status,
            'decided_by' => $admin->id,
            'decided_at' => now(),
            'rejection_reason' => $reason,
        ]);

        $contactRequest = $item->contactRequest;

        if ($status === ItemStatus::Approved) {
            $item->student->user->notify(new ContactApproved($contactRequest));
        }

        $contactRequest->company->user->notify(new ContactRequestDecided($contactRequest));

        $contactRequest->update([
            'status' => $contactRequest->items()->where('status', ItemStatus::Pending)->doesntExist()
                ? RequestStatus::Completed
                : RequestStatus::Pending,
        ]);

        ActivityLog::record(
            $status === ItemStatus::Approved ? 'contact_request.approved' : 'contact_request.rejected',
            $item
        );
    }

    public function decideAll(ContactRequest $contactRequest, User $admin, ItemStatus $status): void
    {
        foreach ($contactRequest->items()->where('status', ItemStatus::Pending)->get() as $item) {
            $this->decideItem($item, $admin, $status);
        }
    }

    private function generateRequestNumber(): string
    {
        $count = ContactRequest::whereYear('created_at', now()->year)->lockForUpdate()->count();

        return sprintf('REQ-%d-%05d', now()->year, $count + 1);
    }
}
