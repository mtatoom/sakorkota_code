<x-app-layout>
    <div class="space-y-6">

        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-base font-bold text-white tracking-tight">Tableau de Bord</h2>
                <p class="text-[11px] text-slate-500">Vue d'ensemble de l'activité : <span
                        class="text-blue-400 font-medium">{{ request()->getHost() }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="bg-[#090d16] p-4 rounded-xl border border-slate-800/60 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Total Références</p>
                    <h3 class="text-xl font-bold text-white mt-0.5">{{ $totalProducts ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-blue-500/10 text-blue-400 rounded-lg border border-blue-500/20">
                    <i data-lucide="package" class="w-4 h-4"></i>
                </div>
            </div>

            <div class="bg-[#090d16] p-4 rounded-xl border border-slate-800/60 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Alertes Stock</p>
                    <h3 class="text-xl font-bold text-red-400 mt-0.5">{{ $lowStockCount ?? 0 }}</h3>
                </div>
                <div class="p-2 bg-red-500/10 text-red-400 rounded-lg border border-red-500/20">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                </div>
            </div>

            <div class="bg-[#090d16] p-4 rounded-xl border border-slate-800/60 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Valeur Estimée</p>
                    <h3 class="text-xl font-bold text-emerald-400 mt-0.5">
                        {{ number_format($estimatedValue ?? 0, 0, ',', ' ') }} Ar
                    </h3>
                </div>
                <div class="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg border border-emerald-500/20">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
            </div>

        </div>

        <div class="bg-[#090d16] rounded-xl border border-slate-800/80 shadow-2xl overflow-hidden">

            <div class="flex justify-between items-center p-3 border-b border-slate-800/60 bg-slate-950/40">
                <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-400"></i> Dernières références ajoutées
                </h4>
                <a href="{{ route('products.index') }}"
                    class="text-[11px] text-blue-400 hover:text-blue-300 font-medium transition-colors">
                    Voir tout le catalogue &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-950/20 text-slate-500 text-[10px] font-bold uppercase tracking-wider border-b border-slate-800/60">
                            <th class="px-4 py-2.5">Produit / SKU</th>
                            <th class="px-4 py-2.5">Catégorie</th>
                            <th class="px-4 py-2.5">Niveau de Stock</th>
                            <th class="px-4 py-2.5 text-right">Prix Public</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40 text-xs">
                        @forelse($recentProducts ?? [] as $product)
                            <tr class="hover:bg-slate-800/10 transition-colors group">

                                <td class="px-4 py-2 flex items-center gap-3">
                                    <div class="w-7 h-7 bg-slate-900 rounded-lg border border-slate-800/80 flex items-center justify-center text-slate-500 shrink-0">
                                        <i data-lucide="package" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-slate-200 group-hover:text-white transition-colors leading-tight">
                                            {{ $product->name }}
                                        </h4>
                                        <p class="text-[9px] text-slate-500 uppercase tracking-tight mt-0.5">
                                            SKU: <span class="text-slate-400 font-mono">{{ $product->sku }}</span>
                                        </p>
                                    </div>
                                </td>

                                <td class="px-4 py-2">
                                    <span class="bg-blue-950/40 text-blue-400 border border-blue-900/40 text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                                        {{ $product->category->name ?? 'Général' }}
                                    </span>
                                </td>

                                <td class="px-4 py-2">
                                    @php
                                        // Extraction des calculs en amont pour simplifier les balises HTML de style
                                        $alertThreshold = $product->alert_threshold ?? 5;
                                        $stockQty = $product->stock_quantity ?? 0;
                                        $stockPercentage = min(($stockQty / 40) * 100, 100);
                                        $isLowStock = $stockQty <= $alertThreshold;
                                    @endphp
                                    <div class="flex items-center gap-2.5 w-40">
                                        <div class="w-full bg-slate-900 rounded-full h-1 border border-slate-800/40">
                                            <div class="h-1 rounded-full {{ $isLowStock ? 'bg-red-500' : 'bg-blue-500' }}"
                                                style="width: {{ $stockPercentage }}%">
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-semibold shrink-0 {{ $isLowStock ? 'text-red-400 font-bold' : 'text-slate-400' }}">
                                            {{ $stockQty }} u.
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-2 text-right font-semibold text-slate-200 tabular-nums">
                                    @if ($product->is_on_promo)
                                        <div class="flex flex-col items-end">
                                            <span class="text-emerald-400 font-bold">
                                                {{ number_format($product->promo_price, 0, ',', ' ') }} Ar
                                            </span>
                                            <span class="text-[10px] text-slate-500 line-through font-normal">
                                                {{ number_format($product->sale_price, 0, ',', ' ') }} Ar
                                            </span>
                                        </div>
                                    @else
                                        <span>{{ number_format($product->sale_price, 0, ',', ' ') }} Ar</span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-600 italic">
                                    Aucune référence récente disponible.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
