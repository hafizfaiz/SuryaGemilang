<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surya Gemilang - Modern Minimalist Management Platform</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Sync Theme Script with Filament Default ('theme') -->
    <script>
        (function() {
            var storedTheme = localStorage.getItem('theme');
            if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
</head>
<body class="bg-slate-50 dark:bg-[#09090b] text-slate-800 dark:text-slate-200 font-sans antialiased min-h-screen flex flex-col selection:bg-primary-500 selection:text-white transition-colors duration-300">

    <!-- Minimalist Navbar -->
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-[#09090b]/80 backdrop-blur-md border-b border-slate-200/80 dark:border-zinc-800 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 dark:bg-primary-950/50 border border-primary-100 dark:border-primary-800/40 flex items-center justify-center text-primary-600 transition-transform duration-300 group-hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-center leading-none">
                        <span class="text-slate-900 dark:text-white font-black tracking-wider text-sm">SURYA</span>
                        <span class="font-bold tracking-[0.24em] text-[10px] text-primary-600 mt-0.5">GEMILANG</span>
                    </div>
                </a>

                <!-- Navigation & Action Buttons -->
                <div class="flex items-center gap-3">
                    <!-- Theme Switcher Button -->
                    <button id="theme-toggle" type="button" aria-label="Toggle Theme" class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 rounded-lg transition-colors">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 4.22a1 1 0 011.415 0l.708.708a1 1 0 01-1.414 1.414l-.708-.708a1 1 0 010-1.414zm2.828 4.223a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.22 4.22a1 1 0 010 1.415l-.708.708a1 1 0 01-1.414-1.414l.708-.708a1 1 0 011.414 0zM10 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.22-4.22a1 1 0 01-1.415 0l-.708.708a1 1 0 011.414 1.414l.708-.708a1 1 0 010-1.414zM3.879 10a1 1 0 01-1-1v-1a1 1 0 112 0v1a1 1 0 01-1 1zM6.22 6.22a1 1 0 010-1.415l.708-.708a1 1 0 011.414 1.414L7.633 6.93a1 1 0 01-1.414 0zM10 5a5 5 0 100 10 5 5 0 000-10z"></path>
                        </svg>
                    </button>

                    <!-- Auth Links / CTA -->
                    @auth
                        <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-blue-600 via-sky-600 to-cyan-500 hover:from-blue-500 hover:via-sky-500 hover:to-cyan-400 rounded-xl transition-all duration-300 shadow-md shadow-blue-500/30 hover:shadow-lg hover:shadow-blue-500/50 hover:-translate-y-0.5">
                            <span>Dasbor Admin</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ url('/admin/login') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-blue-600 via-sky-600 to-cyan-500 hover:from-blue-500 hover:via-sky-500 hover:to-cyan-400 rounded-xl transition-all duration-300 shadow-md shadow-blue-500/30 hover:shadow-lg hover:shadow-blue-500/50 hover:-translate-y-0.5">
                            <span>Masuk Admin</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col justify-center">
        <!-- Hero Section -->
        <section class="relative pt-16 pb-20 md:pt-24 md:pb-28 overflow-hidden">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 dark:bg-primary-950/60 border border-primary-200/60 dark:border-primary-800/50 text-xs font-semibold text-primary-600 mb-6 tracking-wide">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary-600 animate-ping"></span>
                    KELOLA STOK DAN PENJUALAN TANPA RIBET
                </div>

                <h1 class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight text-slate-900 dark:text-white leading-[1.1]">
                   Surya <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-sky-500">Gemilang</span>
                </h1>

                <p class="mt-6 max-w-2xl mx-auto text-base sm:text-lg text-slate-600 dark:text-slate-400 font-normal leading-relaxed">
                    Selamat datang di sistem manajemen internal Surya Gemilang. Nikmati kemudahan akses yang aman, dengan desain modern minimalis untuk mengelola stok, menganalisis penjualan, dan memantau performa bisnis Anda secara efisien dalam satu ekosistem.
                </p>

                <!-- CTA Actions -->
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ url('/admin') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-7 py-4 text-sm font-bold text-white bg-gradient-to-r from-blue-600 via-sky-600 to-cyan-500 hover:from-blue-500 hover:via-sky-500 hover:to-cyan-400 rounded-2xl transition-all duration-300 shadow-lg shadow-blue-500/35 hover:shadow-xl hover:shadow-blue-500/50 hover:-translate-y-1">
                        <span>Buka Panel Manajemen</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Minimalist Footer -->
    <footer class="mt-auto border-t border-slate-200/80 dark:border-zinc-800 py-8 bg-white dark:bg-[#09090b] text-sm text-slate-500 dark:text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-800 dark:text-slate-200">Surya Gemilang</span>
                <span>—</span>
                <span>Inventory Management System</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ url('/admin') }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Admin Panel</a>
                <span class="text-slate-300 dark:text-zinc-700">|</span>
                <span>&copy; {{ date('Y') }} All rights reserved.</span>
            </div>
        </div>
    </footer>

    <!-- Theme Switcher Logic (Clean Native Filament Key 'theme') -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            var themeToggleBtn = document.getElementById('theme-toggle');

            var storedTheme = localStorage.getItem('theme');
            if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon?.classList.remove('hidden');
            } else {
                themeToggleDarkIcon?.classList.remove('hidden');
            }

            themeToggleBtn?.addEventListener('click', function() {
                themeToggleDarkIcon?.classList.toggle('hidden');
                themeToggleLightIcon?.classList.toggle('hidden');

                var isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });
        });
    </script>
</body>
</html>
