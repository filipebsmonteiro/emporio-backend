<?php

use Illuminate\Database\Seeder;
use App\Models\Funcionamento;
use App\Models\Loja;
use App\User;
use App\Models\User_has_perfil;

class SegundaLojaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Loja::create([
            'fantasia'					            => 'Segunda Loja',
            'CNPJ'					                => '00000000000000',
            'razao_social'      					=> 'Segunda Loja',
            'abreviacao'		        			=> '2ª Loja',
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
