<?php
require_once 'HTTP/Request2.php';
$request = new HTTP_Request2();
$request->setUrl('servidor:porta/v1/consumos/?api_key=GUID&codigo=string&modo_venda=(1 à 4)');
$request->setMethod(HTTP_Request2::METHOD_POST);
$request->setConfig(array(
    'follow_redirects' => TRUE
));
$request->setHeader(array(
    'Authorization' => 'Basic c3VwZXI6MTIzNA==',
    'Content-Type' => 'application/json; charset=utf-8'
));
$request->setBody('{
  "itens": [
    {
      "tipo": "combo",
      "codigo": "703",
      "itens": [
        {
          "codigo": 220,
          "slot_indice": 0
        },
        {
          "codigo": 232,
          "slot_indice": 0
        },
        {
          "codigo": 101,
          "slot_indice": 1
        }
      ]
    }
  ]
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
