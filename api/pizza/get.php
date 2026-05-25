<?php
/**
 * =========================================================================
 * PRIMEIRO: lê isto se estiveres perdido
 * =========================================================================
 * Imagina um menu: o getall.php é "mostra-me TODAS as pizzas".
 * Este ficheiro é "mostra-me UMA pizza" — então TENS de dizer qual: ?id=5 na URL.
 * Sem o número, ninguém adivinha qual queres → por isso aparece erro "Id obrigatório".
 *
 * Exemplo de URL certa: .../api/pizza/get.php?id=2
 * =========================================================================
 * POR QUE DUAS ROTAS (get e getall)?
 * =========================================================================
 * Buscar uma só no banco é rápido e simples. Buscar todas também é simples, mas
 * devolve mais dados. O programa do site escolhe o que precisa em cada ecrã.
 *
 * POR QUE O id É OBRIGATÓRIO?
 * =========================================================================
 * Na base de dados cada pizza tem um número único (chave). Esse número é o "nome"
 * da linha. Sem ele o programa não sabe qual linha ir buscar.
 */

// Cabeçalhos no topo: se imprimires texto antes, o PHP pode não conseguir mandar estes avisos ao browser.

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/Database.php';
include_once '../../models/Pizza.php';

$database = new Database();
$db = $database->getConnection();

$pizza = new Pizza($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // id vem da barra de endereço depois de ?id=
    $idParam = isset($_GET['id']) ? trim((string) $_GET['id']) : '';

    if ($idParam === '') {
        http_response_code(400);
        echo json_encode(
            array("Mensagem" => "Id é obrigatório.")
        );
    } else {
        $pizza->idPizza = $idParam;
        $row = $pizza->get();

        if (!$row) {
            http_response_code(404);
            echo json_encode(
                array("Mensagem" => "Pizza não encontrada.")
            );
        } else {
            http_response_code(200);
            $pizza_arr = array(
                "id" => $pizza->idPizza,
                "nome" => $pizza->nome,
                "ingredientes" => $pizza->ingredientes,
                "valor" => $pizza->valor
            );
            echo json_encode($pizza_arr, JSON_PRETTY_PRINT);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(
        array("Mensagem" => "Método não permitido.")
    );
}
