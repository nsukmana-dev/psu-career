<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 24px; }
        table.header { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .photo { width: 80px; height: 80px; border-radius: 50%; }
        h1 { font-size: 22px; margin: 0 0 2px; color: {{ $accentColor }}; }
        .role { font-size: 11px; color: #555; margin: 0; }
        .contact { font-size: 10px; color: #555; margin-top: 4px; }
        h2 { font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; color: #fff; background: {{ $accentColor }}; padding: 4px 8px; margin: 14px 0 8px; }
        .item { margin-bottom: 8px; }
        .item-title { font-weight: bold; }
        .item-meta { font-size: 10px; color: #555; }
        .item-desc { font-size: 10.5px; margin-top: 2px; }
        .skill-chip { display: inline-block; border: 1px solid {{ $accentColor }}; color: {{ $accentColor }}; border-radius: 10px; padding: 2px 8px; margin: 0 4px 4px 0; font-size: 9.5px; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            @if ($photoDataUri)
                <td style="width: 90px;"><img src="{{ $photoDataUri }}" class="photo"></td>
            @endif
            <td style="vertical-align: middle;">
                <h1>{{ $name }}</h1>
                <p class="role">{{ $studyProgram }} · Semester {{ $semester }} · IPK {{ $gpa }}</p>
                <p class="contact">
                    @isset($email)
                        {{ $email }} @if ($phone) · {{ $phone }} @endif
                    @endisset
                    @if ($city) · {{ $city }} @endif
                </p>
            </td>
        </tr>
    </table>

    @if ($summary)
        <h2>Ringkasan</h2>
        <p>{{ $summary }}</p>
    @endif

    @if (count($skills))
        <h2>Skill</h2>
        <p>
            @foreach ($skills as $skill)
                <span class="skill-chip">{{ $skill['name'] }} · {{ $skill['level'] }}</span>
            @endforeach
        </p>
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
</body>
</html>
