<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CEPSuggestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $dados;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        return $this->from(env('MAIL_USERNAME'))
            ->subject("Sugestão de CEP")
            ->view('Mail.CEPSuggest', compact('dados'));
    }
}
