<?php
/**
 * Worker independente para atualizar dadosHome.json.
 *
 * Modos:
 * - CLI: para cron/shell
 * - WEB: pagina com status + botao de atualizacao manual
 *
 * Caminhos padrao (equivalentes ao uso antigo no front-page.php):
 * - JSON local: __DIR__/dadosHome.json
 * - Endpoint remoto: /wp-content/themes/twentyseventeen/getAPICards.php
 */

date_default_timezone_set('America/Sao_Paulo');

$config = dadoshome_default_config();

if (PHP_SAPI === 'cli') {
    dadoshome_handle_cli($config);
    exit;
}

dadoshome_enforce_web_access($config);
dadoshome_handle_web($config);
exit;

function dadoshome_default_config()
{
    $themePath = '/wp-content/themes/twentyseventeen';
    $defaultHost = 'poscursos.unisuam.edu.br';
    $baseUrl = dadoshome_detect_base_url($defaultHost);
    $endpointFromEnv = trim((string) getenv('DADOSHOME_GETAPICARDS_URL'));
    $jsonPathFromEnv = trim((string) getenv('DADOSHOME_JSON_PATH'));
    $accessKeyFromEnv = trim((string) getenv('DADOSHOME_ACCESS_KEY'));
    if ($accessKeyFromEnv === '') {
        $accessKeyFromEnv = 'UniSuAM@2026#';
    }
    $allowedIpsFromEnv = trim((string) getenv('DADOSHOME_ALLOWED_IPS'));
    $minRefreshIntervalSec = (int) getenv('DADOSHOME_MIN_REFRESH_INTERVAL');
    if ($minRefreshIntervalSec <= 0) {
        $minRefreshIntervalSec = 300;
    }

    return array(
        'timezone' => 'America/Sao_Paulo',
        'jsonPath' => $jsonPathFromEnv !== '' ? $jsonPathFromEnv : __DIR__ . '/dadosHome.json',
        'refreshHour' => 23,
        'accessKey' => $accessKeyFromEnv,
        'allowedIps' => dadoshome_parse_allowed_ips($allowedIpsFromEnv),
        'minRefreshIntervalSec' => $minRefreshIntervalSec,
        'lockFile' => __DIR__ . '/dadosHomeRefreshWorker.lock',
        'rateFile' => __DIR__ . '/dadosHomeRefreshWorker.rate',
        'themePath' => $themePath,
        'endpointPath' => $themePath . '/getAPICards.php',
        'workerPath' => $themePath . '/dadosHomeRefreshWorker.php',
        'endpointUrl' => $endpointFromEnv !== '' ? $endpointFromEnv : $baseUrl . $themePath . '/getAPICards.php',
        'workerUrl' => $baseUrl . $themePath . '/dadosHomeRefreshWorker.php',
        'workerFilePath' => __FILE__,
        'baseUrl' => $baseUrl,
    );
}

function dadoshome_detect_base_url($fallbackHost)
{
    $envBase = trim((string) getenv('DADOSHOME_BASE_URL'));
    if ($envBase !== '') {
        return rtrim($envBase, '/');
    }

    if (!empty($_SERVER['HTTP_HOST'])) {
        $host = (string) $_SERVER['HTTP_HOST'];
        $scheme = 'http';
        if (!empty($_SERVER['REQUEST_SCHEME'])) {
            $scheme = (string) $_SERVER['REQUEST_SCHEME'];
        } elseif (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
            $scheme = 'https';
        }
        return $scheme . '://' . $host;
    }

    $host = trim((string) getenv('DADOSHOME_HOST'));
    if ($host === '') {
        $host = $fallbackHost;
    }

    return 'https://' . $host;
}

function dadoshome_parse_allowed_ips($rawValue)
{
    if (!is_string($rawValue) || trim($rawValue) === '') {
        return array();
    }

    $items = preg_split('/\s*,\s*/', trim($rawValue));
    $result = array();

    foreach ($items as $ip) {
        $ip = trim((string) $ip);
        if ($ip === '') {
            continue;
        }
        $result[$ip] = true;
    }

    return array_keys($result);
}

