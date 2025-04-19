<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class DeleteUnverifiedUsers extends Command
{
    protected $signature = 'users:delete-unverified';
    protected $description = '3 kundan beri email tasdiqlamagan foydalanuvchilarni o‘chirish';

    public function handle()
    {
        $users = User::whereNull('email_verified_at')
            ->where('created_at', '<', Carbon::now()->subDays(3))
            ->get();

        foreach ($users as $user) {
            $user->delete();
            $this->info("Foydalanuvchi o‘chirildi: {$user->email}");
        }
    }
}
