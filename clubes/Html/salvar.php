<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$db   = 'gameplan26';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro BD: ' . $e->getMessage()]);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!$data || empty($data['formacaoData'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Dados incompletos']);
    exit;
}

$nome = "Formação criada em " . date("d/m/Y H:i");
$json = json_encode($data['formacaoData']);

$stmt = $pdo->prepare("INSERT INTO formacoes (nome, dados) VALUES (?, ?)");
$ok = $stmt->execute([$nome, $json]);

if ($ok) {
    echo json_encode(['success' => true, 'mensagem' => 'Guardado com sucesso!']);
} else {
    echo json_encode(['success' => false, 'error' => 'Erro ao guardar']);
}
?>