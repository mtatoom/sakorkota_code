<div x-data="{ open: false, formId: '', title: '', text: '' }"
     x-show="open"
     @open-confirm-modal.window="open = true; formId = $event.detail.formId; title = $event.detail.title; text = $event.detail.text"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-xs"
     style="display: none;"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div @click.outside="open = false"
         class="bg-[#090d16] w-full max-w-sm rounded-xl border border-slate-800/90 shadow-2xl p-4 transform transition-all"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="flex items-start gap-3">
            <div class="p-2 bg-red-500/10 text-red-400 rounded-lg border border-red-500/20 shrink-0">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-xs font-bold text-white uppercase tracking-wider mt-0.5" x-text="title"></h3>
                <p class="text-[11px] text-slate-400 mt-1 leading-relaxed" x-text="text"></p>
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-800/40 mt-4">
            <button type="button"
                    @click="open = false"
                    class="px-3 py-1.5 bg-slate-900 border border-slate-800/80 text-[11px] font-medium rounded-lg text-slate-400 hover:text-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button"
                    @click="document.getElementById(formId).submit(); open = false;"
                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-[11px] font-semibold rounded-lg transition-colors shadow-lg shadow-red-500/10">
                Confirmer la suppression
            </button>
        </div>
    </div>
</div>
