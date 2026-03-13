<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LogService;

class CleanOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clean {days=90 : Nombre de jours à conserver}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les logs d\'activité de plus de X jours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->argument('days');
        
        $this->info("Nettoyage des logs de plus de {$days} jours...");
        
        $count = LogService::cleanOldLogs($days);
        
        $this->info("✅ {$count} log(s) supprimé(s).");
        
        return Command::SUCCESS;
    }
}
