<?php
require_once 'HTTP/Request2.php';
$request = new HTTP_Request2();
$request->setUrl('servidor:porta/v1/consumos/?api_key=GUID&codigo=string&modo_venda=(1 à 4)&ticket_id=GUID&perfilimpressao_id=int&dispositivo_utilizado=string');
$request->setMethod(HTTP_Request2::METHOD_POST);
$request->setConfig(array(
    'follow_redirects' => TRUE
));
$request->setHeader(array(
    'Content-Type' => 'application/json; charset=utf-8',
    'Authorization' => 'Basic c3VwZXI6MTIzNA=='
));
$request->setBody('{
  "itens": [
    {
      "tipo": "normal",
      "codigo": 101,
      "quantidade": 1
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
