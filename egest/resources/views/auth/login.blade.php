<x-guest-layout>
    <div class="w-full bg-[#090d16] border border-slate-800/80 p-6 rounded-2xl shadow-2xl">

        <div class="flex flex-col items-center mb-6">
            <div class="w-10 h-10 bg-blue-500/10 text-blue-400 rounded-xl border border-blue-500/20 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package"><path d="M16.5 9.4 7.55 4.24a1.79 1.79 0 0 0-1.8 0L3.3 5.6a1.78 1.78 0 0 0-.89 1.55v6.6a1.78 1.78 0 0 0 .89 1.55l2.45 1.41a1.8 1.8 0 0 0 1.8 0l9-5.18a1.8 1.8 0 0 0 .9-1.55V9.4a1.78 1.78 0 0 0-.89-1.55z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
            </div>
            <h2 class="text-base font-bold text-white tracking-tight">Connexion à votre espace</h2>
            <p class="text-[11px] text-slate-500 mt-0.5">Boutique : <span class="text-blue-400 font-medium">{{ request()->getHost() }}</span></p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Adresse Email</label>
                <input id="email" class="block w-full bg-slate-950/60 border border-slate-800/80 rounded-xl px-3 py-2 text-xs text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all"
                       type="email"
                       name="email"
                       :value="old('email')"
                       required
                       autofocus
                       autocomplete="username"
                       placeholder="exemple@domaine.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-[11px] text-red-400" />
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Mot de passe</label>
                    @if (Route::has('password.request'))
                        <a class="text-[10px] text-slate-500 hover:text-blue-400 transition-colors" href="{{ route('password.request') }}">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>
                <input id="password" class="block w-full bg-slate-950/60 border border-slate-800/80 rounded-xl px-3 py-2 text-xs text-slate-200 placeholder-slate-600 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/30 transition-all"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-[11px] text-red-400" />
            </div>

            <div class="flex items-center">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-800/80 bg-slate-950 text-blue-500 focus:ring-0 focus:ring-offset-0 w-3.5 h-3.5 cursor-pointer" name="remember">
                    <span class="ms-2 text-[11px] text-slate-400">Se souvenir de moi</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-lg shadow-blue-600/10 hover:shadow-blue-600/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <span>Se connecter</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
