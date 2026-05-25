<?php
/**
 * =========================================================================
 * O QUE FAZ ESTE FICHEIRO? (bem simples)
 * =========================================================================
 * Alguém faz um pedido GET (como abrir um link no browser ou o fetch() no JavaScript).
 * Nós ligamos ao MySQL, pedimos TODAS as pizzas à tabela, e devolvemos uma lista em JSON.
 *
 * Não precisa de ?id= porque não estamos a escolher uma só — estamos a listar tudo.
 * Se a tabela estiver vazia, devolvemos 404 com mensagem amigável (não há o que mostrar).
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';
include_once '../../models/Pizza.php';

$database = new Database();
$db = $database->getConnection();

$pizza = new Pizza($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pizza->getall();
    $num = $stmt->rowCount();

    if ($num > 0) {

        $pizzas_arr = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // extract() cria variáveis $idPizza, $nome, etc. a partir das chaves do array.
            // Assim não escreves $row['nome'] dezenas de vezes; atenção: polui o espaço de nomes.
            extract($row);

            $pizza_item = array(
                "id" => $idPizza,
                "nome" => $nome,
                "ingredientes" => $ingredientes,
                "valor" => $valor
            );

            array_push($pizzas_arr, $pizza_item);
        }

        http_response_code(200);
        echo json_encode($pizzas_arr);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "Nenhuma pizza encontrada.")
        );
    }
} else {
    http_response_code(405);
    echo json_encode(
        array("message" => "Método não permitido. Use GET.")
    );
}
