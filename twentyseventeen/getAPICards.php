<?php
$cursosSelecaoInterno = defined('CURSOS_SELECAO_INTERNO') && CURSOS_SELECAO_INTERNO;

if (!$cursosSelecaoInterno) {
    header('Content-Type: application/json');
}

function persistirDadosHomeJson($payload) {
    $jsonPath = __DIR__ . '/dadosHome.json';
    $jsonBase = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($jsonBase === false) {
        return false;
    }

    $jsonComComentario = rtrim($jsonBase);
    $jsonComComentario .= "\n/* Atualizado em: " . date('Y-m-d H:i:s') . " */\n";

    return file_put_contents($jsonPath, $jsonComComentario, LOCK_EX) !== false;
}


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

// Função para buscar todos os cursos de um tipo
function getTodosCursosDetailed($url, $token, $tipo) {
    $urlRequisicao = rtrim($url, '/') . '/api/v1/cards/' . $tipo;
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
        error_log('Erro ao obter os cursos: ' . $curlError);
    }
    curl_close($ch);

    return [
        'ok' => empty($curlError),
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
$debugMode = isset($_GET['debug']) && $_GET['debug'] === '1';
$persistirCacheLocal = isset($_GET['persist']) && $_GET['persist'] === '1';

// Obtendo o token de autenticação
$tokenResult = getTokenDetailed($apiUrl, $login, $password);
$token = $tokenResult['token'];

if ($token) {
    // Tipo do curso para requisitar (pode ser passado via GET, ex: ?tipo=posgraduacao)
    $tipoCurso = $_GET['tipo'] ?? 'posgraduacao';

    // Obtendo todos os cursos do tipo solicitado.
    $cursosResult = getTodosCursosDetailed($apiUrl, $token, $tipoCurso);
    $cursosData = $cursosResult['decoded_response'];

    if (!$cursosResult['ok']) {
        if (!$cursosSelecaoInterno) {
            http_response_code(500);
        }
        echo json_encode([
            'error' => 'Falha ao obter dados dos cursos.',
            'debug' => $debugMode ? [
                'token_request' => $tokenResult,
                'cards_request' => $cursosResult
            ] : null
        ]);
        if (!$cursosSelecaoInterno) {
            exit;
        }
        return;
    }

    if ($debugMode) {
        echo json_encode([
            'success' => true,
            'debug' => [
                'input' => [
                    'tipo' => $tipoCurso
                ],
                'token_request' => $tokenResult,
                'cards_request' => $cursosResult,
                'final_data' => $cursosData['data'] ?? null
            ]
        ]);
        if (!$cursosSelecaoInterno) {
            exit;
        }
        return;
    }

    if (!empty($cursosData['data'])) {
        $payload = $cursosData['data'];

        if ($persistirCacheLocal && !persistirDadosHomeJson($payload)) {
            error_log('Falha ao persistir dadosHome.json via getAPICards.php');
        }

        echo json_encode($payload);
    } else {
        if (!$cursosSelecaoInterno) {
            http_response_code(404);
        }
        echo json_encode(['error' => 'Nenhum curso encontrado.']);
        if (!$cursosSelecaoInterno) {
            exit;
        }
        return;
    }
} else {
    if (!$cursosSelecaoInterno) {
        http_response_code(500);
    }
    echo json_encode([
        'error' => 'Falha na autenticação.',
        'debug' => $debugMode ? ['token_request' => $tokenResult] : null
    ]);
    if (!$cursosSelecaoInterno) {
        exit;
    }
    return;
}
?>