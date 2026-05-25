<?php
/**
 * =========================================================================
 * O QUE É ESTE FICHEIRO? (explicação bem simples)
 * =========================================================================
 * Imagina que o MySQL é um cofre onde estão guardadas as tuas pizzas e bebidas.
 * Para o PHP "falar" com esse cofre, ele precisa de uma CONEXÃO: utilizador,
 * palavra-passe, endereço do servidor, nome da base de dados.
 *
 * Aqui está TUDO isso num sítio só. Os outros ficheiros não repetem a palavra-passe;
 * eles dizem: "Database, dá-me uma ligação" e usam o que este ficheiro prepara.
 *
 * PDO é só o nome da ferramenta do PHP que liga ao MySQL de forma segura e moderna.
 * Se a palavra-passe estiver errada ou o MySQL desligado, o try/catch apanha o erro
 * e mostra uma mensagem em vez de partir a página inteira sem explicação.
 */

class Database
{
    // Dados da "porta de entrada" do cofre MySQL (no USBWebserver costuma ser assim).
    private $host = 'localhost';
    private $db_name = 'pizzjucadb';
    private $username = 'root';
    private $password = 'usbw';
    private $port = '3307';

    // Aqui fica guardada a ligação ativa depois de abrir o cofre.
    public $conn;

    /**
     * Abre (ou tenta abrir) a ligação e devolve o objeto PDO.
     * Porque devolver a ligação? Para o resto do programa usar sempre a mesma "porta"
     * já aberta, em vez de cada ficheiro inventar uma ligação nova.
     */
    public function getConnection()
    {
        // Começamos sem ligação; se der erro, continua null e o resto do código pode testar.
        $this->conn = null;

        try {
            // DSN = "receita" numa só frase: tipo de base, máquina, porta, nome, codificação.
            // charset=utf8 é para acentos (á, ç, ã) guardarem e aparecerem bem.
            $dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->db_name . ';charset=utf8';

            // new PDO(...) tenta mesmo ligar. Se falhar, salta para o catch em baixo.
            $this->conn = new PDO($dsn, $this->username, $this->password);

            // Isto faz com que erros de SQL virem exceções claras em vez de falhas silenciosas.
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            // Erro típico: senha errada, base não existe, MySQL parado.
            echo "Erro de Conexão: " . $e->getMessage();
        } catch (Exception $e) {
            // Qualquer outro erro inesperado.
            echo "Erro: " . $e->getMessage();
        }

        // Pode ser uma ligação boa ou null se falhou — quem chama deve verificar (! $db).
        return $this->conn;
    }
}