function dadoshome_enforce_web_access($config)
{
    $expectedKey = (string) ($config['accessKey'] ?? '');
    $providedKey = dadoshome_get_provided_access_key();
    $ipAllowed = dadoshome_is_ip_allowed($config);

    if ($expectedKey === '' || $providedKey === null || !hash_equals($expectedKey, $providedKey) || !$ipAllowed) {
        dadoshome_deny_access();
    }
}

function dadoshome_get_provided_access_key()
{
    if (isset($_POST['access_key']) && is_string($_POST['access_key'])) {
        return trim($_POST['access_key']);
    }

    if (isset($_GET['access_key']) && is_string($_GET['access_key'])) {
        return trim($_GET['access_key']);
    }

    if (isset($_SERVER['HTTP_X_DADOSHOME_KEY']) && is_string($_SERVER['HTTP_X_DADOSHOME_KEY'])) {
        return trim($_SERVER['HTTP_X_DADOSHOME_KEY']);
    }

    return null;
}

function dadoshome_is_ip_allowed($config)
{
    $allowedIps = isset($config['allowedIps']) && is_array($config['allowedIps'])
        ? $config['allowedIps']
        : array();

    if (empty($allowedIps)) {
        return true;
    }

    $remoteIp = isset($_SERVER['REMOTE_ADDR']) ? trim((string) $_SERVER['REMOTE_ADDR']) : '';
    if ($remoteIp === '') {
        return false;
    }

    return in_array($remoteIp, $allowedIps, true);
}

function dadoshome_deny_access()
{
    if (!headers_sent()) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
    }

    echo '404 Not Found';
    exit;
}

function dadoshome_handle_cli($config)
{
    $options = getopt('', array('endpoint::', 'json-path::', 'refresh-hour::', 'force', 'manual', 'auto', 'print-config'));

    if (isset($options['endpoint']) && trim((string) $options['endpoint']) !== '') {
        $config['endpointUrl'] = trim((string) $options['endpoint']);
    }
    if (isset($options['json-path']) && trim((string) $options['json-path']) !== '') {
        $config['jsonPath'] = trim((string) $options['json-path']);
    }
    if (isset($options['refresh-hour'])) {
        $config['refreshHour'] = (int) $options['refresh-hour'];
    }

    if ($config['refreshHour'] < 0 || $config['refreshHour'] > 23) {
        fwrite(STDERR, "Erro: --refresh-hour deve estar entre 0 e 23.\n");
        exit(1);
    }

    if (isset($options['print-config'])) {
        echo json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n";
        exit(0);
    }

    $force = isset($options['force']) || isset($options['manual']);
    $mode = isset($options['manual']) ? 'manual-cli' : 'auto-cli';
    $result = dadoshome_execute_refresh($config, $force, $mode);

    $prefix = $result['ok'] ? '[OK] ' : '[ERRO] ';
    echo $prefix . $result['message'] . "\n";
    echo 'Endpoint: ' . $config['endpointUrl'] . "\n";
    echo 'JSON: ' . $config['jsonPath'] . "\n";

    if (!empty($result['updatedAt'])) {
        echo 'Atualizado em: ' . $result['updatedAt'] . "\n";
    }

    exit($result['ok'] ? 0 : 1);
}

function dadoshome_handle_web($config)
{
    $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    $action = '';

    if ($method === 'POST') {
        $action = isset($_POST['action']) ? (string) $_POST['action'] : '';
    } else {
        $action = isset($_GET['action']) ? (string) $_GET['action'] : '';
    }

    $result = array(
        'ok' => true,
        'status' => 'idle',
        'message' => 'Painel protegido pronto. Use o botao para atualizacao manual ou action=auto no cron.',
        'updatedAt' => null,
        'source' => 'web-idle',
    );

    if ($action === 'manual-refresh' || $action === 'refresh') {
        if ($method !== 'POST') {
            $result = array(
                'ok' => false,
                'status' => 'error',
                'message' => 'Metodo invalido para atualizacao manual. Use POST.',
                'updatedAt' => null,
                'source' => 'manual-web',
            );
        } else {
            $result = dadoshome_execute_refresh($config, true, 'manual-web');
        }
    } elseif ($action === 'auto') {
        $result = dadoshome_execute_refresh($config, false, 'auto-web');
    }

    $status = dadoshome_collect_status($config);
    $format = strtolower((string) ($_GET['format'] ?? 'html'));

    if ($format === 'json' || $action === 'auto') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            array(
                'ok' => $result['ok'],
                'status' => $result['status'],
                'message' => $result['message'],
                'updatedAt' => $result['updatedAt'],
                'config' => array(
                    'workerUrl' => $config['workerUrl'],
                    'endpointUrl' => $config['endpointUrl'],
                    'endpointPath' => $config['endpointPath'],
                    'jsonPath' => $config['jsonPath'],
                    'refreshHour' => $config['refreshHour'],
                ),
                'cache' => $status,
            ),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );
        return;
    }

    dadoshome_render_web_page($config, $status, $result);
}

