<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RespostaRating extends Mailable
{
    use Queueable, SerializesModels;

    protected $Rating;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($rating)
    {
        $this->Rating   = $rating;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lojaNome       = env('APP_NAME');
        $rating         = $this->Rating;
        return $this
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->subject("Obrigado por sua Opinião!")
            ->view('Mail.RespostaRating', compact('lojaNome', 'rating'));
    }
}
