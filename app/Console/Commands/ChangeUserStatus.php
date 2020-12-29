<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ChangeUserStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:change-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Changes status of users who are inactive to active';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $inactiveUsers = User::where('status', '=', User::INACTIVE)->get();

        foreach ($inactiveUsers as $inactiveUser) {
            $inactiveUser->status = User::ACTIVE;
            $inactiveUser->update();
        }

        echo "Statuses were changed successfully\n";

        return 0;
    }
}
