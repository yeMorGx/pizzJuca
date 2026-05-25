<?php
/**
 * =========================================================================
 * O QUE FAZ ISTO? (bem simples)
 * =========================================================================
 * Atualiza uma bebida que já existe. O JSON tem de trazer id + nome + litros + valor.
 * Mesma lógica do update de pizza.
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(["message" => "Método não permitido. Use PUT."]);
    exit;
}

include_once '../../config/Database.php';
include_once '../../models/Bebidas.php';

$data = json_decode(file_get_contents("php://input"));
if (!$data || !isset($data->id, $data->nome, $data->litros, $data->valor)) {
    http_response_code(400);
    echo json_encode(["message" => "Envie JSON com id, nome, litros e valor."]);
    exit;
}

$database = new Database();
$db = $database->getConnection();
if (!$db) {
    http_response_code(500);
    echo json_encode(["message" => "Erro de conexão com o banco."]);
    exit;
}

$bebida = new Bebidas($db);
$bebida->idBebidas = $data->id;
$bebida->nome = $data->nome;
$bebida->litros = $data->litros;
$bebida->valor = $data->valor;

if ($bebida->update()) {
    http_response_code(200);
    echo json_encode([
        "message" => "Bebida atualizada.",
        "id" => (int) $bebida->idBebidas,
        "nome" => $bebida->nome,
        "litros" => $bebida->litros,
        "valor" => (float) $bebida->valor,
    ]);
} else {
    http_response_code(404);
    echo json_encode(["message" => "Bebida não encontrada ou dados iguais aos atuais."]);
}
