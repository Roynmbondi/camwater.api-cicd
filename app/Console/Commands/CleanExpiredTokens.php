<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TokenBlacklist;

class CleanExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les tokens expirés de la blacklist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Nettoyage des tokens expirés...');
        
        $count = TokenBlacklist::cleanExpired();
        
        $this->info("✅ {$count} token(s) expiré(s) supprimé(s) de la blacklist.");
        
        return Command::SUCCESS;
    }
}
