<?php

namespace App\Filament\Store\Widgets;

use Filament\Widgets\Widget;

class StoreWelcomeWidget extends Widget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected static string $view = 'filament.store.widgets.store-welcome-widget';

    public function getViewData(): array
    {
        $user = auth()->user();
        $store = $user?->store;

        return [
            'user' => $user,
            'store' => $store,
        ];
    }
}
