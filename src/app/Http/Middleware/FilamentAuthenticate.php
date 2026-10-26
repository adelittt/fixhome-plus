<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as BaseAuthenticate;

class FilamentAuthenticate extends BaseAuthenticate
{
    /**
     * Alihkan pengguna yang belum login langsung ke satu pintu login utama (/login).
     */
    protected function redirectTo($request): ?string
    {
        return route('login');
    }
}
