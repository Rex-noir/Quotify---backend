<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use App\Enums\PostStatus;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = ['all' => Tab::make('All')];

        foreach (PostStatus::cases() as $status) {
            $tabs[$status->value] = Tab::make(ucfirst($status->value))->modifyQueryUsing(fn (Builder $query) => $query->where('status', $status->value))
                ->badge(Post::query()->where('status', $status->value)->count())
                ->badgeColor('success');
        }

        return $tabs;
    }
}
