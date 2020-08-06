<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 29/08/2017
 * Time: 00:52
 */
?>

<h2>Serviço de Atendimento ao Cliente</h2>

<p>Foi enviado um e-mail para o SAC vindo do seguinte cliente:</p>
<p>
    <b>Nome: </b>{{ $dados->nome }}
</p>
<p>
    <b>Email: </b>{{ $dados->email }}
</p>
@if ( $dados->phone )
    <p>
        <b>Telefone: </b>{{ $dados->phone }}
    </p>
@endif

<p>
    Para a Loja: <b>{{ $dados->loja }}</b>
</p>
<br>
<p>Com os Seguintes Dizeres:</p>
<p>{{ $dados->mensagem }}</p>