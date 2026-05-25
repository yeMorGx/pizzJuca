<?php
/**
 * =========================================================================
 * PRIMEIRO: lê isto se estiveres perdido
 * =========================================================================
 * O getall.php = "dá-me todas as bebidas".
 * Este ficheiro = "dá-me UMA bebida" → tens de dizer qual com ?id= na URL.
 * Exemplo: .../api/bebidas/get.php?id=2
 *
 * Sem id = não sabemos que linha ir buscar à tabela → erro 400 com mensagem clara.
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';
include_once '../../models/Bebidas.php';

$database = new Database();
$db = $database->getConnection();

$bebidas = new Bebidas($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $idParam = isset($_GET['id']) ? trim((string) $_GET['id']) : '';

    if ($idParam === '') {
        http_response_code(400);
        echo json_encode(
            array("Mensagem" => "Id é obrigatório.")
        );
    } else {
        $bebidas->idBebidas = $idParam;
        $row = $bebidas->get();

        if (!$row) {
            http_response_code(404);
            echo json_encode(
                array("Mensagem" => "Bebida não encontrada.")
            );
        } else {
            http_response_code(200);
            $bebida_arr = array(
                "id" => $bebidas->idBebidas,
                "nome" => $bebidas->nome,
                "litros" => $bebidas->litros,
                "valor" => $bebidas->valor
            );
            echo json_encode($bebida_arr, JSON_PRETTY_PRINT);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(
        array("Mensagem" => "Método não permitido.")
    );
}
