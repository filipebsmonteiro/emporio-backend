<?php

use Illuminate\Database\Seeder;
use App\Models\Funcionamento;
use App\Models\Loja;
use App\User;
use App\Models\User_has_perfil;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Loja::create([
//            'inicio_funcionamento'					=> '00:00',
//            'termino_funcionamento'					=> '23:59',
//            'inicio_delivery'					    => '00:00',
//            'termino_delivery'					    => '23:59',
            'fantasia'					            => 'Pizza Lenta',
            'CNPJ'					                => '09090909090902',
            'razao_social'      					=> 'Pizza Lenta',
            'abreviacao'		        			=> 'Lenta',
            'phone'					                => '(61) 0000-0010',

            'CEP'   					            => '73035-070',
            'Logradouro'        					=> 'Quadra 09, Bloco B, Lojas 4 e 8',
            'Bairro'			            		=> 'Asa Norte',
            'Cidade'				        	    => 'Brasília',
            'latitude'                              => '-16.764851',
            'longitude'                             => '-47.885979',

            'agendamentos'                          => true,
            'pagamentosOnline'                      => true
        ]);

        Funcionamento::create([
            'dia_semana'            => 'domingo',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 2
        ]);
        Funcionamento::create([
            'dia_semana'            => 'segunda',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 2
        ]);
        Funcionamento::create([
            'dia_semana'            => 'terca',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 2
        ]);
        Funcionamento::create([
            'dia_semana'            => 'quarta',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 2
        ]);
        Funcionamento::create([
            'dia_semana'            => 'quinta',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 2
        ]);
        Funcionamento::create([
            'dia_semana'            => 'sexta',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 2
        ]);
        Funcionamento::create([
            'dia_semana'            => 'sabado',
            'inicio_funcionamento'  => '00:00:00',
            'termino_funcionamento' => '23:59:00',
            'inicio_delivery'       => '00:00:00',
            'termino_delivery'      => '23:59:00',
            'Lojas_idLojas'         => 2
        ]);

        //Loja Rápida
        User::create([
            'name'									=> 'Lojista Rápido',
            'email'									=> 'lrapido@bratech.info',
            'password'								=> bcrypt('123456'),
            'Lojas_idLojas'                         => 1
        ]);
        User::create([
            'name'									=> 'Gerente Rápido',
            'email'									=> 'grapido@bratech.info',
            'password'								=> bcrypt('123456'),
            'Lojas_idLojas'                         => 1
        ]);
        User::create([
            'name'									=> 'Atendente Rápido',
            'email'									=> 'arapido@bratech.info',
            'password'								=> bcrypt('123456'),
            'Lojas_idLojas'                         => 1
        ]);
        User_has_perfil::create([
            'Users_idUsers'                         => 2,
            'Perfils_idPerfils'                     => 2
        ]);
        User_has_perfil::create([
            'Users_idUsers'                         => 3,
            'Perfils_idPerfils'                     => 3
        ]);
        User_has_perfil::create([
            'Users_idUsers'                         => 4,
            'Perfils_idPerfils'                     => 4
        ]);

        //Loja Lenta
        User::create([
            'name'									=> 'Lojista Lento',
            'email'									=> 'llento@bratech.info',
            'password'								=> bcrypt('123456'),
            'Lojas_idLojas'                         => 2
        ]);
        User::create([
            'name'									=> 'Gerente Lento',
            'email'									=> 'glento@bratech.info',
            'password'								=> bcrypt('123456'),
            'Lojas_idLojas'                         => 2
        ]);
        User::create([
            'name'									=> 'Atendente Lento',
            'email'									=> 'alento@bratech.info',
            'password'								=> bcrypt('123456'),
            'Lojas_idLojas'                         => 2
        ]);
        User_has_perfil::create([
            'Users_idUsers'                         => 5,
            'Perfils_idPerfils'                     => 2
        ]);
        User_has_perfil::create([
            'Users_idUsers'                         => 6,
            'Perfils_idPerfils'                     => 3
        ]);
        User_has_perfil::create([
            'Users_idUsers'                         => 7,
            'Perfils_idPerfils'                     => 4
        ]);
    }
}
