<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Laundryku Admin' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark': '#191825',
                        'dark-secondary': '#060047',
                        'primary': '#865DFF',
                        'primary-light': '#E384FF',
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #191825; }
        .card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .btn-primary {
            background: #865DFF;
            transition: all 0.2s ease;
        }
        .btn-primary:hover { background: #7048e8; }
        .btn-primary:focus-visible {
            outline: 2px solid #E384FF;
            outline-offset: 2px;
        }
        .sidebar {
            background: #060047;
            transition: transform 0.3s ease, width 0.3s ease;
        }
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(134, 93, 255, 0.15);
            color: #E384FF;
        }
        .sidebar-link:focus-visible {
            outline: 2px solid #E384FF;
            outline-offset: -2px;
        }
        .status-badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }
        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: border-color 0.2s ease;
        }
        .input-field:focus {
            border-color: #865DFF;
            outline: none;
            box-shadow: 0 0 0 2px rgba(134, 93, 255, 0.3);
        }
        .btn-ghost {
            transition: all 0.2s ease;
        }
        .btn-ghost:hover { background: rgba(255, 255, 255, 0.1); }
        .btn-ghost:focus-visible {
            outline: 2px solid #E384FF;
            outline-offset: 2px;
        }

        /* Desktop: sidebar always visible, toggle width */
        @media (min-width: 1024px) {
            .sidebar { position: fixed; left: 0; top: 0; height: 100vh; z-index: 50; }
            .sidebar.collapsed { width: 0; padding: 0; overflow: hidden; }
            .sidebar.expanded { width: 256px; }
            .main-content { transition: margin-left 0.3s ease; }
            .main-content.sidebar-open { margin-left: 256px; }
            .main-content.sidebar-closed { margin-left: 0; }
        }

        /* Mobile: sidebar overlay */
        @media (max-width: 1023px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(-100%);
            }
            .sidebar.mobile-open { transform: translateX(0); }
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
            .sidebar-overlay.open { display: block; }
        }
    </style>
</head>
<body class="text-white min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar Overlay (mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar w-64 min-h-screen p-6 expanded" id="sidebar">
            <div class="mb-8">
                <h1 class="text-xl font-bold text-primary">Laundryku</h1>
                <p class="text-xs text-gray-400 mt-1">Admin Dashboard</p>
            </div>

            <nav class="space-y-1">
                <a href="/admin/dashboard" class="sidebar-link block px-4 py-3 rounded-lg <?= uri_string() === '/admin/dashboard' ? 'active' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="sidebar-text">Dashboard</span>
                    </span>
                </a>
                <a href="/admin/orders" class="sidebar-link block px-4 py-3 rounded-lg <?= strpos(uri_string(), '/admin/orders') !== false && strpos(uri_string(), '/payment') === false ? 'active' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="sidebar-text">Pesanan</span>
                    </span>
                </a>
                <a href="/admin/services" class="sidebar-link block px-4 py-3 rounded-lg <?= strpos(uri_string(), '/admin/services') !== false ? 'active' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="sidebar-text">Layanan</span>
                    </span>
                </a>
                <a href="/admin/customers" class="sidebar-link block px-4 py-3 rounded-lg <?= strpos(uri_string(), '/admin/customers') !== false ? 'active' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="sidebar-text">Pelanggan</span>
                    </span>
                </a>
                <a href="/admin/promotions" class="sidebar-link block px-4 py-3 rounded-lg <?= strpos(uri_string(), '/admin/promotions') !== false ? 'active' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="sidebar-text">Promo</span>
                    </span>
                </a>
                <a href="/admin/faqs" class="sidebar-link block px-4 py-3 rounded-lg <?= strpos(uri_string(), '/admin/faqs') !== false ? 'active' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="sidebar-text">FAQ</span>
                    </span>
                </a>
                <a href="/admin/reports" class="sidebar-link block px-4 py-3 rounded-lg <?= uri_string() === '/admin/reports' ? 'active' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="sidebar-text">Laporan</span>
                    </span>
                </a>
                <a href="/admin/settings" class="sidebar-link block px-4 py-3 rounded-lg <?= strpos(uri_string(), '/admin/settings') !== false ? 'active' : '' ?>">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="sidebar-text">Pengaturan</span>
                    </span>
                </a>
            </nav>

            <div class="absolute bottom-6 left-6 right-6">
                <a href="/auth/logout" class="btn-ghost flex items-center px-4 py-3 rounded-lg text-gray-400 hover:text-white">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="sidebar-text">Keluar</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-h-screen main-content sidebar-open" id="mainContent">
            <!-- Mobile Header -->
            <div class="lg:hidden flex items-center justify-between mb-6 p-4">
                <button onclick="toggleSidebar()" class="btn-ghost p-2 rounded-lg" aria-label="Buka menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-primary">Laundryku</h1>
                <div class="w-10"></div>
            </div>

            <!-- Desktop Toggle Button -->
            <button onclick="toggleSidebar()" class="hidden lg:flex fixed top-4 left-4 z-50 btn-ghost p-2 rounded-lg bg-dark-secondary border border-white/10 hover:border-primary/50" id="sidebarToggle" aria-label="Toggle sidebar">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <div class="p-4 lg:p-8 lg:pl-20">
                <div class="mb-6">
                    <h2 class="text-xl lg:text-2xl font-semibold"><?= $pageTitle ?? 'Dashboard' ?></h2>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-4 p-4 rounded-lg bg-green-500/10 border border-green-500/30 text-green-400" role="alert">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400" role="alert">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const isMobile = window.innerWidth < 1024;

        if (isMobile) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('open');
        } else {
            const isCollapsed = sidebar.classList.contains('collapsed');
            if (isCollapsed) {
                sidebar.classList.remove('collapsed');
                sidebar.classList.add('expanded');
                mainContent.classList.remove('sidebar-closed');
                mainContent.classList.add('sidebar-open');
                localStorage.setItem('sidebar_state', 'expanded');
            } else {
                sidebar.classList.remove('expanded');
                sidebar.classList.add('collapsed');
                mainContent.classList.remove('sidebar-open');
                mainContent.classList.add('sidebar-closed');
                localStorage.setItem('sidebar_state', 'collapsed');
            }
        }
    }

    // Restore sidebar state on desktop
    document.addEventListener('DOMContentLoaded', function() {
        const isMobile = window.innerWidth < 1024;
        if (!isMobile) {
            const state = localStorage.getItem('sidebar_state');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            if (state === 'collapsed') {
                sidebar.classList.remove('expanded');
                sidebar.classList.add('collapsed');
                mainContent.classList.remove('sidebar-open');
                mainContent.classList.add('sidebar-closed');
            }
        }
    });
    </script>
</body>
</html>
