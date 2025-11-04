<?php

namespace App\Filament\Resources\UserPageResource\Pages;

use App\Filament\Resources\UserPageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserPage extends CreateRecord
{
    protected static string $resource = UserPageResource::class;
}
