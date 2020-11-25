<?php

use Illuminate\Database\Seeder;
use App\Models\Categoria_ingrediente;
use App\Models\Categoria_produto;
use App\Models\Combos_prod_multiplo;
use App\Models\Ingrediente;
use App\Models\Produto;
use App\Models\Produto_multiplo;
use App\Models\Prod_multiplo_produto;
use App\Models\Ingrediente_produto;
use App\Models\Ingrediente_multiplo;
use App\Models\Cat_produtos_ing_multiplo;
use App\Models\Ingrediente_ing_multiplo;

class IngProdSegundaLojaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria_ingrediente::insert([
            ['nome' => 'Bebidas', 'Lojas_idLojas' => 2],
            ['nome' => 'Recheios', 'Lojas_idLojas' => 2],
            ['nome' => 'Pães', 'Lojas_idLojas' => 2],
            ['nome' => 'Sobremesas', 'Lojas_idLojas' => 2]
        ]);

        Ingrediente::insert([
            //Bebidas
            ['nome' => 'Coca Lata', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 1,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 2],
            ['nome' => 'Coca 600ml', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 2,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 2],
            ['nome' => 'Fanta Lata', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 3,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 2],
            ['nome' => 'Fanta 600ml', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 4,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 2],
            ['nome' => 'Suco Laranja', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 5,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 2],
            ['nome' => 'Suco Maracujá', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 6,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 2],
            ['nome' => 'Suco Uva', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 7,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 2],

            //Recheios
            ['nome' => 'Queijo', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 7,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 3],
            ['nome' => 'Presunto', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 8,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 3],
            ['nome' => 'Salsicha', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 9,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 3],
            ['nome' => 'Bacon', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 10,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 3],

            //Pães
            ['nome' => 'Pão Ciabata', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 11,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 4],
            ['nome' => 'Pão Francês', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 12,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 4],
            ['nome' => 'Pão Integral', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 13,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 4],

            //Sobremesas
            ['nome' => 'Pudim', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 11,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 5],
            ['nome' => 'Quindin', 'status' => 1,
                'preco' => '0', 'codigo_PDV' => 11,
                'Lojas_idLojas' => 2, 'Cat_ingredientes_idCat_ingredientes' => 5]
        ]);

        Categoria_produto::insert([
            ['nome' => 'Bebidas', 'layout' => 'Padrão',
                'permiteCombinacao' => false, 'quantidadeCombinacoes' => 0,
                'Lojas_idLojas' => 2],
            ['nome' => 'Sandubas', 'layout' => 'Padrão',
                'permiteCombinacao' => false, 'quantidadeCombinacoes' => 0,
                'Lojas_idLojas' => 2],
            ['nome' => 'Sobremesas', 'layout' => 'Padrão',
                'permiteCombinacao' => false, 'quantidadeCombinacoes' => 0,
                'Lojas_idLojas' => 2]
        ]);

        Produto::insert([
            [
                'nome' => 'Misto Quente',
                'preco' => 7,
                'imagem' => null,
                'status' => 'Disponível',
                'codigo_PDV' => 12,
                'Cat_produtos_idCat_produtos' => 5,
                'Lojas_idLojas' => 2,
                'domingo' => true,
                'segunda' => true,
                'terca' => true,
                'quarta' => true,
                'quinta' => true,
                'sexta' => true,
                'sabado' => true,
                'disponibilidade' => 'Sempre Disponível',
                'inicio_periodo1' => null,
                'termino_periodo1' => null,
                'inicio_periodo2' => null,
                'termino_periodo2' => null],
            [
                'nome' => 'Sanduiche Especial',
                'preco' => 15,
                'imagem' => null,
                'status' => 'Disponível',
                'codigo_PDV' => 12,
                'Cat_produtos_idCat_produtos' => 5,
                'Lojas_idLojas' => 2,
                'domingo' => true,
                'segunda' => true,
                'terca' => true,
                'quarta' => true,
                'quinta' => true,
                'sexta' => true,
                'sabado' => true,
                'disponibilidade' => 'Sempre Disponível',
                'inicio_periodo1' => null,
                'termino_periodo1' => null,
                'inicio_periodo2' => null,
                'termino_periodo2' => null]
        ]);
    }
}
