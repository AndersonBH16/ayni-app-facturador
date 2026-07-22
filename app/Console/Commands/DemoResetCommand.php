<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DemoResetCommand extends Command
{
    protected $signature = 'demo:reset';

    protected $description = 'Refresca la base de datos entera y carga datos de prueba de un solo comando';

    public function handle(): int
    {
        $this->call('migrate:fresh');
        $this->call('db:seed');

        $this->info('Base de datos reiniciada con datos de prueba.');

        return self::SUCCESS;
    }
}
