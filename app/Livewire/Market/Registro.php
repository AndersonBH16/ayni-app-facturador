<?php

namespace App\Livewire\Market;

use App\Models\MarketUsuario;
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

        $marketUsuario = MarketUsuario::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'activo' => true,
        ]);

        Auth::guard('market')->login($marketUsuario);

        $this->redirect(route('market.catalogo'), navigate: true);
    }

    public function render()
    {
        return view('livewire.market.registro');
    }
}
