<?php

namespace App\Filament\Resources\ParticipantResource\Pages;

use App\Filament\Resources\ParticipantResource;
use App\Models\Participant;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;

class QrScanner extends Page
{
    protected static string $resource = ParticipantResource::class;

    protected static string $view = 'filament.resources.participant-resource.pages.qr-scanner';
    
    protected static ?string $title = 'Scan QR Code';
    
    // Ini akan membuat tab/link di halaman Participant
    protected static ?string $navigationLabel = 'Scanner';

    public $scannedCode = '';
    
    public $participantData = null;

    public function checkIn()
    {
        if (empty($this->scannedCode)) {
            Notification::make()
                ->title('Error')
                ->body('Kode QR tidak boleh kosong')
                ->danger()
                ->send();
            return;
        }

        $participant = Participant::where('unique_code', $this->scannedCode)->first();

        if (!$participant) {
            Notification::make()
                ->title('Peserta Tidak Ditemukan')
                ->body('Kode QR tidak valid atau peserta tidak terdaftar')
                ->danger()
                ->duration(5000)
                ->send();
            
            $this->participantData = null;
            $this->scannedCode = '';
            return;
        }

        if ($participant->is_checked_in) {
            Notification::make()
                ->title('⚠️ Sudah Check-in')
                ->body("Peserta {$participant->name} sudah melakukan check-in pada " . 
                       $participant->checked_in_at->format('d/m/Y H:i'))
                ->warning()
                ->duration(5000)
                ->send();
                
            $this->participantData = $participant;
            $this->scannedCode = '';
            return;
        }

        $participant->update([
            'is_checked_in' => true,
            'checked_in_at' => now(),
        ]);

        Notification::make()
            ->title('✅ Check-in Berhasil!')
            ->body("Selamat datang, {$participant->name}")
            ->success()
            ->duration(5000)
            ->send();

        $this->participantData = $participant;
        
        $this->dispatch('auto-reset');
    }

    public function resetScan()
    {
        $this->scannedCode = '';
        $this->participantData = null;
    }
}