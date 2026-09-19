@php
    $icons = $getIcons();
    $current = $getState() ?? '';
    $statePath = $getStatePath();
@endphp

<div x-data="{
        open: false,
        search: '',
        selected: @entangle($statePath),
        icons: @js($icons),
        get filtered() {
            if (!this.search) return this.icons;
            let s = this.search.toLowerCase();
            return this.icons.filter(i => i.toLowerCase().includes(s));
        }
    }">
    <input type="hidden" name="{{ $statePath }}" x-model="selected" />

    <label class="block text-sm font-medium text-gray-700 mb-1">Ícone</label>

    <div class="flex items-center gap-3 mb-2">
        <button type="button" @click="open = true"
            class="flex items-center gap-3 rounded-lg border border-gray-300 px-4 py-3 hover:border-primary-500 transition bg-white">
            @if($current)
                <x-filament::icon :icon="$current" class="w-6 h-6 text-primary-600" />
                <span class="text-sm font-medium text-gray-700">{{ $current }}</span>
            @else
                <span class="text-sm font-medium text-gray-400">Clique para selecionar um ícone</span>
            @endif
        </button>
        @if($current)
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
                    @foreach($icons as $icon)
                        <button type="button"
                            x-show="!search || '{{ $icon }}'.toLowerCase().includes(search.toLowerCase())"
                            @click="selected = '{{ $icon }}'; open = false"
                            class="flex flex-col items-center justify-center p-2 rounded-lg border transition hover:border-primary-500 hover:bg-primary-50 {{ $current === $icon ? 'border-primary-600 bg-primary-50' : 'border-gray-200' }}"
                            :class="selected === '{{ $icon }}' ? 'border-primary-600 bg-primary-50' : 'border-gray-200'">
                            <x-filament::icon :icon="$icon" class="w-6 h-6 text-gray-700" />
                            <span class="text-[8px] text-gray-400 mt-1 truncate w-full text-center">{{ $icon }}</span>
                        </button>
                    @endforeach
                </div>
                <p x-show="filtered.length === 0" class="text-center text-gray-400 py-8">Nenhum ícone encontrado.</p>
            </div>
            <div class="px-6 py-3 border-t flex items-center justify-between text-sm text-gray-500">
                <span x-text="filtered.length + ' ícones'"></span>
                <button type="button" @click="open = false" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 font-medium">Fechar</button>
            </div>
        </div>
    </div>
</div>
