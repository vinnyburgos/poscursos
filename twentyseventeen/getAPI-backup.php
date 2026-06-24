<?php
// Função para obter o token de autenticação
function getToken($url, $login, $password) {
    $data = json_encode(['login' => $login, 'password' => $password]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, rtrim($url, '/') . '/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
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

// Função para buscar os dados de um curso específico pelo código
function getCursoPorCodigo($url, $token, $tipo, $codigo) {
    // Monta a URL para a requisição com base no código do curso
    $urlRequisicao = rtrim($url, '/') . '/api/curso/' . $tipo . '/' . $codigo;
    
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
$apiUrl = 'https://apisite.unisuam.edu.br';
$login = 'frog';
$password = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';

// var_dump($mneumonico);

// $mneumonico = 'ANC'; // pegar a variável do código aqui

// Obtendo o token de autenticação
$token = getToken($apiUrl, $login, $password);

if ($token) {
    // Código do curso e tipo para requisitar
    $codigoCurso = $mneumonico;  // Ajuste o código conforme necessário
    $tipoCurso = $_GET['tipo'] ?? 'posgraduacao';  // Ajuste o tipo conforme necessário

    // Obtendo os dados do curso específico pelo código
    $cursoData = getCursoPorCodigo($apiUrl, $token, $tipoCurso, $codigoCurso);

    // Verificar se os dados do curso foram retornados corretamente
    if ($cursoData === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Falha ao obter dados do curso.']);
        exit;
    }

    // Log para verificar a resposta da API
    error_log('Dados da API: ' . print_r($cursoData, true));

    if (!empty($cursoData['data'])) {
        // Se houver dados do curso encontrados, processamos os dados
        $data = $cursoData['data'];
    } else {
        // Caso não haja dados do curso encontrados
        // http_response_code(404);
        // echo json_encode(['error' => 'Nenhum dado do curso encontrado.']);
        // exit;
    }
} else {
    // Erro ao obter o token
    http_response_code(500);
    echo json_encode(['error' => 'Falha na autenticação.']);
    exit;
}
?>