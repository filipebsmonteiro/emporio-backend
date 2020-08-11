<?php
require_once 'HTTP/Request2.php';
$request = new HTTP_Request2();
$request->setUrl('servidor:porta/v1/consumos/proximo/?api_key=GUID&modo_venda=(1 à 4)&abre=(true ou false)');
$request->setMethod(HTTP_Request2::METHOD_POST);
$request->setConfig(array(
    'follow_redirects' => TRUE
));
$request->setHeader(array(
    'Authorization' => 'Basic c3VwZXI6MTIzNA==',
    'Content-Type' => 'application/json; charset=utf-8'
));
$request->setBody('{
  "cliente": {
    "identificacao": "982079950"
  }
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
