<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use SoftDeletes;

	protected $fillable = [
        'nome',
        'preco',
        'imagem',
        'status',
        'unidade_medida',
        'intervalo',
        'minimo_unidade',
        'codigo_PDV',
        'promocionar',
        'valorPromocao',
        'freteGratis',
        'Cat_produtos_idCat_produtos',
        'Lojas_idLojas',
        'domingo',
        'segunda',
        'terca',
        'quarta',
        'quinta',
        'sexta',
        'sabado',
        'disponibilidade',
        'inicio_periodo1',
        'termino_periodo1',
        'inicio_periodo2',
        'termino_periodo2',
        'tempo_producao'
	];

	public $name = 'produtos';

	public function categoria() {
		return $this->belongsTo(Categoria_produto::class, 'Cat_produtos_idCat_produtos', 'id');
	}

	public function ingredientes() {
		return $this->belongsToMany(Ingrediente::class, 'ingrediente_produtos', 'Produtos_idProdutos', 'Ingredientes_idIngredientes')
            ->withPivot('visibilidade');
	}

    public function multiplos() {
        return $this->belongsToMany(Ingrediente_multiplo::class, 'ing_multiplos_produtos', 'Produtos_idProdutos', 'Multiplos_idMultiplos')
            ->wherePivot('deleted_at', null);
    }

    public function produtosMultiplos() {
        return $this->belongsToMany(
            Produto_multiplo::class,
            'combos_prod_multiplos',
            'Produtos_idProdutos',
            'Prod_Multiplos_idProd_Multiplos'
        );
    }

    protected $disponibilidade = ['Sempre Disponível', '1 Turno', '2 Turnos'];

    protected $status = ['Disponível', 'Indisponível', 'Desabilitado'];

    public function getDisponibilidades(){
        return $this->disponibilidade;
    }

    public function getStatus(){
        return $this->status;
    }
}
