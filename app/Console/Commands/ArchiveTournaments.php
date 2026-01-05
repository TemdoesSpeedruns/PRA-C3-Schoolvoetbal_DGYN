<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tournament;

class ArchiveTournaments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'archive:tournaments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive tournaments older than one month';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cutoff = now()->subMonth();

        $count = Tournament::whereNull('archived_at')
            ->where('created_at', '<', $cutoff)
            ->update(['archived_at' => now()]);

        $this->info("Archived {$count} tournaments.");

        return 0;
    }
}
