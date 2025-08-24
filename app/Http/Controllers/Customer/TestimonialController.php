<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'customer_name' => 'required|string|max:255',
            'testimonial_text' => 'required|string'
        ]);

        // Check if order belongs to authenticated user
        $order = Order::findOrFail($request->order_id);
        
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk memberikan testimoni pada pesanan ini.');
        }

        // Check if order is completed
        if ($order->status !== 'completed') {
            return redirect()->back()->with('error', 'Testimoni hanya dapat diberikan untuk pesanan yang telah selesai.');
        }

        // Check if testimonial already exists
        $existingTestimonial = Testimonial::where('order_id', $request->order_id)->first();
        if ($existingTestimonial) {
            return redirect()->back()->with('error', 'Anda sudah memberikan testimoni untuk pesanan ini.');
        }

        // Create testimonial
        Testimonial::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'customer_name' => $request->customer_name,
            'institution_name' => '-', // Set default value
            'testimonial_text' => $request->testimonial_text,
            'rating' => 5, // Set default rating
            'is_approved' => true // Auto approve
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Testimoni Anda telah berhasil dikirim.');
    }
}
