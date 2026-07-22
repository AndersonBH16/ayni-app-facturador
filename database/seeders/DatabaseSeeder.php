<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\ListaPrecio;
use App\Models\ListaPrecioItem;
use App\Models\OfertaProducto;
use App\Models\Producto;
use App\Models\Rubro;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Empresas demo (dos, para probar el marketplace con precios distintos)
        $empresaA = Empresa::factory()->create(['razon_social' => 'Ayni Mikhuna Corporation']);
        $empresaB = Empresa::factory()->create(['razon_social' => 'Distribuidora Norte S.A.C.']);

        Sucursal::factory()->create([
            'empresa_id' => $empresaA->id,
            'codigo' => '0001',
            'nombre' => 'Sede Principal',
        ]);
        Sucursal::factory()->create([
            'empresa_id' => $empresaB->id,
            'codigo' => '0001',
            'nombre' => 'Sede Principal',
        ]);

        // 2. Rubros (por empresa, como ya estaba)
        $rubroConstruccionA = Rubro::factory()->create(['empresa_id' => $empresaA->id, 'nombre' => 'Construcción']);
        $rubroRetailA = Rubro::factory()->create(['empresa_id' => $empresaA->id, 'nombre' => 'Retail']);
        $rubroRetailB = Rubro::factory()->create(['empresa_id' => $empresaB->id, 'nombre' => 'Retail']);

        // 3. Listas de precio (por empresa)
        $listaMayoristaA = ListaPrecio::factory()->create(['empresa_id' => $empresaA->id, 'nombre' => 'Mayorista']);

        // 4. Categorías (globales, sin empresa_id)
        $categorias = collect(['Insumos', 'Cocina', 'Tecnología', 'Hogar', 'Bebidas', 'Limpieza e Higiene'])
            ->map(fn ($nombre) => Categoria::create([
                'nombre' => $nombre,
                'slug' => Str::slug($nombre),
            ]));

        // 5. Productos (catálogo genérico, sin empresa ni precio)
        $productos = Producto::factory()->count(20)->create([
            'categoria_id' => fn () => $categorias->random()->id,
            'imagen' => fn () => 'https://picsum.photos/seed/'.Str::random(8).'/600/600',
        ]);

        // 6. Ofertas: la Empresa A vende TODOS los productos.
        //    La Empresa B vende solo la mitad, con precio distinto para los mismos productos
        //    (así puedes probar la comparación de precios entre empresas, como el ejemplo de la lavadora).
        $ofertasA = $productos->map(fn ($producto) => OfertaProducto::create([
            'empresa_id' => $empresaA->id,
            'producto_id' => $producto->id,
            'precio_base' => fake()->randomFloat(2, 10, 500),
            'controla_stock' => true,
            'stock_disponible' => fake()->numberBetween(0, 200),
        ]));

        $productos->random(10)->each(function ($producto) use ($empresaB) {
            OfertaProducto::create([
                'empresa_id' => $empresaB->id,
                'producto_id' => $producto->id,
                'precio_base' => fake()->randomFloat(2, 10, 500),
                'controla_stock' => true,
                'stock_disponible' => fake()->numberBetween(0, 200),
            ]);
        });

        // 7. Precio especial: asigna precio de mayorista a la mitad de las ofertas de la Empresa A
        foreach ($ofertasA->random(10) as $oferta) {
            ListaPrecioItem::factory()->create([
                'lista_precio_id' => $listaMayoristaA->id,
                'oferta_producto_id' => $oferta->id,
                'precio' => round($oferta->precio_base * 0.85, 2),
            ]);
        }

        // 8. Clientes: uno especial y cinco generales, todos bajo la Empresa A
        Cliente::factory()->create([
            'empresa_id' => $empresaA->id,
            'tipo_cliente' => 'especial',
            'lista_precio_id' => $listaMayoristaA->id,
            'rubro_id' => $rubroConstruccionA->id,
        ]);

        Cliente::factory()->count(5)->create([
            'empresa_id' => $empresaA->id,
            'tipo_cliente' => 'general',
            'rubro_id' => $rubroRetailA->id,
        ]);

        // Un cliente adicional bajo la Empresa B, para probar multiempresa real
        Cliente::factory()->create([
            'empresa_id' => $empresaB->id,
            'tipo_cliente' => 'general',
            'rubro_id' => $rubroRetailB->id,
        ]);

        // 9. Usuario interno (staff) para entrar al panel admin
        User::factory()->create([
            'empresa_id' => $empresaA->id,
            'name' => 'Admin Demo',
            'email' => 'admin@demo.test',
        ]);

        $this->command->info('Datos de prueba creados. Usuario: admin@demo.test');
    }
}
