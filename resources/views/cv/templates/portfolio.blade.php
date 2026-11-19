<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 24px; }
        h1 { font-size: 20px; margin: 0 0 2px; color: {{ $accentColor }}; }
        .role { font-size: 11px; color: #555; margin: 0 0 4px; }
        .contact { font-size: 10px; color: #555; margin-bottom: 10px; }
        h2 { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: {{ $accentColor }}; border-bottom: 2px solid {{ $accentColor }}; padding-bottom: 3px; margin: 16px 0 8px; }
        table.grid { width: 100%; border-collapse: collapse; }
        table.grid td { width: 50%; vertical-align: top; padding: 6px 10px 6px 0; }
        .project { border: 1px solid #ddd; padding: 8px; }
        .project-title { font-weight: bold; color: {{ $accentColor }}; }
        .project-desc { font-size: 10px; margin-top: 3px; }
        .project-url { font-size: 9.5px; color: #555; margin-top: 3px; }
        .item { margin-bottom: 6px; }
        .item-title { font-weight: bold; }
        .item-meta { font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <h1>{{ $name }}</h1>
    <p class="role">{{ $studyProgram }} · Semester {{ $semester }} · IPK {{ $gpa }}</p>
    <p class="contact">
        @isset($email)
            {{ $email }} @if ($phone) · {{ $phone }} @endif
        @endisset
        @if ($city) · {{ $city }} @endif
    </p>

    @if ($summary)
        <p>{{ $summary }}</p>
    @endif

    @if (count($portfolios))
        <h2>Portfolio Proyek</h2>
        <table class="grid">
            @foreach ($portfolios->chunk(2) as $row)
                <tr>
                    @foreach ($row as $portfolio)
                        <td>
                            <div class="project">
                                <div class="project-title">{{ $portfolio->title }}</div>
                                @if ($portfolio->description)
                                    <div class="project-desc">{{ $portfolio->description }}</div>
                                @endif
                                @if ($portfolio->url)
                                    <div class="project-url">{{ $portfolio->url }}</div>
                                @endif
                            </div>
                        </td>
                    @endforeach
                    @if ($row->count() === 1)
                        <td></td>
                    @endif
                </tr>
            @endforeach
        </table>
    @endif

    @if (count($skills))
        <h2>Skill</h2>
        <p>
            @foreach ($skills as $i => $skill)
                {{ $skill['name'] }} ({{ $skill['level'] }}){{ $i < count($skills) - 1 ? ', ' : '' }}
            @endforeach
        </p>
    @endif

    @if (count($experiences))
        <h2>Pengalaman</h2>
        @foreach ($experiences as $experience)
            <div class="item">
                <div class="item-title">{{ $experience->position }} — {{ $experience->organization }}</div>
                <div class="item-meta">
                    {{ $experience->start_date->translatedFormat('M Y') }} –
                    {{ $experience->is_current ? 'Sekarang' : $experience->end_date?->translatedFormat('M Y') }}
                </div>
            </div>
        @endforeach
    @endif
</body>
</html>
