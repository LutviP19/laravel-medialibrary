<?php

namespace App\Filament\Resources\TestingResource\Pages;

use App\Filament\Resources\TestingResource;
use App\Filament\Resources\TestingResource\Widgets\TestingOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestings extends ListRecords
{
    protected static string $resource = TestingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TestingOverview::class,
        ];
    }
}
