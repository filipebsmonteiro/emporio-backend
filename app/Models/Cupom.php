<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    protected $fillable =[
        'codigo',
        'hash',
        'numerador',
        'quantidade',
        'valor',
        'porcentagem',
        'Cat_produtos_idCat_produtos',
        'Produtos_idProdutos',
        'validade',
        'usado'
    ];

    /**
     * @var array
     * Variavel define valores para calculo de validação dos cupons
     * Baseado no Tipo
     */


    protected $codigoCalculo = [
        'N'     => 3,
        'O'     => 5,
        'P'     => 7,
        'Q'     => 9,
        'R'     => 11,
        'S'     => 13,
        'T'     => 2,
        'U'     => 4,
        'V'     => 6,
        'W'     => 8,
        'X'     => 10,
        'Y'     => 12,
        'Z'     => 14
    ];

    protected $codigosPromocionais = [
        'A'     => 'Cupom de Texto para Taxa de entrega',   //Ex.A-entregaFree
        'B'     => 'Cupom de Texto para Porcentagem',       //Ex.B-201810%OFF
        'C'     => 'Cupom de Texto para Valor',             //Ex.C-Cliente20.50
        'D'     => 'Cupom de Texto para Categoria',         //Ex.D-PizzaGrandeGratis
        'E'     => 'Cupom de Texto para Produto',           //Ex.E-PizzaCalabresaGratis
        // 'F'     => '',
        // 'G'     => '',
        // 'H'     => '',
        // 'I'     => '',
        // 'J'     => '',
        // 'K'     => '',
        // 'L'     => '',
        // 'M'     => ''
    ];

    protected $codigosIndividuais = [
        //Cupons Individuais precisam de validação da hash
        'N'     => 'Cupom Individual para Taxa de entrega',     //Ex.N-1234567-89
        'O'     => 'Cupom Individual para Porcentagem',         //Ex.O-1234567-89
        'P'     => 'Cupom Individual para Valor',               //Ex.P-1234567-89
        'Q'     => 'Cupom Individual para Categoria',           //Ex.Q-1234567-89
        'R'     => 'Cupom Individual para Produto',             //Ex.R-1234567-89
        // 'S'     => '',
        // 'T'     => '',
        // 'U'     => '',
        // 'V'     => '',
        // 'W'     => '',
        // 'X'     => '',
        // 'Y'     => '',
        // 'Z'     => ''
    ];

    public function getCodPromocionais()
    {
        return $this->codigosPromocionais;
    }

    public function getCodIndividuais()
    {
        return $this->codigosIndividuais;
    }

    public function getCodCalculo()
    {
        return $this->codigoCalculo;
    }

    public function pedidos()
    {
        return
        $this->belongsToMany(
            Pedido::class,
            'pedido_cupoms',
            'Cupoms_idCupoms',
            'Pedidos_idPedidos'
        )->where('pedidos.status', '!=', 'Cancelado');
    }

    public function categoria()
    {
        return $this->hasOne(Categoria_produto::class, 'id', 'Cat_produtos_idCat_produtos');
    }

    public function produto()
    {
        return $this->hasOne(Produto::class, 'id', 'Produtos_idProdutos');
    }
}
