<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Venduix') }}</title>

        @fonts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
                @layer properties { ... }
            </style>
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="bg-[#070b12] text-slate-200 antialiased font-sans">

        <nav class="w-full max-w-6xl mx-auto px-6 py-4 flex justify-between items-center border-b border-slate-900">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-blue-600 rounded-full shadow-[0_0_10px_rgba(37,99,235,0.6)]"></div>
                <span class="text-white font-bold tracking-tight text-lg">Venduix</span>
            </div>
            <button onclick="openModal('loginModal')" class="text-xs bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 font-medium px-4 py-2 rounded-lg transition-all cursor-pointer">
                Accéder à mon espace
            </button>
        </nav>

        <main class="min-h-[calc(100vh-140px)] flex flex-col justify-center items-center px-4 text-center">

            @if ($errors->any())
                <div class="w-full max-w-md mb-6 bg-red-950/40 border border-red-900/50 text-red-400 p-4 rounded-xl text-xs text-left shadow-xl">
                    <span class="font-semibold block mb-1">Une erreur est survenue :</span>
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="max-w-2xl mx-auto space-y-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-[11px] font-medium text-blue-400 tracking-wide uppercase">
                    Architecture Multi-Tenant Optimisée
                </span>

                <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-none">
                    Gérez vos stocks et factures <br>
                    <span class="bg-gradient-to-r from-blue-500 to-indigo-400 bg-clip-text text-transparent">en toute simplicité.</span>
                </h1>

                <p class="text-sm sm:text-base text-slate-400 max-w-md mx-auto leading-relaxed">
                    Une plateforme puissante et cloisonnée dédiée aux professionnels pour centraliser l'inventaire, automatiser les ventes et éditer des rapports précis.
                </p>

                <div class="pt-4 flex flex-col sm:flex-row justify-center items-center gap-3">
                    <button onclick="openModal('registerModal')" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-6 py-3 rounded-xl transition-all shadow-lg shadow-blue-500/10 cursor-pointer">
                        Créer une boutique
                    </button>
                    <button onclick="openModal('loginModal')" class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 text-xs font-semibold px-6 py-3 rounded-xl transition-all cursor-pointer">
                        Se connecter à un espace
                    </button>
                </div>
            </div>
        </main>

        <footer class="w-full text-center py-6 text-[11px] text-slate-600 border-t border-slate-910">
            &copy; {{ date('Y') }} Venduix. Tous droits réservés. Autonomie & performance locale.
        </footer>


        <div id="registerModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden flex justify-center items-center p-4 z-50 transition-opacity duration-300">
            <div class="w-full max-w-md bg-[#0d1527] border border-slate-800 rounded-2xl p-6 shadow-2xl relative transform scale-95 transition-transform duration-300">
                <button onclick="closeModal('registerModal')" class="absolute top-4 right-4 text-slate-400 hover:text-white text-sm cursor-pointer">&times;</button>

                <div class="text-center mb-5">
                    <h2 class="text-lg font-bold text-white tracking-tight">Initialiser un espace Venduix</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Configurez votre base de données et votre accès administrateur</p>
                </div>

                <form action="{{ route('tenant.register.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Nom de l'entreprise</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" required placeholder="Ex: Boutique Sakorkota"
                            class="w-full bg-[#090d16] border border-slate-800 rounded-lg px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                    <div class="border-t border-slate-800/60 my-3 pt-1"></div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Nom du responsable</label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" required placeholder="Ex: Mijo RABE"
                            class="w-full bg-[#090d16] border border-slate-800 rounded-lg px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Adresse Email</label>
                        <input type="email" name="admin_email" value="{{ old('admin_email') }}" required placeholder="admin@entreprise.com"
                            class="w-full bg-[#090d16] border border-slate-800 rounded-lg px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-blue-500 transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Mot de passe</label>
                            <input type="password" name="password" required
                                class="w-full bg-[#090d16] border border-slate-800 rounded-lg px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Confirmation</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full bg-[#090d16] border border-slate-800 rounded-lg px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-blue-500 transition-colors">
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2.5 rounded-lg transition-colors cursor-pointer shadow-lg">
                        Créer ma base de données isolée
                    </button>
                </form>
            </div>
        </div>


        <div id="loginModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden flex justify-center items-center p-4 z-50 transition-opacity duration-300">
            <div class="w-full max-w-sm bg-[#0d1527] border border-slate-800 rounded-2xl p-6 shadow-2xl relative transform scale-95 transition-transform duration-300">
                <button onclick="closeModal('loginModal')" class="absolute top-4 right-4 text-slate-400 hover:text-white text-sm cursor-pointer">&times;</button>

                <div class="text-center mb-5">
                    <h2 class="text-lg font-bold text-white tracking-tight">Rejoindre votre espace</h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Saisissez l'identifiant unique de votre structure</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Identifiant d'espace</label>
                        <div class="flex items-center bg-[#090d16] border border-slate-800 rounded-lg px-3 py-2 focus-within:border-blue-500 transition-colors">
                            <input type="text" id="tenantSlug" placeholder="ma-boutique" required
                                class="w-full bg-transparent text-xs text-slate-200 focus:outline-none">
                            <span class="text-xs text-slate-500 font-medium select-none">.localhost:8000</span>
                        </div>
                    </div>
                    <button onclick="redirectToTenant()" class="w-full bg-slate-100 hover:bg-white text-slate-950 text-xs font-semibold py-2.5 rounded-lg transition-colors cursor-pointer shadow-lg">
                        Valider et continuer
                    </button>
                </div>
            </div>
        </div>

        <script>
            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.querySelector('.transform').classList.remove('scale-95');
                    modal.querySelector('.transform').classList.add('scale-100');
                }, 10);
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                modal.querySelector('.transform').classList.remove('scale-100');
                modal.querySelector('.transform').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 150);
            }

            function redirectToTenant() {
                const slug = document.getElementById('tenantSlug').value.trim().toLowerCase();
                if (slug) {
                    // Nettoie l'entrée au format slug au cas où l'utilisateur met des espaces
                    const cleanSlug = slug.replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-');
                    window.location.href = `http://${cleanSlug}.localhost:8000/login`;
                }
            }

            // Fermer les fenêtres si on clique à l'extérieur de la boîte blanche
            window.onclick = function(event) {
                if (event.target.classList.contains('backdrop-blur-sm')) {
                    closeModal(event.target.id);
                }
            }
        </script>
    </body>
</html>
