<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    protected $fillable = [
        "ProofOfSale",          // Número da autorização, identico ao NSU.
        "Tid",                  // Id da transação na adquirente.
        "AuthorizationCode",    // Código de autorização.
        "PaymentId",            // Campo Identificador do Pedido.
        "Type",                 // Forma de pagamento (CreditCard)
        "Amount",               // Valor do Pedido (ser enviado em centavos).
        "Status",               // Status da Transação.
        "ReturnCode",           // Código de retorno da Adquirência.
        "ReturnMessage",        // Mensagem de retorno da Adquirência.
        "imagemComprovante",    // Imagem do comprovante
        'Pedidos_idPedidos'
    ];
}

