<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        // Ambil 3 testimoni terbaru yang sudah disetujui
        $testimonials = Testimonial::where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('public.about', [
            'titleShop' => '🏢 Tentang Kami - RAVAZKA | Sejarah & Visi Seragam Sekolah Terpercaya',
            'title' => '🏢 Tentang Kami - RAVAZKA | Sejarah & Visi Seragam Sekolah Terpercaya',
            'metaDescription' => '📖 Kenali lebih dekat RAVAZKA, produsen seragam sekolah terpercaya dengan pengalaman bertahun-tahun. Komitmen kualitas, pelayanan terbaik, dan kepuasan pelanggan.',
            'metaKeywords' => 'tentang RAVAZKA, sejarah perusahaan seragam, visi misi RAVAZKA, produsen seragam terpercaya, profil perusahaan',
            'testimonials' => $testimonials
        ]);
    }
}