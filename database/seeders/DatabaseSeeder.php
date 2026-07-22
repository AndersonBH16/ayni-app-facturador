<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\ListaPrecio;
use App\Models\ListaPrecioItem;
use App\Models\Producto;
use App\Models\Rubro;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Empresa demo (tenant)
        $empresa = Empresa::factory()->create([
            'razon_social' => 'Empresa Demo S.A.C.',
        ]);

        Sucursal::factory()->create([
            'empresa_id' => $empresa->id,
            'codigo' => '0001',
            'nombre' => 'Sede Principal',
        ]);

        // 2. Rubros
        $rubroConstruccion = Rubro::factory()->create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Construcción',
        ]);
        $rubroRetail = Rubro::factory()->create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Retail',
        ]);

        // 3. Lista de precio especial
        $listaMayorista = ListaPrecio::factory()->create([
            'empresa_id' => $empresa->id,
            'nombre' => 'Mayorista',
        ]);

        // 4. Productos
        $productos = Producto::factory()->count(10)->create([
            'empresa_id' => $empresa->id,
        ]);

        // Asignar precio especial (15% menos) a la mitad de los productos
        foreach ($productos->random(5) as $producto) {
            ListaPrecioItem::factory()->create([
                'lista_precio_id' => $listaMayorista->id,
                'producto_id' => $producto->id,
                'precio' => round($producto->precio_base * 0.85, 2),
            ]);
        }

        // 5. Clientes: uno especial, cinco generales
        Cliente::factory()->create([
            'empresa_id' => $empresa->id,
            'tipo_cliente' => 'especial',
            'lista_precio_id' => $listaMayorista->id,
            'rubro_id' => $rubroConstruccion->id,
        ]);

        Cliente::factory()->count(5)->create([
            'empresa_id' => $empresa->id,
            'tipo_cliente' => 'general',
            'rubro_id' => $rubroRetail->id,
        ]);

        // 6. Usuario interno para entrar al sistema
        User::factory()->create([
            'empresa_id' => $empresa->id,
            'name' => 'Admin Demo',
            'email' => 'admin@demo.test',
        ]);

        $this->command->info('Datos de prueba creados. Usuario: admin@demo.test');
    }
}
