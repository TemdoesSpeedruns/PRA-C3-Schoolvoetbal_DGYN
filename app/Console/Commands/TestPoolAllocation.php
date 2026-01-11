<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tournament;
use App\Models\School;
use App\Services\PoolAllocationService;

class TestPoolAllocation extends Command
{
    protected $signature = 'test:pool-allocation';
    protected $description = 'Test the pool allocation system';

    protected $poolService;

    public function __construct(PoolAllocationService $poolService)
    {
        parent::__construct();
        $this->poolService = $poolService;
    }

    public function handle()
    {
        $this->info('🧪 Testing Pool Allocation System');
        $this->line('');

        // Create or get tournament
        $tournament = Tournament::firstOrCreate(
            ['name' => 'Test Tournament 2026'],
            [
                'type' => 'voetbal',
                'year' => 2026,
                'status' => 'active'
            ]
        );

        $this->info("✓ Tournament created/found: {$tournament->name} (ID: {$tournament->id})");

        // Count available schools
        $availableCount = School::where('status', 'approved')
            ->whereNull('pool_id')
            ->count();

        $this->info("✓ Available approved schools: $availableCount");

        if ($availableCount === 0) {
            $this->warn('No available schools to allocate!');
            return;
        }

        // Run pool allocation
        $this->line('');
        $this->info('📋 Starting pool allocation...');
        
        $result = $this->poolService->allocateSchoolsToPoolsAndCreateMatches($tournament);

        if ($result['success']) {
            $this->info("✓ {$result['message']}");
            $this->info("  - Poules created: {$result['pools_created']}");
            $this->info("  - Matches created: {$result['matches_created']}");
        } else {
            $this->error("✗ {$result['message']}");
            return;
        }

        // Show pool summary
        $this->line('');
        $this->info('📊 Pool Summary:');
        
        $pools = $tournament->pools()->with('schools')->get();
        
        foreach ($pools as $pool) {
            $schoolCount = $pool->schools()->count();
            $this->line("  • {$pool->name}: $schoolCount scholen");
            
            foreach ($pool->schools as $school) {
                $this->line("    - {$school->name} (Cat. {$school->category})");
            }
        }

        $this->line('');
        $this->info('✅ Test completed successfully!');
    }
}
