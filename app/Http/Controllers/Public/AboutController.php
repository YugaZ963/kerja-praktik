<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        return view('public.about', [
            'titleShop' => '🏢 Tentang Kami - RAVAZKA | Sejarah & Visi Seragam Sekolah Terpercaya',
            'title' => '🏢 Tentang Kami - RAVAZKA | Sejarah & Visi Seragam Sekolah Terpercaya',
            'metaDescription' => '📖 Kenali lebih dekat RAVAZKA, produsen seragam sekolah terpercaya dengan pengalaman bertahun-tahun. Komitmen kualitas, pelayanan terbaik, dan kepuasan pelanggan.',
            'metaKeywords' => 'tentang RAVAZKA, sejarah perusahaan seragam, visi misi RAVAZKA, produsen seragam terpercaya, profil perusahaan'
        ]);
    }
}