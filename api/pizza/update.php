<?php
/**
 * =========================================================================
 * O QUE FAZ ISTO? (bem simples)
 * =========================================================================
 * ATUALIZAR = a pizza JÁ existe; vamos mudar nome/ingredientes/valor.
 * O JSON tem de trazer o "id" para sabermos QUAL linha alterar na tabela.
 *
 * Usamos PUT porque é o verbo que a maioria das APIs usa para "substituir/atualizar"
 * um recurso que já tem endereço (identificado pelo id).
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
include_once '../../models/Pizza.php';

$data = json_decode(file_get_contents("php://input"));
if (!$data || !isset($data->id, $data->nome, $data->ingredientes, $data->valor)) {
    http_response_code(400);
    echo json_encode(["message" => "Envie JSON com id, nome, ingredientes e valor."]);
    exit;
}

$database = new Database();
$db = $database->getConnection();
if (!$db) {
    http_response_code(500);
    echo json_encode(["message" => "Erro de conexão com o banco."]);
    exit;
}

$pizza = new Pizza($db);
$pizza->idPizza = $data->id;
$pizza->nome = $data->nome;
$pizza->ingredientes = $data->ingredientes;
$pizza->valor = $data->valor;

if ($pizza->update()) {
    http_response_code(200);
    echo json_encode([
        "message" => "Pizza atualizada.",
        "id" => (int) $pizza->idPizza,
        "nome" => $pizza->nome,
        "ingredientes" => $pizza->ingredientes,
        "valor" => (float) $pizza->valor,
    ]);
} else {
    // Ou não existia esse id, ou os dados eram iguais aos que já estavam (0 linhas afetadas).
    http_response_code(404);
    echo json_encode(["message" => "Pizza não encontrada ou dados iguais aos atuais."]);
}
