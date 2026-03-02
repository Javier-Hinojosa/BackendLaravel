<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearOldUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:clear-old-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina los archivos subidos a la capeta tempfiles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folderPath = public_path('tempfiles');
        if (!File::exists($folderPath)) {
            $this->error('La carpeta de archivos temporales no existe.');
            return Command::FAILURE;
        }

        $files = File::files($folderPath);

        if (empty($files)) {
            $this->info('No hay archivos temporales para eliminar.');
            return Command::SUCCESS;
        }

        foreach ($files as $file) {
                File::delete($file);
                $this->info("Archivo eliminado: {$file->getFilename()}");
        }

        $this->info('Todos los archivos temporales han sido eliminados.');
        return Command::SUCCESS;
    }
}