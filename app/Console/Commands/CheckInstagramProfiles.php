<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Helpers\InstagramHelper;

class CheckInstagramProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage: php artisan instagram:check
     */
    protected $signature = 'instagram:check {--delete : Delete users with invalid Instagram accounts}';

    /**
     * The console command description.
     */
    protected $description = 'Check users\' Instagram accounts and optionally delete invalid ones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNotNull('instagram_id')->where('role','user')->get();
        $deleted = 0;

        foreach ($users as $user) {
            $exists = InstagramHelper::exists($user->instagram_id);

            if ($exists) {
                $this->info("✅ {$user->instagram_id} exists");
            } else {
                $this->warn("❌ {$user->instagram_id} does not exist");

                if ($this->option('delete')) {
                    $user->delete();
                    $this->error("   → Deleted {$user->name}");
                    $deleted++;
                }
            }

            // sleep to avoid hitting Instagram rate-limits
            sleep(2);
        }

        if ($this->option('delete')) {
            $this->info("Cleanup finished. Deleted {$deleted} users.");
        } else {
            $this->info("Check finished. Run with --delete to remove invalid users.");
        }
    }
}
