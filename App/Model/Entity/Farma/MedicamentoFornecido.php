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
     * Dose do Medicamento
     *
     * @var string
     */
    public $dose;

    /**
     * Quantidade Fornecida
     *
     * @var int
     */
    public $quantidade;

    /**
     * Data da Retirada
     *
     * @var string
     */
    public $data_retirada;

    /**
     * Observações sobre a Retirada
     *
     * @var string
     */
    public $observacoes;

    /**
     * Funcionário Responsável
     *
     * @var string
     */
    public $funcionario_id;

    /**
     * Responsável pela Retirada
     *
     * @var string
     */
    public $responsavel_retirada;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar()
    {
        $this->id = (new Database('f_medicamentos_fornecidos'))->insert([
            "paciente_id" => $this->paciente_id,
            "medicamento_id" => $this->medicamento_id,
            "dose" => $this->dose,
            "quantidade" => $this->quantidade,
            "data_retirada" => $this->data_retirada,
            "observacoes" => $this->observacoes,
            "funcionario_id" => $this->funcionario_id,
            "responsavel_retirada" => $this->responsavel_retirada
        ]);

        return true;
    }

    /**
     * Método responsável por retornar um ou mais medicamentos fornecidos
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getMedicamentosFornecidos($where = null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('f_medicamentos_fornecidos'))->select($where, $order, $limit, $fields);
    }

    /**
     * Método responsável por retornar um medicamento fornecido com base no seu ID
     *
     * @param integer $id
     * @return MedicamentoFornecido
     */
    public static function getMedicamentoFornecidoById($id)
    {
        return self::getMedicamentosFornecidos("id = $id")->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um medicamento fornecido com base no seu ID
     *
     * @param integer $id
     * @return \PDOStatement
     */
    public static function getRetirada($id,$where, $order, $limit)
    {

        $order = strlen($order) ? 'ORDER BY ' . $order : '';
        $limit = strlen($limit) ? 'LIMIT ' . $limit : '';

        $query = "SELECT 
                        f_medicamentos_fornecidos.id,
                        f_medicamentos_fornecidos.paciente_id,
                        f_medicamentos_fornecidos.medicamento_id,
                        f_medicamentos.nome as nome_medicamento,
                        f_medicamentos_fornecidos.dose,
                        f_medicamentos_fornecidos.quantidade,
                        f_medicamentos_fornecidos.data_retirada,
                        f_medicamentos_fornecidos.observacoes,
                        f_medicamentos_fornecidos.funcionario_id,
                        f_medicamentos_fornecidos.responsavel_retirada,
                        COUNT(*) OVER () as qtd
                    FROM 
                        f_medicamentos_fornecidos
                    JOIN 
                        f_medicamentos ON f_medicamentos_fornecidos.medicamento_id = f_medicamentos.id
                    WHERE
                        f_medicamentos_fornecidos.paciente_id LIKE '$id' AND ($where)
                    $order
                    $limit";

        return (new Database('f_medicamentos_fornecidos'))->execute($query);
    }

    /**
     * Método responsável por atualizar os dados no banco com a instância atual
     *
     * @return boolean
     */
    public function atualizar()
    {
        // Atualiza os dados no banco
        return (new Database('f_medicamentos_fornecidos'))->update("id = " . $this->id, [
            "paciente_id" => $this->paciente_id,
            "medicamento_id" => $this->medicamento_id,
            "dose" => $this->dose,
            "quantidade" => $this->quantidade,
            "data_retirada" => $this->data_retirada,
            "observacoes" => $this->observacoes,
            "funcionario_id" => $this->funcionario_id,
            "responsavel_retirada" => $this->responsavel_retirada
        ]);
    }

    /**
     * Método responsável por excluir um medicamento fornecido do banco
     *
     * @return boolean
     */
    public function excluir()
    {
        // Apagar os dados no banco
        return (new Database('f_medicamentos_fornecidos'))->delete("id = " . $this->id);
    }
}
