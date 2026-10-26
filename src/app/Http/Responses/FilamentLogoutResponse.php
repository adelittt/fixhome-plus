<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;

class FilamentLogoutResponse implements Responsable
{
    /**
     * Alihkan pengguna setelah logout kembali ke halaman satu pintu login.
     */
    public function toResponse($request): RedirectResponse
    {
        return redirect()->route('login');
    }
}
