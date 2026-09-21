<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->seedServices();
        $this->seedSettings();
        $this->seedFeatures();
        $this->seedHowItSteps();
        $this->seedFaqs();
        $this->seedAdmin();
    }

    private function seedServices()
    {
        $services = [
            [
                'name' => 'Cuci Kering',
                'description' => 'Cuci dan keringkan saja',
                'price' => 7000,
                'unit' => 'kg',
                'category' => 'kiloan',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cuci Setrika',
                'description' => 'Cuci dan setrika',
                'price' => 9000,
                'unit' => 'kg',
                'category' => 'kiloan',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cuci Kering Setrika',
                'description' => 'Cuci, keringkan, dan setrika',
                'price' => 12000,
                'unit' => 'kg',
                'category' => 'kiloan',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Setrika Saja',
                'description' => 'Setrika saja tanpa cuci',
                'price' => 5000,
                'unit' => 'kg',
                'category' => 'satuan',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Kilat Express',
                'description' => 'Cuci kilat selesai dalam 4 jam, prioritas antrian mesin',
                'price' => 15000,
                'unit' => 'kg',
                'category' => 'express',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Dry Clean',
                'description' => 'Pembersihan kering untuk bahan halus',
                'price' => 15000,
                'unit' => 'pcs',
                'category' => 'satuan',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Shoe & Bag Spa',
                'description' => 'Pembersihan mendalam sepatu dan tas dengan nano coating',
                'price' => 20000,
                'unit' => 'pcs',
                'category' => 'spa',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bed Cover',
                'description' => 'Cuci bed cover ukuran king/queen',
                'price' => 20000,
                'unit' => 'pcs',
                'category' => 'satuan',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Selimut',
                'description' => 'Cuci selimut bulu dan kain',
                'price' => 15000,
                'unit' => 'pcs',
                'category' => 'satuan',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Gorden',
                'description' => 'Cuci gorden tebal dan tipis',
                'price' => 18000,
                'unit' => 'pcs',
                'category' => 'satuan',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('services')->insertBatch($services);
    }

    private function seedSettings()
    {
        $settings = [
            ['setting_key' => 'company_name', 'setting_value' => 'Laundryku', 'setting_type' => 'text', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'company_tagline', 'setting_value' => 'Perawatan Pakaian Premium & On-Demand Berbasis Digital', 'setting_type' => 'text', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'company_address', 'setting_value' => 'Jl. Senopati Raya No. 42, Jakarta Selatan', 'setting_type' => 'text', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'company_phone', 'setting_value' => '0812-3456-7890', 'setting_type' => 'text', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'company_whatsapp', 'setting_value' => '6281234567890', 'setting_type' => 'text', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'company_email', 'setting_value' => 'info@laundryku.com', 'setting_type' => 'text', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'operational_hours', 'setting_value' => 'Setiap Hari, 07.00 - 22.00 WIB', 'setting_type' => 'text', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'free_delivery_radius', 'setting_value' => '5', 'setting_type' => 'number', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'hero_headline', 'setting_value' => 'Pakaian Bersih, Rapi, & Harum Mewah Tanpa Keluar Rumah', 'setting_type' => 'text', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'hero_description', 'setting_value' => 'Standar perawatan garmen kelas luxury dengan sistem penimbangan digital transparan, detergen ramah serat hipoalergenik, serta pemantauan posisi cucian real-time langsung dari layar ponsel Anda.', 'setting_type' => 'text', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'rating_average', 'setting_value' => '4.9', 'setting_type' => 'number', 'created_at' => date('Y-m-d H:i:s')],
            ['setting_key' => 'total_reviews', 'setting_value' => '15000', 'setting_type' => 'number', 'created_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('settings')->insertBatch($settings);
    }

    private function seedFeatures()
    {
        $features = [
            [
                'title' => 'Radius 5 KM Bebas Biaya',
                'description' => 'Kurir kami siap jemput dan antar kembali cucian Anda tepat ke depan pintu tanpa tambahan ongkir tersembunyi.',
                'icon' => 'near_me',
                'badge_text' => 'Gratis Antar Jemput',
                'badge_color' => 'purple',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Selesai Kilat 4 Jam',
                'description' => 'Butuh baju untuk rapat mendadak atau penerbangan malam? Layanan express kilat kami siap bersih dan wangi dalam hitungan jam.',
                'icon' => 'bolt',
                'badge_text' => 'Super Express',
                'badge_color' => 'pink',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Detergen Organik & Mewah',
                'description' => 'Formula khusus hipoalergenik ramah kulit sensitif & bayi, dipadu ekstrak aroma essential oil parfum Eropa tahan 14 hari.',
                'icon' => 'eco',
                'badge_text' => 'Eco-Friendly Care',
                'badge_color' => 'green',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => '1 Mesin 1 Pelanggan',
                'description' => 'Cucian Anda tidak pernah dicampur dengan pakaian orang lain. Mencegah risiko silang kuman, luntur, maupun baju tertukar.',
                'icon' => 'sanitizer',
                'badge_text' => 'Higienis & Aman',
                'badge_color' => 'purple',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('features')->insertBatch($features);
    }

    private function seedHowItSteps()
    {
        $steps = [
            [
                'step_number' => 1,
                'title' => 'Buat Pesanan',
                'description' => 'Masuk ke akun, pilih layanan, isi alamat dan jadwal penjemputan melalui website.',
                'icon' => 'shopping_cart',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'step_number' => 2,
                'title' => 'Kurir Menjemput',
                'description' => 'Kurir berseragam tiba tepat waktu membawa timbangan digital portabel dan kantong laundry higienis.',
                'icon' => 'local_shipping',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'step_number' => 3,
                'title' => 'Pencucian & QC',
                'description' => 'Proses pemisahan bahan, pencucian terpisah 1 mesin 1 klien, setrika uap, dan pemeriksaan noda berlapis.',
                'icon' => 'local_laundry_service',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'step_number' => 4,
                'title' => 'Diantar Pulang',
                'description' => 'Pakaian siap pakai dikemas rapi anti debu dan diantarkan kembali ke lokasi Anda.',
                'icon' => 'home',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('how_it_steps')->insertBatch($steps);
    }

    private function seedFaqs()
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara memesan layanan Laundryku?',
                'answer' => 'Buat akun terlebih dahulu, lalu pilih layanan yang diinginkan, isi alamat penjemputan, dan tentukan jadwal. Kurir kami akan menjemput cucian Anda tepat waktu.',
                'category' => 'umum',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'question' => 'Berapa lama proses pencucian selesai?',
                'answer' => 'Layanan reguler selesai dalam 1-2 hari. Layanan Express Kilat selesai dalam 4 jam. Untuk dry cleaning dan spesialisasi membutuhkan 2-3 hari.',
                'category' => 'layanan',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'question' => 'Apakah ada biaya antar jemput?',
                'answer' => 'Gratis antar jemput untuk area dalam radius 5 km. Untuk area di luar radius tersebut, dikenakan biaya tambahan sesuai jarak.',
                'category' => 'biaya',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'question' => 'Bagaimana cara melacak status pesanan?',
                'answer' => 'Anda bisa melacak status pesanan melalui website pada menu "Lacak Pesanan" atau menghubungi customer service kami via WhatsApp.',
                'category' => 'umum',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'question' => 'Apakah Laundryku menjamin keamanan pakaian?',
                'answer' => 'Ya, kami memberikan garansi 100% ganti rugi jika terjadi kerusakan atau kehilangan. Setiap cucian ditangani secara terpisah untuk menghindari tertukar.',
                'category' => 'garansi',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('faqs')->insertBatch($faqs);
    }

    private function seedAdmin()
    {
        $existingAdmin = $this->db->table('users')->where('role', 'admin')->get()->getRowArray();
        if (!$existingAdmin) {
            $admin = [
                'name' => 'Admin Laundryku',
                'email' => 'admin@laundryku.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->table('users')->insert($admin);
        }
    }
}
