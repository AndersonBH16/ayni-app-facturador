<?php

namespace App\Livewire\Storefront;

use App\Models\PortalUsuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class Registro extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:portal_usuarios,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }

    public function registrar(): void
    {
        $this->validate();

        $portalUsuario = PortalUsuario::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'activo' => true,
        ]);

        Auth::guard('portal')->login($portalUsuario);

        $this->redirect(route('storefront.catalogo'), navigate: true);
    }

    public function render()
    {
        return view('livewire.storefront.registro');
    }
}
