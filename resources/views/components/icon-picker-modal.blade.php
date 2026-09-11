@props([
    'statePath' => null,
    'state' => null,
])

<div x-data="{
    selected: @entangle($statePath),
    open: false,
    search: '',
    icons: @js($allIcons),
    get filtered() {
        if (!this.search) return this.icons;
        return this.icons.filter(i => i.includes(this.search));
    },
    select(icon) {
        this.selected = icon;
        this.open = false;
        this.$dispatch('input', icon);
    }
}">
    <div class="flex items-center gap-3">
        <button type="button" @click="open = true"
            class="flex items-center gap-3 rounded-lg border border-gray-300 px-4 py-3 hover:border-primary-500 transition bg-white">
            <template x-if="selected">
                <span x-html="''" class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:ignore>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700" x-text="selected"></span>
                </span>
            </template>
            <template x-if="!selected">
                <span class="text-sm font-medium text-gray-400">Clique para selecionar um ícone</span>
            </template>
        </button>
        @if($state)
            <button type="button" @click="selected = ''" class="text-sm text-gray-400 hover:text-red-500">Remover</button>
        @endif
    </div>

    <div x-show="open" x-cloak
         x-transition.opacity
         class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         @keydown.escape.window="open = false">
        <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
        <div class="relative z-10 w-full max-w-4xl max-h-[80vh] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-bold text-gray-800">Selecionar ícone</h3>
                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="px-6 py-3 border-b">
                <input type="text" x-model="search"
                    placeholder="Buscar ícone (opcional)..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 outline-none" />
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-6 sm:grid-cols-8 md:grid-cols-10 lg:grid-cols-12 gap-2">
                    <template x-for="icon in filtered" :key="icon">
                        <button type="button" @click="select(icon)"
                            class="flex flex-col items-center justify-center p-2 rounded-lg border transition hover:border-primary-500 hover:bg-primary-50"
                            :class="selected === icon ? 'border-primary-600 bg-primary-50' : 'border-gray-200'">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:ignore>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-[8px] text-gray-400 mt-1 truncate w-full text-center" x-text="icon"></span>
                        </button>
                    </template>
                </div>
                <template x-if="filtered.length === 0">
                    <p class="text-center text-gray-400 py-8">Nenhum ícone encontrado.</p>
                </template>
            </div>
            <div class="px-6 py-3 border-t flex items-center justify-between text-sm text-gray-500">
                <span x-text="filtered.length + ' ícones'"></span>
                <button type="button" @click="open = false" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 font-medium">Fechar</button>
            </div>
        </div>
    </div>
</div>
