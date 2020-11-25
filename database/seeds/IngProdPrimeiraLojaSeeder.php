<?php

use Illuminate\Database\Seeder;
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

class IngProdPrimeiraLojaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ingrediente::insert([
            // Recheios
            ['nome' => 'Queijo',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 7,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Óregano',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 7,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Calabresa',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 8,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Frango',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 9,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Catupiry',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 10,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Presunto',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 10,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Pimentão',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 10,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Ovo',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 10,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],

            // Bebidas
            ['nome' => 'Coca Lata',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 1,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Coca 600ml',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 2,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Guaraná Lata',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 3,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Guaraná 600ml',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 4,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Suco Laranja',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 5,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Suco Maracujá',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 6,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Suco Uva', 'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 7,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],

            // Bordas
            ['nome' => 'Sem borda',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 10,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Borda catupiry',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 10,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],

            // Massas
            ['nome' => 'Tradicional',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 10,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
            ['nome' => 'Fina',
                'status' => 1,
                'preco' => '0',
                'codigo_PDV' => 10,
                'Lojas_idLojas' => 1,
                'Cat_ingredientes_idCat_ingredientes' => 1],
        ]);

        Categoria_produto::insert([
            [
                'nome' => 'Bebidas',
                'layout' => 'Padrão',
                'permiteCombinacao' => false,
                'quantidadeCombinacoes' => 0,
                'Lojas_idLojas' => 1
            ], [
                'nome' => 'Pizzas',
                'layout' => 'Pizza',
                'permiteCombinacao' => true,
                'quantidadeCombinacoes' => 2,
                'Lojas_idLojas' => 1
            ], [
                'nome' => 'Combos',
                'layout' => 'Combo',
                'permiteCombinacao' => false,
                'quantidadeCombinacoes' => 0,
                'Lojas_idLojas' => 1
            ]
        ]);

        $arrayPadrao = [
            'codigo_PDV' => null,
            'imagem' => null,
            'status' => 'Disponível',
            'Lojas_idLojas' => 1,
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
            'termino_periodo2' => null
        ];

        Produto::insert([
            // Bebidas
            array_merge([
                'nome' => 'Suco Laranja',
                'preco' => 7,
                'Cat_produtos_idCat_produtos' => 1,
            ], $arrayPadrao),
            array_merge([
                'nome' => 'Suco Uva',
                'preco' => 7,
                'Cat_produtos_idCat_produtos' => 1,
            ], $arrayPadrao),
            array_merge([
                'nome' => 'Guaraná',
                'preco' => 5,
                'Cat_produtos_idCat_produtos' => 1,
            ], $arrayPadrao),
            array_merge([
                'nome' => 'Coca Cola',
                'preco' => 5,
                'Cat_produtos_idCat_produtos' => 1,
            ], $arrayPadrao),

            // Pizzas
            array_merge([
                'nome' => 'Mussarela',
                'preco' => 10,
                'Cat_produtos_idCat_produtos' => 2,
            ], $arrayPadrao),
            array_merge([
                'nome' => 'Calabresa',
                'preco' => 11,
                'Cat_produtos_idCat_produtos' => 2,
            ], $arrayPadrao),
            array_merge([
                'nome' => 'Frango Catupiry',
                'preco' => 12,
                'Cat_produtos_idCat_produtos' => 2,
            ], $arrayPadrao),
            array_merge([
                'nome' => 'Portuguesa',
                'preco' => 14,
                'Cat_produtos_idCat_produtos' => 2,
            ], $arrayPadrao),

            // Combos
            array_merge([
                'nome' => 'Pizza e Refrigerante',
                'preco' => 16,
                'Cat_produtos_idCat_produtos' => 3,
            ], $arrayPadrao),
            array_merge([
                'nome' => 'Pizza e Laranja',
                'preco' => 16,
                'Cat_produtos_idCat_produtos' => 3,
            ], $arrayPadrao),
        ]);

        Ingrediente_produto::insert([
            // Mussarela
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 1,
                'Produtos_idProdutos' => 5],
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 2,
                'Produtos_idProdutos' => 5],

            // Calabresa
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 1,
                'Produtos_idProdutos' => 6],
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 2,
                'Produtos_idProdutos' => 6],
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 3,
                'Produtos_idProdutos' => 6],

            // Frango Catupiry
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 1,
                'Produtos_idProdutos' => 7],
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 2,
                'Produtos_idProdutos' => 7],
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 4,
                'Produtos_idProdutos' => 7],
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 5,
                'Produtos_idProdutos' => 7],

            // Portuguesa 1 2 6 7 8
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 1,
                'Produtos_idProdutos' => 8],
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 2,
                'Produtos_idProdutos' => 8],
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 6,
                'Produtos_idProdutos' => 8],
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 7,
                'Produtos_idProdutos' => 8],
            [
                'visibilidade' => 'Essencial Visível',
                'Ingredientes_idIngredientes' => 8,
                'Produtos_idProdutos' => 8],
        ]);

        Ingrediente_multiplo::insert([
            [
                'nome' => 'Borda',
                'obrigatorio' => true,
                'visibilidade' => 'Essencial Visível',
                'quantidade_min' => 1,
                'quantidade_max' => 1,
                'Lojas_idLojas' => 1],
            [
                'nome' => 'Massa',
                'obrigatorio' => true,
                'visibilidade' => 'Essencial Visível',
                'quantidade_min' => 1,
                'quantidade_max' => 1,
                'Lojas_idLojas' => 1],
        ]);

        Cat_produtos_ing_multiplo::insert([
            ['Cat_produtos_idCat_produtos' => 2, 'Multiplos_idMultiplos' => 1],
            ['Cat_produtos_idCat_produtos' => 2, 'Multiplos_idMultiplos' => 2],
        ]);

        Ingrediente_ing_multiplo::insert([
            [
                'Multiplos_idMultiplos' => 1,
                'Ingredientes_idIngredientes' => 16,
                'valor' => 0
            ],
            [
                'Multiplos_idMultiplos' => 1,
                'Ingredientes_idIngredientes' => 17,
                'valor' => 0
            ],
            [
                'Multiplos_idMultiplos' => 2,
                'Ingredientes_idIngredientes' => 18,
                'valor' => 0
            ],
            [
                'Multiplos_idMultiplos' => 2,
                'Ingredientes_idIngredientes' => 19,
                'valor' => 0
            ]
        ]);

        Produto_multiplo::insert([
            [
                'nome' => 'Pizza',
                'obrigatorio' => true,
                'quantidade_min' => 1,
                'quantidade_max' => 1,
                'valor' => 0,
                'Lojas_idLojas' => 1
            ],
            [
                'nome' => 'Refrigerante',
                'obrigatorio' => true,
                'quantidade_min' => 1,
                'quantidade_max' => 1,
                'valor' => 0,
                'Lojas_idLojas' => 1
            ],
        ]);

        Prod_multiplo_produto::insert([
            // Pizzas no Combo
            [
                'Prod_Multiplos_idProd_Multiplos'       => 1,
                'Produtos_idProdutos'                   => 5,
                'valor'                                 => 0
            ],
            [
                'Prod_Multiplos_idProd_Multiplos'       => 1,
                'Produtos_idProdutos'                   => 6,
                'valor'                                 => 0
            ],
            [
                'Prod_Multiplos_idProd_Multiplos'       => 1,
                'Produtos_idProdutos'                   => 7,
                'valor'                                 => 0
            ],

            // Refrigerantes no Combo
            [
                'Prod_Multiplos_idProd_Multiplos'       => 2,
                'Produtos_idProdutos'                   => 3,
                'valor'                                 => 0
            ],
            [
                'Prod_Multiplos_idProd_Multiplos'       => 2,
                'Produtos_idProdutos'                   => 4,
                'valor'                                 => 0
            ]
        ]);

        Combos_prod_multiplo::insert([
            [
                'Produtos_idProdutos'               => 9,
                'Prod_Multiplos_idProd_Multiplos'   => 1
            ],
            [
                'Produtos_idProdutos'               => 9,
                'Prod_Multiplos_idProd_Multiplos'   => 2
            ]
        ]);
    }
}
