<?php
/**
 * Created by PhpStorm.
 * User: Filipe Monteiro
 * Date: 08/03/2018
 * Time: 15:45
 */?>

<h2>Resposta da sua avaliação:</h2>

<p>{{ $rating->resposta }}</p>

<p>Referência do seu Pedido: {{ $rating->pedido->referencia }}</p>

<p>Att. {{ $lojaNome }}</p>