function dadoshome_execute_refresh($config, $force, $source)
{
    $lockHandle = null;
    $lockError = '';
    if (!dadoshome_acquire_lock($config['lockFile'], $lockHandle, $lockError)) {
        return array(
            'ok' => true,
            'status' => 'busy',
            'message' => $lockError,
            'updatedAt' => null,
            'source' => $source,
        );
    }

    $tz = new DateTimeZone($config['timezone']);
    $now = new DateTimeImmutable('now', $tz);

    try {
        if (!$force && !dadoshome_needs_refresh($config['jsonPath'], $now, (int) $config['refreshHour'])) {
            return array(
                'ok' => true,
                'status' => 'skipped',
                'message' => 'Sem atualizacao: cache dentro da janela configurada.',
                'updatedAt' => null,
                'source' => $source,
            );
        }

        $rateCheck = dadoshome_check_rate_limit($config['rateFile'], (int) $config['minRefreshIntervalSec']);
        if (!$rateCheck['allowed']) {
            return array(
                'ok' => true,
                'status' => 'rate-limited',
                'message' => 'Bloqueado por limite de frequencia. Aguarde ' . $rateCheck['retryInSeconds'] . 's.',
                'updatedAt' => null,
                'source' => $source,
            );
        }

        $refreshUrl = dadoshome_build_refresh_url($config['endpointUrl'], time());
        if ($refreshUrl === false) {
            return array(
                'ok' => false,
                'status' => 'error',
                'message' => 'Endpoint invalido.',
                'updatedAt' => null,
                'source' => $source,
            );
        }

        $errorMessage = '';
        $response = dadoshome_http_get($refreshUrl, $errorMessage);
        if ($response === false) {
            return array(
                'ok' => false,
                'status' => 'error',
                'message' => 'Falha ao requisitar getAPICards.php: ' . $errorMessage,
                'updatedAt' => null,
                'source' => $source,
            );
        }

        $payload = json_decode($response, true);
        if (!is_array($payload)) {
            return array(
                'ok' => false,
                'status' => 'error',
                'message' => 'Resposta JSON invalida do getAPICards.php.',
                'updatedAt' => null,
                'source' => $source,
            );
        }

        if (isset($payload['error'])) {
            return array(
                'ok' => false,
                'status' => 'error',
                'message' => 'Erro retornado pelo getAPICards.php: ' . (string) $payload['error'],
                'updatedAt' => null,
                'source' => $source,
            );
        }

        if (!dadoshome_payload_has_courses($payload)) {
            return array(
                'ok' => false,
                'status' => 'error',
                'message' => 'Payload sem cursos validos para persistencia.',
                'updatedAt' => null,
                'source' => $source,
            );
        }

        if (!dadoshome_write_cache($config['jsonPath'], $payload)) {
            return array(
                'ok' => false,
                'status' => 'error',
                'message' => 'Falha ao escrever dadosHome.json.',
                'updatedAt' => null,
                'source' => $source,
            );
        }

        $updatedAt = (new DateTimeImmutable('now', $tz))->format('Y-m-d H:i:s');
        return array(
            'ok' => true,
            'status' => 'updated',
            'message' => 'Cache atualizado com sucesso.',
            'updatedAt' => $updatedAt,
            'source' => $source,
        );
    } finally {
        dadoshome_release_lock($lockHandle);
    }
}

