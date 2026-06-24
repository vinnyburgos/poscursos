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
$apiUrl = 'https://apimatricula.unisuam.edu.br';
$login = 'frog';
$password = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';

// Obter o mnemônico da requisição
$mneumonico = $_GET['mneumonico'] ?? '';

if (empty($mneumonico)) {
    http_response_code(400);
    echo json_encode(['error' => 'Mnemônico não fornecido.']);
    exit;
}

// Obtendo o token de autenticação
$token = getToken($apiUrl, $login, $password);

if ($token) {
    // Código do curso e tipo para requisitar
    $codigoCurso = $mneumonico;
    $tipoCurso = $_GET['tipo'] ?? 'posgraduacao';

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
        
        // Define o header correto para JSON
        header('Content-Type: application/json');
        
        // Retorna os dados completos do curso específico
        echo json_encode([
            'success' => true,
            'data' => $data,
            'mneumonico' => $mneumonico
        ]);
    } else {
        // Caso não haja dados do curso encontrados
        http_response_code(404);
        echo json_encode(['error' => 'Nenhum dado do curso encontrado para o mnemônico: ' . $mneumonico]);
    }
} else {
    // Erro ao obter o token
    http_response_code(500);
    echo json_encode(['error' => 'Falha na autenticação.']);
}
?>