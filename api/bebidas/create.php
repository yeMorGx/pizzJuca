<?php
/**
 * =========================================================================
 * O QUE FAZ ISTO? (bem simples)
 * =========================================================================
 * Cria uma BEBIDA nova. O cliente manda JSON no corpo (POST) com nome, litros e valor.
 * Igual à ideia do create de pizza; só mudam os campos da tabela.
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Método não permitido. Use POST."]);
    exit;
}

include_once '../../config/Database.php';
include_once '../../models/Bebidas.php';

$data = json_decode(file_get_contents("php://input"));
if (!$data || !isset($data->nome, $data->litros, $data->valor)) {
    http_response_code(400);
    echo json_encode(["message" => "Envie JSON com nome, litros e valor."]);
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
$bebida->nome = $data->nome;
$bebida->litros = $data->litros;
$bebida->valor = $data->valor;

if ($bebida->create()) {
    http_response_code(201);
    echo json_encode([
        "message" => "Bebida criada.",
        "id" => (int) $bebida->idBebidas,
        "nome" => $bebida->nome,
        "litros" => $bebida->litros,
        "valor" => (float) $bebida->valor,
    ]);
} else {
    http_response_code(500);
    echo json_encode(["message" => "Não foi possível criar a bebida."]);
}
