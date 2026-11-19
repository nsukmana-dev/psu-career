<?php

namespace App\Services;

use App\Models\CvTemplate;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class CvPdfService
{
    public function __construct(private CvDataBuilder $builder) {}

    public function download(Student $student, CvTemplate $template): Response
    {
        $data = $this->builder->build($student);

        return Pdf::loadView($template->blade_view, $data)
            ->download("cv-{$student->nim}-{$template->slug}.pdf");
    }
}
