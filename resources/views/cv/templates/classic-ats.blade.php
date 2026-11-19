<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 24px; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        h2 { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #ccc; padding-bottom: 3px; margin: 16px 0 8px; color: {{ $accentColor }}; }
        .contact { font-size: 10px; color: #444; margin-bottom: 4px; }
        .item { margin-bottom: 8px; }
        .item-title { font-weight: bold; }
        .item-meta { font-size: 10px; color: #555; }
        .item-desc { font-size: 10.5px; margin-top: 2px; }
        .skills span { display: inline; }
    </style>
</head>
<body>
    <h1>{{ $name }}</h1>
    <p class="contact">
        {{ $studyProgram }} · Semester {{ $semester }} · IPK {{ $gpa }}
        @if ($city) · {{ $city }} @endif
    </p>
    @isset($email)
        <p class="contact">
            {{ $email }}
            @if ($phone) · {{ $phone }} @endif
            @if ($linkedinUrl) · {{ $linkedinUrl }} @endif
        </p>
    @endisset

    @if ($summary)
        <h2>Ringkasan</h2>
        <p>{{ $summary }}</p>
    @endif

    @if (count($skills))
        <h2>Skill</h2>
        <p class="skills">
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

    @if (count($portfolios))
        <h2>Portfolio</h2>
        @foreach ($portfolios as $portfolio)
            <div class="item">
                <div class="item-title">{{ $portfolio->title }}</div>
                @if ($portfolio->url)
                    <div class="item-meta">{{ $portfolio->url }}</div>
                @endif
                @if ($portfolio->description)
                    <div class="item-desc">{{ $portfolio->description }}</div>
                @endif
            </div>
        @endforeach
    @endif
</body>
</html>
