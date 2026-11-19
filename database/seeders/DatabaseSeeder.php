<?php

namespace Database\Seeders;

use App\Enums\FaqAudience;
use App\Enums\LandingSettingType;
use App\Enums\StudyProgramLevel;
use App\Enums\UserRole;
use App\Models\CvTemplate;
use App\Models\Faq;
use App\Models\Industry;
use App\Models\LandingSetting;
use App\Models\Skill;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Career Center',
            'email' => 'admin@psu.ac.id',
            'role' => UserRole::Admin,
        ]);

        // Data contoh — ganti dengan daftar program studi aktual kampus sebelum rilis produksi.
        foreach ([
            ['code' => 'SI', 'name' => 'Sistem Informasi', 'level' => StudyProgramLevel::S1],
            ['code' => 'TI', 'name' => 'Teknik Informatika', 'level' => StudyProgramLevel::S1],
            ['code' => 'MN', 'name' => 'Manajemen', 'level' => StudyProgramLevel::S1],
            ['code' => 'AK', 'name' => 'Akuntansi', 'level' => StudyProgramLevel::S1],
            ['code' => 'HK', 'name' => 'Ilmu Hukum', 'level' => StudyProgramLevel::S1],
        ] as $program) {
            StudyProgram::create([...$program, 'is_active' => true]);
        }

        foreach ([
            'Teknologi Informasi', 'Perbankan & Keuangan', 'Manufaktur', 'Retail & Perdagangan',
            'Pendidikan', 'Kesehatan', 'Konstruksi & Properti', 'Media & Komunikasi',
            'Logistik & Transportasi', 'Pariwisata & Perhotelan',
        ] as $name) {
            Industry::create(['name' => $name]);
        }

        $skills = [
            'IT' => ['PHP', 'Laravel', 'JavaScript', 'TypeScript', 'React', 'Vue.js', 'Node.js', 'Python', 'Django', 'Java', 'Spring Boot', 'C#', '.NET', 'HTML', 'CSS', 'Tailwind CSS', 'MySQL', 'PostgreSQL', 'MongoDB', 'Git', 'Docker', 'Linux', 'REST API', 'GraphQL', 'Flutter'],
            'Bisnis' => ['Microsoft Excel', 'Microsoft Word', 'Microsoft PowerPoint', 'Akuntansi Dasar', 'Analisis Keuangan', 'Pemasaran Digital', 'SEO', 'Copywriting', 'Manajemen Proyek', 'Riset Pasar', 'Perpajakan', 'Audit', 'Customer Service', 'Negosiasi', 'Administrasi Perkantoran', 'Sales', 'Branding', 'Social Media Marketing', 'Data Analysis', 'Business Plan'],
            'Soft Skill' => ['Komunikasi', 'Kerja Tim', 'Kepemimpinan', 'Manajemen Waktu', 'Berpikir Kritis', 'Problem Solving', 'Adaptabilitas', 'Kreativitas', 'Public Speaking', 'Etika Kerja', 'Manajemen Konflik', 'Disiplin', 'Inisiatif', 'Bahasa Inggris', 'Bahasa Mandarin'],
        ];
        foreach ($skills as $category => $names) {
            foreach ($names as $name) {
                Skill::create(['name' => $name, 'category' => $category, 'is_approved' => true]);
            }
        }

        foreach ([
            ['name' => 'Klasik ATS-Friendly', 'slug' => 'classic-ats', 'blade_view' => 'cv.templates.classic-ats', 'sort_order' => 1],
            ['name' => 'Modern Sidebar', 'slug' => 'modern-sidebar', 'blade_view' => 'cv.templates.modern-sidebar', 'sort_order' => 2],
            ['name' => 'Kreatif', 'slug' => 'creative', 'blade_view' => 'cv.templates.creative', 'sort_order' => 3],
            ['name' => 'Portfolio', 'slug' => 'portfolio', 'blade_view' => 'cv.templates.portfolio', 'sort_order' => 4],
        ] as $template) {
            CvTemplate::create([...$template, 'is_active' => true]);
        }

        $faqs = [
            FaqAudience::Student->value => [
                ['Apakah data saya aman?', 'Kontak pribadi (email, no HP) tidak pernah ditampilkan ke perusahaan sampai kamu menyetujui lewat persetujuan admin kampus.'],
                ['Bagaimana cara mengubah IPK atau semester?', 'Perubahan data akademik sensitif perlu disetujui admin, tapi perubahan skill, pengalaman, dan portofolio langsung tersimpan.'],
                ['Kapan profil saya bisa dilihat perusahaan?', 'Setelah profil diverifikasi admin dan toggle "Bersedia dihubungi perusahaan" kamu aktifkan.'],
                ['Apakah saya bisa memilih template CV?', 'Ya, tersedia beberapa template CV yang bisa dipilih dan diunduh dalam format PDF.'],
            ],
            FaqAudience::Company->value => [
                ['Bagaimana cara mendapatkan akun perusahaan?', 'Ajukan kemitraan melalui formulir di halaman ini, admin kampus akan membuat akun dan mengirim undangan.'],
                ['Apakah kami bisa melihat kontak kandidat langsung?', 'Tidak. Kontak hanya dibagikan setelah admin kampus menyetujui request kontak yang diajukan.'],
                ['Berapa lama proses persetujuan request kontak?', 'Admin memproses setiap request sesegera mungkin, status dapat dipantau di dashboard perusahaan.'],
                ['Apakah data kandidat terverifikasi?', 'Ya, hanya mahasiswa yang sudah diverifikasi admin kampus yang muncul di hasil pencarian.'],
            ],
        ];
        foreach ($faqs as $audience => $items) {
            foreach ($items as $order => [$question, $answer]) {
                Faq::create([
                    'audience' => $audience,
                    'question' => $question,
                    'answer' => $answer,
                    'sort_order' => $order + 1,
                    'is_active' => true,
                ]);
            }
        }

        foreach ([
            'campus_name' => LandingSettingType::Text,
            'campus_description' => LandingSettingType::Textarea,
            'accreditation' => LandingSettingType::Text,
            'alumni_count' => LandingSettingType::Number,
            'address' => LandingSettingType::Textarea,
            'career_center_email' => LandingSettingType::Text,
            'career_center_phone' => LandingSettingType::Text,
            'photo_1' => LandingSettingType::Image,
            'photo_2' => LandingSettingType::Image,
            'photo_3' => LandingSettingType::Image,
            'logo' => LandingSettingType::Image,
        ] as $key => $type) {
            LandingSetting::create([
                'key' => $key,
                'value' => '[ISI '.$key.']',
                'type' => $type,
            ]);
        }
    }
}
