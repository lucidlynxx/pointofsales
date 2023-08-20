<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Sale created';
    }

    protected function afterCreate(): void
    {
        Notification::make()
            ->warning()
            ->title('Cetak struk pembayaran!')
            ->body('Klik cetak untuk melanjutkan.')
            ->persistent()
            ->actions([
                Action::make('Cetak')
                    ->button()
                    ->url(route('pdfItem'), shouldOpenInNewTab: true),
            ])
            ->send();
    }
}
