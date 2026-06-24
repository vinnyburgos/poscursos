<?php
header('Content-Type: application/json');


// Função para obter o token de autenticação
function getToken($url, $login, $password) {
    $data = json_encode(['login' => 'frog', 'senha' => $password]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, rtrim($url, '/') . '/api/v1/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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

// Função para buscar todos os cursos de um tipo
function getTodosCursos($url, $token, $tipo) {
    $urlRequisicao = rtrim($url, '/') . '/api/v1/cards/' . $tipo;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlRequisicao);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('Erro ao obter os cursos: ' . curl_error($ch));
        return false;
    }
    curl_close($ch);

    return json_decode($response, true);
}

// Configurações de autenticação da API
$apiUrl = 'https://apimatricula.unisuam.edu.br';
$login = 'frog';
$password = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';

// Obtendo o token de autenticação
$token = getToken($apiUrl, $login, $password);

if ($token) {
    // Tipo do curso para requisitar (pode ser passado via GET, ex: ?tipo=graduacao)
    $tipoCurso = $_GET['tipo'] ?? 'posgraduacao';

    // Obtendo todos os cursos do tipo
    $cursosData = getTodosCursos($apiUrl, $token, $tipoCurso);

    if ($cursosData === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Falha ao obter dados dos cursos.']);
        exit;
    }

    if (!empty($cursosData['data'])) {
        echo json_encode($cursosData['data']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Nenhum curso encontrado.']);
        exit;
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Falha na autenticação.']);
    exit;
}
?>