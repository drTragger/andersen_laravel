<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:comments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all comments to their owners to email';

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
//        $data = User::join('comments', 'comments.user_id', '=', 'users.id')->get(); // Getting users and comments using join
        $users = User::with('comments')->get(); // Getting users and comments using Laravel relations

        foreach ($users->all() as $user) {
            if (!empty($datum->comments->all())) {
                Mail::to($user->email)->send(new \App\Mail\SendComments($user->comments->all()));
            }
        }

        echo "Emails sent successfully\n";

        return 0;
    }
}
