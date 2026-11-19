<x-card title="Tambah Sertifikat">
    <form method="POST" action="{{ route('student.certificates.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="cert_name" value="Nama Sertifikat" />
                <x-text-input id="cert_name" name="name" type="text" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label for="issuer" value="Penerbit" />
                <x-text-input id="issuer" name="issuer" type="text" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label for="issued_at" value="Tanggal Terbit" />
                <x-text-input id="issued_at" name="issued_at" type="date" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label for="credential_url" value="Credential URL (opsional)" />
                <x-text-input id="credential_url" name="credential_url" type="url" class="mt-1 block w-full" />
            </div>
        </div>

        <div>
            <x-input-label for="file" value="File (PDF/JPG, maks 2MB)" />
            <input id="file" name="file" type="file" accept=".pdf,.jpg,.jpeg" class="mt-1 block text-sm">
            <x-input-error :messages="$errors->get('file')" class="mt-1" />
        </div>

        <x-primary-button>Tambah Sertifikat</x-primary-button>
    </form>
</x-card>

<x-card title="Sertifikat Saya" class="mt-6">
    @if ($student->certificates->isEmpty())
        <p class="text-sm text-slate-500">Belum ada sertifikat ditambahkan.</p>
    @else
        <ul class="divide-y divide-slate-100">
            @foreach ($student->certificates->sortByDesc('issued_at') as $certificate)
                <li class="flex items-start justify-between gap-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-slate-900">{{ $certificate->name }}</p>
                        <p class="text-xs text-slate-500">{{ $certificate->issuer }} · {{ $certificate->issued_at->translatedFormat('d M Y') }}</p>
                        <div class="mt-1 flex gap-3 text-xs">
                            @if ($certificate->file_path)
                                <a href="{{ route('certificates.download', $certificate) }}" class="text-[#2457C5] hover:underline">Unduh file</a>
                            @endif
                            @if ($certificate->credential_url)
                                <a href="{{ $certificate->credential_url }}" target="_blank" class="text-[#2457C5] hover:underline">Lihat credential</a>
                            @endif
                        </div>
                    </div>
                    <form method="POST" action="{{ route('student.certificates.destroy', $certificate) }}" onsubmit="return confirm('Hapus sertifikat ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">Hapus</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</x-card>
