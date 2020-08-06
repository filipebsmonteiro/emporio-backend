<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SACMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $dados;

    public function __construct($requestData)
    {
        $this->dados    = $requestData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $dados          = $this->dados;
        return $this->from($this->dados->email)
                    ->subject("SAC Loja Virtual")
                    ->view('Mail.SAC', compact('dados'));
    }
}
