<x-app-layout>
    <div class="mb-4">
        <h2 class="text-base font-bold text-white tracking-tight">Modifier le produit</h2>
        <p class="text-[11px] text-slate-500">Produit : <span class="text-blue-400 font-medium">{{ $product->name }}</span></p>
    </div>

    <div class="max-w-xl" x-data="productForm({
        categoriesList: {{ $categories->toJson() }},
        defaultCategory: '{{ $product->category_id }}',
        hasPromoInitial: {{ $product->promo_price ? 'true' : 'false' }}
    })">

        <div class="bg-[#090d16] rounded-xl border border-slate-800/80 shadow-2xl p-4">
            <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-3.5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Nom du produit</label>
                    <input type="text" name="name" value="{{ $product->name }}" required placeholder="Ex: Robe Zara d'été"
                        class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 placeholder-slate-700 focus:outline-hidden focus:border-blue-500/40 focus:bg-slate-900 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-3.5">
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Catégorie</label>
                            <button type="button" @click="openCategoryModal = true" class="text-[9px] text-blue-500 hover:text-blue-400 flex items-center gap-1 font-medium transition-colors">
                                <i data-lucide="plus" class="w-2.5 h-2.5"></i> Créer à la volée
                            </button>
                        </div>
                        <select name="category_id" required x-model="selectedCategory" class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-300 focus:bg-slate-900 focus:outline-hidden focus:border-blue-500/40">
                            <option value="" class="bg-[#090d16]">Sélectionner...</option>
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" class="bg-[#090d16]" x-text="cat.name" :selected="selectedCategory == cat.id"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Cible (Target)</label>
                        <input type="text" name="target" value="{{ $product->target }}" placeholder="Ex: Femme, Homme"
                            class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 focus:outline-hidden focus:border-blue-500/40">
                    </div>
                </div>

                <div class="p-3 bg-slate-950/40 border border-slate-800/50 rounded-xl space-y-3.5">
                    <div class="grid grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Prix d'Achat (Ar)</label>
                            <input type="number" name="purchase_price" value="{{ $product->purchase_price }}" required placeholder="15000" step="0.01"
                                class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 tabular-nums focus:outline-hidden focus:border-blue-500/40">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Prix de Vente Normal (Ar)</label>
                            <input type="number" name="sale_price" value="{{ $product->sale_price }}" required placeholder="25000" step="0.01"
                                class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 tabular-nums focus:outline-hidden focus:border-blue-500/40">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Prix Promotionnel (Ar) <span class="text-slate-600 lowercase font-normal">(Optionnel)</span></label>
                        <input type="number" name="promo_price" value="{{ $product->promo_price }}" placeholder="Ex: 20000" step="0.01"
                            x-on:input="hasPromo = $el.value.length > 0"
                            class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 tabular-nums focus:outline-hidden focus:border-emerald-500/40">
                    </div>

                    <div class="grid grid-cols-2 gap-3.5 pt-2 border-t border-slate-900"
                         x-show="hasPromo"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         style="display: none;">
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Début de la promo</label>
                            <input type="datetime-local" name="promo_start_at"
                                value="{{ $product->promo_start_at ? $product->promo_start_at->format('Y-m-d\TH:i') : '' }}"
                                class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-300 focus:outline-hidden focus:border-blue-500/40">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Fin de la promo</label>
                            <input type="datetime-local" name="promo_end_at"
                                value="{{ $product->promo_end_at ? $product->promo_end_at->format('Y-m-d\TH:i') : '' }}"
                                class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-300 focus:outline-hidden focus:border-blue-500/40">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Stock Actuel</label>
                        <input type="number" name="stock_quantity" value="{{ $product->stock_quantity }}" required placeholder="20"
                            class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 focus:outline-hidden focus:border-blue-500/40">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1" >Seuil d'Alerte</label>
                        <input type="number" name="alert_threshold" value="{{ $product->alert_threshold }}" required
                            class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 focus:outline-hidden focus:border-blue-500/40">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Description courte</label>
                    <input type="text" name="description" value="{{ $product->description }}" placeholder="Détails du produit..."
                        class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 focus:outline-hidden focus:border-blue-500/40">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-800/40 mt-5">
                    <a href="/products" class="px-3 py-1.5 bg-slate-900 border border-slate-800/80 text-[11px] font-medium rounded-lg text-slate-400 hover:text-slate-200 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-[11px] font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/10">
                        Mettre à jour le produit
                    </button>
                </div>
            </form>
        </div>

        <div x-show="openCategoryModal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs"
             style="display: none;"
             x-transition>

            <div @click.outside="openCategoryModal = false" class="bg-[#090d16] w-full max-w-sm rounded-xl border border-slate-800 p-4 shadow-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider">Nouveau Rayon / Catégorie</h4>
                    <button type="button" @click="openCategoryModal = false" class="text-slate-500 hover:text-slate-300">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Nom du rayon</label>
                        <input type="text" x-model="newCatName" placeholder="Ex: Accessoires"
                            class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 focus:outline-hidden focus:border-blue-500/40">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Description</label>
                        <input type="text" x-model="newCatDesc" placeholder="Optionnel..."
                            class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 focus:outline-hidden focus:border-blue-500/40">
                    </div>

                    <div x-show="errorMessage" x-text="errorMessage" class="text-[11px] text-red-400 bg-red-500/10 p-2 rounded-lg border border-red-500/10" style="display: none;"></div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-800/40">
                        <button type="button" @click="openCategoryModal = false" class="px-3 py-1.5 bg-slate-900 border border-slate-800/80 text-[11px] font-medium rounded-lg text-slate-400 hover:text-slate-200">Annuler</button>
                        <button type="button" @click="submitCategory()" :disabled="loading"
                                class="px-3 py-1.5 bg-blue-600 text-white text-[11px] font-semibold rounded-lg hover:bg-blue-700 disabled:opacity-50">
                            <span x-show="!loading">Créer</span>
                            <span x-show="loading">Création...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function productForm(config) {
            return {
                categories: config.categoriesList,
                openCategoryModal: false,
                selectedCategory: config.defaultCategory,
                newCatName: '',
                newCatDesc: '',
                loading: false,
                errorMessage: '',
                hasPromo: config.hasPromoInitial, // S'initialise à true si le produit a déjà une promo

                async submitCategory() {
                    if (!this.newCatName.trim()) {
                        this.errorMessage = "Le nom du rayon est obligatoire.";
                        return;
                    }

                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const response = await axios.post("{{ route('categories.store') }}", {
                            name: this.newCatName,
                            description: this.newCatDesc
                        });

                        if (response.data.success) {
                            this.categories.push(response.data.category);
                            this.selectedCategory = response.data.category.id;
                            this.newCatName = '';
                            this.newCatDesc = '';
                            this.openCategoryModal = false;
                        }
                    } catch (error) {
                        this.errorMessage = "Ce rayon existe déjà ou une erreur est survenue.";
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>
