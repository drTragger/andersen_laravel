<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeleteAccount extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;
    public $data;

    /**
     * Create a new message instance.
     *
     * @param $pdf
     * @param array $data
     */
    public function __construct($pdf, $data=[])
    {
        $this->pdf = $pdf;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('misha@laravel.com')->markdown('emails.account.delete')->attachData($this->pdf, 'delete_account.pdf')->with($this->data);
    }
}
