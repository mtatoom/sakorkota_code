<x-app-layout>
    <div class="mb-5">
        <h2 class="text-base font-bold text-white tracking-tight">Gestion des Catégories</h2>
        <p class="text-[11px] text-slate-500">Configuration des rayons pour la boutique : <span class="text-blue-400 font-medium">{{ request()->getHost() }}</span></p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-2.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-2.5 bg-red-500/10 border border-red-500/20 text-red-400 text-xs rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5" x-data="{ editOpen: false, editName: '', editDescription: '', editAction: '' }">

        <div class="bg-[#090d16] rounded-xl border border-slate-800/80 p-4 h-fit">
            <h3 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-4">Nouvelle Catégorie</h3>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Nom du rayon</label>
                    <input type="text" name="name" required placeholder="Ex: Chaussures, Boissons..."
                        class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 placeholder-slate-700 focus:outline-hidden focus:border-blue-500/40 focus:bg-slate-900 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Description</label>
                    <input type="text" name="description" placeholder="Optionnel..."
                        class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 placeholder-slate-700 focus:outline-hidden focus:border-blue-500/40 focus:bg-slate-900 transition-all">
                </div>
                <button type="submit" class="w-full py-1.5 bg-blue-600 text-white text-[11px] font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                    Ajouter au catalogue
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-[#090d16] rounded-xl border border-slate-800/80 shadow-2xl overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-950/20 text-slate-500 text-[10px] font-bold uppercase tracking-wider border-b border-slate-800/60">
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40 text-xs">
                    @forelse($categories as $category)
                        <tr class="hover:bg-slate-800/10 transition-colors group">
                            <td class="px-4 py-2.5 font-medium text-slate-200">
                                {{ $category->name }}
                                <p class="text-[9px] font-mono text-slate-500 mt-0.5">slug: {{ $category->slug }}</p>
                            </td>
                            <td class="px-4 py-2.5 text-slate-400">{{ $category->description ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-center">
                                <div class="flex items-center justify-center gap-2 text-slate-500">
                                    <button type="button"
                                            @click="editOpen = true; editName = '{{ addslashes($category->name) }}'; editDescription = '{{ addslashes($category->description) }}'; editAction = '/categories/{{ $category->id }}'"
                                            class="hover:text-blue-400 p-1 rounded transition-colors">
                                        <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                    </button>

                                    <form id="delete-category-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                @click="$dispatch('open-confirm-modal', {
                                                    formId: 'delete-category-{{ $category->id }}',
                                                    title: 'Supprimer le rayon ?',
                                                    text: 'Êtes-vous sûr de vouloir supprimer la catégorie &quot;{{ $category->name }}&quot; ? Aucun produit ne doit y être lié.'
                                                })"
                                                class="hover:text-red-400 p-1 rounded transition-colors">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-slate-600 italic">Aucune catégorie créée pour cette boutique.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="editOpen"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-xs"
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.outside="editOpen = false"
                 class="bg-[#090d16] w-full max-w-md rounded-xl border border-slate-800/90 shadow-2xl p-4 transform transition-all"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-bold text-slate-200 uppercase tracking-wider">Modifier la catégorie</h3>
                    <button type="button" @click="editOpen = false" class="text-slate-500 hover:text-slate-300">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <form :action="editAction" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Nom du rayon</label>
                        <input type="text" x-model="editName" name="name" required
                            class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 focus:outline-hidden focus:border-blue-500/40 focus:bg-slate-900 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Description</label>
                        <input type="text" x-model="editDescription" name="description"
                            class="w-full px-3 py-1.5 bg-slate-900/50 border border-slate-800/80 text-xs rounded-lg text-slate-200 focus:outline-hidden focus:border-blue-500/40 focus:bg-slate-900 transition-all">
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-800/40 mt-4">
                        <button type="button" @click="editOpen = false" class="px-3 py-1.5 bg-slate-900 border border-slate-800/80 text-[11px] font-medium rounded-lg text-slate-400 hover:text-slate-200 transition-colors">Annuler</button>
                        <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-[11px] font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/10">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
