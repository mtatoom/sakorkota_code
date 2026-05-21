<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://unpkg.com/lucide@latest"></script>

        <script src="https://cdn.tailwindcss.com"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-200 bg-[#0b0f19] flex min-h-screen selection:bg-blue-500/30">

        <aside class="w-60 bg-[#090d16] text-slate-400 flex flex-col justify-between p-4 shrink-0 min-h-screen border-r border-slate-800/60">
            <div>
                <div class="mb-6 px-2 py-3 border-b border-slate-800/60">
                    <h1 class="text-white text-lg font-bold tracking-tight">E-GEST</h1>
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-0.5">Gestion de Stock </p>
                </div>

                <nav class="space-y-0.5">
                    <a href="/dashboard" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs transition-colors hover:bg-slate-800/50 hover:text-white {{ request()->is('dashboard') ? 'bg-slate-800/80 text-white font-medium border-l-2 border-blue-500' : '' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-slate-400"></i> Tableau de bord
                    </a>
                    <a href="#" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs transition-colors hover:bg-slate-800/50 hover:text-white">
                        <i data-lucide="shopping-cart" class="w-4 h-4 text-slate-400"></i> Vente
                    </a>
                    <a href="/products" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs transition-colors hover:bg-slate-800/50 hover:text-white {{ request()->is('products*') ? 'bg-slate-800/80 text-white font-medium border-l-2 border-blue-500' : '' }}">
                        <i data-lucide="package" class="w-4 h-4 text-slate-400"></i> Inventaire
                    </a>
                    <a href="#" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs transition-colors hover:bg-slate-800/50 hover:text-white">
                        <i data-lucide="git-commit" class="w-4 h-4 text-slate-400"></i> Traçabilité
                    </a>
                    <a href="#" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-xs transition-colors hover:bg-slate-800/50 hover:text-white">
                        <i data-lucide="wallet" class="w-4 h-4 text-slate-400"></i> Journal
                    </a>
                </nav>
            </div>

            <div class="flex items-center justify-between p-2 border-t border-slate-800/60 bg-slate-950/30 rounded-lg">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-md bg-blue-600/20 text-blue-400 border border-blue-500/30 font-bold flex items-center justify-center text-xs">
                        U
                    </div>
                    <div>
                        <h4 class="text-xs font-medium text-slate-200 truncate w-24 leading-tight">Utilisateur</h4>
                        <p class="text-[10px] text-slate-500">Admin</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-h-screen">

            <header class="flex justify-between items-center p-3 bg-[#090d16]/80 backdrop-blur-md border-b border-slate-800/60">
                <div class="relative w-80">
                    <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-2.5 text-slate-500"></i>
                    <input type="text" placeholder="Rechercher des produits, catégories..." class="w-full pl-9 pr-4 py-1.5 bg-slate-900/50 border border-slate-800 text-xs rounded-lg text-slate-300 placeholder-slate-600 focus:outline-hidden focus:border-blue-500/50 focus:bg-slate-900 transition-all">
                </div>
                <div class="flex items-center gap-3 text-slate-400">
                    <button class="p-1.5 hover:bg-slate-800/50 rounded-lg transition-colors"><i data-lucide="bell" class="w-4 h-4"></i></button>
                    <button class="p-1.5 hover:bg-slate-800/50 rounded-lg transition-colors"><i data-lucide="settings" class="w-4 h-4"></i></button>
                    <span class="text-xs font-medium border-l pl-3 border-slate-800 text-slate-500">Boutique: {{ ucfirst(explode('.', request()->getHost())[0]) }}</span>
                </div>
            </header>

            <main class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>

        <script>
            lucide.createIcons();
        </script>
    </body>
</html>