function dadoshome_acquire_lock($lockFile, &$handle, &$errorMessage)
{
    $handle = @fopen($lockFile, 'c+');
    if ($handle === false) {
        $errorMessage = 'Falha ao abrir arquivo de lock.';
        return false;
    }

    if (!@flock($handle, LOCK_EX | LOCK_NB)) {
        @fclose($handle);
        $handle = null;
        $errorMessage = 'Atualizacao em andamento. Tente novamente em instantes.';
        return false;
    }

    return true;
}

function dadoshome_release_lock($handle)
{
    if (is_resource($handle)) {
        @flock($handle, LOCK_UN);
        @fclose($handle);
    }
}

function dadoshome_check_rate_limit($rateFile, $minIntervalSeconds)
{
    $minIntervalSeconds = (int) $minIntervalSeconds;
    if ($minIntervalSeconds <= 0) {
        return array('allowed' => true, 'retryInSeconds' => 0);
    }

    $now = time();
    $lastRun = 0;

    if (is_file($rateFile)) {
        $raw = @file_get_contents($rateFile);
        if ($raw !== false) {
            $lastRun = (int) trim($raw);
        }
    }

    if ($lastRun > 0) {
        $diff = $now - $lastRun;
        if ($diff < $minIntervalSeconds) {
            return array(
                'allowed' => false,
                'retryInSeconds' => $minIntervalSeconds - $diff,
            );
        }
    }

    @file_put_contents($rateFile, (string) $now, LOCK_EX);
    return array('allowed' => true, 'retryInSeconds' => 0);
}

function dadoshome_collect_status($config)
{
    $path = $config['jsonPath'];
    $exists = is_file($path);
    $size = $exists ? (int) @filesize($path) : 0;
    $mtime = $exists ? @filemtime($path) : false;

    $tz = new DateTimeZone($config['timezone']);
    $now = new DateTimeImmutable('now', $tz);
    $due = dadoshome_needs_refresh($path, $now, (int) $config['refreshHour']);

    $lastUpdate = null;
    if ($mtime !== false) {
        $lastUpdate = (new DateTimeImmutable('@' . $mtime))
            ->setTimezone($tz)
            ->format('Y-m-d H:i:s');
    }

    return array(
        'exists' => $exists,
        'sizeBytes' => $size,
        'isEmpty' => dadoshome_cache_file_is_empty($path),
        'lastUpdate' => $lastUpdate,
        'dueNow' => $due,
    );
}

