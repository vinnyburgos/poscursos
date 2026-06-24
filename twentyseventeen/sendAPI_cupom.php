<?php
// Função para obter o token de autenticação
function getToken($url, $login, $password) {
    $data = json_encode(['login' => $login, 'senha' => $password]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, rtrim($url, '/') . '/api/v1/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('Erro ao obter o token: ' . curl_error($ch));
        return false;
    }
    curl_close($ch);
    $responseData = json_decode($response, true);
    return $responseData['token'] ?? false;
}

// Configurações de autenticação da API
$apiUrl = 'https://apimatricula.unisuam.edu.br';
$login = 'frog';
$password = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';

// Recebe os dados do POST (JSON)
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

// Autentica e obtém o token
$token = getToken($apiUrl, $login, $password);

if (!$token) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha na autenticação.']);
    exit;
}

// Monta o payload conforme solicitado
$payload = [
    "oferta" => $data['oferta'] ?? null,
    "descricao_curso" => $data['descricao_curso'] ?? ''
];

// Envia para o gateway
$ch = curl_init('https://apimatricula.unisuam.edu.br/api/v1/gateway');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'accept: application/json',
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($http_code);
echo $response;
?>