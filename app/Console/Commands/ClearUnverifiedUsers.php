<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class ClearUnverifiedUsers extends Command
{
    protected $signature = 'users:clear-unverified';

    protected $description = '3 kundan beri email tasdiqlamagan userlarni ochirish';

    public function handle()
    {
        $deleted = User::whereNull('email_verified_at')
            ->where('created_at', '<=', now()->subDays(3))
            ->delete();

        $this->info("$deleted ta tasdiqlanmagan user ochirildi.");
    }
}
