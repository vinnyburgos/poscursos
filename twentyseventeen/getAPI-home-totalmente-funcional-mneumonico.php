<?php
header('Content-Type: application/json');

// Função para obter o token de autenticação
function getToken($url, $login, $password) {
    $data = json_encode(['login' => $login, 'senha' => $password]); // campo correto: senha
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
    // O token está em data.token.access_token
    return $responseData['token'] ?? false;
}

// Função para buscar os dados de um curso específico pelo código
function getCursoPorCodigo($url, $token, $tipo, $codigo) {
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
    if (curl_errno($ch)) {
        error_log('Erro ao obter o curso: ' . curl_error($ch));
        return false;
    }
    curl_close($ch);

    return json_decode($response, true);
}

// Configurações de autenticação da API
$apiUrl = 'https://apiinscricao-dev.unisuam.edu.br';
$login = 'frog';
$password = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';

// Obtendo o mneumonico via GET
$mneumonico = $_GET['mneumonico'] ?? null;

if (!$mneumonico) {
    http_response_code(400);
    echo json_encode(['error' => 'Mneumonico não fornecido.']);
    exit;
}

// Obtendo o token de autenticação
$token = getToken($apiUrl, $login, $password);

if ($token) {
    // Tipo do curso para requisitar
    $tipoCurso = $_GET['tipo'] ?? 'posgraduacao';  // Ajuste o tipo conforme necessário

    // Obtendo os dados do curso específico pelo código
    $cursoData = getCursoPorCodigo($apiUrl, $token, $tipoCurso, $mneumonico);

    // Verificar se os dados do curso foram retornados corretamente
    if ($cursoData === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Falha ao obter dados do curso.']);
        exit;
    }

    // Log para verificar a resposta da API
    error_log('Dados da API: ' . print_r($cursoData, true));

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
    echo json_encode(['error' => 'Falha na autenticação.']);
    exit;
}


?>