<?php

namespace App\Model\Entity\Usuarios;


use App\Utils\Database;

class Log
{
    /**
     * Id do Log
     *
     * @var int
     */
    public $id;

    /**
     * Data do Log
     *
     * @var string
     */
    public $data;

    /**
     * Conteúdo do Log
     *
     * @var array
     */
    public $log;

    /**
     * Id do Usuário relacionado ao Log
     *
     * @var int
     */
    public $usuario_id;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar()
    {
        $this->id = (new Database('log'))->insert([
            "log" => json_encode($this->log),
            "usuario_id" => $this->usuario_id
        ]);

        return true;
    }

    /**
     * Método responsável por retornar um ou mais logs
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getLogs($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('log'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar um log com base no seu ID
     *
     * @param integer $id
     * @return Log
     */
    public static function getLogById($id)
    {
        return self::getLogs("id = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por atualizar os dados no banco com a instância atual
     *
     * @return boolean
     */
    public function atualizar()
    {
        // Atualiza os dados no banco
        return (new Database('log'))->update("id = " . $this->id, [
            "data" => $this->data,
            "log" => json_encode($this->log),
            "usuario_id" => $this->usuario_id
        ]);
    }

    /**
     * Método responsável por excluir um log do banco
     *
     * @return boolean
     */
    public function excluir()
    {
        // Apagar os dados no banco
        return (new Database('log'))->delete("id = " . $this->id);
    }
}
