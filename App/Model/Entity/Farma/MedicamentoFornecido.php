<?php

namespace App\Model\Entity\Farma;

use App\Utils\Database;

class MedicamentoFornecido
{
    /**
     * Id do Medicamento Fornecido
     *
     * @var int
     */
    public $id;

    /**
     * Id do Paciente
     *
     * @var int
     */
    public $paciente_id;

    /**
     * Id do Medicamento
     *
     * @var int
     */
    public $medicamento_id;

    /**
     * Dose do Medicamento Fornecido
     *
     * @var string
     */
    public $dose;

    /**
     * Quantidade do Medicamento Fornecido
     *
     * @var int
     */
    public $quantidade;

    /**
     * Data de Retirada do Medicamento
     *
     * @var string
     */
    public $data_retirada;

    /**
     * Observações Adicionais sobre o Fornecimento
     *
     * @var string
     */
    public $observacoes;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar()
    {
        $this->id = (new Database('F_Medicamentos_Fornecidos'))->insert([
            "paciente_id" => $this->paciente_id,
            "medicamento_id" => $this->medicamento_id,
            "dose" => $this->dose,
            "quantidade" => $this->quantidade,
            "data_retirada" => $this->data_retirada,
            "observacoes" => $this->observacoes
        ]);

        return true;
    }

    /**
     * Método responsável por retornar um ou mais fornecimentos de medicamentos
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getMedicamentosFornecidos($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('F_Medicamentos_Fornecidos'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar um fornecimento de medicamento com base no seu ID
     *
     * @param integer $id
     * @return MedicamentoFornecido
     */
    public static function getMedicamentoFornecidoById($id)
    {
        return self::getMedicamentosFornecidos("id = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por atualizar os dados no banco com a instância atual
     *
     * @return boolean
     */
    public function atualizar()
    {
        // Atualiza os dados no banco
        return (new Database('F_Medicamentos_Fornecidos'))->update("id = " . $this->id, [
            "paciente_id" => $this->paciente_id,
            "medicamento_id" => $this->medicamento_id,
            "dose" => $this->dose,
            "quantidade" => $this->quantidade,
            "data_retirada" => $this->data_retirada,
            "observacoes" => $this->observacoes
        ]);
    }

    /**
     * Método responsável por excluir um fornecimento de medicamento do banco
     *
     * @return boolean
     */
    public function excluir()
    {
        // Apagar os dados no banco
        return (new Database('F_Medicamentos_Fornecidos'))->delete("id = " . $this->id);
    }
}
