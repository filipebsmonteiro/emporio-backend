<?php
require_once 'HTTP/Request2.php';
$request = new HTTP_Request2();
$request->setUrl('servidor:porta/v1/consumos/checkin/?api_key=GUID');
$request->setMethod(HTTP_Request2::METHOD_POST);
$request->setConfig(array(
    'follow_redirects' => TRUE
));
$request->setHeader(array(
    'Content-Type' => 'application/json; charset=utf-8',
    'Authorization' => 'Basic c3VwZXI6MTIzNA=='
));
$request->setBody('{
  "codigo": 1,
  "cliente": {
    "identificacao": "123458",
    "id_externo": 123,
    "nome": "CLIENTE NOVO",
    "sexo": "M",
    "dt_nascimento": "19850215 00:00:00",
    "cnpj_cpf": "22233344456",
    "inscricao_rg": "123456789",
    "endereco": "Rua Cel Artur de Godoy",
    "numero": 7,
    "complemento": "Sala 15",
    "bairro": "Vila Mariana",
    "referencia": "Prox Praça",
    "cep": "02432-123",
    "cidade": "São Paulo",
    "estado": "SP",
    "telefone": "32456785",
    "email": "cliente.novo@provedor.com",
    "fax": "",
    "taxa_entrega": 10.53,
    "regiao": 1
  },
  "perfil_id": 4
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
