<?php

use Illuminate\Database\Seeder;
use App\Models\Perfil;
use App\Models\Permissao;
use App\Models\Perfil_has_permissao;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //------------- SECTION PERFIL
        Perfil::create([
            'nome'                                  => 'Admin',
            'descricao'                             => 'Administrador Master.'
        ]);

        Perfil::create([
            'nome'                                  => 'Lojista',
            'descricao'                             => 'Lojista: O Dono da Loja.'
        ]);

        Perfil::create([
            'nome'                                  => 'Gerente',
            'descricao'                             => 'Gerente de 1 Loja.'
        ]);

        Perfil::create([
            'nome'                                  => 'Atendente',
            'descricao'                             => 'Atende os Pedidos.'
        ]);
        //------------- END PERFIL

        //------------- SECTION PERMISSAOS
        Permissao::create([
            'nome'                                  => 'visao_loja',
            'descricao'                             => 'Permissão: Visualizar funcionalidades da Loja.'
        ]);//1

        Permissao::create([
            'nome'                                  => 'visao_administrativo',
            'descricao'                             => 'Permissão: Visualizar funcionalidades Administrativas.'
        ]);//2

        Permissao::create([
            'nome'                                  => 'chats',
            'descricao'                             => 'Permissão: Visualizar, Aceitar e Finalizar Chats.'
        ]);//3

        Permissao::create([
            'nome'                                  => 'cat_prod_index',
            'descricao'                             => 'Permissão: Listar categoria de produtos existentes.'
        ]);//4

        Permissao::create([
            'nome'                                  => 'cat_prod_show',
            'descricao'                             => 'Permissão: Visualizar os produtos pela listagem das categorias.'
        ]);//5

        Permissao::create([
            'nome'                                  => 'cat_prod_create',
            'descricao'                             => 'Permissão: Criar categoria produtos.'
        ]);//6

        Permissao::create([
            'nome'                                  => 'cat_prod_edit',
            'descricao'                             => 'Permissão: Editar categoria produtos existentes.'
        ]);//7

        Permissao::create([
            'nome'                                  => 'cat_prod_delete',
            'descricao'                             => 'Permissão: Deletar categoria produtos.'
        ]);//8

        Permissao::create([
            'nome'                                  => 'produto_index',
            'descricao'                             => 'Permissão: Listar produtos existentes.'
        ]);//9

        Permissao::create([
            'nome'                                  => 'produto_create',
            'descricao'                             => 'Permissão: Criar produtos.'
        ]);//10

        Permissao::create([
            'nome'                                  => 'produto_edit',
            'descricao'                             => 'Permissão: Editar produtos existentes.'
        ]);//11

        Permissao::create([
            'nome'                                  => 'produto_delete',
            'descricao'                             => 'Permissão: Deletar produtos.'
        ]);//12

        Permissao::create([
            'nome'                                  => 'cat_ing_index',
            'descricao'                             => 'Permissão: Listar categoria ingredientes existentes.'
        ]);//13

        Permissao::create([
            'nome'                                  => 'cat_ing_show',
            'descricao'                             => 'Permissão: Visualizar os ingredientes pela listagem das categorias.'
        ]);//14

        Permissao::create([
            'nome'                                  => 'cat_ing_create',
            'descricao'                             => 'Permissão: Criar categoria ingredientes.'
        ]);//15

        Permissao::create([
            'nome'                                  => 'cat_ing_edit',
            'descricao'                             => 'Permissão: Editar categoria ingredientes existentes.'
        ]);//16

        Permissao::create([
            'nome'                                  => 'cat_ing_delete',
            'descricao'                             => 'Permissão: Deletar categoria ingredientes.'
        ]);//17

        Permissao::create([
            'nome'                                  => 'ingrediente_index',
            'descricao'                             => 'Permissão: Listar ingredientes existentes.'
        ]);//18

        Permissao::create([
            'nome'                                  => 'ingrediente_create',
            'descricao'                             => 'Permissão: Criar ingredientes.'
        ]);//19

        Permissao::create([
            'nome'                                  => 'ingrediente_edit',
            'descricao'                             => 'Permissão: Editar ingredientes existentes.'
        ]);//20

        Permissao::create([
            'nome'                                  => 'ingrediente_delete',
            'descricao'                             => 'Permissão: Deletar ingredientes.'
        ]);//21

        Permissao::create([
            'nome'                                  => 'loja_index',
            'descricao'                             => 'Permissão: Listar lojas existentes.'
        ]);//22

        Permissao::create([
            'nome'                                  => 'loja_create',
            'descricao'                             => 'Permissão: Criar novas lojas.'
        ]);//23

        Permissao::create([
            'nome'                                  => 'loja_edit',
            'descricao'                             => 'Permissão: Editar lojas existentes.'
        ]);//24

        Permissao::create([
            'nome'                                  => 'loja_delete',
            'descricao'                             => 'Permissão: Deletar lojas existentes.'
        ]);//25

        Permissao::create([
            'nome'                                  => 'loja_cep_index',
            'descricao'                             => 'Permissão: Gerenciar loja.'
        ]);//26

        Permissao::create([
            'nome'                                  => 'loja_edita_cep',
            'descricao'                             => 'Permissão: Editar valores de taxa de Entrega.'
        ]);//27

        Permissao::create([
            'nome'                                  => 'loja_vincula_cep',
            'descricao'                             => 'Permissão: Vincular CEPs a uma Loja.'
        ]);//28

        Permissao::create([
            'nome'                                  => 'loja_desvincula_cep',
            'descricao'                             => 'Permissão: Desvincular CEPs de uma Loja.'
        ]);//29

        Permissao::create([
            'nome'                                  => 'cupom_index',
            'descricao'                             => 'Permissão: Listar cupons existentes.'
        ]);//30

        Permissao::create([
            'nome'                                  => 'cupom_create',
            'descricao'                             => 'Permissão: Criar cupons.'
        ]);//31

        Permissao::create([
            'nome'                                  => 'cupom_edit',
            'descricao'                             => 'Permissão: Editar cupons existentes.'
        ]);//32

        Permissao::create([
            'nome'                                  => 'cupom_delete',
            'descricao'                             => 'Permissão: Deletar cupons.'
        ]);//33

        Permissao::create([
            'nome'                                  => 'pedido_index',
            'descricao'                             => 'Permissão: Listar Pedidos existentes.'
        ]);//34

        Permissao::create([
            'nome'                                  => 'pedido_create',
            'descricao'                             => 'Permissão: Criar pedidos.'
        ]);//35

        Permissao::create([
            'nome'                                  => 'pedido_edit',
            'descricao'                             => 'Permissão: Atualizar status de pedidos existentes.'
        ]);//36

        Permissao::create([
            'nome'                                  => 'pedido_delete',
            'descricao'                             => 'Permissão: Deletar pedidos.'
        ]);//37

        Permissao::create([
            'nome'                                  => 'pedido_cancel',
            'descricao'                             => 'Permissão: Cancelar pedidos.'
        ]);//38

        Permissao::create([
            'nome'                                  => 'user_index',
            'descricao'                             => 'Permissão: Listar usuários existentes.'
        ]);//39

        Permissao::create([
            'nome'                                  => 'user_create',
            'descricao'                             => 'Permissão: Criar usuários.'
        ]);//40

        Permissao::create([
            'nome'                                  => 'user_edit',
            'descricao'                             => 'Permissão: Editar usuários existentes.'
        ]);//41

        Permissao::create([
            'nome'                                  => 'user_delete',
            'descricao'                             => 'Permissão: Deletar usuários.'
        ]);//42

        Permissao::create([
            'nome'                                  => 'cliente_index',
            'descricao'                             => 'Permissão: Listar clientes existentes.'
        ]);//43

        Permissao::create([
            'nome'                                  => 'cliente_show',
            'descricao'                             => 'Permissão: Visualizar os dados dos clientes.'
        ]);//44

        Permissao::create([
            'nome'                                  => 'cliente_create',
            'descricao'                             => 'Permissão: Criar clientes.'
        ]);//45

        Permissao::create([
            'nome'                                  => 'cliente_edit',
            'descricao'                             => 'Permissão: Editar clientes existentes.'
        ]);//46

        Permissao::create([
            'nome'                                  => 'dash_lojistica_show',
            'descricao'                             => 'Permissão: Gráficos de Lojística.'
        ]);//47

        Permissao::create([
            'nome'                                  => 'dash_marketing_show',
            'descricao'                             => 'Permissão: Gráficos de Marketing.'
        ]);//48

        Permissao::create([
            'nome'                                  => 'dash_desempenho_show',
            'descricao'                             => 'Permissão: Gráficos de Desempenho.'
        ]);//49

        Permissao::create([
            'nome'                                  => 'roteiro_index',
            'descricao'                             => 'Permissão: Listar roteiros existentes.'
        ]);//50

        Permissao::create([
            'nome'                                  => 'roteiro_create',
            'descricao'                             => 'Permissão: Criar novos roteiros.'
        ]);//51

        Permissao::create([
            'nome'                                  => 'roteiro_edit',
            'descricao'                             => 'Permissão: Editar roteiros existentes.'
        ]);//52

        Permissao::create([
            'nome'                                  => 'roteiro_delete',
            'descricao'                             => 'Permissão: Deletar roteiros existentes.'
        ]);//53

        Permissao::create([
            'nome'                                  => 'tarefa_index',
            'descricao'                             => 'Permissão: Listar tarefas existentes.'
        ]);//54

        Permissao::create([
            'nome'                                  => 'tarefa_create',
            'descricao'                             => 'Permissão: Criar novas tarefas.'
        ]);//55

        Permissao::create([
            'nome'                                  => 'tarefa_edit',
            'descricao'                             => 'Permissão: Editar tarefas existentes.'
        ]);//56

        Permissao::create([
            'nome'                                  => 'tarefa_delete',
            'descricao'                             => 'Permissão: Deletar tarefas existentes.'
        ]);//57

        Permissao::create([
            'nome'                                  => 'relacoes_filtrar',
            'descricao'                             => 'Permissão: Filtrar Clientes para relações.'
        ]);//58

        Permissao::create([
            'nome'                                  => 'relacoes_acoes',
            'descricao'                             => 'Permissão: Executar alguma ação com Cliente.'
        ]);//59

        Permissao::create([
            'nome'                                  => 'canal_venda_index',
            'descricao'                             => 'Permissão: Listar canais de venda existentes.'
        ]);//60

        Permissao::create([
            'nome'                                  => 'canal_venda_create',
            'descricao'                             => 'Permissão: Criar novos canais de venda.'
        ]);//61

        Permissao::create([
            'nome'                                  => 'canal_venda_edit',
            'descricao'                             => 'Permissão: Editar canais de venda existentes.'
        ]);//62

        Permissao::create([
            'nome'                                  => 'canal_venda_delete',
            'descricao'                             => 'Permissão: Deletar canais de venda existentes.'
        ]);//63

        Permissao::create([
            'nome'                                  => 'custo_canal_venda_index',
            'descricao'                             => 'Permissão: Listar custos dos canais de venda existentes.'
        ]);//64

        Permissao::create([
            'nome'                                  => 'custo_canal_venda_create',
            'descricao'                             => 'Permissão: Criar novos custos dos canais de venda.'
        ]);//65

        Permissao::create([
            'nome'                                  => 'custo_canal_venda_edit',
            'descricao'                             => 'Permissão: Editar custos dos canais de venda existentes.'
        ]);//66

        Permissao::create([
            'nome'                                  => 'custo_canal_venda_delete',
            'descricao'                             => 'Permissão: Deletar custos dos canais de venda existentes.'
        ]);//67

        Permissao::create([
            'nome'                                  => 'periodo_venda_index',
            'descricao'                             => 'Permissão: Listar Periodos de venda existentes.'
        ]);//68

        Permissao::create([
            'nome'                                  => 'periodo_venda_create',
            'descricao'                             => 'Permissão: Criar novos Periodos de venda.'
        ]);//69

        Permissao::create([
            'nome'                                  => 'periodo_venda_edit',
            'descricao'                             => 'Permissão: Editar Periodos de venda existentes.'
        ]);//70

        Permissao::create([
            'nome'                                  => 'periodo_venda_delete',
            'descricao'                             => 'Permissão: Deletar Periodos de venda existentes.'
        ]);//71

        Permissao::create([
            'nome'                                  => 'ficha_tecnica_index',
            'descricao'                             => 'Permissão: Listar dados existentes na Ficha técnica.'
        ]);//72

        Permissao::create([
            'nome'                                  => 'ficha_tecnica_create',
            'descricao'                             => 'Permissão: Adicionar novos dados na Ficha técnica.'
        ]);//73

        Permissao::create([
            'nome'                                  => 'ficha_tecnica_edit',
            'descricao'                             => 'Permissão: Editar dados existentes na Ficha técnica.'
        ]);//74

        Permissao::create([
            'nome'                                  => 'ficha_tecnica_delete',
            'descricao'                             => 'Permissão: Deletar dados existentes na Ficha técnica.'
        ]);//75

        Permissao::create([
            'nome'                                  => 'custo_ficha_tecnica_index',
            'descricao'                             => 'Permissão: Listar custos dos canais de venda existentes.'
        ]);//76

        Permissao::create([
            'nome'                                  => 'custo_ficha_tecnica_create',
            'descricao'                             => 'Permissão: Adicionar novos custos à Ficha técnica.'
        ]);//77

        Permissao::create([
            'nome'                                  => 'custo_ficha_tecnica_edit',
            'descricao'                             => 'Permissão: Editar custos da Ficha técnica.'
        ]);//78

        Permissao::create([
            'nome'                                  => 'custo_ficha_tecnica_delete',
            'descricao'                             => 'Permissão: Deletar custos existentes da Ficha técnica.'
        ]);//79

        Permissao::create([
            'nome'                                  => 'loja_layout',
            'descricao'                             => 'Permissão: Visualizar, Editar Layout da Loja.'
        ]);//80

        Permissao::create([
            'nome'                                  => 'user_perfil_index',
            'descricao'                             => 'Permissão: Listar Perfis dos usuario.'
        ]);//81

        Permissao::create([
            'nome'                                  => 'user_perfil_create',
            'descricao'                             => 'Permissão: Criar Perfis dos usuario.'
        ]);//82

        Permissao::create([
            'nome'                                  => 'user_perfil_edit',
            'descricao'                             => 'Permissão: Editar Perfis dos usuario.'
        ]);//83

        Permissao::create([
            'nome'                                  => 'user_perfil_delete',
            'descricao'                             => 'Permissão: Deletar Perfis dos usuario.'
        ]);//84

        Permissao::create([
            'nome'                                  => 'forma_pagamento_index',
            'descricao'                             => 'Permissão: Listar formas de pagamento existentes.'
        ]);//85

        Permissao::create([
            'nome'                                  => 'forma_pagamento_create',
            'descricao'                             => 'Permissão: Criar novas formas de pagamento .'
        ]);//86

        Permissao::create([
            'nome'                                  => 'forma_pagamento_edit',
            'descricao'                             => 'Permissão: Editar formas de pagamento existentes.'
        ]);//87

        Permissao::create([
            'nome'                                  => 'forma_pagamento_delete',
            'descricao'                             => 'Permissão: Deletar formas de pagamento existentes.'
        ]);//88

        Permissao::create([
            'nome'                                  => 'rating_index',
            'descricao'                             => 'Permissão: Listar avaliações.'
        ]);//89

        Permissao::create([
            'nome'                                  => 'rating_edit',
            'descricao'                             => 'Permissão: Responder avaliações.'
        ]);//90

        Permissao::create([
            'nome'                                  => 'rating_delete',
            'descricao'                             => 'Permissão: Deletar avaliações.'
        ]);//91

        Permissao::create([
            'nome'                                  => 'perfil_index',
            'descricao'                             => 'Permissão: Listar Perfis existentes.'
        ]);//92

        Permissao::create([
            'nome'                                  => 'perfil_create',
            'descricao'                             => 'Permissão: Criar novos Perfis.'
        ]);//93

        Permissao::create([
            'nome'                                  => 'perfil_edit',
            'descricao'                             => 'Permissão: Editar Perfis existentes.'
        ]);//94

        Permissao::create([
            'nome'                                  => 'perfil_delete',
            'descricao'                             => 'Permissão: Deletar Perfis existentes.'
        ]);//95

        Permissao::create([
            'nome'                                  => 'playpause_ingrediente',
            'descricao'                             => 'Permissão: Play/Pause ingredientes.'
        ]);//96

        Permissao::create([
            'nome'                                  => 'playpause_produto',
            'descricao'                             => 'Permissão: Play/Pause produtos.'
        ]);//97

        Permissao::create([
            'nome'                                  => 'fidelidade_edit',
            'descricao'                             => 'Permissão: Creditar/Debitar fidelidade.'
        ]);//98

        Permissao::create([
            'nome'                                  => 'chat_notify',
            'descricao'                             => 'Permissão: Receber Notificações de novos Chats.'
        ]);//99

        Permissao::create([
            'nome'                                  => 'pedido_notify',
            'descricao'                             => 'Permissão: Receber Notificações de novos Pedidos.'
        ]);//100

        //------------- END PERMISSAOS

        //------------- PERFIL HAS PERMISSAO
        //Lojista
        for ($i=1; $i<=100; $i++){
            // Não inclui os listados
            if( in_array($i, [
                3, //chats
                23, //loja_create
                25, //loja_delete
                37, //pedido_delete
                40, //user_create
                42, //user_delete
            ]) )
                continue;

            Perfil_has_permissao::create([
                'Perfils_idPerfils'                     => 2,
                'Permissaos_idPermissaos'               => $i
            ]);
        }

        //Gerente
        for ($i=1; $i<=100; $i++){
            // Inclui os Listados
            if ( in_array($i, [
                1, //visao_loja
                3, //chats
                4, //cat_prod_index
                5, //cat_prod_show
                9, //produto_index
                11, //produto_show
                13, //cat_ing_index
                14, //cat_ing_show
                18, //ingrediente_index
                20, //ingrediente_edit
                34, //pedido_index
                36, //pedido_edit
                38, //pedido_cancel
                50, //roteiro_index
                54, //tarefa_index
                58, //relacose_filtrar
                59, //relacoes_acoes
                96, //playpause_ingrediente
                97, //playpause_produto
            ]) ) {
                Perfil_has_permissao::create([
                    'Perfils_idPerfils' => 3,
                    'Permissaos_idPermissaos' => $i
                ]);
            }
        }

        //Atendente
        for ($i=1; $i<=100; $i++){
            if( in_array($i, [
                1, //visao_loja
                3, //chats
                34, //pedido_index
                36, //pedido_edit
                99, //chat_notify
                100 //pedido_notify
            ]) ) {
                Perfil_has_permissao::create([
                    'Perfils_idPerfils' => 4,
                    'Permissaos_idPermissaos' => $i
                ]);
            }
        }
        //------------- END PERFIL HAS PERMISSAO
    }
}
