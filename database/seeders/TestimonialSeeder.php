<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Order;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user and order for sample testimonials
        $user = User::first();
        $order = Order::first();
        
        if ($user && $order) {
            $testimonials = [
                [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'customer_name' => 'Ahmad Rizki',
                    'institution_name' => '-',
                    'testimonial_text' => 'Pelayanan sangat memuaskan, seragam berkualitas tinggi dan pengiriman cepat. Terima kasih RAVAZKA!',
                    'rating' => 5,
                    'is_approved' => true,
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(5),
                ],
                [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'customer_name' => 'Siti Nurhaliza',
                    'institution_name' => '-',
                    'testimonial_text' => 'Kualitas bahan sangat bagus, jahitan rapi dan harga terjangkau. Sangat puas dengan produk RAVAZKA.',
                    'rating' => 5,
                    'is_approved' => true,
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDays(3),
                ],
                [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'customer_name' => 'Budi Santoso',
                    'institution_name' => '-',
                    'testimonial_text' => 'Proses pemesanan mudah, customer service responsif dan hasil seragam sesuai ekspektasi. Recommended!',
                    'rating' => 5,
                    'is_approved' => true,
                    'created_at' => now()->subDays(1),
                    'updated_at' => now()->subDays(1),
                ],
                [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'customer_name' => 'Maya Sari',
                    'institution_name' => '-',
                    'testimonial_text' => 'Desain modern dan nyaman dipakai. Anak saya sangat suka dengan seragam barunya.',
                    'rating' => 5,
                    'is_approved' => true,
                    'created_at' => now()->subDays(7),
                    'updated_at' => now()->subDays(7),
                ],
                [
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'customer_name' => 'Dedi Kurniawan',
                    'institution_name' => '-',
                    'testimonial_text' => 'Sudah langganan di RAVAZKA bertahun-tahun. Selalu puas dengan kualitas dan pelayanannya.',
                    'rating' => 5,
                    'is_approved' => true,
                    'created_at' => now()->subDays(10),
                    'updated_at' => now()->subDays(10),
                ],
            ];

            foreach ($testimonials as $testimonial) {
                Testimonial::create($testimonial);
            }
        }
    }
}
