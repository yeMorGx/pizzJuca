<?php
/**
 * =========================================================================
 * FICHEIRO DE TESTE MANUAL (não é a API oficial)
 * =========================================================================
 * Isto NÃO é o que o site do cliente usa. É só para TU abrires no browser, veres
 * se a ligação ao MySQL funciona e se a classe Pizza carrega sem erro.
 *
 * Abre no browser algo como: http://localhost/pizzJuca/tmptestepizza.php
 * Se vir "Conexão bem-sucedida" e um print da pizza de exemplo, está tudo ok no modelo.
 */

require_once 'models/Pizza.php';
require_once 'config/Database.php';

echo "<h1>Testando Conexão e Modelo Pizza</h1>";

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    echo "<p style='color: red;'>Falha na conexão.</p>";
    die();
}

echo "<p style='color: green;'>Conexão bem-sucedida!</p>";

echo "<h2>Criando um objeto Pizza...</h2>";

$pizza = new Pizza($db);

$pizza->nome = 'Margherita';
$pizza->ingredientes = 'Mussarela, fatias de tomate e manjericão fresco';
$pizza->valor = 42.50;

echo "<pre>";
print_r($pizza);
echo "</pre>";
