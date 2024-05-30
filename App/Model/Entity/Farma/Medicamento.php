<?php

namespace App\Model\Entity\Farma;

use App\Utils\Database;

class Medicamento
{
    /**
     * Id do Medicamento
     *
     * @var int
     */
    public $id;

    /**
     * Nome do Medicamento
     *
     * @var string
     */
    public $nome;

    /**
     * Status do Medicamento
     *
     * @var bool
     */
    public $status;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar()
    {
        $this->id = (new Database('F_Medicamentos'))->insert([
            "nome" => $this->nome,
            "status" => $this->status
        ]);

        return true;
    }

    /**
     * Método responsável por retornar um ou mais medicamentos
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getMedicamentos($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('F_Medicamentos'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar um medicamento com base no seu ID
     *
     * @param integer $id
     * @return Medicamento
     */
    public static function getMedicamentoById($id)
    {
        return self::getMedicamentos("id = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por atualizar os dados no banco com a instância atual
     *
     * @return boolean
     */
    public function atualizar()
    {
        // Atualiza os dados no banco
        return (new Database('F_Medicamentos'))->update("id = " . $this->id, [
            "nome" => $this->nome,
            "status" => $this->status
        ]);
    }

    /**
     * Método responsável por excluir um medicamento do banco
     *
     * @return boolean
     */
    public function excluir()
    {
        // Apagar os dados no banco
        return (new Database('F_Medicamentos'))->delete("id = " . $this->id);
    }
}
