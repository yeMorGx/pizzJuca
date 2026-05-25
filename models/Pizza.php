<?php
/**
 * =========================================================================
 * O QUE É UMA "MODEL" / MODELO? (bem simples)
 * =========================================================================
 * Este ficheiro é a "receita" da Pizza no código. Não é o ecrã do site; é a parte
 * que sabe COMO ler e escrever pizzas na tabela `pizzas` do MySQL.
 *
 * Cada função abaixo faz UMA coisa óbvia:
 *   getall   → traz todas as linhas da tabela (lista do cardápio).
 *   get      → traz UMA linha, pela chave idPizza.
 *   create   → insere uma linha nova.
 *   update   → altera uma linha que já existe.
 *   delete   → apaga uma linha.
 *
 * Porque prepare() e bind? Porque assim o valor do id/nome vai "encaixado" no sítio
 * certo do comando SQL sem o utilizador mal-intencionado poder injetar comando falso
 * (é a defesa básica contra SQL injection — não precisas de decorar, só saber que é boa prática).
 */

class Pizza
{
    // Ligação ao MySQL (vem de fora, do Database.php).
    private $conn;
    private $tabela = "pizzas";
 
    public $idPizza;
    public $nome;
    public $ingredientes;
    public $valor;
 
    public function __construct($db) {
        $this->conn = $db;
    }

    // método para obter todas as pizzas do banco de dados
    public function getall(){

        // consulta SQL para selecionar os campos idPizza, nome, ingredientes e valor da tabela de pizzas
        $query ="SELECT idPizza, nome, ingredientes, valor FROM " . $this->tabela;
        // prepara a consulta SQL usando a conexão com o banco de dados e executa a consulta, retornando o resultado
        $stmt = $this->conn->prepare($query);
        // executa a consulta SQL preparada
        $stmt->execute();
        // retorna o resultado da consulta SQL, que é um objeto PDOStatement contendo as linhas selecionadas da tabela de pizzas
        return $stmt;
    }

// método para obter uma pizza específica do banco de dados com base no idPizza
public function get() {
    $query = "SELECT idPizza, nome, ingredientes, valor 
    FROM " . $this->tabela . " 
    WHERE idPizza = ? 
    LIMIT 1";

    // prepara a consulta SQL usando a conexão com o banco de dados, vinculando o parâmetro idPizza à consulta, executando a consulta e retornando a linha resultante como um array associativo
    $stmt = $this->conn->prepare($query);
    // vincula o valor da propriedade idPizza ao primeiro parâmetro da consulta SQL usando o método bindParam, que é uma forma segura de passar valores para a consulta e evitar ataques de injeção de SQL
    $stmt->bindParam(1, $this->idPizza);
    // executa a consulta SQL preparada
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $this->idPizza = $row['idPizza'];
        $this->nome = $row['nome'];
        $this->ingredientes = $row['ingredientes'];
        $this->valor = $row['valor'];
    }

    return $row;
}

    public function create() {
        $query = "INSERT INTO " . $this->tabela . " (nome, ingredientes, valor) VALUES (:nome, :ingredientes, :valor)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':ingredientes', $this->ingredientes);
        $stmt->bindValue(':valor', $this->valor);
        if (!$stmt->execute()) {
            return false;
        }
        $this->idPizza = $this->conn->lastInsertId();
        return true;
    }

    public function update() {
        $query = "UPDATE " . $this->tabela . "
            SET nome = :nome, ingredientes = :ingredientes, valor = :valor
            WHERE idPizza = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':ingredientes', $this->ingredientes);
        $stmt->bindValue(':valor', $this->valor);
        $stmt->bindValue(':id', $this->idPizza);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->tabela . " WHERE idPizza = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->idPizza);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

}