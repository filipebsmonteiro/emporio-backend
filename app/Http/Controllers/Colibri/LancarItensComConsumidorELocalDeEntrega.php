<?php
require_once 'HTTP/Request2.php';
$request = new HTTP_Request2();
$request->setUrl('servidor:porta/v1/consumos/?api_key=GUID&codigo=string&modo_venda=(1 à 4)&num_maximo=int&num_minimo=int&numerodechamada=true');
$request->setMethod(HTTP_Request2::METHOD_POST);
$request->setConfig(array(
    'follow_redirects' => TRUE
));
$request->setHeader(array(
    'Authorization' => 'Basic c3VwZXI6MTIzNA==',
    'Content-Type' => 'application/json'
));
$request->setBody('{
  "consumidor": {
    "endereco": "Rua Teste",
    "cpf_cnpj": "35062267997",
    "nome": "Fulano de Oliveira",
    "email": "email@gmail.com"
  },
  "itens": [
    {
      "codigo": 101,
      "quantidade": 1,
      "observacao": [
        "Sem açucar"
      ],
      "tipo": "normal"
    }
  ],
  "local_entrega": "mesa 11"
}');
try {
    $response = $request->send();
    if ($response->getStatus() == 200) {
        echo $response->getBody();
    }
    else {
        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
            $response->getReasonPhrase();
    }
}
catch(HTTP_Request2_Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
