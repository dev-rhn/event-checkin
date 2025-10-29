<?php

namespace App\Filament\Resources\ParticipantResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ParticipantResource;

class ListParticipants extends ListRecords
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {

        $scannerAction = Action::make('scanner')
            ->label('Scanner QRCode')
            ->url('/admin/participants/scanner') 
            ->icon('heroicon-o-qr-code')
            ->color('gray'); 
        return [
            $scannerAction,
            Actions\CreateAction::make(),
        ];
    }
}
