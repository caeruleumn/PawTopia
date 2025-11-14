<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pet;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class FixOrphanedPets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:orphaned-pets {--delete : Delete orphaned pets instead of reassigning}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix pets with invalid member_id (orphaned pets)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking for orphaned pets...');
        
        // Find orphaned pets
        $orphanedPets = DB::table('pets')
            ->leftJoin('members', 'pets.member_id', '=', 'members.id')
            ->whereNull('members.id')
            ->select('pets.*')
            ->get();
        
        if ($orphanedPets->isEmpty()) {
            $this->info('✅ No orphaned pets found. Database is clean!');
            return 0;
        }
        
        $this->warn("Found {$orphanedPets->count()} orphaned pet(s):");
        
        foreach ($orphanedPets as $pet) {
            $this->line("  - Pet ID: {$pet->id}, Name: {$pet->name}, Invalid Owner ID: {$pet->member_id}");
        }
        
        if ($this->option('delete')) {
            // Delete orphaned pets
            if ($this->confirm('⚠️  Delete all orphaned pets?', false)) {
                $deleted = DB::table('pets')
                    ->leftJoin('members', 'pets.member_id', '=', 'members.id')
                    ->whereNull('members.id')
                    ->delete();
                
                $this->info("🗑️  Deleted {$deleted} orphaned pet(s)");
            }
        } else {
            // Try to reassign to first available member
            $firstMember = Member::orderBy('id')->first();
            
            if (!$firstMember) {
                $this->error('❌ No valid members found in database!');
                return 1;
            }
            
            $this->info("Found valid member: {$firstMember->name} (ID: {$firstMember->id})");
            
            if ($this->confirm("Reassign all orphaned pets to {$firstMember->name}?", false)) {
                $updated = DB::table('pets')
                    ->leftJoin('members', 'pets.member_id', '=', 'members.id')
                    ->whereNull('members.id')
                    ->update(['pets.member_id' => $firstMember->id]);
                
                $this->info("✅ Reassigned {$updated} pet(s) to {$firstMember->name}");
            }
        }
        
        return 0;
    }
}
