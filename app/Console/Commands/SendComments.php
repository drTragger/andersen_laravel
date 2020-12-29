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
//        $data = DB::table('comments')->join('users', 'comments.user_id', '=', 'users.id')->get(); // Getting users and comments using join
        $data = Comment::with('user')->get(); // Getting users and comments using Laravel relations

        foreach ($data as $datum) {
            if ($datum->user->id === $datum->user_id) {
                $groupedComments[$datum->user->email][] =  ['text' => $datum->text, 'date' => $datum->created_at];
            }
        }

        if (isset($groupedComments)) {
            foreach ($groupedComments as $email => $comments) {
                Mail::to($email)->send(new \App\Mail\SendComments($comments));
            }
            echo "Emails sent successfully\n";
        } else {
            echo "No data to send\n";
        }

        return 0;
    }
}
