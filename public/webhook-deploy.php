<?php
/**
 * Webhook receiver para deploy automático do GitHub
 * Acessível em: https://cruzdeossos.com.br/webhook-deploy.php
 *
 * Segurança: verifica assinatura HMAC SHA-256 do GitHub
 */

// === CONFIGURAÇÃO ===
$WEBHOOK_SECRET = 'cruzdeossos-deploy-2025';
$DEPLOY_SCRIPT = __DIR__ . '/../deploy.sh';
$LOG_FILE = __DIR__ . '/../storage/logs/deploy.log';

// === NÃO EDITAR ABAIXO ===

http_response_code(200);

// Só aceita POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Pega a assinatura enviada pelo GitHub
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
if (!$signature) {
    http_response_code(403);
    exit('No signature');
}

// Calcula a assinatura esperada
$payload = file_get_contents('php://input');
$expected = 'sha256=' . hash_hmac('sha256', $payload, $WEBHOOK_SECRET);

// Verifica a assinatura (timing-safe comparison)
if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    exit('Invalid signature');
}

// Decodifica o payload
$data = json_decode($payload, true);

// Só executa em push para a branch main
$ref = $data['ref'] ?? '';
if ($ref !== 'refs/heads/main') {
    echo 'Ignored: not main branch';
    exit;
}

// Log
$timestamp = date('Y-m-d H:i:s');
$pusher = $data['pusher']['name'] ?? 'unknown';
$commit = substr($data['head_commit']['id'] ?? '', 0, 7);
$message = $data['head_commit']['message'] ?? '';
$message = explode("\n", $message)[0];

$logLine = "[$timestamp] Deploy triggered by $pusher (commit $commit): $message\n";
file_put_contents($LOG_FILE, $logLine, FILE_APPEND);

// Executa o script de deploy em background
$cmd = "nohup bash $DEPLOY_SCRIPT > $LOG_FILE 2>&1 &";
exec($cmd);

echo "Deploy started: $commit";
