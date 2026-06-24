<?php
header('Content-Type: application/json');

// Função para obter o token de autenticação
function getTokenDetailed($url, $login, $password) {
    $requestBody = json_encode(['login' => $login, 'senha' => $password]);
    $ch = curl_init();
    $requestUrl = rtrim($url, '/') . '/api/v1/token';
    curl_setopt($ch, CURLOPT_URL, $requestUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    if (curl_errno($ch)) {
        error_log('Erro ao obter o token: ' . $curlError);
    }
    curl_close($ch);

    $responseData = json_decode($response, true);
    $token = $responseData['token'] ?? false;

    return [
        'ok' => ($token !== false),
        'token' => $token,
        'http_code' => $httpCode,
        'curl_error' => $curlError,
        'raw_response' => $response,
        'decoded_response' => $responseData,
        'request' => [
            'method' => 'POST',
            'url' => $requestUrl,
            'headers' => [
                'accept: application/json',
                'Content-Type: application/json'
            ],
            // Evita expor a senha real em debug.
            'body' => ['login' => $login, 'senha' => '***']
        ]
    ];
}

// Função para buscar os dados de um curso específico pelo código
function getCursoPorCodigoDetailed($url, $token, $tipo, $codigo) {
    // Monta a URL para a requisição com base no código do curso
    $urlRequisicao = rtrim($url, '/') . '/api/v1/cards/' . $tipo . '/' . $codigo;
    
    // Inicia a requisição CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlRequisicao);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    if (curl_errno($ch)) {
        error_log('Erro ao obter o curso: ' . $curlError);
    }
    curl_close($ch);

    return [
        'ok' => (empty($curlError)),
        'http_code' => $httpCode,
        'curl_error' => $curlError,
        'raw_response' => $response,
        'decoded_response' => json_decode($response, true),
        'request' => [
            'method' => 'GET',
            'url' => $urlRequisicao,
            'headers' => [
                'Authorization: Bearer ***',
                'Content-Type: application/json'
            ]
        ]
    ];
}

// Configurações de autenticação da API
$apiUrl = 'https://apimatricula.unisuam.edu.br';
$login = 'frog';
$password = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';

// Obtendo o mneumonico via GET
$mneumonico = $_GET['mneumonico'] ?? null;
$debugMode = isset($_GET['debug']) && $_GET['debug'] === '1';

if (!$mneumonico) {
    http_response_code(400);
    echo json_encode(['error' => 'Mneumonico não fornecido.']);
    exit;
}

// Obtendo o token de autenticação
$tokenResult = getTokenDetailed($apiUrl, $login, $password);
$token = $tokenResult['token'];

if ($token) {
    // Tipo do curso para requisitar
    $tipoCurso = $_GET['tipo'] ?? 'posgraduacao';

    // Obtendo os dados do curso específico pelo código
    $cursoResult = getCursoPorCodigoDetailed($apiUrl, $token, $tipoCurso, $mneumonico);
    $cursoData = $cursoResult['decoded_response'];

    // Verificar se os dados do curso foram retornados corretamente
    if (!$cursoResult['ok']) {
        http_response_code(500);
        echo json_encode([
            'error' => 'Falha ao obter dados do curso.',
            'debug' => $debugMode ? [
                'token_request' => $tokenResult,
                'cards_request' => $cursoResult
            ] : null
        ]);
        exit;
    }

    if ($debugMode) {
        echo json_encode([
            'success' => true,
            'debug' => [
                'input' => [
                    'mneumonico' => $mneumonico,
                    'tipo' => $tipoCurso
                ],
                'token_request' => $tokenResult,
                'cards_request' => $cursoResult,
                'final_data' => $cursoData['data'] ?? null
            ]
        ]);
        exit;
    }

    if (!empty($cursoData['data'])) {
        // Se houver dados do curso encontrados, retornamos os dados
        echo json_encode($cursoData['data']);
    } else {
        // Caso não haja dados do curso encontrados
        http_response_code(404);
        echo json_encode(['error' => 'Nenhum dado do curso encontrado.']);
        exit;
    }
} else {
    // Erro ao obter o token
    http_response_code(500);
    echo json_encode([
        'error' => 'Falha na autenticação.',
        'debug' => $debugMode ? ['token_request' => $tokenResult] : null
    ]);
    exit;
}


?>