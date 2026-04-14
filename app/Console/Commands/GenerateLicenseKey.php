<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\TrialManager;

class GenerateLicenseKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:generate {install_id : L\'identifiant d\'installation affiché sur l\'application du client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère une clé de licence pour un identifiant d\'installation donné';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $installID = $this->argument('install_id');

        if (!$installID) {
            $this->error('Veuillez fournir un identifiant d\'installation.');
            return 1;
        }

        $key = TrialManager::generateLicenseKey($installID);

        $this->info('--- GÉNÉRATEUR DE LICENCE DÉPÔT GAZ ---');
        $this->line('Identifiant: ' . $installID);
        $this->info('Clé de Licence: ' . $key);
        $this->line('---------------------------------------');
        
        return 0;
    }
}
