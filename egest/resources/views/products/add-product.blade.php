<x-app-layout>
    <div class="mb-6">
        <h2 class="text-lg font-bold text-white tracking-tight">Ajouter un nouveau produit</h2>
        <p class="text-[11px] text-slate-500">Enregistrer une nouvelle référence dans l'inventaire de la boutique.</p>
    </div>

    <div class="max-w-2xl bg-[#090d16] rounded-xl border border-slate-800/80 shadow-2xl p-5">
        <form action="/products" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-[11px] font-medium text-slate-400 uppercase tracking-wider mb-1.5">Nom du produit</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800 text-xs rounded-lg text-slate-200 placeholder-slate-600 focus:outline-hidden focus:border-blue-500/50 focus:bg-slate-900 transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="sku" class="block text-[11px] font-medium text-slate-400 uppercase tracking-wider mb-1.5">Référence (SKU)</label>
                    <input type="text" id="sku" name="sku" required
                        class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800 text-xs rounded-lg text-slate-200 placeholder-slate-600 focus:outline-hidden focus:border-blue-500/50 focus:bg-slate-900 transition-all">
                </div>

                <div>
                    <label for="category_id" class="block text-[11px] font-medium text-slate-400 uppercase tracking-wider mb-1.5">Catégorie</label>
                    <select id="category_id" name="category_id" required
                        class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800 text-xs rounded-lg text-slate-300 focus:outline-hidden focus:border-blue-500/50 focus:bg-slate-900 transition-all">
                        <option value="" class="bg-[#090d16]">Sélectionner...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" class="bg-[#090d16]">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="sale_price" class="block text-[11px] font-medium text-slate-400 uppercase tracking-wider mb-1.5">Prix de vente (Ar)</label>
                    <input type="number" id="sale_price" name="sale_price" required
                        class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800 text-xs rounded-lg text-slate-200 focus:outline-hidden focus:border-blue-500/50 focus:bg-slate-900 transition-all tabular-nums">
                </div>

                <div>
                    <label for="stock_quantity" class="block text-[11px] font-medium text-slate-400 uppercase tracking-wider mb-1.5">Quantité initiale</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" required
                        class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800 text-xs rounded-lg text-slate-200 focus:outline-hidden focus:border-blue-500/50 focus:bg-slate-900 transition-all">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-800/60 mt-6">
                <a href="/products" class="px-3 py-1.5 bg-slate-900 border border-slate-800 text-xs font-medium rounded-lg hover:bg-slate-800 text-slate-400 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-xs shadow-blue-500/10">
                    Enregistrer le produit
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
