<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 0; }
        table.layout { width: 100%; border-collapse: collapse; }
        td.sidebar { width: 32%; background: {{ $accentColor }}; color: #fff; padding: 20px 16px; vertical-align: top; }
        td.main { width: 68%; padding: 20px 18px; vertical-align: top; }
        .photo { width: 90px; height: 90px; border-radius: 50%; margin-bottom: 12px; }
        .sidebar h2 { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin: 16px 0 6px; border-bottom: 1px solid rgba(255,255,255,0.4); padding-bottom: 3px; }
        .sidebar p { font-size: 10px; margin: 0 0 4px; }
        h1 { font-size: 19px; margin: 0 0 2px; color: {{ $accentColor }}; }
        .role { font-size: 11px; color: #555; margin: 0 0 14px; }
        .main h2 { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: {{ $accentColor }}; border-bottom: 1px solid #ddd; padding-bottom: 3px; margin: 14px 0 6px; }
        .item { margin-bottom: 8px; }
        .item-title { font-weight: bold; }
        .item-meta { font-size: 10px; color: #555; }
        .item-desc { font-size: 10.5px; margin-top: 2px; }
    </style>
</head>
<body>
    <table class="layout">
        <tr>
            <td class="sidebar">
                @if ($photoDataUri)
                    <img src="{{ $photoDataUri }}" class="photo">
                @endif

                <h2>Kontak</h2>
                @isset($email)
                    <p>{{ $email }}</p>
                    @if ($phone)<p>{{ $phone }}</p>@endif
                    @if ($linkedinUrl)<p>{{ $linkedinUrl }}</p>@endif
                @endisset
                @if ($city)<p>{{ $city }}</p>@endif

                @if (count($skills))
                    <h2>Skill</h2>
                    @foreach ($skills as $skill)
                        <p>{{ $skill['name'] }} · {{ $skill['level'] }}</p>
                    @endforeach
                @endif
            </td>
            <td class="main">
                <h1>{{ $name }}</h1>
                <p class="role">{{ $studyProgram }} · Semester {{ $semester }} · IPK {{ $gpa }}</p>

                @if ($summary)
                    <h2>Ringkasan</h2>
                    <p>{{ $summary }}</p>
                @endif

                @if (count($experiences))
                    <h2>Pengalaman</h2>
                    @foreach ($experiences as $experience)
                        <div class="item">
                            <div class="item-title">{{ $experience->position }} — {{ $experience->organization }}</div>
                            <div class="item-meta">
                                {{ $experience->type->label() }} ·
                                {{ $experience->start_date->translatedFormat('M Y') }} –
                                {{ $experience->is_current ? 'Sekarang' : $experience->end_date?->translatedFormat('M Y') }}
                            </div>
                            @if ($experience->description)
                                <div class="item-desc">{{ $experience->description }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif

                @if (count($educations))
                    <h2>Pendidikan</h2>
                    @foreach ($educations as $education)
                        <div class="item">
                            <div class="item-title">{{ $education->institution }}</div>
                            <div class="item-meta">{{ $education->major }} · {{ $education->level }} · {{ $education->start_year }}–{{ $education->end_year ?? 'sekarang' }}</div>
                        </div>
                    @endforeach
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
