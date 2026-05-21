<x-app-layout>
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-lg font-bold text-white tracking-tight">Gestion des Produits</h2>
            <p class="text-[11px] text-slate-500">Inventaire consolidé en temps réel.</p>
        </div>
        <div class="flex gap-2">
            <button class="flex items-center gap-1.5 bg-slate-900 border border-slate-800 text-xs font-medium px-2.5 py-1.5 rounded-lg hover:bg-slate-800 text-slate-300 transition-colors">
                <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5"></i> Filtrer
            </button>
            <a href="/add-product" class="flex items-center gap-1.5 bg-blue-600 text-white text-xs font-semibold px-2.5 py-1.5 rounded-lg hover:bg-blue-700 transition-colors shadow-xs shadow-blue-500/10">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Ajouter
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div class="bg-[#090d16] p-3.5 rounded-xl border border-slate-800/60 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-medium text-slate-500 uppercase tracking-wider">Total Produits</p>
                <h3 class="text-lg font-bold text-white mt-0.5">{{ $products->count() }}</h3>
            </div>
            <div class="p-2 bg-blue-500/10 text-blue-400 rounded-lg border border-blue-500/20"><i data-lucide="package" class="w-3.5 h-3.5"></i></div>
        </div>

        <div class="bg-[#090d16] p-3.5 rounded-xl border border-slate-800/60 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-medium text-slate-500 uppercase tracking-wider">Stock Faible</p>
                <h3 class="text-lg font-bold text-red-400 mt-0.5">{{ $products->where('stock_quantity', '<=', 5)->count() }}</h3>
            </div>
            <div class="p-2 bg-red-500/10 text-red-400 rounded-lg border border-red-500/20"><i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i></div>
        </div>

        <div class="bg-[#090d16] p-3.5 rounded-xl border border-slate-800/60 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-medium text-slate-500 uppercase tracking-wider">Valeur Totale</p>
                <h3 class="text-lg font-bold text-emerald-400 mt-0.5">{{ number_format($products->sum(fn($p) => $p->sale_price * $p->stock_quantity), 0, ',', ' ') }} Ar</h3>
            </div>
            <div class="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg border border-emerald-500/20"><i data-lucide="banknote" class="w-3.5 h-3.5"></i></div>
        </div>

        <div class="bg-[#090d16] p-3.5 rounded-xl border border-slate-800/60 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-medium text-slate-500 uppercase tracking-wider">Catégories</p>
                <h3 class="text-lg font-bold text-purple-400 mt-0.5">{{ $products->pluck('category_id')->unique()->count() }}</h3>
            </div>
            <div class="p-2 bg-purple-500/10 text-purple-400 rounded-lg border border-purple-500/20"><i data-lucide="folder-open" class="w-3.5 h-3.5"></i></div>
        </div>
    </div>

    <div class="bg-[#090d16] rounded-xl border border-slate-800/80 shadow-2xl overflow-hidden">
        <div class="flex justify-between items-center p-2.5 border-b border-slate-800/60 bg-slate-950/40">
            <div class="flex bg-slate-900 p-0.5 rounded-md gap-0.5 text-[11px] font-medium">
                <button class="bg-slate-800 text-white px-3 py-1 rounded shadow-xs">Tous</button>
                <button class="px-3 py-1 text-slate-500 hover:text-slate-300 transition-colors">Actifs</button>
                <button class="px-3 py-1 text-slate-500 hover:text-slate-300 transition-colors">Hors Stock</button>
            </div>
            <div class="text-[11px] text-slate-500">
                Affichage par <span class="font-semibold text-slate-300 border border-slate-800 rounded px-1.5 py-0.5 bg-slate-900 cursor-pointer">25 <i data-lucide="chevron-down" class="w-2.5 h-2.5 inline"></i></span>
            </div>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-950/20 text-slate-500 text-[10px] font-bold uppercase tracking-wider border-b border-slate-800/60">
                    <th class="px-4 py-2">Produit</th>
                    <th class="px-4 py-2">Catégorie</th>
                    <th class="px-4 py-2">Stock</th>
                    <th class="px-4 py-2 text-right">Prix Unit.</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/40 text-xs">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-800/20 transition-colors group">
                        <td class="px-4 py-1.5 flex items-center gap-2.5">
                            <div class="w-6 h-6 bg-slate-900 rounded border border-slate-800/80 flex items-center justify-center text-slate-600 shrink-0">
                                <i data-lucide="image" class="w-3.5 h-3.5 text-slate-600 group-hover:text-slate-400 transition-colors"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-slate-200 group-hover:text-white transition-colors leading-tight">{{ $product->name }}</h4>
                                <p class="text-[9px] text-slate-500 uppercase tracking-tight">REF: {{ $product->sku }}</p>
                            </div>
                        </td>

                        <td class="px-4 py-1.5">
                            <span class="bg-blue-950/40 text-blue-400 border border-blue-900/50 text-[9px] font-bold px-2 py-0.5 rounded-sm uppercase tracking-wider">
                                {{ $product->category->name }}
                            </span>
                        </td>

                        <td class="px-4 py-1.5">
                            <div class="flex items-center gap-2 w-36">
                                <div class="w-full bg-slate-900 rounded-full h-1 border border-slate-800/30">
                                    <div class="h-1 rounded-full {{ $product->stock_quantity > 5 ? 'bg-blue-500' : 'bg-red-500' }}"
                                         style="width: {{ min(($product->stock_quantity / 50) * 100, 100) }}%"></div>
                                </div>
                                <span class="text-[10px] font-medium shrink-0 {{ $product->stock_quantity > 5 ? 'text-slate-400' : 'text-red-400 font-bold' }}">
                                    {{ $product->stock_quantity }} u.
                                </span>
                            </div>
                        </td>

                        <td class="px-4 py-1.5 text-right font-semibold text-slate-300 tabular-nums">
                            {{ number_format($product->sale_price, 0, ',', ' ') }} Ar
                        </td>

                        <td class="px-4 py-1.5 text-center">
                            <div class="flex items-center justify-center gap-1 text-slate-500">
                                <button class="hover:text-blue-400 p-1 rounded transition-colors"><i data-lucide="edit-2" class="w-3.5 h-3.5"></i></button>
                                <button class="hover:text-slate-300 p-1 rounded transition-colors"><i data-lucide="more-vertical" class="w-3.5 h-3.5"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-slate-600 italic bg-slate-950/10">Aucun produit en stock.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-2 bg-slate-950/40 border-t border-slate-800/60 flex justify-between items-center text-[10px] text-slate-500">
            <p>Affichage de 1 à {{ $products->count() }} sur {{ $products->count() }} produits</p>
            <div class="flex gap-0.5">
                <button class="px-2 py-0.5 bg-slate-900 border border-slate-800 rounded text-slate-600 cursor-not-allowed">‹</button>
                <button class="px-2 py-0.5 bg-blue-600 text-white rounded font-medium shadow-xs">1</button>
                <button class="px-2 py-0.5 bg-slate-900 border border-slate-800 rounded text-slate-400 hover:bg-slate-800 transition-colors">›</button>
            </div>
        </div>
    </div>
</x-app-layout>
