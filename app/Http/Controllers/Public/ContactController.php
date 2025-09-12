<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GoogleMapsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class ContactController
 *
 * Handles the display of the contact page and sending contact messages.
 */
class ContactController extends Controller
{
    /**
     * Display the contact page.
     *
     * @return View
     */
    public function index(): View
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

    /**
     * Send the contact form message to WhatsApp.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function send(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $message = $this->generateWhatsAppMessage($request->all());
        
        $whatsappNumber = '6289677754918';
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        return redirect()->away($whatsappUrl);
    }

    /**
     * Generate a WhatsApp message from the contact form data.
     *
     * @param array $data
     * @return string
     */
    private function generateWhatsAppMessage(array $data): string
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
