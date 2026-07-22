<?php

namespace App\Livewire\Storefront;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public string $error = '';

    public function ingresar(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::guard('portal')->attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->error = 'Credenciales incorrectas.';
            return;
        }

        $this->redirect(route('storefront.catalogo'), navigate: true);
    }

    public function render()
    {
        return view('livewire.storefront.login');
    }
}
