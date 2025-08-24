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
            'titleShop' => 'RAVAZKA',
            'testimonials' => $testimonials
        ]);
    }
}