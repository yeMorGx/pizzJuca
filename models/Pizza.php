<?php

class Pizza
{
    private $conn;
    private $tabela = "pizzas";

    public $idPizza;
    public $nome;
    public $ingredientes;
    public $valor;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getall()
    {
        $query = "SELECT idPizza, nome, ingredientes, valor FROM " . $this->tabela;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function get()
    {
        $query = "SELECT idPizza, nome, ingredientes, valor
                  FROM " . $this->tabela . "
                  WHERE idPizza = ?
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->idPizza);
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

    public function create()
    {
        $query = "INSERT INTO " . $this->tabela . " (nome, ingredientes, valor)
                  VALUES (:nome, :ingredientes, :valor)";

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

    public function update()
    {
        $query = "UPDATE " . $this->tabela . "
                  SET nome = :nome,
                      ingredientes = :ingredientes,
                      valor = :valor
                  WHERE idPizza = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':ingredientes', $this->ingredientes);
        $stmt->bindValue(':valor', $this->valor);
        $stmt->bindValue(':id', $this->idPizza);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->tabela . " WHERE idPizza = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':id', $this->idPizza);

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}