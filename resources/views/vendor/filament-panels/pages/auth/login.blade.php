<x-filament-panels::page.simple>
    
    {{-- [PERBAIKAN] Logo dipindah ke atas, di luar card --}}
    <x-slot name="subheading">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo Koperasi" 
                 class="h-16 w-16 object-cover rounded-full shadow-lg mb-3 mx-auto">
        </a>
    </x-slot>

    {{-- [PERBAIKAN] Hapus blok <div>...</div> yang menampilkan logo dan teks duplikat dari sini --}}
    
    {{-- Link "Register" (Dihilangkan) --}}
    @if (filament()->hasRegistration())
        {{-- <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}
            {{ $this->registerAction }}
        </x-slot> --}}
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        {{-- Link Lupa Password? --}}
        @if (filament()->hasPasswordReset())
            <div class="flex justify-end mt-4">
                {{ $this->resetPasswordAction }}
            </div>
        @endif

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}

    {{-- Link "Kembali ke Halaman Utama" di luar card --}}
    <div class="text-center mt-8 text-sm">
        <a href="{{ url('/') }}" class="kembali-link">
            <i class="fas fa-arrow-left fa-xs mr-1"></i>
            Kembali ke Halaman Utama
        </a>
    </div>

</x-filament-panels::page.simple>