function dadoshome_render_web_page($config, $status, $result)
{
    header('Content-Type: text/html; charset=utf-8');

    $badgeColor = '#1f2937';
    if ($result['status'] === 'updated') {
        $badgeColor = '#065f46';
    } elseif ($result['status'] === 'error') {
        $badgeColor = '#991b1b';
    } elseif ($result['status'] === 'skipped') {
        $badgeColor = '#1e40af';
    } elseif ($result['status'] === 'rate-limited' || $result['status'] === 'busy') {
        $badgeColor = '#92400e';
    }

    $message = htmlspecialchars($result['message'], ENT_QUOTES, 'UTF-8');
    $workerUrl = htmlspecialchars($config['workerUrl'], ENT_QUOTES, 'UTF-8');
    $endpointUrl = htmlspecialchars($config['endpointUrl'], ENT_QUOTES, 'UTF-8');
    $endpointPath = htmlspecialchars($config['endpointPath'], ENT_QUOTES, 'UTF-8');
    $jsonPath = htmlspecialchars($config['jsonPath'], ENT_QUOTES, 'UTF-8');
    $refreshHour = (int) $config['refreshHour'];
    $minInterval = (int) $config['minRefreshIntervalSec'];
    $allowedIps = isset($config['allowedIps']) && is_array($config['allowedIps']) ? $config['allowedIps'] : array();
    $allowedIpsText = empty($allowedIps) ? 'nao (desativada)' : htmlspecialchars(implode(', ', $allowedIps), ENT_QUOTES, 'UTF-8');
    $lastUpdate = $status['lastUpdate'] !== null ? htmlspecialchars($status['lastUpdate'], ENT_QUOTES, 'UTF-8') : 'n/a';
    $sizeBytes = (int) $status['sizeBytes'];
    $dueNow = $status['dueNow'] ? 'sim' : 'nao';
    $exists = $status['exists'] ? 'sim' : 'nao';

    echo '<!doctype html>';
    echo '<html lang="pt-BR"><head><meta charset="utf-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>Worker dadosHome</title>';
    echo '<style>';
    echo 'body{font-family:Segoe UI,Arial,sans-serif;background:#f5f7fb;color:#111827;margin:0;padding:24px;}';
    echo '.wrap{max-width:900px;margin:0 auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:20px;}';
    echo '.badge{display:inline-block;padding:6px 10px;border-radius:999px;color:#fff;font-size:12px;background:' . $badgeColor . ';}';
    echo '.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:14px;}';
    echo '.card{border:1px solid #e5e7eb;border-radius:10px;padding:12px;background:#f9fafb;}';
    echo 'h1{margin:0 0 12px;} h2{font-size:16px;margin:0 0 8px;}';
    echo '.mono{font-family:Consolas,Monaco,monospace;font-size:13px;word-break:break-all;}';
    echo 'button{background:#111827;color:#fff;border:0;border-radius:8px;padding:10px 14px;cursor:pointer;}';
    echo 'button:hover{background:#1f2937;}';
    echo 'form{margin:14px 0 0;}';
    echo 'a{color:#1d4ed8;text-decoration:none;} a:hover{text-decoration:underline;}';
    echo '@media (max-width: 780px){.grid{grid-template-columns:1fr;}}';
    echo '</style></head><body>';
    echo '<div class="wrap">';
    echo '<h1>Worker de atualizacao do dadosHome.json</h1>';
    echo '<div class="badge">Status: ' . htmlspecialchars($result['status'], ENT_QUOTES, 'UTF-8') . '</div>';
    echo '<p style="margin-top:10px;">' . $message . '</p>';

    echo '<div class="grid">';
    echo '<div class="card">';
    echo '<h2>Caminhos fixos</h2>';
    echo '<div class="mono">Worker URL: ' . $workerUrl . '</div>';
    echo '<div class="mono">Endpoint path: ' . $endpointPath . '</div>';
    echo '<div class="mono">Endpoint URL: ' . $endpointUrl . '</div>';
    echo '<div class="mono">JSON path: ' . $jsonPath . '</div>';
    echo '</div>';

    echo '<div class="card">';
    echo '<h2>Estado do cache</h2>';
    echo '<div>Arquivo existe: <strong>' . $exists . '</strong></div>';
    echo '<div>Tamanho: <strong>' . $sizeBytes . ' bytes</strong></div>';
    echo '<div>Ultima atualizacao: <strong>' . $lastUpdate . '</strong></div>';
    echo '<div>Atualizacao devida agora: <strong>' . $dueNow . '</strong></div>';
    echo '<div>Hora diaria configurada: <strong>' . $refreshHour . 'h</strong></div>';
    echo '</div>';
    echo '</div>';

    echo '<div class="card" style="margin-top:12px;">';
    echo '<h2>Seguranca</h2>';
    echo '<div>Protecao por chave: <strong>ativa</strong></div>';
    echo '<div>Whitelist de IPs: <strong>' . $allowedIpsText . '</strong></div>';
    echo '<div>Intervalo minimo entre refresh: <strong>' . $minInterval . 's</strong></div>';
    echo '</div>';

    echo '<form method="post">';
    echo '<input type="hidden" name="action" value="manual-refresh">';
    echo '<input type="hidden" name="access_key" value="' . htmlspecialchars((string) $config['accessKey'], ENT_QUOTES, 'UTF-8') . '">';
    echo '<button type="submit">Atualizar agora (manual)</button>';
    echo '</form>';

    echo '<p style="margin-top:18px;">Auto update diario via cron (recomendado).</p>';
    echo '<p class="mono">Cron web: ' . $workerUrl . '?action=auto&format=json&access_key=SEU_TOKEN</p>';
    echo '<p class="mono">Cron CLI sugerido: 5 23 * * * php ' . htmlspecialchars($config['workerFilePath'], ENT_QUOTES, 'UTF-8') . ' --auto</p>';
    echo '</div></body></html>';
}

