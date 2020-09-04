<?php


namespace App\Http\Controllers\Colibri;


use App\Models\Pedido;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Consumo
{
    /** @var string */
    private $token;
    /** @var PendingRequest */
    private $pendingRequest;

    public function __construct()
    {
        $this->token = base64_encode("usuario:senha");
        // $this->token = base64_encode("$usuario:$senha");

        $this->pendingRequest = Http::withHeaders([
            'Authorization' => "Basic $this->token",
            'Content-Type' => 'application/json; charset=utf-8'
        ]);
    }

    protected function generateUrl(string $urn = null, string $query = null): string
    {
        $servidor = env('COLIBRI_SERVER');
        $porta = env('COLIBRI_PORT');
        $GUID = env('COLIBRI_GUID');
        $url = "$servidor:$porta/v1/consumos/?api_key=$GUID";

        if ($urn) {
            $url = "$servidor:$porta/v1/consumos/$urn/?api_key=$GUID";
        }

        if ($query) {
            return "$url&$query";
        }

        return $url;
    }

    /**
     * Identificação do Cliente Configurado na empresa
     * @param string $identificacao
     */
    public function getNextTicket(string $identificacao)
    {
        $response = $this->pendingRequest
            ->withBody("{'cliente':{'identificacao': '$identificacao'}}", 'json')
            ->post($this->generateUrl('proximo', 'modo_venda=2&abre=true'));

        $response->dados->ticket_id;
    }

    public function lancarPedido(Pedido $pedido)
    {

    }
}
