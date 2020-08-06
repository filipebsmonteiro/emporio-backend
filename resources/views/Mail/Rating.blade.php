<?php
/**
 * Created by PhpStorm.
 * User: Filipe Monteiro
 * Date: 16/01/2018
 * Time: 23:08
 */?>
<!DOCTYPE>
<html>
    <head>

        <style type="text/css">
            body{
                border: black 2px solid;
                border-radius: 5px;
                padding: 10px;
            }
            .topo{
                background:linear-gradient(#4c8faf, #9ccae0);
                color: #FFFFFF;
                border-radius: 5px;
                padding: 5px;
            }
            .table {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 60%;
                margin: 10px;
            }

            .table td, .table th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            .table tr:nth-child(even){background-color: #f2f2f2;}

            .table tr:hover {background-color: #ddd;}

            .table th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #4c8faf;
                color: white;
            }
            .foot{
                background-color: #145d80;
                color: white;
                border-radius: 5px;
                padding: 10px;
            }
            .fa-star-o:hover{
                cursor: pointer;
            }
            .yellow{color: yellow;}
            .button{
                width: 60%;
                margin: 10px;
                border-radius: 10px;
                padding: 10px;
                background-color: #4CAF50;
                color: white;
            }
            .bratech *{
                color: gray;
            }
            input{
                border-radius: 7px;
                padding: 5px;
                font-size: medium;
            }
        </style>
        <script type="text/javascript">
            function colorir( estrela ) {
                linha = estrela.parentElement;

                if ( estrela.classList.contains('fa-star-o') ){
                    do {
                        elemento    = linha.getElementsByClassName('fa-star-o')[0];
                        elemento.classList.remove('fa-star-o');
                        elemento.classList.add('fa-star');
                        elemento.classList.add('yellow');
                    }
                    while (elemento != estrela);
                }else{
                    do {
                        elemento    = linha.getElementsByClassName('fa-star')[0];
                        elemento.classList.remove('fa-star');
                        elemento.classList.add('fa-star-o');
                        elemento.classList.remove('yellow');
                    }
                    while (linha.getElementsByClassName('fa-star')[0]);

                    do {
                        elemento    = linha.getElementsByClassName('fa-star-o')[0];
                        elemento.classList.remove('fa-star-o');
                        elemento.classList.add('fa-star');
                        elemento.classList.add('yellow');
                    }
                    while (elemento != estrela);
                }

                //seleciona no form de acordo com o nome
                $input          = document.getElementsByName( linha.getAttribute('id') );

                //como retorna array, seleciona primeira posicao e atribui o valor
                $input[0].value = linha.getElementsByClassName('fa-star').length;
            }
        </script>
    </head>
    <body>
        <div class="topo">
            <h2>Sua opinião vale muito !!!</h2>
            <h4>Conte-nos o que acho de comprar na {{ $lojaNome }}</h4>
        </div>

        <form method="post" action="{{ route('pedido.rating', ['idPedido' => $idPedido, 'idCliente' => $idCliente]) }}">

            <table class="table">
                <thead>
                    <tr>
                        <th>Sobre:</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tr>
                    <td>Site</td>
                    <td id="site">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star-o" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star-o.png') }}"  width="20" height="20">
                        <input name="site"      type="number" value="4">
                    </td>
                </tr>
                <tr>
                    <td>Comida</td>
                    <td id="comida">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star-o" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star-o.png') }}"  width="20" height="20">
                        <input name="comida"    type="number" value="4">
                    </td>
                </tr>
                <tr>
                    <td>Entrega</td>
                    <td id="entrega">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star-o" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star-o.png') }}"  width="20" height="20">
                        <input name="entrega"   type="number" value="4">
                    </td>
                </tr>
                <tr>
                    <td>Embalagem</td>
                    <td id="embalagem">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star.png') }}"  width="20" height="20">
                        <img class="fa-star-o" onclick="colorir( this )"
                             src="{{ asset('/imagens/email/star-o.png') }}"  width="20" height="20">
                        <input name="embalagem" type="number" value="4">
                    </td>
                </tr>
                <tr>
                    <td>Comentários</td>
                    <td id="comentarios">
                        <textarea name="comentarios"></textarea>
                    </td>
                </tr>
            </table>

            <button type="submit" class="button">Enviar Minha Opinião</button>
        </form>

        <div class="foot">
            Obrigado por sua opinião!<br>
            Esperamos te rever em breve!
        </div>
        <div class="bratech">
            <p>Desenvolvido por <a target="_blank" href="http://bratech.info.br">Bratech - IT Solutions</a></p>
        </div>
    </body>
</html>
