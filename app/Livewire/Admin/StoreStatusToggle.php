<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\GlobalSetting;

class StoreStatusToggle extends Component
{
    public bool $isStoreOpen;
    public GlobalSetting $settingRecord;

    public function mount()
    {
        $this->settingRecord = GlobalSetting::firstOrNew([]);
        if (!$this->settingRecord->exists) {
            $this->settingRecord->is_store_open = true;
            $this->settingRecord->save();
        }

        $this->isStoreOpen = $this->settingRecord->is_store_open;
    }

    public function updatedIsStoreOpen(bool $value)
    {
        $this->settingRecord->is_store_open = $value;
        $this->settingRecord->save();

        // Kirim event untuk me-refresh tabel di Admin (untuk update tampilan)
        $this->dispatch('refreshFilamentTable'); 
    }

    public function render()
    {
        return view('livewire.admin.store-status-toggle');
    }
}