<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $roles = $data['roles'];
        unset($data['roles']);

        $this->record = $this->handleRecordCreation($data);
        $this->record->syncRoles($roles);

        return $data;
    }
}
