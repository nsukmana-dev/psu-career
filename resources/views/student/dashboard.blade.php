<x-dashboard-layout role="student" title="Dashboard Mahasiswa">
    <x-card title="Selamat datang, {{ auth()->user()->name }}">
        <div class="flex items-center gap-3">
            <x-badge :color="$student->verification_status->color()">{{ $student->verification_status->label() }}</x-badge>
            <span class="text-sm text-slate-600">NIM: {{ $student->nim }} · Kelengkapan profil: {{ $student->profile_completion }}%</span>
        </div>

        @if ($pendingUpdateRequest)
            <p class="mt-3 text-sm text-amber-600">Ada perubahan data akademik yang masih menunggu persetujuan admin.</p>
        @endif

        <p class="mt-3 text-sm text-slate-600">
            {{ $profileViewsThisMonth }} perusahaan melihat profilmu bulan ini.
        </p>
        <p class="mt-1 text-sm text-slate-600">
            {{ $contactHistoryCount }} kontak sudah dibagikan ke perusahaan.
        </p>

        <div class="mt-4 flex flex-wrap gap-3">
            <a href="{{ route('student.profile.edit') }}">
                <x-primary-button>Update Semester</x-primary-button>
            </a>
            <a href="{{ route('student.cv.index') }}">
                <x-secondary-button>Download CV</x-secondary-button>
            </a>
            <a href="{{ route('student.contact-history.index') }}">
                <x-secondary-button>Riwayat Kontak</x-secondary-button>
            </a>
        </div>
    </x-card>
</x-dashboard-layout>
