<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Categoria_ingrediente;
use App\Models\Cliente;
use App\Models\EnderecoCliente;
use App\Models\FormaPagamento;
use \App\Models\Funcionamento;
use App\Models\Loja;
use App\Models\User_has_perfil;
use App\User;

class BasicSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Loja::create([
            'fantasia'					            => 'Primeira Loja',
            'CNPJ'					                => '00000000000000',
            'razao_social'      					=> 'Primeira Loja',
            'abreviacao'		        			=> '1ª Loja',
            'phone'					                => '(00) 0000-0000',

            'CEP'   					            => '00000-000',
            'Logradouro'        					=> 'Logradouro Completo da Loja',
            'Bairro'			            		=> 'Bairro da Loja',
            'Cidade'				        	    => 'Cidade da Loja',
            'latitude'                              => '-15.794174',
            'longitude'                             => '-47.881883',

            'agendamentos'                          => true,
            'pagamentosOnline'                      => true
        ]);

        Funcionamento::create([
            'dia_semana'            => 'domingo',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 1
        ]);
        Funcionamento::create([
            'dia_semana'            => 'segunda',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 1
        ]);
        Funcionamento::create([
            'dia_semana'            => 'terca',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 1
        ]);
        Funcionamento::create([
            'dia_semana'            => 'quarta',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 1
        ]);
        Funcionamento::create([
            'dia_semana'            => 'quinta',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 1
        ]);
        Funcionamento::create([
            'dia_semana'            => 'sexta',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 1
        ]);
        Funcionamento::create([
            'dia_semana'            => 'sabado',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 1
        ]);

        User::create([
            'name'									=> 'Administrador',
            'email'									=> 'admin@bratech.info',
            'password'								=> bcrypt('Br@T3ch!'),
            'Lojas_idLojas'                         => 1
        ]);
        User_has_perfil::create([
            'Users_idUsers'                         => 1,
            'Perfils_idPerfils'                     => 1
        ]);
        User::create([
            'name'									=> 'Lojista Rápido',
            'email'									=> 'lrapido@bratech.info',
            'password'								=> bcrypt('123456'),
            'Lojas_idLojas'                         => 1
        ]);
        User_has_perfil::create([
            'Users_idUsers'                         => 2,
            'Perfils_idPerfils'                     => 2
        ]);
        User::create([
            'name'									=> 'Gerente Rápido',
            'email'									=> 'grapido@bratech.info',
            'password'								=> bcrypt('123456'),
            'Lojas_idLojas'                         => 1
        ]);
        User_has_perfil::create([
            'Users_idUsers'                         => 3,
            'Perfils_idPerfils'                     => 3
        ]);
        User::create([
            'name'									=> 'Atendente Rápido',
            'email'									=> 'arapido@bratech.info',
            'password'								=> bcrypt('123456'),
            'Lojas_idLojas'                         => 1
        ]);
        User_has_perfil::create([
            'Users_idUsers'                         => 4,
            'Perfils_idPerfils'                     => 4
        ]);

        Cliente::create([
            'nome'									=> 'Usuário Administrativo',
            'nascimento'							=> '2000-01-01',
            'phone'									=> '(99) 9 9999-9999',
            'email'									=> 'admin@bratech.info',
            'password'								=> bcrypt('Br@T3ch!')
        ]);
        EnderecoCliente::create([
            'CEP'                                   => '00000000',
            'Clientes_idClientes'                   => 1,
            'deleted_at'                            => Carbon::now()
        ]);

        FormaPagamento::create([
            'nome'                                  => 'Dinheiro',
            'imagem'                                => 0
        ]);
        FormaPagamento::create([
            'nome'                                  => 'Pagamento Online',
            'imagem'                                => 0
        ]);

        Categoria_ingrediente::insert([
            ['nome' => 'Categoria Padrão', 'Lojas_idLojas' => 1]
        ]);
    }
}
