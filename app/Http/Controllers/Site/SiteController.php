<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\SACMailRequest;
use App\Mail\SACMail;
use App\Models\Ceps;
use App\Models\Ceps_loja;
use App\Models\EnderecoCliente;
use App\Models\Ingrediente;
use App\Models\Loja;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categoria_produto;
use Carbon\Carbon;
use App\Models\Produto;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class SiteController extends Controller
{
	public function excel()
    {
        ini_set('max_execution_time', 900); //15 minutes
        ini_set('memory_limit', '2048M');//'8192M'); //Change Performance 8Gb

        $xlsx = new SimpleXLSX('ExcelPHP/CEPS.xlsx');
        if ($xlsx->success()) {
            $rows = $xlsx->rows();
        } else {
            return 'xlsx error: '.$xlsx->error();
        }

        $CepObj     = new Ceps();
        $cepLoja    = new Ceps_loja();

        try{
            foreach ($rows as $index => $row) {
//                Ignora Primeira Linha
                if($index==0)
                    continue;

                $taxa   = 0;//(($row[2]!= null) ? $row[2] : 0);
                $cepCriado  = $CepObj->create([
                    'CEP'               => $row[0],
                    'logradouro'        => $row[1],
                    'complemento'       => $row[2],
                    'local'             => $row[3],
                    'bairro'            => $row[4],
                    'taxa_entrega'       => $taxa,
                    'vlr_minimo_pedido' => 5
                ]);
//                if ($row[1] == 'INCLUSION'){
                    $cepLoja->create([
                        'Lojas_idLojas' => 1,
                        'Ceps_idCeps'   => $cepCriado->id
                    ]);
//                }
            }
            return "Foram Importados ".sizeof($rows)." CEPs Com Sucesso, Confira o Banco de Dados !!!";
        } catch (\Illuminate\Database\QueryException $e) {
            return $e->getMessage();
        }
        //return "Funcionalidade desabilitada por Segurança!";
    }

}
