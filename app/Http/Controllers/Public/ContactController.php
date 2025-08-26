<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GoogleMapsService;

class ContactController extends Controller
{
    public function index()
    {
        $mapsData = [
            'apiKey' => GoogleMapsService::getApiKey(),
            'storeLocation' => GoogleMapsService::getStoreLocation(),
            'directionsUrl' => GoogleMapsService::getDirectionsUrl(),
            'simpleDirectionsUrl' => GoogleMapsService::getSimpleDirectionsUrl(),
            'embedUrl' => GoogleMapsService::getEmbedUrl(),
            'mapSettings' => GoogleMapsService::getMapSettings(),
        ];

        return view('public.contact', [
            'titleShop' => '📞 Hubungi Kami - RAVAZKA | Kontak & Lokasi Toko Seragam Sekolah',
            'title' => '📞 Hubungi Kami - RAVAZKA | Kontak & Lokasi Toko Seragam Sekolah',
            'metaDescription' => '📍 Hubungi RAVAZKA untuk konsultasi seragam sekolah. Alamat toko, nomor telepon, WhatsApp, dan peta lokasi lengkap untuk kemudahan akses pelanggan.',
            'metaKeywords' => 'kontak RAVAZKA, alamat toko seragam, nomor telepon RAVAZKA, lokasi toko, WhatsApp seragam sekolah',
            'mapsData' => $mapsData
        ]);
    }
    public function send(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        // Buat pesan WhatsApp
        $message = $this->generateWhatsAppMessage($request->all());
        
        // Redirect ke WhatsApp
        $whatsappNumber = '6289677754918'; // Nomor WhatsApp tujuan
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        return redirect()->away($whatsappUrl);
    }

    private function generateWhatsAppMessage($data)
    {
        $message = "*PESAN KONTAK - RAVAZKA*\n\n";
        $message .= "📧 *Pesan Baru dari Website*\n\n";
        
        $message .= "👤 *Data Pengirim:*\n";
        $message .= "Nama: {$data['name']}\n";
        $message .= "Email: {$data['email']}\n";
        $message .= "Subjek: {$data['subject']}\n\n";
        
        $message .= "💬 *Pesan:*\n";
        $message .= "{$data['message']}\n\n";
        
        $message .= "📅 Dikirim pada: " . date('d/m/Y H:i') . "\n";
        $message .= "\nTerima kasih telah menghubungi RAVAZKA! 🙏";
        
        return $message;
    }
}
