<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RatingMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $Pedido;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pedido)
    {
        $this->Pedido   = $pedido;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $lojaNome       = env('APP_NAME');
        $idCliente      = $this->Pedido->Clientes_idClientes;
        $idPedido       = $this->Pedido->id;
        return $this
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->subject("Sua opinião")
            ->view('Mail.Rating', compact('lojaNome', 'idCliente', 'idPedido'));
    }
}
