<?php
/**
 * =========================================================================
 * O QUE É ISTO?
 * =========================================================================
 * Quando abres a pasta do projeto no browser, o servidor costuma mostrar primeiro
 * o index.php. Este ficheiro aqui é só um TESTE RÁPIDO: devolve um JSON pequeno
 * para veres se o PHP está a correr e se os cabeçalhos CORS (para o JavaScript
 * noutro sítio poder chamar a API) estão definidos.
 *
 * Não é o "cérebro" do projeto — as pizzas de verdade estão em api/pizza/...
 */

// Permite que páginas noutro endereço peçam dados a este servidor (evita bloqueio do browser).
header("Access-Control-Allow-Origin: *");

// Diz "a resposta é JSON", não uma página HTML.
header("Content-Type: application/json; charset=UTF-8");

// Lista que tipos de pedido HTTP este script aceita (informação para o browser).
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

// Resposta de exemplo. Podes mudar a mensagem; é só texto para testar.
echo json_encode([
    "message" => "Lorem ipsum dolor"
]);
