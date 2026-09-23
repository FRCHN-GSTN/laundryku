<!DOCTYPE html>
<html class="dark scroll-smooth" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= esc($settings['company_name'] ?? 'Laundryku') ?> — <?= esc($settings['company_tagline'] ?? 'Perawatan Pakaian Premium') ?></title>
    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Plus Jakarta Sans Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS v3 -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="/assets/js/tailwind-config.js"></script>
    <!-- Custom CSS -->
    <link href="/assets/css/landing.css" rel="stylesheet"/>
</head>
<body class="bg-[#191825] text-on-background antialiased font-body-md text-body-md overflow-x-hidden selection:bg-[#865DFF] selection:text-white">
<!-- ========================================================================= -->
<!-- 1. TOP APP BAR -->
<!-- ========================================================================= -->
<header class="fixed top-0 left-0 right-0 z-50 bg-[#191825]/85 backdrop-blur-md border-b border-outline-variant/20 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin flex items-center justify-between h-20 w-full">
        <!-- Brand Logo Anchor -->
        <a class="flex items-center gap-2 group" href="/">
            <div class="w-10 h-10 rounded-full bg-[#060047] border border-[#865DFF]/50 flex items-center justify-center text-[#FFA3FD] shadow-md shadow-[#865DFF]/30 group-hover:scale-105 transition-transform duration-200">
                <span class="material-symbols-outlined text-headline-md">local_laundry_service</span>
            </div>
            <span class="text-headline-md font-headline-md font-bold tracking-tight text-primary">Laundryku</span>
        </a>
        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center gap-8">
            <a class="text-secondary font-label-lg text-label-lg border-b-2 border-secondary pb-1 transition-colors" href="#layanan">Layanan</a>
            <a class="text-on-surface-variant font-label-lg text-label-lg hover:text-secondary transition-colors duration-200" href="#cara-kerja">Cara Kerja</a>
            <a class="text-on-surface-variant font-label-lg text-label-lg hover:text-secondary transition-colors duration-200" href="#paket-harga">Paket Harga</a>
            <a class="text-on-surface-variant font-label-lg text-label-lg hover:text-secondary transition-colors duration-200" href="#testimoni">Testimoni</a>
            <a class="text-on-surface-variant font-label-lg text-label-lg hover:text-secondary transition-colors duration-200" href="#lacak-pesanan">Lacak Pesanan</a>
        </nav>
        <!-- Trailing Action -->
        <div class="flex items-center gap-3">
            <a class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-[#865DFF]/40 text-[#FFA3FD] text-label-lg font-label-lg hover:bg-[#865DFF]/15 transition-all" href="#lacak-pesanan">
                <span class="material-symbols-outlined text-sm">search</span>
                <span>Lacak</span>
            </a>
            <a class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-[#865DFF]/40 text-[#FFA3FD] text-label-lg font-label-lg hover:bg-[#865DFF]/15 transition-all" href="/auth/login">
                <span class="material-symbols-outlined text-sm">login</span>
                <span>Masuk</span>
            </a>
            <a class="inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-[#865DFF] text-white font-label-lg text-label-lg hover:bg-[#E384FF] hover:shadow-[0_0_20px_rgba(227,132,255,0.5)] active:scale-95 transition-all duration-200" href="/auth/register">
                Daftar Sekarang
            </a>
        </div>
    </div>
</header>

<!-- ========================================================================= -->
<!-- 2. HERO SECTION -->
<!-- ========================================================================= -->
<section class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden">
    <!-- Ambient Neo-Glow Blobs -->
    <div class="absolute top-1/4 -left-20 w-96 h-96 bg-[#865DFF]/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 -right-20 w-[30rem] h-[30rem] bg-[#E90064]/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left Hero Copy -->
            <div class="lg:col-span-7 flex flex-col items-start gap-6">
                <!-- Information Tagline Badge -->
                <?php if (!empty($hero_promo)): ?>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#FFA3FD]/10 border border-[#FFA3FD]/30">
                    <span class="text-[#FFA3FD] font-label-caps text-label-caps tracking-wider flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">local_fire_department</span>
                        <?= esc($hero_promo['title']) ?>
                    </span>
                </div>
                <?php else: ?>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#FFA3FD]/10 border border-[#FFA3FD]/30">
                    <span class="text-[#FFA3FD] font-label-caps text-label-caps tracking-wider flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">arrow_back_ios_new</span>
                        Solusi Laundry Cerdas & Terpercaya di Kota Anda
                    </span>
                </div>
                <?php endif; ?>
                <!-- Main Hero Headline -->
                <h1 class="text-headline-xl-mobile md:text-display-hero font-headline-xl-mobile md:font-display-hero text-on-background tracking-tight">
                    <?= esc($settings['hero_headline'] ?? 'Pakaian Bersih, Rapi, & Harum Mewah Tanpa Keluar Rumah') ?>
                </h1>
                <!-- Body Description -->
                <p class="text-on-surface-variant font-body-lg text-body-lg max-w-2xl leading-relaxed">
                    <?= esc($settings['hero_description'] ?? 'Standar perawatan garmen kelas luxury dengan sistem penimbangan digital transparan.') ?>
                </p>
                <!-- CTA Cluster -->
                <div class="flex flex-wrap items-center gap-4 pt-2 w-full sm:w-auto">
                    <a class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full bg-[#865DFF] text-white font-label-lg text-label-lg hover:bg-[#E384FF] custom-aura-glow active:scale-95 transition-all duration-200" href="/auth/register">
                        <span class="material-symbols-outlined text-base">local_shipping</span>
                        Buat Akun Gratis
                    </a>
                    <a class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-4 rounded-full bg-transparent border border-gradient-to-r from-[#865DFF] to-[#FFA3FD] border-[#865DFF]/60 text-on-surface hover:bg-[#865DFF]/15 font-label-lg text-label-lg active:scale-95 transition-all duration-200" href="#paket-harga">
                        <span class="material-symbols-outlined text-base">calculate</span>
                        Cek Estimasi Harga
                    </a>
                </div>
                <!-- Social Proof Bar -->
                <div class="pt-6 border-t border-outline-variant/20 flex flex-wrap items-center gap-6 text-on-surface-variant">
                    <div class="flex items-center gap-2">
                        <div class="flex text-[#FFA3FD]">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                            <?php endfor; ?>
                        </div>
                        <span class="font-label-lg text-label-lg text-on-surface font-semibold"><?= esc($social_proof['average_rating'] ?? '4.9') ?> / 5.0</span>
                        <span class="font-body-sm text-body-sm text-outline">(<?= esc($social_proof['formatted_reviews'] ?? '15.000') ?> Ulasan)</span>
                    </div>
                    <div class="h-4 w-px bg-outline-variant/30 hidden sm:block"></div>
                    <div class="inline-flex items-center gap-1.5 text-[#FF5F9E] font-label-lg text-label-lg">
                        <span class="material-symbols-outlined text-base">verified_user</span>
                        100% Garansi Ganti Rugi
                    </div>
                </div>
            </div>
            <!-- Right Hero Showcase Interactive Card -->
            <div class="lg:col-span-5 relative">
                <div class="relative bg-[#060047]/90 rounded-2xl p-6 border border-[#865DFF]/40 shadow-2xl shadow-[#865DFF]/20 backdrop-blur-xl">
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between pb-5 border-b border-outline-variant/30">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full bg-[#FF5F9E] animate-ping"></div>
                            <span class="font-label-caps text-label-caps text-[#FFA3FD] tracking-wider">LIVE STATUS PESANAN #LND-9824</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-[#865DFF]/20 border border-[#865DFF]/40 text-[#E384FF] text-label-caps font-label-caps">
                            EXPRESS 4 JAM
                        </span>
                    </div>
                    <!-- Customer & Service Overview -->
                    <div class="py-5 flex items-center justify-between">
                        <div>
                            <p class="text-outline text-label-md font-label-md">Pelanggan</p>
                            <p class="text-on-surface font-headline-md font-semibold">Clarissa Amanda</p>
                            <p class="text-[#FFA3FD] font-body-sm text-body-sm mt-0.5">3.8 Kg — Paket Kiloan Wangi Parisian Rose</p>
                        </div>
                        <div class="text-right">
                            <p class="text-outline text-label-md font-label-md">Estimasi Tuntas</p>
                            <p class="text-[#FF5F9E] font-headline-md font-bold">16.45 WIB</p>
                            <p class="text-[#FFA3FD]/80 text-label-caps font-label-caps mt-0.5">Tepat Waktu</p>
                        </div>
                    </div>
                    <!-- Glowing Order Tracking Timeline -->
                    <div class="bg-[#191825]/70 rounded-xl p-4 border border-outline-variant/20 mb-5">
                        <p class="text-label-caps font-label-caps text-outline mb-3">TAHAPAN PROSES HIGIENIS</p>
                        <div class="space-y-3">
                            <!-- Step 1: Completed -->
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-[#FF5F9E] text-white flex items-center justify-center text-xs">
                                    <span class="material-symbols-outlined text-xs">check</span>
                                </div>
                                <span class="text-on-surface font-body-sm text-body-sm">Penjemputan & Timbang Digital Valid</span>
                            </div>
                            <!-- Step 2: Active Glow -->
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-full bg-[#865DFF] text-white flex items-center justify-center text-xs shadow-[0_0_12px_#865DFF] animate-pulse">
                                    <span class="material-symbols-outlined text-xs">sync</span>
                                </div>
                                <span class="text-[#E384FF] font-label-lg text-label-lg font-semibold">Sedang Dicuci & Setrika Uap (Suhu Terkontrol)</span>
                            </div>
                            <!-- Step 3: Upcoming -->
                            <div class="flex items-center gap-3 opacity-40">
                                <div class="w-6 h-6 rounded-full bg-[#7E7B9A]/30 border border-outline text-outline flex items-center justify-center text-xs">
                                    <span class="material-symbols-outlined text-xs">inventory_2</span>
                                </div>
                                <span class="text-on-surface-variant font-body-sm text-body-sm">Quality Inspection & Pengemasan Kedap Debu</span>
                            </div>
                            <!-- Step 4: Upcoming Delivery -->
                            <div class="flex items-center gap-3 opacity-40">
                                <div class="w-6 h-6 rounded-full bg-[#7E7B9A]/30 border border-outline text-outline flex items-center justify-center text-xs">
                                    <span class="material-symbols-outlined text-xs">two_wheeler</span>
                                </div>
                                <span class="text-on-surface-variant font-body-sm text-body-sm">Pengantaran Kembali ke Alamat</span>
                            </div>
                        </div>
                    </div>
                    <!-- Courier Dispatch Badge -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-[#060047] border border-[#865DFF]/30">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="w-11 h-11 rounded-full bg-[#865DFF]/30 border-2 border-[#865DFF] flex items-center justify-center text-white font-bold text-sm">RS</div>
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-[#FF5F9E] border-2 border-[#060047] rounded-full"></span>
                            </div>
                            <div>
                                <h4 class="text-on-surface font-label-lg text-label-lg font-bold">Rian Saputra</h4>
                                <p class="text-outline font-label-md text-label-md">Kurir Dedicated Zona Barat</p>
                            </div>
                        </div>
                        <a class="p-2 rounded-full bg-[#865DFF]/20 text-[#FFA3FD] hover:bg-[#865DFF] hover:text-white transition-colors" href="https://wa.me/<?= esc($settings['company_whatsapp'] ?? '6281234567890') ?>" rel="noopener noreferrer" target="_blank" title="Hubungi Kurir">
                            <span class="material-symbols-outlined text-lg">chat</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 3. KEUNGGULAN KAMI -->
<!-- ========================================================================= -->
<section class="py-20 bg-[#13121f] relative border-y border-outline-variant/20">
    <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="px-3.5 py-1 rounded-full bg-[#FFA3FD]/10 border border-[#FFA3FD]/30 text-[#FFA3FD] font-label-caps text-label-caps">
                STANDAR TERBAIK DI KELASNYA
            </span>
            <h2 class="text-headline-xl-mobile md:text-headline-xl font-headline-xl-mobile md:font-headline-xl text-on-surface mt-3">
                Mengapa Laundryku Jadi Pilihan Utama Ribuan Keluarga?
            </h2>
            <p class="text-on-surface-variant font-body-md text-body-md mt-2">
                Kami memadukan ketelitian tangan terampil dengan teknologi otomatisasi untuk menjaga serat pakaian tetap prima.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Feature 1 -->
            <div class="p-6 rounded-2xl bg-[#060047]/60 border border-outline-variant/30 hover:border-[#865DFF]/80 transition-all duration-300 group hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-[#865DFF]/20 border border-[#865DFF]/40 flex items-center justify-center text-[#FFA3FD] mb-5 group-hover:bg-[#865DFF] group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-2xl">near_me</span>
                </div>
                <div class="inline-block px-2 py-0.5 rounded bg-[#FFA3FD]/10 border border-[#FFA3FD]/20 text-[#FFA3FD] font-label-caps text-label-caps mb-2">
                    Gratis Antar Jemput
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface mb-2 font-semibold">Radius 5 KM Bebas Biaya</h3>
                <p class="text-on-surface-variant font-body-sm text-body-sm">
                    Kurir kami siap jemput dan antar kembali cucian Anda tepat ke depan pintu tanpa tambahan ongkir tersembunyi.
                </p>
            </div>
            <!-- Feature 2 -->
            <div class="p-6 rounded-2xl bg-[#060047]/60 border border-[#E90064]/30 hover:border-[#E90064] transition-all duration-300 group hover:-translate-y-1 relative">
                <div class="w-12 h-12 rounded-xl bg-[#E90064]/20 border border-[#E90064]/40 flex items-center justify-center text-[#E90064] mb-5 group-hover:bg-[#E90064] group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-2xl">bolt</span>
                </div>
                <div class="inline-block px-2 py-0.5 rounded bg-[#E90064]/15 border border-[#E90064]/30 text-[#E90064] font-label-caps text-label-caps mb-2 font-bold">
                    Super Express
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface mb-2 font-semibold">Selesai Kilat 4 Jam</h3>
                <p class="text-on-surface-variant font-body-sm text-body-sm">
                    Butuh baju untuk rapat mendadak atau penerbangan malam? Layanan express kilat kami siap bersih dan wangi dalam hitungan jam.
                </p>
            </div>
            <!-- Feature 3 -->
            <div class="p-6 rounded-2xl bg-[#060047]/60 border border-outline-variant/30 hover:border-[#865DFF]/80 transition-all duration-300 group hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-[#865DFF]/20 border border-[#865DFF]/40 flex items-center justify-center text-[#FFA3FD] mb-5 group-hover:bg-[#865DFF] group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-2xl">eco</span>
                </div>
                <div class="inline-block px-2 py-0.5 rounded bg-[#FF5F9E]/10 border border-[#FF5F9E]/20 text-[#FF5F9E] font-label-caps text-label-caps mb-2">
                    Eco-Friendly Care
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface mb-2 font-semibold">Detergen Organik & Mewah</h3>
                <p class="text-on-surface-variant font-body-sm text-body-sm">
                    Formula khusus hipoalergenik ramah kulit sensitif & bayi, dipadu ekstrak aroma essential oil parfum Eropa tahan 14 hari.
                </p>
            </div>
            <!-- Feature 4 -->
            <div class="p-6 rounded-2xl bg-[#060047]/60 border border-outline-variant/30 hover:border-[#865DFF]/80 transition-all duration-300 group hover:-translate-y-1">
                <div class="w-12 h-12 rounded-xl bg-[#865DFF]/20 border border-[#865DFF]/40 flex items-center justify-center text-[#FFA3FD] mb-5 group-hover:bg-[#865DFF] group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-2xl">sanitizer</span>
                </div>
                <div class="inline-block px-2 py-0.5 rounded bg-[#FFA3FD]/10 border border-[#FFA3FD]/20 text-[#FFA3FD] font-label-caps text-label-caps mb-2">
                    Higienis & Aman
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface mb-2 font-semibold">1 Mesin 1 Pelanggan</h3>
                <p class="text-on-surface-variant font-body-sm text-body-sm">
                    Cucian Anda tidak pernah dicampur dengan pakaian orang lain. Mencegah risiko silang kuman, luntur, maupun baju tertukar.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 4. LAYANAN PILIHAN LAUNDRYKU -->
<!-- ========================================================================= -->
<section class="py-24 relative" id="layanan">
    <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
            <div>
                <span class="px-3.5 py-1 rounded-full bg-[#FFA3FD]/10 border border-[#FFA3FD]/30 text-[#FFA3FD] font-label-caps text-label-caps">
                    MENU PERAWATAN
                </span>
                <h2 class="text-headline-xl-mobile md:text-headline-xl font-headline-xl-mobile md:font-headline-xl text-on-surface mt-3">
                    Layanan Pilihan Laundryku
                </h2>
                <p class="text-on-surface-variant font-body-md text-body-md mt-2 max-w-xl">
                    Dari pakaian harian hingga busana pesta berdetail rumit, tim kami memiliki sertifikasi penanganan bahan spesifik.
                </p>
            </div>
            <a class="inline-flex items-center gap-2 text-[#E384FF] font-label-lg text-label-lg hover:text-[#FFA3FD] transition-colors" href="#paket-harga">
                Lihat Daftar Lengkap Biaya
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Service Card 1 -->
            <div class="rounded-2xl bg-[#060047]/70 border border-outline-variant/30 overflow-hidden flex flex-col group hover:border-[#865DFF] transition-all">
                <div class="relative h-48 overflow-hidden bg-[#865DFF]/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-[#865DFF]/40">local_laundry_service</span>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#060047] to-transparent"></div>
                    <span class="absolute bottom-3 left-4 px-2.5 py-1 rounded-full bg-[#865DFF] text-white text-label-caps font-label-caps">
                        Paling Sering Dipesan
                    </span>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface font-semibold mb-2">Laundry Kiloan Premium</h3>
                        <p class="text-on-surface-variant font-body-sm text-body-sm leading-relaxed mb-4">
                            Pencucian harian menyeluruh: cuci bersih dengan detergen ramah serat, pengeringan suhu aman, setrika uap anti-kusut, dan lipat presisi berstandar hotel.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-outline-variant/20 flex items-center justify-between">
                        <span class="text-[#FFA3FD] font-label-lg text-label-lg">Mulai Rp 7.000 / kg</span>
                        <a class="text-primary hover:text-secondary font-label-lg text-label-lg flex items-center gap-1" href="/auth/register">
                            Pesan <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Service Card 2 -->
            <div class="rounded-2xl bg-[#060047]/70 border border-outline-variant/30 overflow-hidden flex flex-col group hover:border-[#865DFF] transition-all">
                <div class="relative h-48 overflow-hidden bg-[#B3005E]/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-[#B3005E]/40">dry_cleaning</span>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#060047] to-transparent"></div>
                    <span class="absolute bottom-3 left-4 px-2.5 py-1 rounded-full bg-[#B3005E] text-white text-label-caps font-label-caps">
                        Perawatan Spesialis
                    </span>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface font-semibold mb-2">Dry Cleaning & Wet Clean</h3>
                        <p class="text-on-surface-variant font-body-sm text-body-sm leading-relaxed mb-4">
                            Penanganan busana formal, jas, kebaya brokat, gaun pesta, batik sutra, dan jaket kulit menggunakan solven ramah lingkungan.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-outline-variant/20 flex items-center justify-between">
                        <span class="text-[#FFA3FD] font-label-lg text-label-lg">Mulai Rp 15.000 / pcs</span>
                        <a class="text-primary hover:text-secondary font-label-lg text-label-lg flex items-center gap-1" href="/auth/register">
                            Pesan <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Service Card 3 -->
            <div class="rounded-2xl bg-[#060047]/70 border border-outline-variant/30 overflow-hidden flex flex-col group hover:border-[#865DFF] transition-all">
                <div class="relative h-48 overflow-hidden bg-[#FF5F9E]/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-[#FF5F9E]/40">cleaning_services</span>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#060047] to-transparent"></div>
                    <span class="absolute bottom-3 left-4 px-2.5 py-1 rounded-full bg-[#865DFF]/40 text-[#FFA3FD] border border-[#FFA3FD]/40 text-label-caps font-label-caps">
                        Deep Clean Spa
                    </span>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface font-semibold mb-2">Shoe & Bag Spa</h3>
                        <p class="text-on-surface-variant font-body-sm text-body-sm leading-relaxed mb-4">
                            Pembersihan mendalam sepatu sneakers, canvas, suede, serta tas kulit kesayangan. Dilengkapi perlindungan unyellowing dan nano coating.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-outline-variant/20 flex items-center justify-between">
                        <span class="text-[#FFA3FD] font-label-lg text-label-lg">Mulai Rp 20.000 / pasang</span>
                        <a class="text-primary hover:text-secondary font-label-lg text-label-lg flex items-center gap-1" href="/auth/register">
                            Pesan <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Service Card 4 -->
            <div class="rounded-2xl bg-[#060047]/70 border border-outline-variant/30 overflow-hidden flex flex-col group hover:border-[#865DFF] transition-all">
                <div class="relative h-48 overflow-hidden bg-[#FFA3FD]/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-[#FFA3FD]/40">bed</span>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#060047] to-transparent"></div>
                    <span class="absolute bottom-3 left-4 px-2.5 py-1 rounded-full bg-[#FF5F9E]/20 text-[#FF5F9E] text-label-caps font-label-caps">
                        Sanitasi UV-C
                    </span>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface font-semibold mb-2">Bedcover & Home Linen</h3>
                        <p class="text-on-surface-variant font-body-sm text-body-sm leading-relaxed mb-4">
                            Pembersihan berkapasitas besar untuk bedcover king size, sprei sutra, gorden tebal, dan selimut bulu. Dilengkapi sterilisasi basmi tungau.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-outline-variant/20 flex items-center justify-between">
                        <span class="text-[#FFA3FD] font-label-lg text-label-lg">Mulai Rp 20.000 / pcs</span>
                        <a class="text-primary hover:text-secondary font-label-lg text-label-lg flex items-center gap-1" href="/auth/register">
                            Pesan <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 5. CARA KERJA 4 LANGKAH MUDAH -->
<!-- ========================================================================= -->
<section class="py-24 bg-[#13121f] border-t border-outline-variant/20 relative" id="cara-kerja">
    <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin">
        <div class="text-center max-w-2xl mx-auto mb-20">
            <span class="px-3.5 py-1 rounded-full bg-[#FFA3FD]/10 border border-[#FFA3FD]/30 text-[#FFA3FD] font-label-caps text-label-caps">
                PRAKTIS & CEPAT
            </span>
            <h2 class="text-headline-xl-mobile md:text-headline-xl font-headline-xl-mobile md:font-headline-xl text-on-surface mt-3">
                Cara Kerja 4 Langkah Mudah
            </h2>
            <p class="text-on-surface-variant font-body-md text-body-md mt-2">
                Hanya dalam hitungan menit dari ponsel, cucian kotor Anda akan diubah kembali rapi layaknya pakaian baru.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
            <!-- Step 1 -->
            <div class="relative flex flex-col items-start p-6 rounded-2xl bg-[#060047]/40 border border-outline-variant/20">
                <div class="w-12 h-12 rounded-full bg-[#865DFF] text-white font-bold text-headline-md flex items-center justify-center mb-6 shadow-[0_0_15px_#865DFF]">
                    1
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface font-semibold mb-2">Buat Pesanan</h3>
                <p class="text-on-surface-variant font-body-sm text-body-sm">
                    Masuk ke akun, pilih layanan, isi alamat dan jadwal penjemputan melalui website.
                </p>
            </div>
            <!-- Step 2 -->
            <div class="relative flex flex-col items-start p-6 rounded-2xl bg-[#060047]/40 border border-outline-variant/20">
                <div class="w-12 h-12 rounded-full bg-[#865DFF] text-white font-bold text-headline-md flex items-center justify-center mb-6 shadow-[0_0_15px_#865DFF]">
                    2
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface font-semibold mb-2">Kurir Menjemput</h3>
                <p class="text-on-surface-variant font-body-sm text-body-sm">
                    Kurir berseragam tiba tepat waktu membawa timbangan digital portabel dan kantong laundry higienis.
                </p>
            </div>
            <!-- Step 3 -->
            <div class="relative flex flex-col items-start p-6 rounded-2xl bg-[#060047]/40 border border-outline-variant/20">
                <div class="w-12 h-12 rounded-full bg-[#865DFF] text-white font-bold text-headline-md flex items-center justify-center mb-6 shadow-[0_0_15px_#865DFF]">
                    3
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface font-semibold mb-2">Pencucian & QC</h3>
                <p class="text-on-surface-variant font-body-sm text-body-sm">
                    Proses pemisahan bahan, pencucian terpisah 1 mesin 1 klien, setrika uap, dan pemeriksaan noda berlapis.
                </p>
            </div>
            <!-- Step 4 -->
            <div class="relative flex flex-col items-start p-6 rounded-2xl bg-[#060047]/40 border border-outline-variant/20">
                <div class="w-12 h-12 rounded-full bg-[#FF5F9E] text-white font-bold text-headline-md flex items-center justify-center mb-6 shadow-[0_0_15px_#FF5F9E]">
                    4
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface font-semibold mb-2">Diantar Pulang</h3>
                <p class="text-on-surface-variant font-body-sm text-body-sm">
                    Pakaian siap pakai dikemas rapi anti debu dan diantarkan kembali ke lokasi Anda.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 6. PAKET HARGA POPULER -->
<!-- ========================================================================= -->
<section class="py-24 relative" id="paket-harga">
    <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="px-3.5 py-1 rounded-full bg-[#FFA3FD]/10 border border-[#FFA3FD]/30 text-[#FFA3FD] font-label-caps text-label-caps">
                BIAYA TRANSPARAN
            </span>
            <h2 class="text-headline-xl-mobile md:text-headline-xl font-headline-xl-mobile md:font-headline-xl text-on-surface mt-3">
                Paket Harga Populer & Bersahabat
            </h2>
            <p class="text-on-surface-variant font-body-md text-body-md mt-2">
                Tidak ada biaya tersembunyi. Timbangan digital disaksikan langsung saat penjemputan.
            </p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
            <!-- Pricing Card 1: Reguler -->
            <div class="rounded-3xl bg-[#060047]/60 border border-outline-variant/30 p-8 flex flex-col justify-between hover:border-[#865DFF]/50 transition-all">
                <div>
                    <span class="text-label-caps font-label-caps text-outline">PAKET EKONOMIS</span>
                    <h3 class="text-headline-lg font-headline-lg text-on-surface mt-2 mb-1">Reguler Kiloan</h3>
                    <p class="text-on-surface-variant font-body-sm text-body-sm mb-6">Cocok untuk kebutuhan cucian mingguan keluarga.</p>
                    <div class="flex items-baseline gap-1 mb-8 pb-6 border-b border-outline-variant/20">
                        <span class="text-headline-xl font-headline-xl font-bold text-on-surface">Rp 7.000</span>
                        <span class="text-outline font-body-sm text-body-sm">/ Kg</span>
                    </div>
                    <ul class="space-y-3.5 text-on-surface-variant font-body-sm text-body-sm mb-8">
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Cuci + Kering + Setrika Uap Presisi
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            1 Mesin 1 Pelanggan (Anti Campur)
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Parfum Pilihan (Lavender / Soft Rose)
                        </li>
                        <li class="flex items-center gap-3 text-outline line-through">
                            <span class="material-symbols-outlined text-outline text-base">cancel</span>
                            Antar Jemput Express Prioritas
                        </li>
                    </ul>
                </div>
                <a class="w-full py-3.5 rounded-full border border-[#865DFF]/60 text-on-surface hover:bg-[#865DFF]/20 text-center font-label-lg text-label-lg transition-all" href="/auth/register">
                    Pilih Paket Reguler
                </a>
            </div>
            <!-- Pricing Card 2: Kilat Express (BEST SELLER) -->
            <div class="rounded-3xl bg-[#060047] border-2 border-[#865DFF] p-8 flex flex-col justify-between relative shadow-[0_0_35px_rgba(134,93,255,0.35)] scale-105 z-10">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-[#865DFF] via-[#FFA3FD] to-[#E90064] text-white text-label-caps font-label-caps tracking-wider shadow-lg">
                    PALING DIMINATI
                </div>
                <div>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-label-caps font-label-caps text-[#FFA3FD]">KEBUTUHAN MENDESAK</span>
                        <span class="px-2 py-0.5 rounded bg-[#E90064]/20 border border-[#E90064]/40 text-[#E90064] font-label-caps text-label-caps">SELESAI 4 JAM</span>
                    </div>
                    <h3 class="text-headline-lg font-headline-lg text-white mt-2 mb-1">Kilat Express</h3>
                    <p class="text-on-surface-variant font-body-sm text-body-sm mb-6">Pilihan tepat saat butuh pakaian bersih secepat kilat.</p>
                    <div class="flex items-baseline gap-1 mb-8 pb-6 border-b border-[#865DFF]/30">
                        <span class="text-headline-xl font-headline-xl font-bold text-white">Rp 15.000</span>
                        <span class="text-[#FFA3FD] font-body-sm text-body-sm">/ Kg</span>
                    </div>
                    <ul class="space-y-3.5 text-on-surface font-body-sm text-body-sm mb-8">
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Garansi selesai dalam 4 Jam
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Prioritas Antrian Mesin Utama
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Detergen Hipoalergenik + Disinfeksi UV-C
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Packaging Kedap Air & Tas Laundry Gratis
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Gratis Antar Jemput Langsung ke Pintu
                        </li>
                    </ul>
                </div>
                <a class="w-full py-4 rounded-full bg-[#865DFF] text-white hover:bg-[#E384FF] text-center font-label-lg text-label-lg custom-aura-glow transition-all" href="/auth/register">
                    Pesan Kilat Sekarang
                </a>
            </div>
            <!-- Pricing Card 3: Satuan & Sepatu -->
            <div class="rounded-3xl bg-[#060047]/60 border border-outline-variant/30 p-8 flex flex-col justify-between hover:border-[#865DFF]/50 transition-all">
                <div>
                    <span class="text-label-caps font-label-caps text-outline">PERAWATAN KHUSUS</span>
                    <h3 class="text-headline-lg font-headline-lg text-on-surface mt-2 mb-1">Satuan & Sepatu Spa</h3>
                    <p class="text-on-surface-variant font-body-sm text-body-sm mb-6">Penanganan individual garmen pesta & alas kaki.</p>
                    <div class="flex items-baseline gap-1 mb-8 pb-6 border-b border-outline-variant/20">
                        <span class="text-headline-xl font-headline-xl font-bold text-on-surface">Mulai Rp 15.000</span>
                        <span class="text-outline font-body-sm text-body-sm">/ Item</span>
                    </div>
                    <ul class="space-y-3.5 text-on-surface-variant font-body-sm text-body-sm mb-8">
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Setrika Saja (Rp 5.000/kg)
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Dry Clean (Rp 15.000/pcs)
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Bed Cover (Rp 20.000/pcs)
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Selimut (Rp 15.000/pcs)
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#FF5F9E] text-base">check_circle</span>
                            Plastik pelindung gantung anti jamur
                        </li>
                    </ul>
                </div>
                <a class="w-full py-3.5 rounded-full border border-[#865DFF]/60 text-on-surface hover:bg-[#865DFF]/20 text-center font-label-lg text-label-lg transition-all" href="/auth/register">
                    Pilih Paket Satuan
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 6.5 PROMO BANNER -->
<!-- ========================================================================= -->
<?php if (!empty($hero_promo)): ?>
<section class="py-16 relative" id="promo">
    <div class="max-w-4xl mx-auto px-margin-mobile md:px-margin">
        <div class="rounded-3xl bg-gradient-to-r from-[#865DFF]/20 to-[#E384FF]/20 border border-[#865DFF]/40 p-8 md:p-12 text-center relative overflow-hidden">
            <div class="absolute -left-16 -top-16 w-48 h-48 bg-[#FFA3FD]/10 rounded-full blur-2xl pointer-events-none"></div>
            <span class="px-3 py-1 rounded-full bg-[#FFA3FD]/10 border border-[#FFA3FD]/30 text-[#FFA3FD] font-label-caps text-label-caps">
                PROMO TERBATAS
            </span>
            <h2 class="text-headline-xl-mobile md:text-headline-xl font-headline-xl-mobile md:font-headline-xl text-on-surface mt-3 mb-2">
                <?= esc($hero_promo['title']) ?>
            </h2>
            <?php if (!empty($hero_promo['description'])): ?>
                <p class="text-on-surface-variant font-body-md text-body-md mb-4 max-w-lg mx-auto">
                    <?= esc($hero_promo['description']) ?>
                </p>
            <?php endif; ?>
            <?php if (!empty($hero_promo['promo_code'])): ?>
                <div class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#865DFF]/30 border border-[#865DFF]/50 mt-2">
                    <span class="text-[#E384FF] font-label-lg text-label-lg">Gunakan kode:</span>
                    <span class="text-white font-mono font-bold text-lg tracking-wider"><?= esc($hero_promo['promo_code']) ?></span>
                </div>
            <?php endif; ?>
            <p class="text-outline font-label-sm text-label-sm mt-4">
                Berlaku hingga <?= date('d M Y', strtotime($hero_promo['end_date'])) ?>
            </p>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========================================================================= -->
<!-- 7. SIMULASI LACAK PESANAN -->
<!-- ========================================================================= -->
<section class="py-20 bg-[#13121f] border-t border-outline-variant/20 relative" id="lacak-pesanan">
    <div class="max-w-4xl mx-auto px-margin-mobile md:px-margin">
        <div class="rounded-3xl bg-[#060047] border border-[#865DFF]/40 p-8 md:p-12 text-center shadow-xl shadow-[#865DFF]/10 relative overflow-hidden">
            <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-[#E384FF]/10 rounded-full blur-2xl pointer-events-none"></div>
            <span class="px-3 py-1 rounded-full bg-[#FFA3FD]/10 border border-[#FFA3FD]/30 text-[#FFA3FD] font-label-caps text-label-caps">
                MONITORING REAL-TIME
            </span>
            <h2 class="text-headline-xl-mobile md:text-headline-xl font-headline-xl-mobile md:font-headline-xl text-on-surface mt-3 mb-2">
                Lacak Status Cucian Anda Secara Langsung
            </h2>
            <p class="text-on-surface-variant font-body-md text-body-md mb-8 max-w-lg mx-auto">
                Ketahui posisi cucian mulai dari penimbangan, pencucian, hingga saat kurir menuju ke tempat Anda.
            </p>
            <!-- Tracking Form -->
            <form id="tracking-form" action="/track" method="post" class="max-w-xl mx-auto flex flex-col sm:flex-row gap-3">
                <?= csrf_field() ?>
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
                    <input class="w-full pl-12 pr-4 py-3.5 rounded-full bg-[#191825] border border-outline-variant/40 text-on-surface placeholder:text-outline focus:outline-none focus:border-[#865DFF] focus:ring-2 focus:ring-[#865DFF]/30 font-body-sm text-body-sm" name="order_code" placeholder="Masukkan kode pesanan (contoh: ORD-20260922-0001)" required type="text"/>
                </div>
                <button class="px-8 py-3.5 rounded-full bg-[#865DFF] text-white font-label-lg text-label-lg hover:bg-[#E384FF] active:scale-95 transition-all" type="submit">
                    Cek Resi
                </button>
            </form>
            <p class="text-outline font-label-md text-label-md mt-4">
                Atau lacak via WhatsApp resmi di <a class="text-[#FFA3FD] underline hover:text-white" href="https://wa.me/<?= esc($settings['company_whatsapp'] ?? '6281234567890') ?>"><?= esc($settings['company_phone'] ?? '0812-3456-7890') ?></a>
            </p>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 8. TESTIMONI PELANGGAN -->
<!-- ========================================================================= -->
<section class="py-24 relative" id="testimoni">
    <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="px-3.5 py-1 rounded-full bg-[#FFA3FD]/10 border border-[#FFA3FD]/30 text-[#FFA3FD] font-label-caps text-label-caps">
                CERITA KEPUASAN
            </span>
            <h2 class="text-headline-xl-mobile md:text-headline-xl font-headline-xl-mobile md:font-headline-xl text-on-surface mt-3">
                Kata Mereka yang Telah Merasakan Kenyamanan Laundryku
            </h2>
            <p class="text-on-surface-variant font-body-md text-body-md mt-2">
                Dipercaya para profesional sibuk, ibu rumah tangga modern, hingga ekspatriat di seluruh kota.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Testimonial 1 -->
            <div class="p-8 rounded-2xl bg-[#060047]/60 border border-outline-variant/30 backdrop-blur-md flex flex-col justify-between">
                <div>
                    <div class="flex text-[#FFA3FD] mb-4">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                    </div>
                    <p class="text-[#cac3d7] font-body-sm text-body-sm leading-relaxed mb-6 italic">
                        "Sebagai dokter dengan jadwal jaga padat, layanan jemput-antar Laundryku sangat menyelamatkan. Kemeja putih dan snelli saya selalu wangi lembut, tanpa ada noda kuning, dan lipatan setrikanya benar-benar rapi sempurna."
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-outline-variant/20">
                    <div class="w-11 h-11 rounded-full bg-[#865DFF]/30 border-2 border-[#865DFF] flex items-center justify-center text-white font-bold text-sm">NP</div>
                    <div>
                        <p class="text-on-surface font-label-lg text-label-lg font-bold">dr. Nadine Paramita</p>
                        <p class="text-outline font-label-md text-label-md">Pelanggan Kiloan & Jas Sejak 2023</p>
                    </div>
                </div>
            </div>
            <!-- Testimonial 2 -->
            <div class="p-8 rounded-2xl bg-[#060047]/60 border border-[#865DFF]/40 backdrop-blur-md flex flex-col justify-between shadow-lg shadow-[#865DFF]/10">
                <div>
                    <div class="flex text-[#FFA3FD] mb-4">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                    </div>
                    <p class="text-[#cac3d7] font-body-sm text-body-sm leading-relaxed mb-6 italic">
                        "Pernah order paket Express 4 Jam karena jas harus dipakai untuk gala dinner dadakan jam 7 malam. Jam 5 sore kurir sudah antar jas dalam hanger bersih berplastik anti-debu. Sangat profesional dan tepat waktu!"
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-outline-variant/20">
                    <div class="w-11 h-11 rounded-full bg-[#865DFF]/30 border-2 border-[#865DFF] flex items-center justify-center text-white font-bold text-sm">DW</div>
                    <div>
                        <p class="text-on-surface font-label-lg text-label-lg font-bold">Dimas Widyatama</p>
                        <p class="text-outline font-label-md text-label-md">Managing Partner Finansial</p>
                    </div>
                </div>
            </div>
            <!-- Testimonial 3 -->
            <div class="p-8 rounded-2xl bg-[#060047]/60 border border-outline-variant/30 backdrop-blur-md flex flex-col justify-between">
                <div>
                    <div class="flex text-[#FFA3FD] mb-4">
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">star</span>
                    </div>
                    <p class="text-[#cac3d7] font-body-sm text-body-sm leading-relaxed mb-6 italic">
                        "Sepatu sneakers putih saya yang tadinya menguning dan kena noda lumpur festival konser bisa kembali bersih seperti baru keluar dari toko. Harganya terjangkau banget dibanding kualitas spa premium begini."
                    </p>
                </div>
                <div class="flex items-center gap-3 pt-4 border-t border-outline-variant/20">
                    <div class="w-11 h-11 rounded-full bg-[#865DFF]/30 border-2 border-[#865DFF] flex items-center justify-center text-white font-bold text-sm">FA</div>
                    <div>
                        <p class="text-on-surface font-label-lg text-label-lg font-bold">Felicia Angelica</p>
                        <p class="text-outline font-label-md text-label-md">Content Creator & Pelanggan Shoe Spa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 9. CTA PENUTUP -->
<!-- ========================================================================= -->
<section class="py-20 relative" id="pesan">
    <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin">
        <div class="rounded-3xl bg-[#060047] border border-[#865DFF]/50 p-8 md:p-16 relative overflow-hidden shadow-2xl shadow-[#865DFF]/25">
            <!-- Atmospheric Ambient Radiance -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#865DFF]/30 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-[#E384FF]/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#B3005E]/20 border border-[#B3005E]/40 text-[#FFA3FD] text-label-caps font-label-caps mb-4">
                    <span class="material-symbols-outlined text-sm">local_fire_department</span>
                    SLOT PENJEMPUTAN HARI INI TERBATAS
                </div>
                <h2 class="text-headline-xl-mobile md:text-headline-xl font-headline-xl-mobile md:font-headline-xl text-white font-bold leading-tight mb-4">
                    Pakaian Menumpuk? Biarkan Tim Laundryku yang Selesaikan Hari Ini!
                </h2>
                <p class="text-on-surface-variant font-body-lg text-body-lg mb-8 max-w-xl">
                    Nikmati waktu santai berharga bersama keluarga. Kurir kami siap tiba dalam 30 menit ke kediaman Anda.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a class="inline-flex items-center gap-3 px-8 py-4 rounded-full bg-[#865DFF] text-white font-label-lg text-label-lg hover:bg-[#E384FF] custom-aura-glow active:scale-95 transition-all duration-200" href="/auth/register">
                        <span class="material-symbols-outlined text-xl">chat</span>
                        Buat Akun & Pesan Sekarang
                    </a>
                    <div class="flex items-center gap-2 text-[#FF5F9E] text-label-lg font-label-lg">
                        <span class="material-symbols-outlined text-lg">bolt</span>
                        Respons Cepat &lt; 2 Menit
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================================= -->
<!-- 10. FOOTER -->
<!-- ========================================================================= -->
<footer class="w-full bg-surface-container-lowest border-t border-outline-variant/20">
    <div class="max-w-7xl mx-auto px-margin py-space-xl flex flex-col justify-between gap-space-lg w-full">
        <!-- Top Row Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            <!-- Brand Summary Column -->
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-9 h-9 rounded-full bg-[#060047] border border-[#865DFF]/50 flex items-center justify-center text-[#FFA3FD]">
                        <span class="material-symbols-outlined text-xl">local_laundry_service</span>
                    </div>
                    <span class="text-headline-lg font-headline-lg font-bold text-primary">Laundryku</span>
                </div>
                <p class="text-on-surface-variant font-body-sm text-body-sm leading-relaxed max-w-sm mb-6">
                    Platform on-demand binatu dan perawatan garmen premium dengan teknologi penimbangan digital dan penanganan tersertifikasi.
                </p>
                <div class="text-outline font-label-md text-label-md space-y-1">
                    <p>Head Office: <?= esc($settings['company_address'] ?? 'Jl. Senopati Raya No. 42, Jakarta Selatan') ?></p>
                    <p>Jam Operasional: <?= esc($settings['operational_hours'] ?? 'Setiap Hari, 07.00 - 22.00 WIB') ?></p>
                </div>
            </div>
            <!-- Links Column 1: Layanan -->
            <div>
                <h4 class="text-on-surface font-label-caps text-label-caps tracking-wider mb-4">LAYANAN UTAMA</h4>
                <ul class="space-y-2.5">
                    <li><a class="text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors duration-200" href="#layanan">Layanan Kiloan & Satuan</a></li>
                    <li><a class="text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors duration-200" href="#layanan">Dry Cleaning & Wet Clean</a></li>
                    <li><a class="text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors duration-200" href="#layanan">Perawatan Sepatu & Tas</a></li>
                </ul>
            </div>
            <!-- Links Column 2: Bantuan & Status -->
            <div>
                <h4 class="text-on-surface font-label-caps text-label-caps tracking-wider mb-4">NAVIGASI & BANTUAN</h4>
                <ul class="space-y-2.5">
                    <li><a class="text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors duration-200" href="#lacak-pesanan">Lacak Status Pesanan</a></li>
                    <li><a class="text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors duration-200" href="https://wa.me/<?= esc($settings['company_whatsapp'] ?? '6281234567890') ?>">Bantuan & FAQ WhatsApp</a></li>
                    <li><a class="text-secondary font-body-sm text-body-sm hover:text-primary transition-colors duration-200" href="#paket-harga">Daftar Paket Harga</a></li>
                </ul>
            </div>
            <!-- Links Column 3: Kebijakan & Legal -->
            <div>
                <h4 class="text-on-surface font-label-caps text-label-caps tracking-wider mb-4">LEGALITAS</h4>
                <ul class="space-y-2.5">
                    <li><a class="text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors duration-200" href="#">Syarat & Ketentuan</a></li>
                    <li><a class="text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors duration-200" href="#">Kebijakan Privasi</a></li>
                    <li><a class="text-on-surface-variant font-body-sm text-body-sm hover:text-primary transition-colors duration-200" href="#">Garansi Kualitas 100%</a></li>
                </ul>
            </div>
        </div>
        <!-- Bottom Copyright Row -->
        <div class="pt-8 border-t border-outline-variant/10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-outline font-body-sm text-body-sm text-center sm:text-left">
                &copy; 2026 Laundryku Indonesia. All rights reserved.
            </p>
            <div class="flex items-center gap-4">
                <span class="w-2 h-2 rounded-full bg-[#FF5F9E]"></span>
                <span class="text-outline font-label-caps text-label-caps">SERVER OPERATIONAL</span>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript -->
<script src="/assets/js/landing.js"></script>
</body>
</html>
