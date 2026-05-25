<?php
/**
 * =========================================================================
 * O QUE FAZ ESTE FICHEIRO? (bem simples)
 * =========================================================================
 * Igual ao getall das pizzas, mas para bebidas: um GET devolve JSON com TODAS
 * as linhas da tabela bebidas. Não precisa de id na URL.
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';
include_once '../../models/Bebidas.php';

$database = new Database();
$db = $database->getConnection();

$bebidas = new Bebidas($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $bebidas->getall();
    $num = $stmt->rowCount();

    if ($num > 0) {

        $bebidas_arr = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            extract($row);

            $bebida_item = array(
                "id" => $idBebidas,
                "nome" => $nome,
                "litros" => $litros,
                "valor" => $valor
            );

            array_push($bebidas_arr, $bebida_item);
        }

        http_response_code(200);
        echo json_encode($bebidas_arr);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "Nenhuma bebida encontrada.")
        );
    }
} else {
    http_response_code(405);
    echo json_encode(
        array("message" => "Método não permitido. Use GET.")
    );
}
