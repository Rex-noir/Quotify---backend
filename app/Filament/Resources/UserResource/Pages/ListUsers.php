<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;


class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('all'),
            'active' => Tab::make('Users')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', function ($query) {
                    return $query->where('name', 'user');
                })),
            'inactive' => Tab::make('Admins')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })),
            'inactive' => Tab::make('Super Admins')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', function ($query) {
                    $query->where('name', 'super admin');
                })),
        ];
    }
}
