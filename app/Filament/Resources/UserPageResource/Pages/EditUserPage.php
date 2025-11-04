<?php

namespace App\Filament\Resources\UserPageResource\Pages;

use App\Filament\Resources\UserPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserPage extends EditRecord
{
    protected static string $resource = UserPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
