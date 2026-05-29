<x-app-layout>
    <div x-data="{
        selectedProducts: [],
        openDeleteModal: false,
        toggleAll() {
            if (this.selectedProducts.length === {{ $products->count() }}) {
                this.selectedProducts = [];
            } else {
                this.selectedProducts = [ @foreach($products as $p) '{{ $p->id }}', @endforeach ];
            }
        }
    }">

        <div class="flex justify-between items-center mb-5">
            <div>
                <h2 class="text-base font-bold text-white tracking-tight">Gestion des Produits</h2>
                <p class="text-[11px] text-slate-500">Inventaire de la boutique : <span
                        class="text-blue-400 font-medium">{{ request()->getHost() }}</span></p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('products.create') }}"
                    class="flex items-center gap-1.5 bg-blue-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-blue-700 transition-colors shadow-sm shadow-blue-500/10">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Ajouter un produit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            <div class="bg-[#090d16] p-4 rounded-xl border border-slate-800/60 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Total Références</p>
                    <h3 class="text-xl font-bold text-white mt-0.5">{{ $products->count() }}</h3>
                </div>
                <div class="p-2 bg-blue-500/10 text-blue-400 rounded-lg border border-blue-500/20">
                    <i data-lucide="package" class="w-4 h-4"></i>
                </div>
            </div>

            <div class="bg-[#090d16] p-4 rounded-xl border border-slate-800/60 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Alertes Stock</p>
                    <h3 class="text-xl font-bold text-red-400 mt-0.5">
                        {{ $products->filter(fn($p) => $p->stock_quantity <= $p->alert_threshold)->count() }}
                    </h3>
                </div>
                <div class="p-2 bg-red-500/10 text-red-400 rounded-lg border border-red-500/20">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                </div>
            </div>

            <div class="bg-[#090d16] p-4 rounded-xl border border-slate-800/60 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Valeur Estimée</p>
                    <h3 class="text-xl font-bold text-emerald-400 mt-0.5">
                        {{ number_format($products->sum(fn($p) => $p->sale_price * $p->stock_quantity), 0, ',', ' ') }} Ar
                    </h3>
                </div>
                <div class="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg border border-emerald-500/20">
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                </div>
            </div>
        </div>

        <div x-show="selectedProducts.length > 0"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-1"
             class="mb-3 p-2 bg-red-500/5 border border-red-500/20 rounded-xl flex justify-between items-center bg-slate-950/40"
             style="display: none;">
            <span class="text-xs text-red-400 font-medium ml-2">
                <span x-text="selectedProducts.length"></span> produit(s) sélectionné(s)
            </span>

            <button type="button" @click="openDeleteModal = true"
                    class="flex items-center gap-1.5 bg-red-600/90 hover:bg-red-600 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg transition-colors shadow-lg shadow-red-500/10">
                <i data-lucide="trash-2" class="w-3 h-3"></i> Supprimer la sélection
            </button>
        </div>

        <div class="bg-[#090d16] rounded-xl border border-slate-800/80 shadow-2xl overflow-hidden">
            <div class="flex justify-between items-center p-3 border-b border-slate-800/60 bg-slate-950/40">
                <div class="flex bg-slate-900 p-0.5 rounded-md gap-0.5 text-[11px] font-medium">
                    <a href="{{ route('products.index', ['filter' => 'all']) }}"
                        class="px-3 py-1 rounded transition-colors {{ $filter === 'all' ? 'bg-slate-800 text-white' : 'text-slate-500 hover:text-slate-300' }}">Tous</a>
                    <a href="{{ route('products.index', ['filter' => 'active']) }}"
                        class="px-3 py-1 rounded transition-colors {{ $filter === 'active' ? 'bg-slate-800 text-white' : 'text-slate-500 hover:text-slate-300' }}">Actifs</a>
                    <a href="{{ route('products.index', ['filter' => 'out_of_stock']) }}"
                        class="px-3 py-1 rounded transition-colors {{ $filter === 'out_of_stock' ? 'bg-slate-800 text-white' : 'text-slate-500 hover:text-slate-300' }}">En Rupture</a>
                </div>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950/20 text-slate-500 text-[10px] font-bold uppercase tracking-wider border-b border-slate-800/60">
                        <th class="px-4 py-2.5 w-10">
                            <input type="checkbox" @click="toggleAll()" :checked="selectedProducts.length === {{ $products->count() }} && {{ $products->count() }} > 0"
                                   class="rounded bg-slate-900 border-slate-800 text-blue-600 focus:ring-0 focus:ring-offset-0 w-3.5 h-3.5 transition-colors cursor-pointer">
                        </th>
                        <th class="px-4 py-2.5">Produit / SKU</th>
                        <th class="px-4 py-2.5">Catégorie</th>
                        <th class="px-4 py-2.5">Niveau de Stock</th>
                        <th class="px-4 py-2.5 text-right">Prix Public</th>
                        <th class="px-4 py-2.5 text-right w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-xs">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-800/10 transition-colors group" :class="selectedProducts.includes('{{ $product->id }}') ? 'bg-blue-500/5' : ''">

                            <td class="px-4 py-2">
                                <input type="checkbox" value="{{ $product->id }}" x-model="selectedProducts"
                                       class="rounded bg-slate-900 border-slate-800 text-blue-600 focus:ring-0 focus:ring-offset-0 w-3.5 h-3.5 transition-colors cursor-pointer">
                            </td>

                            <td class="px-4 py-2 flex items-center gap-3">
                                <div class="w-7 h-7 bg-slate-900 rounded-lg border border-slate-800/80 flex items-center justify-center text-slate-500 shrink-0">
                                    <i data-lucide="package" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-200 group-hover:text-white transition-colors leading-tight">
                                        {{ $product->name }}</h4>
                                    <p class="text-[9px] text-slate-500 uppercase tracking-tight mt-0.5">
                                        SKU: <span class="text-slate-400 font-mono">{{ $product->sku }}</span>
                                        @if ($product->target)
                                            | Cible: <span class="text-slate-400">{{ $product->target }}</span>
                                        @endif
                                    </p>
                                </div>
                            </td>

                            <td class="px-4 py-2">
                                <span class="bg-blue-950/40 text-blue-400 border border-blue-900/40 text-[9px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                                    {{ $product->category->name ?? 'Général' }}
                                </span>
                            </td>

                            <td class="px-4 py-2">
                                <div class="flex items-center gap-2.5 w-40">
                                    <div class="w-full bg-slate-900 rounded-full h-1 border border-slate-800/40">
                                        <div class="h-1 rounded-full {{ $product->stock_quantity > $product->alert_threshold ? 'bg-blue-500' : 'bg-red-500' }}"
                                            style="width: {{ min(($product->stock_quantity / 40) * 100, 100) }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-semibold shrink-0 {{ $product->stock_quantity > $product->alert_threshold ? 'text-slate-400' : 'text-red-400 font-bold animate-pulse' }}">
                                        {{ $product->stock_quantity }} u.
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-2 text-right font-semibold text-slate-200 tabular-nums">
                                {{ number_format($product->sale_price, 0, ',', ' ') }} Ar
                            </td>

                            <td class="px-4 py-2 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('products.edit', $product->id) }}"
                                       class="inline-flex items-center gap-1 px-2 py-1 bg-slate-900/80 border border-slate-800/80 hover:border-slate-700 rounded-md text-[10px] font-medium text-slate-400 hover:text-blue-400 opacity-60 group-hover:opacity-100 transition-all shadow-xs">
                                        <i data-lucide="pencil" class="w-3 h-3"></i>
                                        <span>Modifier</span>
                                    </a>

                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                          onsubmit="return confirm('Supprimer ce produit ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center p-1 bg-slate-900/80 border border-slate-800/80 hover:border-red-900/40 rounded-md text-slate-500 hover:text-red-400 opacity-60 group-hover:opacity-100 transition-all shadow-xs">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-600 italic">Aucune référence produit enregistrée pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="openDeleteModal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs"
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.outside="openDeleteModal = false"
                 class="bg-[#090d16] w-full max-w-sm rounded-xl border border-slate-800 p-4 shadow-2xl">

                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xs font-bold text-red-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Confirmation
                    </h4>
                    <button type="button" @click="openDeleteModal = false" class="text-slate-500 hover:text-slate-300">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Êtes-vous sûr de vouloir supprimer définitivement les <span class="text-red-400 font-bold" x-text="selectedProducts.length"></span> produits sélectionnés ? Cette opération affectera l'état de votre inventaire.
                    </p>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-800/40">
                        <button type="button" @click="openDeleteModal = false"
                                class="px-3 py-1.5 bg-slate-900 border border-slate-800/80 text-[11px] font-medium rounded-lg text-slate-400 hover:text-slate-200">
                            Annuler
                        </button>

                        <form action="{{ route('products.destroyMass') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="ids" :value="selectedProducts.join(',')">
                            <button type="submit"
                                    class="px-3 py-1.5 bg-red-600 text-white text-[11px] font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-lg shadow-red-500/10">
                                Confirmer la suppression
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
