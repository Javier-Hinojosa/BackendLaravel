<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ProductInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-info 
                            {productId : The ID of the product to display} 
                            {--details : Show detailed information about the product}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta un producto por su ID desde la base de datos y muestra su información básica o detallada según la opción proporcionada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productId = $this->argument('productId');
        $details = $this->option('details');

        // Simulación de consulta a la base de datos
        $product = Product::find($productId);

        if(!is_numeric($productId) || $productId <= 0) {
            $this->error('El ID del producto debe ser un número');
            return Command::FAILURE;
        }

        if (!$product) {
            $this->error('Producto no encontrado');
            return Command::FAILURE;
        }

        if ($details) {
            $this->info('Información detallada del producto:');
            $this->line('ID: ' . $product->id);
            $this->line('Nombre: ' . $product->name);
            $this->line('Descripción: ' . $product->description);
            $this->line('Precio: ' . $product->price);
            $this->line('Stock: ' . $product->stock);
        } else {
            $this->info('Información básica del producto:');
            $this->line('ID: ' . $product->id);
            $this->line('Nombre: ' . $product->name);
            $this->line('Precio: ' . $product->price);
        }


        return Command::SUCCESS;
    }
}
