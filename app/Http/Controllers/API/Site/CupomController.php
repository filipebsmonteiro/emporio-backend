<?php


namespace App\Http\Controllers\API\Site;


use App\Http\Controllers\API\ApiController;
use App\Models\Cupom;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CupomController extends ApiController
{
    public function __construct(Cupom $cupom)
    {
        parent::__construct($cupom);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function valida(Request $request)
    {
        $codigo = explode('-',  $request->text);
        if ( !isset($codigo[0]) || !isset($codigo[1]) ){
            throw new BadRequestHttpException('Cupom Inválido! Padrão: X-xxxxx', null, 400);
        }
        $cupom = $this->validaRegrasCupom($codigo[0], $codigo[1]);

        switch (strtoupper($cupom->codigo)) {
            case 'A':
            case 'G':
                return response()->json([
                    'message'       => "Cupom válido!",
                    'description'   => "Sua taxa de entrega será zerada ao finalizar o pedido."
                ]);
                break;
            case 'B':
            case 'H':
                return response()->json([
                    'message'       => "Cupom válido!",
                    'description'   => "Seu pedido terá desconto de ".$cupom->porcentagem."% ao finalizar o pedido."
                ]);
                break;
            case 'C':
            case 'I':
                return response()->json([
                    'message'       => "Cupom válido!",
                    'description'   => "Seu pedido terá desconto de R$ ".number_format($cupom->valor, 2, ',', '.')." ao finalizar o pedido."
                ]);
                break;
            case 'D':
            case 'J':
                return response()->json([
                    'message'       => "Cupom válido!",
                    'description'   => "Certifique-se que seu pedido tenha algum produto da categoria: ".$cupom->categoria->nome."."
                ]);
                break;
            case 'E':
            case 'K':
                return response()->json([
                    'message'       => "Cupom válido!",
                    'description'   => "Certifique-se que seu pedido tenha o produto : ".$cupom->produto->nome."."
                ]);
                break;
        }
    }

    /**
     * @param $codigo
     * @param $consulta
     * @return Cupom|null
     */
    public function validaRegrasCupom($codigo, $consulta): ?Cupom
    {
        $cupom = $this->Model->where('codigo', $codigo)
            ->where('hash', $consulta)
            ->orWhere('numerador', $consulta)
            ->first();

        if ( !$cupom ){
            throw new BadRequestHttpException('Cupom inexistente!', null, 400);
        }
        if ( $cupom->usado ){
            throw new BadRequestHttpException('Cupom já foi utilizado!', null, 400);
        }
        if ( Carbon::today() > $cupom->validade  ){
            throw new BadRequestHttpException('Cupom vencido!', null, 400);
        }

        $array_codigo = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'
        ];
        if ( in_array($cupom->codigo, $array_codigo) && $cupom->pedidos->count() >= $cupom->quantidade ){
            throw new BadRequestHttpException(
                'Limite de utilizações do Cupom: '.$cupom->hash.' excedido!',
                null,
                400
            );
        }

        return $cupom;
    }
}
