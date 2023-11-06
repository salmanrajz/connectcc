<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class RejectMail extends Mailable
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // use Queueable, SerializesModels;
    public $detail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($detail)
    {
        //
        $this->detail = $detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Rejection - ' . $this->detail['selected_number'])
        ->view('email.comments');
        // return $this->view('view.name');
    }
}
