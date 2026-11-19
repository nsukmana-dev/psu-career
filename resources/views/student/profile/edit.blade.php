@php
    $tabs = [
        'data-diri' => 'Data Diri',
        'skills' => 'Skill',
        'experiences' => 'Pengalaman',
        'educations' => 'Pendidikan',
        'certificates' => 'Sertifikat',
        'portfolios' => 'Portfolio',
        'preferences' => 'Preferensi Kerja',
    ];
@endphp

<x-dashboard-layout role="student" title="Profil Saya">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <x-badge :color="$student->verification_status->color()">{{ $student->verification_status->label() }}</x-badge>
            <span class="text-sm text-slate-500">Kelengkapan profil: {{ $student->profile_completion }}%</span>
        </div>

        @if (in_array($student->verification_status, [\App\Enums\VerificationStatus::Draft, \App\Enums\VerificationStatus::Rejected]))
            <form method="POST" action="{{ route('student.profile.submit') }}">
                @csrf
                <x-primary-button>Ajukan Verifikasi</x-primary-button>
            </form>
        @endif
    </div>

    @if ($student->verification_status === \App\Enums\VerificationStatus::Rejected && $student->rejection_reason)
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <strong>Alasan ditolak:</strong> {{ $student->rejection_reason }}
        </div>
    @endif

    @if ($pendingUpdateRequest)
        <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
            Ada perubahan data akademik yang masih menunggu persetujuan admin.
        </div>
    @endif

    <div class="w-full overflow-x-auto" x-data="{ tab: '{{ old('_tab', 'data-diri') }}' }">
        <div class="flex gap-1 border-b border-slate-200">
            @foreach ($tabs as $key => $label)
                <button
                    type="button"
                    @click="tab = '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-[#0E7C74] text-[#0E7C74]' : 'border-transparent text-slate-500 hover:text-slate-700'"
                    class="whitespace-nowrap border-b-2 px-4 py-2 text-sm font-medium"
                >{{ $label }}</button>
            @endforeach
        </div>

        <div class="mt-6" x-show="tab === 'data-diri'">
            @include('student.profile.partials.data-diri')
        </div>
        <div class="mt-6" x-show="tab === 'skills'" x-cloak>
            @include('student.profile.partials.skills')
        </div>
        <div class="mt-6" x-show="tab === 'experiences'" x-cloak>
            @include('student.profile.partials.experiences')
        </div>
        <div class="mt-6" x-show="tab === 'educations'" x-cloak>
            @include('student.profile.partials.educations')
        </div>
        <div class="mt-6" x-show="tab === 'certificates'" x-cloak>
            @include('student.profile.partials.certificates')
        </div>
        <div class="mt-6" x-show="tab === 'portfolios'" x-cloak>
            @include('student.profile.partials.portfolios')
        </div>
        <div class="mt-6" x-show="tab === 'preferences'" x-cloak>
            @include('student.profile.partials.preferences')
        </div>
    </div>
</x-dashboard-layout>
