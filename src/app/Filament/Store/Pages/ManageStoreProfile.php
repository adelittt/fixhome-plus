<?php

namespace App\Filament\Store\Pages;

use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageStoreProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Profil Toko';

    protected static ?string $title = 'Kelola Profil Toko';

    protected static string $view = 'filament.store.pages.manage-store-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $store = auth()->user()?->store;

        if ($store) {
            $this->form->fill($store->attributesToArray());
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Toko')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Toko')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor Telepon Toko')
                            ->tel()
                            ->required(),
                        Forms\Components\TextInput::make('verification_status')
                            ->label('Status Verifikasi Toko')
                            ->disabled(),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Fisik Toko')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $store = auth()->user()?->store;

        if ($store) {
            $store->update([
                'name' => $state['name'],
                'phone' => $state['phone'],
                'address' => $state['address'],
            ]);

            Notification::make()
                ->title('Profil Toko Berhasil Diperbarui')
                ->success()
                ->send();
        }
    }
}
