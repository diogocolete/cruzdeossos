<x-filament-panels::page.simple>
    <div class="flex flex-col items-center justify-center mb-6">
        <img src="{{ asset('images/logo-vetorial.png') }}" alt="Cruz de Ossos" class="h-24 w-auto object-contain mb-4" />
        <h2 class="text-xl font-bold text-center" style="color: #ED1C24;">Cruz de Ossos</h2>
        <p class="text-sm text-gray-500 text-center mt-1">Irmãos da estrada. Cavaleiros, história e irmandade.</p>
    </div>

    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
