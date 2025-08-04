<?php

namespace App\Http\Controllers;

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

        return view('contact', [
            'titleShop' => 'RAVAZKA',
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