function dadoshome_payload_has_courses($payload)
{
    if (!is_array($payload)) {
        return false;
    }

    $lista = array();
    if (isset($payload['posgraduacao']) && is_array($payload['posgraduacao'])) {
        $lista = $payload['posgraduacao'];
    } elseif (isset($payload['graduacao']) && is_array($payload['graduacao'])) {
        $lista = $payload['graduacao'];
    } elseif (isset($payload['data']) && is_array($payload['data'])) {
        $lista = $payload['data'];
    } else {
        $lista = $payload;
    }

    if (!is_array($lista) || empty($lista)) {
        return false;
    }

    foreach ($lista as $item) {
        if (
            is_array($item) &&
            (
                !empty($item['curso']) ||
                !empty($item['nome']) ||
                !empty($item['mnemonico']) ||
                !empty($item['mneumonico'])
            )
        ) {
            return true;
        }
    }

    return false;
}

function dadoshome_cache_file_is_empty($path)
{
    if (!is_file($path)) {
        return true;
    }

    $size = @filesize($path);
    if ($size === false || $size === 0) {
        return true;
    }

    $contents = @file_get_contents($path);
    return ($contents === false || trim($contents) === '');
}

function dadoshome_needs_refresh($jsonPath, $now, $refreshHour)
{
    if (dadoshome_cache_file_is_empty($jsonPath)) {
        return true;
    }

    $mtime = @filemtime($jsonPath);
    if ($mtime === false) {
        return true;
    }

    $lastUpdate = new DateTimeImmutable('@' . $mtime);
    $lastUpdate = $lastUpdate->setTimezone($now->getTimezone());

    $lastDayKey = $lastUpdate->format('Y-m-d');
    $todayDayKey = $now->format('Y-m-d');
    $yesterdayDayKey = $now->modify('-1 day')->format('Y-m-d');

    if ($lastDayKey < $yesterdayDayKey) {
        return true;
    }

    $currentHour = (int) $now->format('G');
    if ($currentHour < (int) $refreshHour) {
        return false;
    }

    return $lastDayKey !== $todayDayKey;
}

function dadoshome_build_refresh_url($endpoint, $cacheBust)
{
    $parts = parse_url($endpoint);
    if ($parts === false) {
        return false;
    }

    $query = array();
    if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
    }

    $query['tipo'] = 'posgraduacao';
    $query['no_cache'] = (string) $cacheBust;

    $scheme = isset($parts['scheme']) ? $parts['scheme'] . '://' : '';
    $host = isset($parts['host']) ? $parts['host'] : '';
    $port = isset($parts['port']) ? ':' . $parts['port'] : '';
    $path = isset($parts['path']) ? $parts['path'] : '';

    $userInfo = '';
    if (isset($parts['user'])) {
        $userInfo = $parts['user'];
        if (isset($parts['pass'])) {
            $userInfo .= ':' . $parts['pass'];
        }
        $userInfo .= '@';
    }

    $queryString = http_build_query($query);
    $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';

    return $scheme . $userInfo . $host . $port . $path . ($queryString !== '' ? '?' . $queryString : '') . $fragment;
}

function dadoshome_http_get($url, &$errorMessage)
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        if ($response === false) {
            $errorMessage = curl_error($ch);
            curl_close($ch);
            return false;
        }

        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            $errorMessage = 'HTTP ' . $httpCode;
            return false;
        }

        return $response;
    }

    $context = stream_context_create(array(
        'http' => array(
            'method' => 'GET',
            'timeout' => 30,
            'ignore_errors' => true,
        ),
    ));

    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        $errorMessage = 'Falha em file_get_contents';
        return false;
    }

    return $response;
}

function dadoshome_write_cache($jsonPath, $payload)
{
    $jsonBase = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($jsonBase === false) {
        return false;
    }

    $jsonComComentario = rtrim($jsonBase);
    $jsonComComentario .= "\n/* Atualizado em: " . date('Y-m-d H:i:s') . " */\n";

    return @file_put_contents($jsonPath, $jsonComComentario, LOCK_EX) !== false;
}
